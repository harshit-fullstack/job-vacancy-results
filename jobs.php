<?php
require_once 'include/db.php';

if (!function_exists('slugify')) {
  function slugify($string)
  {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    return trim($string, '-');
  }
}

$jobId = $_GET['id'] ?? 0;

$sql = "
    SELECT 
        jobs.*, 
        companies.name AS company_name, 
        companies.id AS company_id,
        companies.description AS company_description,
        companies.logo AS company_logo, 
        companies.website AS company_website, 
        companies.industry_ids AS company_industry_ids,
        GROUP_CONCAT(DISTINCT company_industry.name SEPARATOR ', ') AS company_industries,
        job_industry.name AS industry_name, 
        role_categories.name AS role_category_name, 
        departments.name AS department_name
    FROM jobs
    LEFT JOIN companies ON jobs.company_id = companies.id
    LEFT JOIN industries AS job_industry ON jobs.industry_id = job_industry.id
    LEFT JOIN industries AS company_industry ON FIND_IN_SET(company_industry.id, companies.industry_ids)
    LEFT JOIN role_categories ON jobs.role_category_id = role_categories.id
    LEFT JOIN departments ON jobs.department_id = departments.id
    WHERE jobs.id = ?
    GROUP BY jobs.id
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $jobId);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();
$stmt->close();

if (!$job) {
  include 'include/header.php';
  echo '<section class="py-12"><div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8 text-center"><h2 class="text-2xl font-bold text-red-600 mb-4">Job Not Found</h2><p class="text-gray-700 mb-6">Sorry, the job you are looking for does not exist or has been removed.</p><a href="jobs.php" class="inline-block bg-black-600 hover:bg-black-700 text-white px-6 py-2 rounded-xl font-semibold">Back to Jobs</a></div></section>';
  include 'include/footer.php';
  exit;
}

// Pagination for related jobs by similar industry
$limit = 3;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$industryIds = array_filter(array_map('intval', explode(',', $job['company_industry_ids'])));
$industryIds[] = (int)$job['industry_id'];
$industryIds = array_unique($industryIds);

$countSql = "
    SELECT COUNT(*) as total
    FROM jobs
    LEFT JOIN companies ON jobs.company_id = companies.id
    WHERE jobs.id != ? AND (
        " . implode(' OR ', array_map(fn($id) => "FIND_IN_SET($id, companies.industry_ids) OR jobs.industry_id = $id", $industryIds)) . "
    )
";
$countStmt = $conn->prepare($countSql);
$countStmt->bind_param("i", $jobId);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRows = $countResult->fetch_assoc()['total'] ?? 0;
$totalPages = ceil($totalRows / $limit);
$countStmt->close();

$relatedSql = "
    SELECT jobs.id, jobs.title, jobs.description, jobs.salary, jobs.city, jobs.state, companies.name AS company_name, companies.logo AS company_logo
    FROM jobs
    LEFT JOIN companies ON jobs.company_id = companies.id
    WHERE jobs.id != ? AND (
        " . implode(' OR ', array_map(fn($id) => "FIND_IN_SET($id, companies.industry_ids) OR jobs.industry_id = $id", $industryIds)) . "
    )
    LIMIT ? OFFSET ?
";
$relatedStmt = $conn->prepare($relatedSql);
$relatedStmt->bind_param("iii", $jobId, $limit, $offset);
$relatedStmt->execute();
$relatedResult = $relatedStmt->get_result();
$relatedJobs = [];
while ($row = $relatedResult->fetch_assoc()) {
  $relatedJobs[] = $row;
}
$relatedStmt->close();

function timeAgo($datetime)
{
  if (empty($datetime) || strtotime($datetime) === false) {
    return 'Unknown';
  }
  $time = strtotime($datetime);
  $diff = time() - $time;
  if ($diff < 60) return $diff . ' seconds ago';
  elseif ($diff < 3600) return floor($diff / 60) . ' minutes ago';
  elseif ($diff < 86400) return floor($diff / 3600) . ' hours ago';
  elseif ($diff < 604800) return floor($diff / 86400) . ' days ago';
  elseif ($diff < 2592000) return floor($diff / 604800) . ' weeks ago';
  elseif ($diff < 31536000) return floor($diff / 2592000) . ' months ago';
  else return floor($diff / 31536000) . ' years ago';
}
?>


