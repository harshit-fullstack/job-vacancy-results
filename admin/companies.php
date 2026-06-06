<?php
require_once '../include/auth.php';
require_once '../include/db.php';

// Handle search query
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch all companies with their industry/type/nature names
$companies = [];
$sql = "
    SELECT c.*, 
        i.name AS industry_name, 
        t.name AS company_type_name, 
        n.name AS nature_name
    FROM companies c
    LEFT JOIN industries i ON c.industry_id = i.id
    LEFT JOIN company_types t ON c.company_type_id = t.id
    LEFT JOIN nature_of_business n ON c.nature_of_business_id = n.id
";
if (!empty($search_query)) {
    $sql .= " WHERE c.name LIKE '%" . mysqli_real_escape_string($conn, $search_query) . "%'";
}
$sql .= " ORDER BY c.name";

$result = mysqli_query($conn, $sql);
if ($result === false) {
    die('Companies query error: ' . mysqli_error($conn));
}
while ($row = mysqli_fetch_assoc($result)) {
    $companies[] = $row;
}

// Fetch jobs for all companies, grouped by company and department
$jobs_by_company = [];
$jobs = mysqli_query($conn, "
    SELECT j.*, c.id AS company_id, c.name AS company_name, d.name AS department_name,
           CONCAT(j.city, IF(j.state != '', CONCAT(', ', j.state), '')) AS location
    FROM jobs j
    INNER JOIN companies c ON j.company_id = c.id
    LEFT JOIN departments d ON j.department_id = d.id
    ORDER BY c.name, d.name, j.title
");
if ($jobs === false) {
    die('Jobs query error: ' . mysqli_error($conn));
}
while ($job = mysqli_fetch_assoc($jobs)) {
    $cid = $job['company_id'];
    $dept = $job['department_name'] ?: 'Other';
    $jobs_by_company[$cid][$dept][] = $job;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Companies & Jobs - Job Vacancy Result</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <?php include '../include/navbar.php'; ?>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-8 text-center text-blue-800">Companies & Their Jobs</h1>
        <form method="GET" class="mb-6">
            <input 
                type="text" 
                name="search" 
                value="<?= htmlspecialchars($search_query) ?>" 
                placeholder="Search companies..." 
                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
        </form>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($companies)): ?>
                <?php foreach ($companies as $company): ?>
                    <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col">
                        <?php
                        $logo_web_path = !empty($company['logo']) ? $company['logo'] : '';
                        $logo_file_path = !empty($company['logo']) ? $_SERVER['DOCUMENT_ROOT'] . '/jobvacancyresult/' . ltrim($company['logo'], '/') : '';
                        ?>
                        <div class="flex items-center gap-4 mb-4">
                            <?php if (!empty($company['logo']) && file_exists($logo_file_path)): ?>
                                <img src="/jobvacancyresult/<?= htmlspecialchars($logo_web_path) ?>" alt="Logo" class="w-16 h-16 object-contain rounded bg-gray-100" />
                            <?php else: ?>
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Logo</div>
                            <?php endif; ?>
                            <div>
                                <h2 class="text-xl font-semibold text-blue-700">
                                    <a href="company.php?id=<?= $company['id'] ?>" class="hover:underline"><?= htmlspecialchars($company['name']) ?></a>
                                </h2>
                                <div class="text-xs text-gray-600">
                                    <?= htmlspecialchars($company['industry_name'] ?? '') ?>
                                    <?php if (!empty($company['company_type_name'])): ?>
                                        | <?= htmlspecialchars($company['company_type_name']) ?>
                                    <?php endif; ?>
                                    <?php if (!empty($company['nature_name'])): ?>
                                        | <?= htmlspecialchars($company['nature_name']) ?>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($company['website'])): ?>
                                    <a href="<?= htmlspecialchars($company['website']) ?>" target="_blank" class="text-xs text-blue-500 hover:underline"><?= htmlspecialchars($company['website']) ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($company['description'])): ?>
                            <div class="mb-3 text-gray-700 text-sm"><?= nl2br(htmlspecialchars($company['description'])) ?></div>
                        <?php endif; ?>
                        <div class="flex flex-wrap gap-2 mb-4 text-xs text-gray-500">
                            <?php if (!empty($company['founded'])): ?>
                                <span>Founded: <?= htmlspecialchars($company['founded']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($company['company_size'])): ?>
                                <span>Size: <?= htmlspecialchars($company['company_size']) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="mt-2">
                            <h3 class="text-base font-semibold text-blue-800 mb-2">Jobs</h3>
                            <?php if (!empty($jobs_by_company[$company['id']])): ?>
                                <?php foreach ($jobs_by_company[$company['id']] as $dept => $jobs): ?>
                                    <div class="mb-3">
                                        <div class="font-semibold text-sm text-blue-600 mb-1 flex items-center">
                                            <?= htmlspecialchars($dept) ?>
                                            <span class="ml-2 bg-blue-100 text-blue-700 rounded-full px-2 py-0.5 text-xs font-bold">
                                                <?= count($jobs) ?>
                                            </span>
                                        </div>
                                        <ul class="list-disc list-inside space-y-1">
                                            <?php foreach ($jobs as $job): ?>
                                                <li>
                                                    <span class="font-medium"><?= htmlspecialchars($job['title']) ?></span>
                                                    <?php if (!empty($job['location'])): ?>
                                                        <span class="text-xs text-gray-500"> (<?= htmlspecialchars($job['location']) ?>)</span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($job['salary'])): ?>
                                                        <span class="text-xs text-green-700 ml-2"><?= htmlspecialchars($job['salary']) ?></span>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-xs text-gray-400 italic">No jobs listed.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center text-gray-500 italic">No companies found.</div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>