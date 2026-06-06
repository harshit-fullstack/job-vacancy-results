<?php
require_once 'include/db.php';

// Define slugify function
if (!function_exists('slugify')) {
    function slugify($string)
    {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
        $string = preg_replace('/[\s-]+/', '-', $string);
        return trim($string, '-');
    }
}

// Get company ID from URL
$companyId = intval($_GET['id'] ?? 0);
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 6;
$offset = ($page - 1) * $limit;

// Fetch company details
$sql = "
 SELECT 
   c.*,
   GROUP_CONCAT(DISTINCT i.name SEPARATOR ', ') AS industry_names,
   t.name AS company_type_name,
   GROUP_CONCAT(DISTINCT n.name SEPARATOR ', ') AS nature_names
 FROM companies c
 LEFT JOIN industries i ON FIND_IN_SET(i.id, c.industry_ids)
 LEFT JOIN company_types t ON c.company_type_id = t.id
 LEFT JOIN nature_of_business n ON FIND_IN_SET(n.id, c.nature_of_business_ids)
 WHERE c.id = ?
 GROUP BY c.id
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $companyId);
$stmt->execute();
$company = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$company) {
    die("Company not found.");
}

$companySlug = slugify($company['name']);

// Fetch total job count
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM jobs WHERE company_id = ?");
$stmt->bind_param("i", $companyId);
$stmt->execute();
$res = $stmt->get_result();
$total_jobs = $res->fetch_assoc()['total'] ?? 0;
$stmt->close();

// Fetch paginated jobs
$jobs = [];
$stmt = $conn->prepare("SELECT * FROM jobs WHERE company_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bind_param("iii", $companyId, $limit, $offset);
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc()) $jobs[] = $r;
$stmt->close();

// Calculate total pages
$total_pages = ceil($total_jobs / $limit);

// Fetch related companies by industry
$related = [];
$ids = explode(',', $company['industry_ids']);
if ($ids) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $sql = "
        SELECT c.id, c.name, c.logo, c.industry_ids,
               GROUP_CONCAT(DISTINCT i.name SEPARATOR ', ') AS industry_names
        FROM companies c
        LEFT JOIN industries i ON FIND_IN_SET(i.id, c.industry_ids)
        WHERE c.id != ? AND (
            " . implode(' OR ', array_fill(0, count($ids), "FIND_IN_SET(?, c.industry_ids)")) . "
        )
        GROUP BY c.id
        LIMIT 6
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i" . $types, ...array_merge([$companyId], $ids));
    $stmt->execute();
    $res2 = $stmt->get_result();
    while ($r = $res2->fetch_assoc()) $related[] = $r;
    $stmt->close();
}

// Time ago helper
function timeAgo($datetime)
{
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php
    
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $canonicalUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
    if (!empty($_SERVER['QUERY_STRING'])) {
        $canonicalUrl .= '?' . htmlspecialchars($_SERVER['QUERY_STRING']);
    }
    $seoTitle = isset($company['name']) ? htmlspecialchars($company['name']) . ' | Company Details | JobVacancyResult' : 'Company Details | JobVacancyResult';
    $desc = isset($company['description']) ? htmlspecialchars($company['description']) : 'View company details, jobs, and more on JobVacancyResult.';
    ?>
    <title><?= $seoTitle ?></title>
    <meta name="description" content="<?= $desc ?>" />
    <meta name="robots" content="index, follow" />
    <link rel="canonical" href="<?= $canonicalUrl ?>" />
      <link rel="icon" href="/jvr-logo.jpg" width="32">

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
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;600;700&display=swap" rel="stylesheet">

    <?php
$ratingValue = 4.5;
$reviewCount = 23;
$reviews = [
    [
        "author" => "John Doe",
        "date" => "2024-11-01",
        "body" => "Great company to work with. Supportive management and good culture.",
        "rating" => 5
    ],
    [
        "author" => "Jane Smith",
        "date" => "2024-10-12",
        "body" => "Solid place to grow your career, though work-life balance could improve.",
        "rating" => 4
    ]
];
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "<?= htmlspecialchars($company['name']) ?>",
  "url": "<?= htmlspecialchars($company['website']) ?>",
  "logo": "<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jobvacancyresult/<?= htmlspecialchars($company['logo']) ?>",
  "description": "<?= htmlspecialchars(mb_strimwidth(strip_tags($company['description'] ?? ''), 0, 200, '...')) ?>",
  "foundingDate": "<?= htmlspecialchars($company['founded']) ?>",
  "numberOfEmployees": "<?= htmlspecialchars($company['company_size']) ?>",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?= $ratingValue ?>",
    "reviewCount": "<?= $reviewCount ?>"
  },
  "review": [
    <?php foreach ($reviews as $index => $r): ?>
    {
      "@type": "Review",
      "author": {
        "@type": "Person",
        "name": "<?= htmlspecialchars($r['author']) ?>"
      },
      "datePublished": "<?= $r['date'] ?>",
      "reviewBody": "<?= htmlspecialchars($r['body']) ?>",
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": "<?= $r['rating'] ?>",
        "bestRating": "5"
      }
    }<?= $index + 1 < count($reviews) ? ',' : '' ?>
    <?php endforeach; ?>
  ]
}
</script>