<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

<?php
// Protocol
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';

// Build canonical URL from job details
if (isset($job['title'], $job['city'], $job['id'])) {
    $slug = slugify($job['title'] . '-in-' . $job['city'] . '-' . $job['id']);
    $canonicalUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/jobs/' . $slug;
} else {
    // Fallback: current URL without query string
    $canonicalUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
}
$seoTitle = htmlspecialchars($job['title']) . ' in ' . htmlspecialchars($job['city'] ?? 'Unknown') . ' - ' . htmlspecialchars($job['company_name']) . ' (' . htmlspecialchars($job['experience'] ?? 'N/A') . ' experience) | JobVacancyResult';
$desc = 'View details and apply for ' . htmlspecialchars($job['title']) . ' at ' . htmlspecialchars($job['company_name']) . '. Find your next opportunity on JobVacancyResult.';
?>
  <title><?= $seoTitle ?></title>
  <meta name="description" content="<?= $desc ?>" />
  <meta name="robots" content="index, follow" />
    <link rel="icon" href="/jvr-logo.jpg" width="32">

  <link rel="canonical" href="<?= $canonicalUrl ?>" />
  <!-- Open Graph Meta -->
  <meta property="og:title" content="<?= $seoTitle ?>" />
  <meta property="og:description" content="<?= $desc ?>" />
  <meta property="og:type" content="article" />
  <meta property="og:url" content="<?= $canonicalUrl ?>" />
  <meta property="og:site_name" content="JobVacancyResult" />
  <meta property="og:image" content="<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jvr-logo.jpg" />
  <!-- Twitter Card Meta -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?= $seoTitle ?>" />
  <meta name="twitter:description" content="<?= $desc ?>" />
  <meta name="twitter:image" content="<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jvr-logo.jpg" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet" />

  <?php
$datePosted = isset($job['posted_on']) ? date('Y-m-d', strtotime($job['posted_on'])) : date('Y-m-d');
$jobType = isset($job['jobtype']) ? $job['jobtype'] : 'Full-Time';
$description = isset($job['description']) ? strip_tags($job['description']) : 'Job opening available.';
?>
<script type="application/ld+json">
<?= json_encode([
  "@context" => "https://schema.org",
  "@type" => "JobPosting",
  "title" => $job['title'],
  "description" => $description,
  "datePosted" => $datePosted,
  "employmentType" => $jobType,
  "hiringOrganization" => [
    "@type" => "Organization",
    "name" => "Your Company Name",
    "sameAs" => "https://jobvacancyresult.com"
  ],
  "jobLocation" => [
    "@type" => "Place",
    "address" => [
      "@type" => "PostalAddress",
      "addressLocality" => $job['city'] ?? 'Unknown',
      "addressRegion" => $job['state'] ?? 'Unknown',
      "addressCountry" => "IN"
    ]
  ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>

  <style>
    /* Scrollbar for long content */
    .prose {
      max-width: 100%;
    }
  </style>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          animation: {
            'fade-in': 'fadeIn 0.6s ease-out',
            'slide-up': 'slideUp 0.6s ease-out',
            'scale-in': 'scaleIn 0.3s ease-out',
            'bounce-slow': 'bounce 2s infinite',
            'pulse-slow': 'pulse 3s infinite',
          },
          keyframes: {
            fadeIn: {
              '0%': {
                opacity: '0',
                transform: 'translateY(20px)'
              },
              '100%': {
                opacity: '1',
                transform: 'translateY(0)'
              }
            },
            slideUp: {
              '0%': {
                opacity: '0',
                transform: 'translateY(30px)'
              },
              '100%': {
                opacity: '1',
                transform: 'translateY(0)'
              }
            },
            scaleIn: {
              '0%': {
                opacity: '0',
                transform: 'scale(0.9)'
              },
              '100%': {
                opacity: '1',
                transform: 'scale(1)'
              }
            }
          }
        }
      }
    }
  </script>
