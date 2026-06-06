<?php
require_once 'include/db.php';

// --- Filters (from search form) ---
$search = $_GET['job'] ?? '';
$location = $_GET['location'] ?? '';
$experience = $_GET['experience'] ?? '';

// Build query
$sql = "SELECT
            jobs.*,
            companies.name AS company_name,
            companies.logo AS company_logo,
            companies.id AS company_id
        FROM jobs
        LEFT JOIN companies ON jobs.company_id = companies.id
        WHERE jobs.is_featured = 1";
if (!empty($search)) {
  $searchEsc = $conn->real_escape_string($search);
  $sql .= " AND (jobs.title LIKE '%$searchEsc%' OR companies.name LIKE '%$searchEsc%')";
}
if (!empty($location)) {
  $locationEsc = $conn->real_escape_string($location);
  $sql .= " AND (jobs.city LIKE '%$locationEsc%' OR jobs.state LIKE '%$locationEsc%')";
}
// Order by created_at DESC and limit to 12 jobs
$sql .= " ORDER BY jobs.created_at DESC LIMIT 12";

$result = $conn->query($sql);

$jobs = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    // Badges: use job_type if available, and add Featured if is_featured
    $row['badges'] = [];
    if (!empty($row['job_type'])) {
      $row['badges'][] = $row['job_type'];
    }
    if ($row['is_featured']) {
      $row['badges'][] = 'Featured';
    }
    // Skills: comma separated
    $row['skills'] = isset($row['skills']) ? array_filter(array_map('trim', explode(',', $row['skills']))) : [];
    // Location: city, state
    $row['location'] = trim(($row['city'] ?? '') . (isset($row['state']) && $row['state'] ? ', ' . $row['state'] : ''));
    // Logo: from companies table if available, else placeholder
    $row['logo'] = !empty($row['company_logo']) ? $row['company_logo'] : '';
    // Job link
    $row['job_link'] = $row['job_link'] ?? '#';
    // Experience
    $row['experience'] = $row['experience'] ?? '';
    // Salary
    $row['salary'] = $row['salary'] ?? '';
    $jobs[] = $row;
  }
}

// Experience filter (range, e.g. "6-7" means show jobs with max experience >= 6)
if (!empty($experience) && preg_match('/^(\d+)-(\d+)$/', $experience, $expMatch)) {
  $expMin = (int)$expMatch[1];
  $expMax = (int)$expMatch[2];
  $jobs = array_filter($jobs, function ($job) use ($expMin, $expMax) {
    // Extract min and max from job's experience string like "4 - 6 years"
    if (preg_match('/(\d+)\s*-\s*(\d+)/', $job['experience'], $jobExpMatch)) {
      $jobMin = (int)$jobExpMatch[1];
      $jobMax = (int)$jobExpMatch[2];
      // Show jobs where job's min experience is less than or equal to selected max
      // (so jobs with higher min experience are NOT shown)
      return $jobMin <= $expMax;
    }
    // If experience is not a range, try to match single value
    if (preg_match('/(\d+)/', $job['experience'], $jobExpMatch)) {
      $jobVal = (int)$jobExpMatch[1];
      return $jobVal <= $expMax;
    }
    return false;
  });
}

// Fetch top 4 industries based on job count
$sqlIndustries = "SELECT industries.name, industries.id, COUNT(jobs.id) AS job_count 
                  FROM jobs 
                  LEFT JOIN industries ON jobs.industry_id = industries.id 
                  GROUP BY industries.id 
                  ORDER BY job_count DESC 
                  LIMIT 8";
$resultIndustries = $conn->query($sqlIndustries);

$topIndustries = [];
if ($resultIndustries && $resultIndustries->num_rows > 0) {
  while ($row = $resultIndustries->fetch_assoc()) {
    $topIndustries[] = $row;
  }
}

