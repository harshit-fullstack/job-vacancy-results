<?php
require_once 'include/db.php';

$industryId = $_GET['id'] ?? '';
if (empty($industryId)) {
    die('Invalid industry ID.');
}

// Fetch jobs for the selected industry
$sql = "SELECT 
            jobs.*, 
            companies.name AS company_name, 
            companies.logo AS company_logo 
        FROM jobs 
        LEFT JOIN companies ON jobs.company_id = companies.id 
        WHERE jobs.industry_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $industryId);
$stmt->execute();
$result = $stmt->get_result();

$jobs = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Construct location using city and state
        $row['location'] = trim(($row['city'] ?? '') . (isset($row['state']) && $row['state'] ? ', ' . $row['state'] : ''));
        $jobs[] = $row;
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <?php
  $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
  $canonicalUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
  if (!empty($_SERVER['QUERY_STRING'])) {
      $canonicalUrl .= '?' . htmlspecialchars($_SERVER['QUERY_STRING']);
  }
  $seoTitle = 'Industry Jobs | JobVacancyResult';
  $desc = 'Browse jobs by industry on JobVacancyResult. Find your next opportunity in your preferred sector.';
  ?>
  <title><?= $seoTitle ?></title>
  <meta name="description" content="<?= $desc ?>" />
    <link rel="icon" href="/jobvacancyresult/jvr-logo.jpg" width="32">

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
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <h1>Jobs in <?= htmlspecialchars($_GET['name'] ?? 'Selected Industry') ?></h1>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php if (empty($jobs)): ?>
      <p>No jobs found for this industry.</p>
    <?php else: ?>
      <?php foreach ($jobs as $job): ?>
        <div class="card">
          <img src="<?= htmlspecialchars($job['company_logo'] ?: 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($job['company_name']) ?> logo">
          <h2><?= htmlspecialchars($job['title']) ?></h2>
          <p><?= htmlspecialchars($job['company_name']) ?></p>
          <p><?= htmlspecialchars($job['location']) ?></p>
          <a href="jobs.php?id=<?= htmlspecialchars($job['id']) ?>">View Details</a>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>