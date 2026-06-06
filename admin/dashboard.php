<?php

require_once '../include/auth.php';

require_once '../include/db.php';
// session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// --- Pagination variables ---
$perPage = 25;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

// Get filter inputs safely
$search = $_GET['search'] ?? '';
$industryFilter = $_GET['industry'] ?? '';
$jobTypeFilter = $_GET['job_type'] ?? '';

// Build base query
$query = "SELECT j.*, c.name AS company_name, i.name AS industry_name
          FROM jobs j
          LEFT JOIN companies c ON j.company_id = c.id
          LEFT JOIN industries i ON j.industry_id = i.id
          WHERE 1=1";

// Search filter (title, company, city, state)
if (!empty($search)) {
    $searchEsc = mysqli_real_escape_string($conn, $search);
    $query .= " AND (
        j.title LIKE '%$searchEsc%' OR 
        c.name LIKE '%$searchEsc%' OR 
        j.city LIKE '%$searchEsc%' OR 
        j.state LIKE '%$searchEsc%'
    )";
}

// Industry filter
if (!empty($industryFilter)) {
    $industryEsc = mysqli_real_escape_string($conn, $industryFilter);
    $query .= " AND j.industry_id = '$industryEsc'";
}

// Job type filter
if (!empty($jobTypeFilter)) {
    $jobTypeEsc = mysqli_real_escape_string($conn, $jobTypeFilter);
    $query .= " AND j.job_type = '$jobTypeEsc'";
}

$query .= " ORDER BY i.name ASC, j.created_at DESC";

// Get total count for pagination
$countQuery = "SELECT COUNT(*) as total
               FROM jobs j
               LEFT JOIN companies c ON j.company_id = c.id
               LEFT JOIN industries i ON j.industry_id = i.id
               WHERE 1=1";

if (!empty($search)) {
    $searchEsc = mysqli_real_escape_string($conn, $search);
    $countQuery .= " AND (
        j.title LIKE '%$searchEsc%' OR 
        c.name LIKE '%$searchEsc%' OR 
        j.city LIKE '%$searchEsc%' OR 
        j.state LIKE '%$searchEsc%'
    )";
}
if (!empty($industryFilter)) {
    $industryEsc = mysqli_real_escape_string($conn, $industryFilter);
    $countQuery .= " AND j.industry_id = '$industryEsc'";
}
if (!empty($jobTypeFilter)) {
    $jobTypeEsc = mysqli_real_escape_string($conn, $jobTypeFilter);
    $countQuery .= " AND j.job_type = '$jobTypeEsc'";
}

$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalJobsFiltered = (int)$countRow['total'];

// Append LIMIT for pagination
$query .= " LIMIT $perPage OFFSET $offset";

$result = mysqli_query($conn, $query);

// Group jobs by industry name
$industries = [];
while ($row = mysqli_fetch_assoc($result)) {
    $industryName = $row['industry_name'] ?? 'Other';
    $industries[$industryName][] = $row;
}