// Fetch top 20 unique cities with jobs
$citiesResult = $conn->query("
    SELECT city, COUNT(*) as job_count 
    FROM jobs 
    WHERE city IS NOT NULL AND city != '' 
    GROUP BY city 
    ORDER BY job_count DESC 
    LIMIT 20
");

$allCities = [];
if ($citiesResult && $citiesResult->num_rows > 0) {
  while ($row = $citiesResult->fetch_assoc()) {
    $allCities[] = $row['city'];
  }
}

function timeAgo($datetime)
{
  $time = strtotime($datetime);
  $diff = time() - $time;

  if ($diff < 60) {
    return $diff . ' seconds ago';
  } elseif ($diff < 3600) {
    return floor($diff / 60) . ' minutes ago';
  } elseif ($diff < 86400) {
    return floor($diff / 3600) . ' hours ago';
  } elseif ($diff < 604800) {
    return floor($diff / 86400) . ' days ago';
  } elseif ($diff < 2592000) {
    return floor($diff / 604800) . ' weeks ago';
  } elseif ($diff < 31536000) {
    return floor($diff / 2592000) . ' months ago';
  } else {
    return floor($diff / 31536000) . ' years ago';
  }
}

$conn->close();


?>
<!DOCTYPE html>
<html class="scroll-smooth" data-theme="light" lang="en">

<head>
  <!-- ...existing head code... -->
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <?php
  $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
  $canonicalUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
  if (!empty($_SERVER['QUERY_STRING'])) {
    $canonicalUrl .= '?' . htmlspecialchars($_SERVER['QUERY_STRING']);
  }
  $seoTitle = 'Job Vacancy Result';
  $desc = 'Browse the latest job openings and career opportunities. Search and apply for jobs online on JobVacancyResult.';
  ?>
  <title><?= $seoTitle ?></title>
  <meta name="description" content="<?= $desc ?>" />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="<?= $canonicalUrl ?>" />
  <!-- Open Graph Meta -->
  <meta property="og:title" content="<?= $seoTitle ?>" />
  <meta property="og:description" content="<?= $desc ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?= $canonicalUrl ?>" />
  <meta property="og:site_name" content="JobVacancyResult" />
  <meta property="og:image" content="<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jvr-logo.jpg" />
  <!-- Twitter Card Meta -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?= $seoTitle ?>" />
  <meta name="twitter:description" content="<?= $desc ?>" />
  <meta name="twitter:image" content="<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jvr-logo.jpg" />

  <!-- Schema Markup -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "Job Vacancy Result",
      "description": "Browse the latest job openings and career opportunities. Search and apply for jobs online on JobVacancyResult.",
      "url": "<?= $canonicalUrl ?>"
    }
  </script>

  <!-- <link href="https://job-vacancy-result.com/" rel="canonical" /> -->
  <link href="https://job-vacancy-result.com/jvr-logo.jpg" rel="icon" type="image/x-icon" />
  <link rel="icon" href="/jvr-logo.jpg" width="32">
  <link rel="stylesheet" href="css/main.css">

  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

</head>

<body class="bg-white text-blue-900 font-inter">
  <?php include 'include/header.php' ?>
  <!-- Hero Section -->
  <section aria-label="Job search hero section" class="max-w-7xl mx-auto px-6 lg:px-8 mt-12 flex flex-col-reverse lg:flex-row items-center gap-12 hero-section">
    <div class="max-w-xl w-full">
      <h1 class="text-5xl font-extrabold leading-tight tracking-tight mb-4 text-blue-800 drop-shadow-lg">
        Got Talent?
        <br />
        Meet Opportunity
      </h1>
      <p class="text-lg text-blue-900 mb-8 font-semibold">
        Company reviews. Salaries. Interviews. Jobs.
      </p>
      <form aria-label="Job search form" autocomplete="off" class="flex flex-col sm:flex-row items-center gap-4 max-w-full relative" id="searchForm" role="search" method="get">
        <input aria-label="Job title or keywords" class="flex-grow rounded-lg border border-gray-300 px-5 py-3 text-base placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" id="job" name="job" placeholder="Job title or keywords" type="text" value="<?= htmlspecialchars($search) ?>" />
        <input aria-label="Location" class="rounded-lg border border-gray-300 px-4 py-3 text-base placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition w-40" id="location" name="location" placeholder="Location" type="text" value="<?= htmlspecialchars($location) ?>" />
        <select aria-label="Experience" class="rounded-lg border border-gray-300 px-4 py-3 text-base placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition w-40" id="experience" name="experience">
          <option value="">Experience</option>
          <option value="0-1" <?= $experience == '0-1' ? 'selected' : '' ?>>0-1 years</option>
          <option value="2-3" <?= $experience == '2-3' ? 'selected' : '' ?>>2-3 years</option>
          <option value="4-5" <?= $experience == '4-5' ? 'selected' : '' ?>>4-5 years</option>
          <option value="6-7" <?= $experience == '6-7' ? 'selected' : '' ?>>6-7 years</option>
          <option value="8-10" <?= $experience == '8-10' ? 'selected' : '' ?>>8-10 years</option>
          <option value="10-20" <?= $experience == '10-20' ? 'selected' : '' ?>>10-20 years</option>
        </select>
        <button class="btn-primary" type="submit">
          Search
        </button>
       <button type="button" class="btn-primary" onclick="window.location.href='https://jobvacancyresult.com/'">
  Clear
