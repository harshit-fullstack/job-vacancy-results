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

// Pagination
$limit = 12;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search
$search = $_GET['search'] ?? '';
$searchParam = '%' . $search . '%';

// Count total
$countSql = "
  SELECT COUNT(DISTINCT companies.id) AS total
  FROM companies
  LEFT JOIN jobs ON companies.id = jobs.company_id
  WHERE companies.name LIKE ?
";
$countStmt = $conn->prepare($countSql);
$countStmt->bind_param("s", $searchParam);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalCompanies = ($countResult && $countResult->num_rows > 0) ? $countResult->fetch_assoc()['total'] : 0;
$countStmt->close();
$totalPages = ceil($totalCompanies / $limit);

// Fetch paginated companies
$sql = "
  SELECT 
    companies.*, 
    COUNT(DISTINCT jobs.id) AS job_count,
    GROUP_CONCAT(DISTINCT industries.name SEPARATOR ', ') AS industries
  FROM companies
  LEFT JOIN jobs ON companies.id = jobs.company_id
  LEFT JOIN industries ON FIND_IN_SET(industries.id, companies.industry_ids)
  WHERE companies.name LIKE ?
  GROUP BY companies.id
  ORDER BY job_count DESC
  LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $searchParam, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
$companies = [];
while ($row = $result->fetch_assoc()) {
  $companies[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <?php
  $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
  $canonicalUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
  $seoTitle = 'Companies | JobVacancyResult';
  $desc = 'Browse companies hiring on JobVacancyResult. View company profiles, open jobs, and more.';
  ?>
  <title><?= htmlspecialchars($seoTitle) ?></title>
    <link rel="icon" href="/jobvacancyresult/jvr-logo.jpg" width="32">

  <meta name="description" content="<?= htmlspecialchars($desc) ?>" />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>" />
  <!-- Open Graph Meta -->
  <meta property="og:title" content="<?= htmlspecialchars($seoTitle) ?>" />
  <meta property="og:description" content="<?= htmlspecialchars($desc) ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>" />
  <meta property="og:site_name" content="JobVacancyResult" />
  <meta property="og:image" content="<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jvr-logo.jpg" />
  <!-- Twitter Card Meta -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?= htmlspecialchars($seoTitle) ?>" />
  <meta name="twitter:description" content="<?= htmlspecialchars($desc) ?>" />
  <meta name="twitter:image" content="<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jvr-logo.jpg" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>

  <!-- ✅ JSON-LD SCHEMA -->
  <?php
  $ratingValue = 4.4;
  $reviewCount = 17;
  $sampleReviews = [
    [
      "author" => "John D.",
      "date" => "2024-12-01",
      "body" => "Excellent workplace with transparent leadership.",
      "rating" => 5
    ],
    [
      "author" => "Anjali K.",
      "date" => "2024-11-15",
      "body" => "Good learning curve and growth opportunities.",
      "rating" => 4
    ]
  ];
  ?>
  <?php foreach ($companies as $company): ?>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "<?= htmlspecialchars($company['name']) ?>",
      "url": "<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jobvacancyresult/company/<?= slugify($company['name']) ?>-<?= (int)$company['id'] ?>",
      "logo": "<?= htmlspecialchars($company['logo'] ?: 'https://placehold.co/100x100') ?>",
      "description": "<?= htmlspecialchars($company['industries'] ?: 'Browse company profile, jobs, and more') ?>",
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "<?= $ratingValue ?>",
        "reviewCount": "<?= $reviewCount ?>"
      },
      "review": [
        <?php foreach ($sampleReviews as $index => $r): ?>
        {
          "@type": "Review",
          "author": { "@type": "Person", "name": "<?= htmlspecialchars($r['author']) ?>" },
          "datePublished": "<?= $r['date'] ?>",
          "reviewBody": "<?= htmlspecialchars($r['body']) ?>",
          "reviewRating": { "@type": "Rating", "ratingValue": "<?= $r['rating'] ?>", "bestRating": "5" }
        }<?= $index + 1 < count($sampleReviews) ? ',' : '' ?>
        <?php endforeach; ?>
      ]
    }
    </script>
  <?php endforeach; ?>

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
        "item": "<?= htmlspecialchars($canonicalUrl) ?>"
      }
    ]
  }
  </script>