// Fetch all industries for dropdown (show all, not just those with jobs)
$industryList = mysqli_query($conn, "
    SELECT id, name 
    FROM industries
    ORDER BY name ASC
");
// Fetch distinct job types for dropdown
$jobTypeList = mysqli_query($conn, "SELECT DISTINCT job_type FROM jobs ORDER BY job_type ASC");

// Count industries shown in current page
$totalIndustries = count($industries);

// Fetch featured jobs count
$featuredCountQuery = "SELECT COUNT(*) as total_featured FROM jobs WHERE is_featured = 1";
$featuredCountResult = mysqli_query($conn, $featuredCountQuery);
$featuredCountRow = mysqli_fetch_assoc($featuredCountResult);
$totalFeatured = (int)$featuredCountRow['total_featured'];

// Fetch featured jobs
$featuredQuery = "SELECT j.*, c.name AS company_name
                  FROM jobs j
                  LEFT JOIN companies c ON j.company_id = c.id
                  WHERE j.is_featured = 1
                  ORDER BY j.created_at DESC";
$featuredResult = mysqli_query($conn, $featuredQuery);
$featuredJobs = [];
while ($row = mysqli_fetch_assoc($featuredResult)) {
    $featuredJobs[] = $row;
}

function buildQueryString($overrides = []) {
    $params = $_GET;
    foreach ($overrides as $key => $value) {
        $params[$key] = $value;
    }
    return http_build_query($params);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard - JVR</title>
  <link rel="icon" type="/jvr-logo.jpg" href="/jvr-logo.jpg" sizes="32x32" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function toggleSection(id) {
      document.getElementById(id).classList.toggle("hidden");
    }
    function clearFilters() {
      window.location.href = 'dashboard.php';
    }
  </script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

  <?php include '../include/navbar.php'; ?>

  <div class="max-w-7xl mx-auto px-6 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">📊 Job Overview Dashboard</h1>

    <!-- Filters -->
    <form method="GET" class="mb-8 bg-white p-6 rounded shadow grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Search</label>
        <input
          type="text"
          name="search"
          value="<?= htmlspecialchars($search) ?>"
          class="px-3 py-2 border border-gray-300 rounded w-full"
          placeholder="Title, company, city, state"
          aria-label="Search jobs by title, company, city or state"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Industry</label>
        <select name="industry" class="px-3 py-2 border border-gray-300 rounded w-full" aria-label="Filter by industry">
          <option value="">All Industries</option>
          <?php while ($ind = mysqli_fetch_assoc($industryList)) : ?>
            <option value="<?= htmlspecialchars($ind['id']) ?>" <?= $industryFilter === (string)$ind['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($ind['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-600 mb-1">Job Type</label>
        <select name="job_type" class="px-3 py-2 border border-gray-300 rounded w-full" aria-label="Filter by job type">
          <option value="">All Job Types</option>
          <?php while ($jt = mysqli_fetch_assoc($jobTypeList)) : ?>
            <option value="<?= htmlspecialchars($jt['job_type']) ?>" <?= $jobTypeFilter === $jt['job_type'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($jt['job_type']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="flex space-x-2 justify-end">
        <button
          type="submit"
          class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition"
          aria-label="Apply filters"
        >
          Search
        </button>
        <button
          type="button"
          onclick="clearFilters()"
          class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400 transition"
          aria-label="Clear filters"
        >
          Clear
        </button>
      </div>

    </form>

    <!-- Export button -->
    <div class="mb-6">
      <a
        href="export-jobs.php?<?= buildQueryString() ?>"
        class="inline-block bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 transition"
        aria-label="Export filtered jobs as CSV"
        target="_blank" rel="noopener noreferrer"
      >
        Export CSV
      </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white shadow rounded p-6">
        <h3 class="text-sm text-gray-500 uppercase">Total Jobs (Filtered)</h3>
        <p class="text-2xl font-semibold text-blue-700"><?= $totalJobsFiltered ?></p>
      </div>
      <div class="bg-white shadow rounded p-6">
        <h3 class="text-sm text-gray-500 uppercase">Industries (Shown)</h3>
        <p class="text-2xl font-semibold text-green-600"><?= $totalIndustries ?></p>
      </div>
      <div class="bg-white shadow rounded p-6">
        <h3 class="text-sm text-gray-500 uppercase">Featured Jobs</h3>
        <p class="text-2xl font-semibold text-yellow-600"><?= $totalFeatured ?></p>
      </div>
      <div class="bg-white shadow rounded p-6">
        <h3 class="text-sm text-gray-500 uppercase">Last Update</h3>
        <p class="text-2xl font-semibold text-gray-700"><?= date("d M Y") ?></p>
      </div>
    </div>

    <!-- Featured Jobs Section -->
    <?php if (!empty($featuredJobs)): ?>
      <section class="mb-8 border border-gray-200 rounded bg-white shadow">
        <div class="flex justify-between items-center px-6 py-4 bg-yellow-100 border-b">
          <h2 class="text-lg font-semibold text-yellow-800">⭐ Featured Jobs (<?= count($featuredJobs) ?> shown, Total: <?= $totalFeatured ?>)</h2>
          <button onclick="toggleSection('featured-section')" class="text-sm text-yellow-600 hover:underline" aria-expanded="true" aria-controls="featured-section">Toggle View</button>
        </div>
        <div id="featured-section" class="overflow-x-auto">
          <table class="min-w-full table-auto text-sm" role="table">
            <thead class="bg-yellow-700 text-white">
              <tr>
                <th class="px-4 py-2 text-left" scope="col">Title</th>
                <th class="px-4 py-2 text-left" scope="col">Company</th>
                <th class="px-4 py-2 text-left" scope="col">Location</th>
                <th class="px-4 py-2 text-left" scope="col">Job Type</th>
                <th class="px-4 py-2 text-left" scope="col">Created</th>
                <th class="px-4 py-2 text-left" scope="col">Salary</th>
                <th class="px-4 py-2 text-left" scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($featuredJobs as $job): ?>
                <tr class="hover:bg-gray-50 border-b">
                  <td class="px-4 py-2 font-medium text-gray-800"><?= htmlspecialchars($job['title']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($job['company_name']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($job['city'] . ', ' . $job['state']) ?></td>
                  <td class="px-4 py-2">
                    <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs"><?= htmlspecialchars($job['job_type']) ?></span>
                  </td>
                  <td class="px-4 py-2 text-gray-600"><?= date('d M Y', strtotime($job['created_at'])) ?></td>
                  <td class="px-4 py-2 text-gray-700 font-semibold"><?= htmlspecialchars($job['salary']) ?></td>
                  <td class="px-4 py-2 space-x-2">
                    <a href="edit-job.php?id=<?= $job['id'] ?>" class="text-indigo-600 hover:underline text-sm" aria-label="Edit job <?= htmlspecialchars($job['title']) ?>">Edit</a>
                    <button> <a onclick="openDeleteModal(<?= $job['id'] ?>)" class="text-red-600 hover:underline text-sm" aria-label="Delete job <?= htmlspecialchars($job['title']) ?>">Delete</a></button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>
    <?php endif; ?>

    <!-- Jobs by Industry -->
    <?php if (!empty($industries)): ?>
      <?php foreach ($industries as $industry => $jobs): ?>
        <section class="mb-8 border border-gray-200 rounded bg-white shadow">
          <div class="flex justify-between items-center px-6 py-4 bg-gray-100 border-b">
            <h2 class="text-lg font-semibold text-blue-800"><?= htmlspecialchars($industry) ?> (<?= count($jobs) ?> jobs)</h2>
            <button onclick="toggleSection('section-<?= md5($industry) ?>')" class="text-sm text-blue-600 hover:underline" aria-expanded="true" aria-controls="section-<?= md5($industry) ?>">Toggle View</button>
          </div>
          <div id="section-<?= md5($industry) ?>" class="overflow-x-auto">
            <table class="min-w-full table-auto text-sm" role="table">
              <thead class="bg-blue-700 text-white">
                <tr>
                  <th class="px-4 py-2 text-left" scope="col">Title</th>
                  <th class="px-4 py-2 text-left" scope="col">Company</th>
                  <th class="px-4 py-2 text-left" scope="col">Location</th>
                  <th class="px-4 py-2 text-left" scope="col">Job Type</th>
                  <th class="px-4 py-2 text-left" scope="col">Created</th>
                  <th class="px-4 py-2 text-left" scope="col">Salary</th>
                  <th class="px-4 py-2 text-left" scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($jobs as $job): ?>
                  <tr class="hover:bg-gray-50 border-b">
                    <td class="px-4 py-2 font-medium text-gray-800"><?= htmlspecialchars($job['title']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($job['company_name']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($job['city'] . ', ' . $job['state']) ?></td>
                    <td class="px-4 py-2">
                      <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs"><?= htmlspecialchars($job['job_type']) ?></span>
                    </td>
                    <td class="px-4 py-2 text-gray-600"><?= date('d M Y', strtotime($job['created_at'])) ?></td>
                    <td class="px-4 py-2 text-gray-700 font-semibold"><?= htmlspecialchars($job['salary']) ?></td>
                    <td class="px-4 py-2 space-x-2">
                      <a href="edit-job.php?id=<?= $job['id'] ?>" class="text-indigo-600 hover:underline text-sm" aria-label="Edit job <?= htmlspecialchars($job['title']) ?>">Edit</a>
                      <button> <a onclick="openDeleteModal(<?= $job['id'] ?>)" class="text-red-600 hover:underline text-sm" aria-label="Delete job <?= htmlspecialchars($job['title']) ?>">Delete</a></button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </section>
      <?php endforeach; ?>

      <!-- Pagination -->
      <?php 
      $totalPages = ceil($totalJobsFiltered / $perPage);
      if ($totalPages > 1): ?>
      <nav aria-label="Page navigation" class="flex justify-center items-center space-x-2 my-8">
        <?php if ($page > 1): ?>
          <a href="?<?= buildQueryString(['page' => $page - 1]) ?>" class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">Prev</a>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
          <a href="?<?= buildQueryString(['page' => $p]) ?>" 
             class="px-3 py-1 rounded <?= $p === $page ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' ?>"
             aria-current="<?= $p === $page ? 'page' : 'false' ?>">
            <?= $p ?>
          </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <a href="?<?= buildQueryString(['page' => $page + 1]) ?>" class="px-3 py-1 rounded bg-gray-200 hover:bg-gray-300">Next</a>
        <?php endif; ?>
      </nav>
      <?php endif; ?>

    <?php else: ?>
      <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded">No jobs found for this filter.</div>
    <?php endif; ?>

  </div>

  <!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Are you sure you want to delete this job?</h2>
    <div class="flex justify-end space-x-2">
      <button 
        onclick="closeDeleteModal()" 
        class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400"
      >
        Cancel
      </button>
      <a 
        id="confirmDeleteBtn" 
        href="#" 
        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
      >
        Delete
      </a>
    </div>
  </div>
</div>
<script>
  function openDeleteModal(jobId) {
    document.getElementById('confirmDeleteBtn').href = 'delete-job.php?id=' + jobId;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
  }

  function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
  }
</script>

</body>
</html>