</button>

      </form>

    </div>
    <div class="relative max-w-md w-full ml-0 lg:ml-40">
      <img alt="Smiling woman with glasses and curly hair wearing pink hoodie using laptop, sitting cross-legged with hand pointing to right empty space" class="w-full h-auto drop-shadow-lg rounded-lg" height="400" src="images\main-img.png" width="400" />
      <a href="https://whatsapp.com/channel/0029VaeVzVI2975BErKiGG1Z">
        <div aria-label="Subscribe to job alert" class="absolute top-12 left-6 bg-gradient-to-r from-blue-700 to-blue-900 rounded-full shadow-lg px-5 py-2 flex items-center gap-3 text-sm font-semibold text-white cursor-pointer hover:from-blue-800 hover:to-blue-950 transition select-none" id="jobAlertSubscribeBtn" role="button" tabindex="0">
          <i class="fas fa-bell text-yellow-400 text-lg drop-shadow">
          </i>
          Job Alert Subscribe
        </div>
      </a>
      <div class="absolute bottom-8 right-6 bg-blue-100 rounded-full shadow-md px-5 py-2 flex items-center gap-3 text-sm font-semibold text-blue-900 select-none min-w-[180px]">
        <span class="bg-blue-400 rounded-full flex -space-x-2">
          <img alt="Avatar of candidate 1, smiling young man with short hair" class="rounded-full border-2 border-white w-6 h-6" height="24" src="https://storage.googleapis.com/a1aa/image/c25b5e17-77fd-43a5-c05d-9d036d568d78.jpg" width="24" />
          <img alt="Avatar of candidate 2, young woman with long hair and glasses" class="rounded-full border-2 border-white w-6 h-6" height="24" src="https://storage.googleapis.com/a1aa/image/b5d9a4d2-f66f-4d7c-3d98-063dcd644245.jpg" width="24" />
          <img alt="Avatar of candidate 3, middle-aged man with beard and glasses" class="rounded-full border-2 border-white w-6 h-6" height="24" src="https://storage.googleapis.com/a1aa/image/b806e4ef-7437-4e09-66c7-b0af8357b988.jpg" width="24" />
          <img alt="Avatar of candidate 4, young woman with curly hair and bright smile" class="rounded-full border-2 border-white w-6 h-6" height="24" src="https://storage.googleapis.com/a1aa/image/952e61af-3dd9-4f35-01f2-2c4faaf9340a.jpg" width="24" />
          <img alt="Avatar of candidate 5, young man with short hair and beard" class="rounded-full border-2 border-white w-6 h-6" height="24" src="https://storage.googleapis.com/a1aa/image/e21ab358-0258-4157-7b48-4974a0de586c.jpg" width="24" />
        </span>
        <span>
          5k+ candidates get job
        </span>
        <button aria-label="Add more candidates" class="bg-blue-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold hover:bg-blue-800 transition shadow-md" type="button">
          +
        </button>
      </div>
    </div>
  </section>

  <!-- Jobs Section -->
  <section aria-label="Job listings" class="max-w-[1200px] mx-auto px-4 py-10">
    <h2 class="text-center font-semibold text-2xl mb-2 text-black drop-shadow-md">
      Latest Job Listings
    </h2>
    <p class="text-center text-sm text-black mb-8 max-w-[600px] mx-auto">
      Browse through the latest job opportunities tailored for you.
    </p>

    <div class="relative">
      <!-- Left Button -->
      <button id="prevBtn"
        class="absolute left-2 top-1/2 -translate-y-1/2 bg-gradient-to-r from-gray-700 to-gray-900 text-white shadow-lg rounded-full p-3 z-10 hover:scale-110 hover:from-gray-800 hover:to-black transition" aria-label="left">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
          stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
      </button>

      <!-- Slider wrapper -->
      <div id="jobsSlider" class="flex overflow-x-auto scroll-smooth gap-6 px-10 no-scrollbar">
        <?php foreach ($jobs as $job): ?>
          <div
            class="job-card min-w-[280px] max-w-[280px] bg-white rounded-2xl shadow-lg overflow-hidden flex-shrink-0">
            <div class="p-6">
              <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-3">
                  <img src="<?= $job['logo'] ?: 'https://placehold.co/80x80/png?text=Logo' ?>"
                    alt="<?= htmlspecialchars($job['company_name']) ?>" class="w-12 h-12 rounded-xl">
                  <div>
                    <h3 class="font-semibold text-black"><?= htmlspecialchars($job['company_name']) ?></h3>
                    <div class="flex gap-2 mt-1 flex-wrap">
                      <?php if (in_array('Featured', $job['badges'])): ?>
                        <span
                          class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded-full">Featured</span>
                      <?php endif; ?>
                      <?php if (in_array('Urgent', $job['badges'])): ?>
                        <span
                          class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">Urgent</span>
                      <?php endif; ?>
                      <?php if (in_array('Fulltime', $job['badges'])): ?>
                        <span
                          class="bg-green-600 text-white text-xs font-semibold rounded px-2 py-0.5 uppercase tracking-wide">Fulltime</span>
                      <?php endif; ?>
                      <?php if (in_array('Internship', $job['badges'])): ?>
                        <span
                          class="bg-yellow-500 text-white text-xs font-semibold rounded px-2 py-0.5 uppercase tracking-wide">Internship</span>
                      <?php endif; ?>
                      <?php if (in_array('Parttime', $job['badges'])): ?>
                        <span
                          class="bg-purple-600 text-white text-xs font-semibold rounded px-2 py-0.5 uppercase tracking-wide">Parttime</span>
                      <?php endif; ?>
                      <?php if (in_array('Freelancer', $job['badges'])): ?>
                        <span
                          class="bg-pink-600 text-white text-xs font-semibold rounded px-2 py-0.5 uppercase tracking-wide">Freelancer</span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>

              <h4 class="text-xl font-bold text-black mb-3"><?= htmlspecialchars($job['title']) ?></h4>

              <div class="space-y-2 mb-4">
                <div class="flex items-center gap-2 text-sm text-black">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  </svg>
                  <span><?= htmlspecialchars($job['location']) ?></span>
                </div>
                <div class="flex items-center gap-2 text-sm text-black">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 4h12M6 8h12m-6 0a5 5 0 0 1-5 5h5l-6 7" />
                  </svg>
                  <span><?= htmlspecialchars($job['salary']) ?></span>
                </div>
                <div class="flex items-center gap-2 text-sm text-black">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span><?= htmlspecialchars($job['job_type']) ?> </span>
                </div>
              </div>

              <p class="text-sm text-black mb-4 line-clamp-3">
                <?= htmlspecialchars($job['description'] ?: 'No description available.') ?>
              </p>

              <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                <span class="text-xs text-black"><?= timeAgo($job['created_at']) ?></span>

                <?php
                // Slug generation function
                if (!function_exists('slugify')) {
                  function slugify($string)
                  {
                    $string = strtolower(trim($string));
                    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
                    $string = preg_replace('/[\s-]+/', '-', $string);
                    return trim($string, '-');
                  }
                }
                $slugTitle = slugify($job['title']);
                $slugCity  = slugify($job['city']);
                ?>

                <a href="jobs/<?= $slugTitle ?>-in-<?= $slugCity ?>-<?= $job['id'] ?>"
                  class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-sm font-semibold px-6 py-2 rounded-xl transition-all transform hover:scale-105 flex items-center gap-2">
                  View Details
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                  </svg>
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Right Button -->
      <button id="nextBtn"
        class="absolute right-2 top-1/2 -translate-y-1/2 bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-lg rounded-full p-3 z-10 hover:scale-110 hover:from-blue-700 hover:to-purple-700 transition" aria-label="right">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
          stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>
  </section>






  <!-- REDESIGNED Placement Guarantee Courses Section -->
  <section class="py-20 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="max-w-7xl mx-auto px-6">

      <!-- Header Section -->
      <div class="courses-section-header">
        <h1 class="courses-main-title">
          Transform Your Career with AI-Powered Learning
        </h1>

        <div class="courses-feature-tags">
          <div class="courses-feature-tag">
            <i class="fas fa-shield-alt"></i>
            100% Placement Guarantee
          </div>
          <div class="courses-feature-tag">
            <i class="fas fa-money-bill-wave"></i>
            Full Refund if Not Hired
          </div>
          <div class="courses-feature-tag">
            <i class="fas fa-rocket"></i>
            Industry-Ready Skills
          </div>
          <div class="courses-feature-tag">
            <i class="fas fa-brain"></i>
            AI-Enhanced Learning
          </div>
        </div>
      </div>

      <!-- Course Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12" id="modern-cards-container">
        <!-- Cards will be rendered here by JavaScript -->
      </div>

      <!-- Modern Navigation -->
      <div class="modern-nav-container">
        <button class="modern-nav-btn" id="modernPrevBtn" aria-label="Previous courses">
          <i class="fas fa-chevron-left"></i>
        </button>

        <div class="modern-progress-container">
          <div class="modern-progress-track">
            <div class="modern-progress-fill" id="modernProgressBar" style="width: 25%"></div>
          </div>
          <span class="modern-page-indicator" id="modernPageIndicator">1 of 2</span>
        </div>

        <button class="modern-nav-btn" id="modernNextBtn" aria-label="Next courses">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>

    </div>
  </section>


  <!-- Why Section -->
  <!-- Process Section -->
  <section class="py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16 scroll-reveal">
        <!-- <div class="inline-block mb-6">
                    <span
                        class="bg-blue-100  text-blue-600  text-sm font-semibold uppercase tracking-wider px-4 py-2 rounded-full">
                        How it works
                    </span>
                </div> -->
        <h2 class="text-4xl font-bold text-gray-900  mb-4">
          Three Steps to <span class="text-gradient">Your Dream Job</span>
        </h2>
        <p class="text-xl text-gray-600  max-w-2xl mx-auto">
          Our streamlined process makes job searching simple and effective
        </p>
      </div>

      <div class="flex flex-col lg:flex-row items-center justify-center gap-8 lg:gap-16">
        <!-- Step 1 -->
        <div class="relative flex flex-col items-center max-w-sm scroll-reveal">
          <div
            class="step-card bg-white  rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-4">
            <div
              class="relative feature-icon rounded-2xl w-20 h-20 flex items-center justify-center mb-6 mx-auto">
              <svg class="w-10 h-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
              <div
                class="absolute -top-2 -right-2 bg-yellow-400 text-gray-900 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">
                1
              </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900  mb-4 text-center">
              Search & Discover
            </h3>
            <p class="text-gray-600  text-center leading-relaxed">
              Search from thousands of job opportunities and discover positions that match your skills and
              interests.
            </p>
          </div>
          <!-- Arrow -->
          <div class="hidden lg:block absolute -right-8 top-1/2 transform -translate-y-1/2">
            <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
              </path>
            </svg>
          </div>
        </div>

        <!-- Step 2 -->
        <div class="relative flex flex-col items-center max-w-sm scroll-reveal">
          <div
            class="step-card bg-white  rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-4">
            <div
              class="relative feature-icon rounded-2xl w-20 h-20 flex items-center justify-center mb-6 mx-auto">
              <svg class="w-10 h-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                </path>
              </svg>
              <div
                class="absolute -top-2 -right-2 bg-yellow-400 text-gray-900 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">
                2
              </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900  mb-4 text-center">
              Apply & Schedule
            </h3>
            <p class="text-gray-600  text-center leading-relaxed">
              Apply with one click and schedule interviews at your convenience with our integrated
              calendar system.
            </p>
          </div>
          <!-- Arrow -->
          <div class="hidden lg:block absolute -right-8 top-1/2 transform -translate-y-1/2">
            <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
              </path>
            </svg>
          </div>
        </div>

        <!-- Step 3 -->
        <div class="flex flex-col items-center max-w-sm scroll-reveal">
          <div
            class="step-card bg-white  rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-4">
            <div
              class="relative feature-icon rounded-2xl w-20 h-20 flex items-center justify-center mb-6 mx-auto">
              <svg class="w-10 h-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <div
                class="absolute -top-2 -right-2 bg-yellow-400 text-gray-900 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">
                3
              </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-900  mb-4 text-center">
              Get Hired
            </h3>
            <p class="text-gray-600  text-center leading-relaxed">
              Complete the hiring process and start your new career journey with confidence and support.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Categories Section -->
  <!-- Categories Section -->
  <section id="categories" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

      <div class="text-center mb-16">
        <h2 class="text-4xl font-bold leading-tight mb-4">
          <span class="text-blue-600">Countless Career Options</span><br>
          <span class="text-gray-900">Are Waiting For You</span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Discover a world of exciting opportunities across various industries and find the perfect career path to shape your future.
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <?php foreach ($topIndustries as $industry): ?>
          <a href="job.php?industry=<?= urlencode($industry['id']) ?>&name=<?= urlencode($industry['name']) ?>" 
   class="category-card block bg-white rounded-2xl p-6 text-center hover:shadow-xl transition-all duration-300 border border-gray-200">

  <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-700 rounded-2xl flex items-center justify-center text-2xl text-white mb-4 mx-auto">
    <i class="fas fa-briefcase"></i>
  </div>

  <h3 class="text-xl font-bold text-gray-900 mb-2">
    <?= htmlspecialchars($industry['name']) ?>
  </h3>

  <p class="text-gray-600">
    <?= htmlspecialchars($industry['job_count']) ?> jobs available
  </p>
