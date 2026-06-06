<?php
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require_once '../include/auth.php';
require_once '../include/db.php';

// Check database connection
$connection_ok = $conn ? true : false;

// Count jobs
$job_count_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM jobs");
$job_count = $job_count_result ? mysqli_fetch_assoc($job_count_result)['total'] : 0;

// Fetch recent jobs
$recent_jobs = mysqli_query($conn, "SELECT title, company, created_at FROM jobs ORDER BY created_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Backend Status - JVR</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

  <?php include '../include/navbar.php'; ?>

  <main class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Backend Status</h1>

    <div class="bg-white rounded shadow p-6 mb-6">
      <h2 class="text-xl font-semibold mb-4">Database Connection</h2>
      <p class="<?= $connection_ok ? 'text-green-600' : 'text-red-600' ?>">
        <?= $connection_ok ? '✅ Connected successfully to the database.' : '❌ Failed to connect to the database.' ?>
      </p>
    </div>

    <div class="bg-white rounded shadow p-6 mb-6">
      <h2 class="text-xl font-semibold mb-4">Total Jobs</h2>
      <p class="text-gray-700 text-lg">📝 <?= $job_count ?> job(s) in the database.</p>
    </div>

    <div class="bg-white rounded shadow p-6">
      <h2 class="text-xl font-semibold mb-4">Recent Jobs</h2>
      <?php if ($recent_jobs && mysqli_num_rows($recent_jobs) > 0): ?>
        <ul class="list-disc pl-5 space-y-2 text-gray-800">
          <?php while ($job = mysqli_fetch_assoc($recent_jobs)): ?>
            <li>
              <strong><?= htmlspecialchars($job['title']) ?></strong> at <?= htmlspecialchars($job['company']) ?>
              <span class="text-sm text-gray-500">(<?= date('d M Y', strtotime($job['created_at'])) ?>)</span>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p class="text-gray-500">No jobs found.</p>
      <?php endif; ?>
    </div>
  </main>

</body>
</html>