</head>

<body class="bg-gray-50 text-blue-900">
  <?php include 'include/header.php'; ?>

  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-bold text-blue-800 mb-6 text-center">Top Hiring Companies</h1>

    <form method="get" class="flex flex-col sm:flex-row items-center gap-4 mb-10 justify-center">
      <input type="text" name="search" placeholder="Search companies..." value="<?= htmlspecialchars($search) ?>"
        class="w-full sm:w-80 rounded-lg border border-gray-300 px-4 py-2 text-base focus:outline-none focus:ring-2 focus:ring-blue-500" />

      <button type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl font-semibold transition-all">Search</button>

      <?php if (!empty($search)): ?>
        <a href="?" class="text-sm text-red-600 hover:text-red-700 font-medium underline">Clear All</a>
      <?php endif; ?>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php if (empty($companies)): ?>
        <p class="col-span-full text-center text-gray-500">No companies found.</p>
      <?php else: ?>
        <?php foreach ($companies as $company): ?>
          <?php $slug = slugify($company['name']); ?>
          <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg transition duration-300 text-center">
            <img loading="lazy" src="<?= htmlspecialchars($company['logo'] ?: 'https://placehold.co/100x100') ?>"
              alt="Logo of <?= htmlspecialchars($company['name']) ?>"
              class="w-20 h-20 object-contain mx-auto mb-4 rounded-lg" />

            <h2 class="text-xl font-bold text-blue-900 mb-2"><?= htmlspecialchars($company['name']) ?></h2>

            <p class="text-sm text-gray-600 mb-2">
              <strong>Industries:</strong><br>
              <?= htmlspecialchars($company['industries'] ?: 'N/A') ?>
            </p>

            <p class="text-sm text-gray-600 mb-4">
              <strong>Available Jobs:</strong> <?= (int)$company['job_count'] ?>
            </p>

            <a href="company/<?= $slug ?>-<?= (int)$company['id'] ?>"
              class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl font-semibold transition-all inline-block mt-auto">
              View Details
            </a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
      <div class="mt-10 flex justify-center items-center gap-2 flex-wrap text-sm font-medium">
        <?php
        $baseParams = [];
        if (!empty($search)) $baseParams['search'] = $search;

        $start = max(1, $page - 2);
        $end = min($totalPages, $page + 2);

        if ($page > 1) {
          echo '<a href="?' . http_build_query(array_merge($baseParams, ['page' => $page - 1])) . '" class="px-3 py-2 rounded border bg-white text-blue-600 hover:bg-blue-50">Prev</a>';
        }
        if ($start > 1) {
          echo '<a href="?' . http_build_query(array_merge($baseParams, ['page' => 1])) . '" class="px-3 py-2 rounded border bg-white text-blue-600 hover:bg-blue-50">1</a>';
          if ($start > 2) echo '<span class="px-3 py-2 text-gray-400">...</span>';
        }

        for ($i = $start; $i <= $end; $i++) {
          $active = $i === $page ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 hover:bg-blue-50';
          echo '<a href="?' . http_build_query(array_merge($baseParams, ['page' => $i])) . "\" class=\"px-3 py-2 rounded border $active\">$i</a>";
        }

        if ($end < $totalPages) {
          if ($end < $totalPages - 1) echo '<span class="px-3 py-2 text-gray-400">...</span>';
          echo '<a href="?' . http_build_query(array_merge($baseParams, ['page' => $totalPages])) . '" class="px-3 py-2 rounded border bg-white text-blue-600 hover:bg-blue-50">' . $totalPages . '</a>';
        }

        if ($page < $totalPages) {
          echo '<a href="?' . http_build_query(array_merge($baseParams, ['page' => $page + 1])) . '" class="px-3 py-2 rounded border bg-white text-blue-600 hover:bg-blue-50">Next</a>';
        }
        ?>
      </div>
    <?php endif; ?>
  </section>

  <?php include 'include/footer.php'; ?>
</body>

</html>