</head>

<body class="bg-white text-black-900 transition-colors duration-300 font-inter">
  <?php include 'include/header.php'; ?>

  <!-- Breadcrumb -->
  <section class="bg-black-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <nav class="flex items-center gap-2 text-sm" aria-label="Breadcrumb">
        <a href="https://jobvacancyresult.com/" class="text-black-600 hover:text-black-700">Home</a>
        <svg class="w-4 h-4 text-black-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <a href="/job" class="text-black-600 hover:text-black-700">Jobs</a>
        <svg class="w-4 h-4 text-black-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span class="text-black-700" aria-current="page"><?= htmlspecialchars($job['title']) ?></span>
      </nav>
    </div>
  </section>

  <!-- Job Details Section -->
  <section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Main Content -->
        <main class="flex-1 order-0">
          <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
            <div class="flex flex-col items-center sm:flex-row gap-6">
              <!-- Company Logo -->
              <div class="flex-shrink-0">
                <img src="/<?= htmlspecialchars($job['company_logo'] ?: 'https://placehold.co/200x200?text=Logo') ?>" alt="<?= htmlspecialchars($job['company_name']) ?> logo" class="w-20 h-20 rounded-xl object-contain mx-auto" />
              </div>

              <!-- Job Info -->
              <div class="flex-1">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                  <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                      <h1 class="text-3xl font-bold text-black-900"><?= htmlspecialchars($job['title']) ?></h1>
                      <div class="flex gap-2">
                        <?php if (!empty($job['job_type'])): ?>
                          <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full"><?= htmlspecialchars($job['job_type']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($job['additional_info']) && stripos($job['additional_info'], 'urgent') !== false): ?>
                          <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full">Urgent</span>
                        <?php endif; ?>
                      </div>
                    </div>
                    <h2 class="text-xl font-semibold text-black-700 mb-4"><?= htmlspecialchars($job['company_name']) ?></h2>

                    <div class="flex flex-wrap gap-6 text-black-600 mb-4">
                      <div class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?= htmlspecialchars(trim(($job['city'] ?? '') . ($job['state'] ? ', ' . $job['state'] : ''))) ?></span>
                      </div>
                      <?php if (!empty($job['salary'])): ?>
                        <div class="flex items-center gap-2">
                          <span class="text-lg font-semibold">&#8377;</span>
                          <span><?= htmlspecialchars($job['salary']) ?></span>
                        </div>
                      <?php endif; ?>
                      <div class="flex items-center gap-2">
                        <i class="fas fa-briefcase"></i>
                        <span><?= htmlspecialchars($job['experience'] ?? 'N/A') ?></span>
                      </div>
                      <div class="flex items-center gap-2">
                        <i class="fas fa-clock"></i>
                        <span><?= htmlspecialchars(timeAgo($job['created_at'])) ?></span>
                      </div>
                    </div>

                    <!-- Skills / Tags -->
                    <div class="flex flex-wrap gap-2 mb-6">
                      <?php
                      $skills = array_filter(array_map('trim', explode(',', $job['skills'] ?? '')));
                      foreach ($skills as $skill):
                      ?>
                        <span class="bg-blue-100 text-black-800 text-sm font-medium px-3 py-1 rounded-full"><?= htmlspecialchars($skill) ?></span>
                      <?php endforeach; ?>
                    </div>
                  </div>

                  <!-- Actions -->
                  <div class="flex flex-col gap-3 sm:items-end">
                    <!-- <form method="post" action="apply.php" class="w-full">
                      <input type="hidden" name="job_id" value="<?= (int)$job['id'] ?>" />
                      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl transition-all transform hover:scale-105 font-semibold w-full">
                        Apply Now
                      </button>
                    </form> -->
                    <a href="<?= htmlspecialchars($job['job_link'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl transition-all transform hover:scale-105 font-semibold w-full">
                     Apply Now
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Job Description -->
          <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 prose max-w-none">
            <h3 class="text-2xl font-bold text-black-900 mb-6">Job Description</h3>
            <p><?= nl2br(htmlspecialchars($job['description'] ?? 'No description available.')) ?></p>
          </div>

          <!-- Additional Info -->
          <?php if (!empty($job['additional_info'])): ?>
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 prose max-w-none">
              <h3 class="text-2xl font-bold text-black-900 mb-6">Additional Information</h3>
              <p><?= nl2br(htmlspecialchars($job['additional_info'])) ?></p>
            </div>
          <?php endif; ?>

          <!-- Job Description -->
          <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 prose max-w-none">
            <h3 class="text-2xl font-bold text-black-900 mb-6">About <?= htmlspecialchars($job['company_name']) ?></h3>
            <p><?= nl2br(htmlspecialchars($job['company_description'] ?? 'No description available.')) ?></p>
          </div>

          <?php if (!empty($relatedJobs)): ?>
            <section class="bg-white rounded-2xl shadow-lg p-8 mb-8">
              <h3 class="text-2xl font-bold text-black-900 mb-6">Other Jobs You May Like</h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($relatedJobs as $relatedJob): ?>

                  <?php
                  $slugTitle = slugify($relatedJob['title']);
                  $slugCity = slugify($relatedJob['city']);
                  $jobId = (int)$relatedJob['id'];
                  ?>

                  <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center gap-4 mb-4">
                      <img src="/<?= htmlspecialchars($relatedJob['company_logo'] ?: 'https://placehold.co/60x60?text=Logo') ?>" alt="<?= htmlspecialchars($relatedJob['company_name']) ?>" class="w-12 h-12 object-contain rounded-xl">
                      <div>
                        <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($relatedJob['company_name']) ?></h4>
                        <div class="flex gap-2 mt-1">
                          <?= !empty($relatedJob['is_featured']) ? '<span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded-full">Featured</span>' : '' ?>
                          <?= !empty($relatedJob['is_urgent']) ? '<span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">Urgent</span>' : '' ?>
                        </div>
                      </div>
                    </div>

                    <h5 class="text-lg font-bold text-gray-900 mb-3"><?= htmlspecialchars($relatedJob['title']) ?></h5>

                    <div class="space-y-2 mb-4">
                      <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                        <span><?= htmlspecialchars(trim(($relatedJob['city'] ?? '') . ($relatedJob['state'] ? ', ' . $relatedJob['state'] : ''))) ?></span>
                      </div>

                      <?php if (!empty($relatedJob['salary'])): ?>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 4h12M6 8h12m-6 0a5 5 0 0 1-5 5h5l-6 7" />
                          </svg>
                          <span><?= htmlspecialchars($relatedJob['salary']) ?></span>
                        </div>
                      <?php endif; ?>
                    </div>

                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                      <?= htmlspecialchars(strip_tags($relatedJob['short_description'] ?? $relatedJob['description'] ?? '')) ?>
                    </p>
                    <a href="<?= $slugTitle ?>-in-<?= $slugCity ?>-<?= $jobId ?>"


                      class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-center py-2 rounded-xl transition-all transform hover:scale-105 font-semibold">
                      View Details
                    </a>
                  </div>
                <?php endforeach; ?>
              </div>
            </section>
          <?php endif; ?>


          <?php if ($totalPages > 1): ?>
            <div class="mt-6 flex justify-center items-center gap-2 flex-wrap">
              <?php
              $urlParams = $_GET;
              $range = 2;
              $start = max(1, $page - $range);
              $end = min($totalPages, $page + $range);

              if ($page > 1):
                $urlParams['page'] = $page - 1;
                echo '<a href="?' . htmlspecialchars(http_build_query($urlParams)) . '" class="px-4 py-2 rounded border bg-white text-blue-600 hover:bg-blue-50">Prev</a>';
              endif;

              if ($start > 1) echo '<span class="px-3 py-2">...</span>';

              for ($i = $start; $i <= $end; $i++):
                $urlParams['page'] = $i;
                $isActive = $i === $page;
                echo '<a href="?' . htmlspecialchars(http_build_query($urlParams)) . '" class="px-4 py-2 rounded border ' . ($isActive ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 hover:bg-blue-50') . '">' . $i . '</a>';
              endfor;

              if ($end < $totalPages) echo '<span class="px-3 py-2">...</span>';

              if ($page < $totalPages):
                $urlParams['page'] = $page + 1;
                echo '<a href="?' . htmlspecialchars(http_build_query($urlParams)) . '" class="px-4 py-2 rounded border bg-white text-blue-600 hover:bg-blue-50">Next</a>';
              endif;
              ?>
            </div>
          <?php endif; ?>
        </main>

        <!-- Right Sidebar: Company + Job Summary Minimal -->
        <aside class="lg:w-80 flex-shrink-0 space-y-6 sticky top-24 self-start order-1">
          <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-black-900 mb-4">Company Details</h3>
            <img src="/<?= htmlspecialchars($job['company_logo'] ?: 'https://placehold.co/200x200?text=Logo') ?>" alt="<?= htmlspecialchars($job['company_name']) ?> logo" class="w-40 h-40 object-contain rounded-lg mb-4" />
            <p class="text-lg font-bold text-black-900 mb-2"><?= htmlspecialchars($job['company_name']) ?></p>
            <!-- <p class="text-base text-black-700 mb-2">
              <strong>Website:</strong>
              <a href="<?= htmlspecialchars($job['company_website'] ?? '#') ?>" target="_blank" rel="noopener noreferrer" class="text-black-600 hover:underline">
                <?= htmlspecialchars($job['company_website'] ?? 'N/A') ?>
              </a>
            </p> -->
            <p class="text-base text-black-700 mb-4">
              <strong>Industries:</strong>
              <?= !empty($job['company_industries']) ? htmlspecialchars($job['company_industries']) : 'N/A' ?>
            </p>

            <!-- Company Profile Redirect Button -->

            <a href="/company/<?= slugify($job['company_name']) ?>-<?= (int)$job['company_id'] ?>" class="mt-4 inline-block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl transition-all">
              Visit Company Profile
            </a>


            <h3 class="text-xl font-bold text-black-900 mb-4 border-t pt-4">Job Summary</h3>
            <div class="space-y-3 text-black-700">
              <div><span class="text-sm font-medium text-black-500">Job Type</span>
                <p class="font-semibold"><?= htmlspecialchars($job['job_type'] ?? 'N/A') ?></p>
              </div>
              <div><span class="text-sm font-medium text-black-500">Industry</span>
                <p class="font-semibold"><?= htmlspecialchars($job['industry_name'] ?? 'N/A') ?></p>
              </div>
              <div><span class="text-sm font-medium text-black-500">Experience</span>
                <p class="font-semibold"><?= htmlspecialchars($job['experience'] ?? 'N/A') ?></p>
              </div>
              <div><span class="text-sm font-medium text-black-500">Location</span>
                <p class="font-semibold"><?= htmlspecialchars(trim(($job['city'] ?? '') . ($job['state'] ? ', ' . $job['state'] : ''))) ?></p>
              </div>
              <div><span class="text-sm font-medium text-black-500">Salary</span>
                <p class="font-semibold"> <span class="text-lg ">&#8377; </span> <?= htmlspecialchars($job['salary'] ?? 'N/A') ?></p>
              </div>
              <div><span class="text-sm font-medium text-black-500">Posted</span>
                <p class="font-semibold"><?= isset($job['created_at']) && strtotime($job['created_at']) ? htmlspecialchars(timeAgo($job['created_at'])) : 'Not available' ?></p>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </section>

  <?php include 'include/footer.php'; ?>
</body>

</html>