</a>

        <?php endforeach; ?>
      </div>



    </div>
  </section>
  <!-- Testimonials -->
  <section aria-label="Testimonials" class="max-w-7xl mx-auto px-6 py-16">
    <div class="text-center max-w-3xl mx-auto mb-12">
      <h2 class="text-3xl font-extrabold tracking-tight mb-2 text-blue-900 drop-shadow-md">
        What Job Seekers Say About Us
      </h2>
      <p class="text-xs text-blue-700 leading-tight drop-shadow-sm">
        JobVacancyResult is a trusted platform where candidates find opportunities and build successful careers.
      </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8" id="testimonialsGrid">

      <!-- Testimonial 1 -->
      <div class="testimonial-card">
        <div class="flex items-center space-x-5 mb-5">
          <img alt="Portrait of Ayesha Rahman" class="w-16 h-16 rounded-full object-cover object-center border-2 border-blue-300 shadow-md"
            src="https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg?auto=compress&cs=tinysrgb&w=64&h=64&dpr=2" width="64" height="64" />
          <div>
            <h3 class="text-sm font-semibold text-blue-900">Ayesha Rahman</h3>
            <p class="text-sm text-blue-600">Marketing Graduate</p>
          </div>
        </div>
        <p class="text-sm text-blue-800 leading-relaxed italic">
          "I landed my first marketing role within weeks of using JobVacancyResult. The job alerts matched my skills perfectly."
        </p>
        <i class="fas fa-quote-right testimonial-quote"></i>
      </div>

      <!-- Testimonial 2 -->
      <div class="testimonial-card">
        <div class="flex items-center space-x-5 mb-5">
          <img alt="Portrait of Daniel Smith" class="w-16 h-16 rounded-full object-cover object-center border-2 border-blue-300 shadow-md"
            src="https://images.pexels.com/photos/220453/pexels-photo-220453.jpeg?auto=compress&cs=tinysrgb&w=64&h=64&dpr=2" width="64" height="64" />
          <div>
            <h3 class="text-sm font-semibold text-blue-900">Daniel Smith</h3>
            <p class="text-sm text-blue-600">Software Engineer</p>
          </div>
        </div>
        <p class="text-sm text-blue-800 leading-relaxed italic">
          "The search filters saved me so much time. I only saw jobs that matched my expertise, and applying was super easy."
        </p>
        <i class="fas fa-quote-right testimonial-quote"></i>
      </div>

      <!-- Testimonial 3 -->
      <div class="testimonial-card">
        <div class="flex items-center space-x-5 mb-5">
          <img alt="Portrait of Nusrat Jahan" class="w-16 h-16 rounded-full object-cover object-center border-2 border-blue-300 shadow-md"
            src="https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&w=64&h=64&dpr=2" width="64" height="64" />
          <div>
            <h3 class="text-sm font-semibold text-blue-900">Nusrat Jahan</h3>
            <p class="text-sm text-blue-600">HR Professional</p>
          </div>
        </div>
        <p class="text-sm text-blue-800 leading-relaxed italic">
          "After months of searching elsewhere, I finally found a company that truly valued my skills through JobVacancyResult."
        </p>
        <i class="fas fa-quote-right testimonial-quote"></i>
      </div>

    </div>

    <div class="flex justify-center mt-12 space-x-3">
      <span class="w-3 h-3 rounded-full bg-blue-700 shadow-md"></span>
      <span class="w-3 h-3 rounded-full border-2 border-blue-700"></span>
      <span class="w-3 h-3 rounded-full bg-blue-700 shadow-md"></span>
      <span class="w-3 h-3 rounded-full border-2 border-blue-700"></span>
    </div>
  </section>
  <!-- Back to Top Button -->
  <button aria-label="Back to top" class="fixed bottom-6 right-6 z-50 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-full p-3 shadow-lg hidden hover:from-blue-700 hover:to-blue-600 transition transform hover:scale-110" id="backToTopBtn" type="button">
    <i class="fas fa-arrow-up text-lg">
    </i>
  </button>
  <!-- Modal for Job Alert Subscription -->
  <div aria-labelledby="modalTitle" aria-modal="true" id="modalOverlay" role="dialog" tabindex="-1">
    <div id="modal">
      <button aria-label="Close modal" id="modalCloseBtn" title="Close modal" type="button">
        ×
      </button>
      <h3 id="modalTitle">
        Subscribe to Job Alerts
      </h3>
      <form id="jobAlertForm" novalidate="">
        <label class="block mb-2 font-semibold text-blue-900" for="emailInput">
          Email Address
        </label>
        <input aria-describedby="emailHelp" aria-required="true" id="emailInput" name="email" placeholder="you@example.com" required="" type="email" />
        <p class="text-xs text-red-600 hidden" id="emailHelp">
          Please enter a valid email address.
        </p>
        <button class="mt-4" type="submit">
          Subscribe
        </button>
      </form>
    </div>
  </div>
  <div aria-labelledby="chatbotHeader" aria-modal="true" id="chatbotWindow" role="dialog" tabindex="-1">
    <div id="chatbotHeader">
      Help Center
      <button aria-label="Close chatbot" class="text-white bg-transparent border-none text-2xl cursor-pointer" id="chatbotCloseBtn" title="Close chatbot" type="button">
        ×
      </button>
    </div>
    <div aria-atomic="false" aria-live="polite" id="chatbotMessages">
    </div>
    <div id="chatbotInputContainer">
      <input aria-label="Type your message" autocomplete="off" id="chatbotInput" placeholder="Ask me anything..." type="text" />
      <button aria-label="Send message" id="chatbotSendBtn" type="button">
        Send
      </button>
    </div>
  </div>
  <!-- Toast container -->
  <div aria-atomic="true" aria-live="polite" class="fixed bottom-6 right-6 flex flex-col gap-2 max-w-xs z-50 pointer-events-none" id="toast-container">
  </div>
  <?php include 'include/footer.php' ?>





  <section class="py-12 bg-gray-50 " style="display: none;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-2xl font-bold mb-6 text-center text-blue-900">Browse Jobs by City</h2>
      <div class="flex flex-wrap justify-center gap-3">
        <?php foreach ($allCities as $city): ?>
          <a href="job?location=<?= urlencode($city) ?>" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full font-semibold hover:bg-blue-200 transition">
            Jobs in <?= htmlspecialchars($city) ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <script>
    const modernCourses = [{
        title: "Digital Marketing Mastery",
        rating: "4.8",
        ratingAlt: "4.8 star rating",
        imageSrc: "course/digital-marketing.jpg",
        imageAlt: "Illustration of SEO, social media, and ads strategies",
        label: "🚀 100% Internship + Placement Assistance",
        duration: "6–8 months program with live projects",
        salary: "Average salary ₹5–8 LPA • Top earners ₹12+ LPA",
        experts: "Learn from Google & HubSpot certified professionals",
        type: "digitalmarketing",
        link: "https://cultureofinternet.com/digital-marketing-institute-delhi"
      },
      {
        title: "Web Designing Pro",
        rating: "4.7",
        ratingAlt: "4.7 star rating",
        imageSrc: "course/web-designing.jpg",
        imageAlt: "Creative illustration of modern responsive websites",
        label: "🎨 Hands-on UI/UX + Creative Portfolio Building",
        duration: "6 months with real-time design assignments",
        salary: "Average salary ₹4–7 LPA • Top earners ₹10+ LPA",
        experts: "Learn from industry-leading UI/UX designers",
        type: "webdesign",
        link: "https://cultureofinternet.com/web-designing-institute-delhi"
      },
      {
        title: "Full Stack Development Mastery",
        rating: "4.9",
        ratingAlt: "4.9 star rating",
        imageSrc: "course/full-stack.jpg",
        imageAlt: "Illustration of Full Stack Development Course with modern web technologies",
        label: "🔥 Placement Guarantee with AI",
        duration: "12-14 months intensive program with live mentorship",
        salary: "Average salary: ₹8–15 LPA • Top earners: ₹20+ LPA",
        experts: "Learn from Google, Microsoft & Meta engineers",
        type: "fullstack",
        link: " https://cultureofinternet.com/full-stack-development-institute-delhi"
      },
      {
        title: "Graphic Designing Mastery",
        rating: "4.8",
        ratingAlt: "4.8 star rating",
        imageSrc: "course/graphic-designing.jpg",
        imageAlt: "Creative illustration with Photoshop, Illustrator & Canva tools",
        label: "🖌️ Build Portfolio + Freelance Earning Opportunities",
        duration: "5–7 months of practical creative learning",
        salary: "Average salary ₹3–6 LPA • Freelancers earn ₹50k+/month",
        experts: "Learn from award-winning graphic designers",
        type: "graphicdesign",
        link: "https://cultureofinternet.com/graphic-designing-institute-delhi"
      },
      {
        title: "Data Analytics & Business Intelligence",
        rating: "4.9",
        ratingAlt: "4.9 star rating",
        imageSrc: "course/data-analyst.jpg",
        imageAlt: "Dashboard illustration with data charts & analytics tools",
        label: "📊 Industry-Ready Training with Real Datasets",
        duration: "8–10 months with hands-on case studies",
        salary: "Average salary ₹6–12 LPA • Top earners ₹18+ LPA",
        experts: "Learn from data scientists at top tech firms",
        type: "dataanalytics",
        link: "https://cultureofinternet.com/data-analyst-institute-delhi"
      },
      {
        title: "Multimedia & Animation Pro",
        rating: "4.7",
        ratingAlt: "4.7 star rating",
        imageSrc: "course/multimedia.jpg",
        imageAlt: "Illustration of 2D/3D animation and VFX tools",
        label: "🎬 Creative + Technical Skills with Studio Projects",
        duration: "10–12 months with portfolio-based learning",
        salary: "Average salary ₹4–8 LPA • Top artists earn ₹12+ LPA",
        experts: "Learn from top animation and multimedia professionals",
        type: "multimedia",
        link: "https://cultureofinternet.com/multimedia-institute-delhi"
      }


    ];

    const modernCardsContainer = document.getElementById("modern-cards-container");
    const modernProgressBar = document.getElementById("modernProgressBar");
    const modernPrevBtn = document.getElementById("modernPrevBtn");
    const modernNextBtn = document.getElementById("modernNextBtn");
    const modernPageIndicator = document.getElementById("modernPageIndicator");

    let modernCurrentIndex = 0;
    const modernCardsPerPage = 4;

    function renderModernCards() {
      modernCardsContainer.innerHTML = "";

      for (let i = modernCurrentIndex; i < modernCurrentIndex + modernCardsPerPage && i < modernCourses.length; i++) {
        const course = modernCourses[i];
        const card = document.createElement("div");
        card.className = "modern-course-card";

        card.innerHTML = `
          <div class="course-image-container ${course.type}">
            <img src="${course.imageSrc}" alt="${course.imageAlt}" />
            <div class="course-rating-badge">
              <i class="fas fa-star star"></i>
              ${course.rating}
            </div>
          </div>
          
          <div class="course-content">
            <h2 class="course-title">${course.title}</h2>
            
            <div class="course-guarantee-badge">
              ${course.label}
            </div>
            
            <ul class="course-features">
              <li class="course-feature">
                <div class="feature-icon">
                  <i class="fas fa-clock"></i>
                </div>
                <span>${course.duration}</span>
              </li>
              
              <li class="course-feature">
                <div class="feature-icon">
                  <i class="fas fa-chart-line"></i>
                </div>
                <span>${course.salary}</span>
              </li>
              
              <li class="course-feature">
                <div class="feature-icon">
                  <i class="fas fa-users"></i>
                </div>
                <span>${course.experts}</span>
              </li>
            </ul>
            
            <div class="course-cta">
              <a href="${course.link}" class="course-link">
                Explore Now
                <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>
        `;

        modernCardsContainer.appendChild(card);
      }
    }

    function updateModernProgressBar() {
      const totalPages = Math.ceil(modernCourses.length / modernCardsPerPage);
      const currentPage = Math.floor(modernCurrentIndex / modernCardsPerPage) + 1;
      const progressPercent = (currentPage / totalPages) * 100;

      modernProgressBar.style.width = progressPercent + "%";
      modernPageIndicator.textContent = `${currentPage} of ${totalPages}`;
    }

    function updateModernButtons() {
      modernPrevBtn.disabled = modernCurrentIndex === 0;
      modernNextBtn.disabled = modernCurrentIndex + modernCardsPerPage >= modernCourses.length;
    }

    modernPrevBtn.addEventListener("click", () => {
      if (modernCurrentIndex > 0) {
        modernCurrentIndex -= modernCardsPerPage;
        if (modernCurrentIndex < 0) modernCurrentIndex = 0;
        renderModernCards();
        updateModernProgressBar();
        updateModernButtons();
      }
    });

    modernNextBtn.addEventListener("click", () => {
      if (modernCurrentIndex + modernCardsPerPage < modernCourses.length) {
        modernCurrentIndex += modernCardsPerPage;
        renderModernCards();
        updateModernProgressBar();
        updateModernButtons();
      }
    });

    // Auto-rotate courses every 5 seconds
    setInterval(() => {
      if (modernCurrentIndex + modernCardsPerPage >= modernCourses.length) {
        modernCurrentIndex = 0;
      } else {
        modernCurrentIndex += modernCardsPerPage;
      }
      renderModernCards();
      updateModernProgressBar();
      updateModernButtons();
    }, 8000);

    // Initialize modern courses
    renderModernCards();
    updateModernProgressBar();
    updateModernButtons();
  </script>
  <script src="js/main.js"></script>
</body>

</html>