<?php if (!empty($jobs)): ?>
<?php foreach ($jobs as $j): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "JobPosting",
  "title": "<?= htmlspecialchars($j['title']) ?>",
  "description": "<?= htmlspecialchars(mb_strimwidth(strip_tags($j['description'] ?? ''), 0, 300, '...')) ?>",
  "datePosted": "<?= htmlspecialchars(date(DATE_ATOM, strtotime($j['created_at']))) ?>",
  "employmentType": "<?= htmlspecialchars($j['job_type']) ?>",
  "hiringOrganization": {
    "@type": "Organization",
    "name": "<?= htmlspecialchars($company['name']) ?>",
    "sameAs": "<?= htmlspecialchars($company['website']) ?>",
    "logo": "<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jobvacancyresult/<?= htmlspecialchars($company['logo']) ?>"
  },
  "jobLocation": {
    "@type": "Place",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "<?= htmlspecialchars($j['city']) ?>",
      "addressRegion": "<?= htmlspecialchars($j['state']) ?>",
      "addressCountry": "IN"
    }
  },
  "baseSalary": {
    "@type": "MonetaryAmount",
    "currency": "INR",
    "value": {
      "@type": "QuantitativeValue",
      "value": "<?= htmlspecialchars(preg_replace('/[^\d]/', '', $j['salary'])) ?>",
      "unitText": "MONTH"
    }
  }
}
</script>
<?php endforeach; ?>
<?php endif; ?>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jobvacancyresult/"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Companies",
      "item": "<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jobvacancyresult/companies"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "<?= htmlspecialchars($company['name']) ?>",
      "item": "<?= $canonicalUrl ?>"
    }
  ]
}
</script>

    <style>
        body {
            font-family: 'Google Sans', 'Roboto', sans-serif;
        }

        .fade-in {
            animation: fadeIn .3s ease-in-out
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .hover-lift {
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .job-card {
            transition: all .3s ease;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            position: relative;
            background: #fff;
        }

        .job-card:hover {
            border-color: #4285f4;
            box-shadow: 0 8px 20px rgba(66, 133, 244, .15);
        }

        .btn-primary {
            background: #4285f4;
            color: #fff;
            border-radius: 20px;
            padding: .5rem 1rem;
            font-weight: 500;
            transition: all .2s ease;
        }

        .btn-primary:hover {
            background: #3367d6;
        }

        .icon {
            width: 1rem;
            height: 1rem;
            display: inline-block;
            vertical-align: middle;
        }

        .google-colors {
            background: linear-gradient(90deg, #4285f4 0%, #34a853 25%, #fbbc05 50%, #ea4335 75%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .company-logo {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background: #f0f4f8;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .heart-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 20px;
            height: 20px;
            cursor: pointer;
            color: #cbd5e1;
            transition: color .2s;
        }

        .heart-icon:hover,
        .heart-icon.liked {
            color: #ea4335;
            fill: #ea4335;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .section-header hr {
            flex-grow: 1;
            border: none;
            border-top: 1px solid #e2e8f0;
            margin-left: 1rem;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 to-green-50 min-h-screen">
    <?php include 'include/header.php' ?>

    <div class="max-w-6xl mx-auto p-6">
        <!-- Company Header -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 fade-in">
            <div class="flex flex-col lg:flex-row items-center gap-6">
                <div class="w-32 h-32 bg-white rounded-lg shadow border flex items-center justify-center">
                    <img src="/<?= htmlspecialchars($company['logo'] ?: 'https://via.placeholder.com/128') ?>" alt="Logo" class="w-20 h-20 object-contain" />
                </div>
                <div class="flex-1 text-center lg:text-left">
                    <h1 class="text-4xl font-bold google-colors mb-2"><?= htmlspecialchars($company['name']) ?></h1>
                    <p class="text-gray-600 text-lg"><?= htmlspecialchars($company['industry_names'] ?: '—') ?></p>
                    <div class="flex flex-wrap gap-3 justify-center lg:justify-start mt-4 text-sm">
                        <?php if ($company['founded']): ?>
                            <span class="px-3 py-1 bg-blue-50 rounded-full text-blue-700">Founded <?= htmlspecialchars($company['founded']) ?></span>
                        <?php endif; ?>
                        <?php if ($company['company_size']): ?>
                            <span class="px-3 py-1 bg-green-50 rounded-full text-green-700"><?= htmlspecialchars($company['company_size']) ?> employees</span>
                        <?php endif; ?>
                        <?php if ($company['website']): ?>
                            <a href="<?= htmlspecialchars($company['website']) ?>" class="px-3 py-1 bg-yellow-50 rounded-full text-yellow-700" target="_blank">Visit website</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Sections -->
        <div class="grid  gap-6 mb-10">
            <div class="bg-white hover-lift p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-3">About Us</h2>
                <p class="text-gray-700"><?= nl2br(htmlspecialchars($company['description'] ?: '—')) ?></p>
            </div>
            <div class="bg-white hover-lift p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-3">Details</h2>
                <ul class="text-gray-700 space-y-2">
                    <li><strong>Type:</strong> <?= htmlspecialchars($company['company_type_name'] ?: '—') ?></li>
                    <li><strong>Nature:</strong> <?= htmlspecialchars($company['nature_names'] ?: '—') ?></li>
                    <?php if ($company['industry_names']): ?>
                        <li><strong>Industries:</strong> <?= htmlspecialchars($company['industry_names']) ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Jobs Section -->
        <div>
            <div class="section-header">
                <h2 class="text-2xl font-semibold text-gray-800">Current Opportunities</h2>
                <hr />
            </div>

            <?php if (empty($jobs)): ?>
                <p class="text-gray-600">No open positions at the moment.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    if (!function_exists('slugify')) {
                        function slugify($string)
                        {
                            $string = strtolower(trim($string));
                            $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
                            $string = preg_replace('/[\s-]+/', '-', $string);
                            return trim($string, '-');
                        }
                    }
                    ?>

                    <?php foreach ($jobs as $j): ?>
                        <?php
                        $slugTitle = slugify($j['title']);
                        $slugCity  = slugify($j['city']);
                        $jobId     = (int)$j['id'];
                        ?>
                        <div class="job-card">
                            <div class="flex justify-between items-center mb-4">
                                <div class="company-logo">
                                    <img src="/<?= htmlspecialchars($company['logo'] ?: 'https://via.placeholder.com/48') ?>" alt="Logo" />
                                </div>
                            </div>

                            <h3 class="text-lg font-semibold"><?= htmlspecialchars($j['title']) ?></h3>
                            <p class="text-sm text-gray-600 mb-3"><?= htmlspecialchars($company['name']) ?></p>

                            <div class="text-gray-600 text-sm space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-black">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <span><?= htmlspecialchars($j['city'] . ($j['state'] ? ', ' . $j['state'] : '')) ?></span>
                                </div>

                                <div class="flex items-center gap-2 text-sm text-black">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 4h12M6 8h12m-6 0a5 5 0 0 1-5 5h5l-6 7" />
                                    </svg>
                                    <span><?= htmlspecialchars($j['salary']) ?></span>
                                </div>

                                <div class="flex items-center gap-2 text-sm text-black">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span><?= htmlspecialchars($j['job_type']) ?></span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center">
                                <div><span class="text-xs text-black-900"><?= timeAgo($j['created_at']) ?></span></div>
                                <a href="/jobs/<?= $slugTitle ?>-in-<?= $slugCity ?>-<?= $jobId ?>" class="btn-primary">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="mt-8 flex justify-center flex-wrap gap-2">
                <?php
                $range = 2;
                $start = max(1, $page - $range);
                $end = min($total_pages, $page + $range);
                $baseUrl = "/jobvacancyresult/company/{$companySlug}-{$companyId}";
                ?>

                <!-- Previous -->
                <?php if ($page > 1): ?>
                    <a href="<?= $baseUrl ?>?page=<?= $page - 1 ?>" class="px-4 py-2 bg-white border text-gray-800 rounded-full hover:bg-gray-100">Previous</a>
                <?php endif; ?>

                <!-- First Page -->
                <?php if ($start > 1): ?>
                    <a href="<?= $baseUrl ?>?page=1" class="px-4 py-2 bg-white border text-gray-800 rounded-full hover:bg-gray-100">1</a>
                    <?php if ($start > 2): ?>
                        <span class="px-3 py-2 text-gray-400">...</span>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php for ($p = $start; $p <= $end; $p++): ?>
                    <a href="<?= $baseUrl ?>?page=<?= $p ?>"
                        class="px-4 py-2 <?= $p == $page ? 'bg-indigo-600 text-white' : 'bg-white border text-gray-800' ?> rounded-full hover:bg-gray-100">
                        <?= $p ?>
                    </a>
                <?php endfor; ?>

                <!-- Last Page -->
                <?php if ($end < $total_pages): ?>
                    <?php if ($end < $total_pages - 1): ?>
                        <span class="px-3 py-2 text-gray-400">...</span>
                    <?php endif; ?>
                    <a href="<?= $baseUrl ?>?page=<?= $total_pages ?>" class="px-4 py-2 bg-white border text-gray-800 rounded-full hover:bg-gray-100"><?= $total_pages ?></a>
                <?php endif; ?>

                <!-- Next -->
                <?php if ($page < $total_pages): ?>
                    <a href="<?= $baseUrl ?>?page=<?= $page + 1 ?>" class="px-4 py-2 bg-white border text-gray-800 rounded-full hover:bg-gray-100">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>


        <!-- Related Companies -->
        <div class="mt-12">
            <div class="section-header">
                <h2 class="text-2xl font-semibold text-gray-800">Similar Companies</h2>
                <hr />
            </div>
            <?php if (empty($related)): ?>
                <p class="text-gray-600">No related companies available.</p>
            <?php else: ?>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php foreach ($related as $r): ?>
                        <?php $relatedSlug = slugify($r['name']); ?>
                        <a href="/company/<?= $relatedSlug ?>-<?= $r['id'] ?>" class="bg-white hover-lift p-6 rounded-lg shadow text-center block">


                            <img src="/<?= htmlspecialchars($r['logo'] ?: 'https://via.placeholder.com/80') ?>" alt="<?= htmlspecialchars($r['name']) ?>" class="w-16 h-16 mx-auto rounded-md mb-4 object-contain" />
                            <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($r['name']) ?></h4>
                            <?php if (!empty($r['industry_names'])): ?>
                                <div class="flex flex-wrap justify-center gap-2 mt-2">
                                    <?php foreach (explode(',', $r['industry_names']) as $industry): ?>
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                                            <?= htmlspecialchars(trim($industry)) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'include/footer.php' ?>

</body>

</html>