<?php
require_once '../include/auth.php';
require_once '../include/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: companies.php');
    exit;
}
$company_id = (int)$_GET['id'];

// Fetch company info
$company = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT c.*, 
        i.name AS industry_name, 
        t.name AS company_type_name, 
        n.name AS nature_name
    FROM companies c
    LEFT JOIN industries i ON c.industry_id = i.id
    LEFT JOIN company_types t ON c.company_type_id = t.id
    LEFT JOIN nature_of_business n ON c.nature_of_business_id = n.id
    WHERE c.id = $company_id
"));
if (!$company) {
    echo "<h2 class='text-center text-red-600 mt-10'>Company not found.</h2>";
    exit;
}

// Fetch jobs for this company, grouped by department
$jobs_by_dept = [];
$jobs = mysqli_query($conn, "
    SELECT j.*, d.name AS department_name, CONCAT(j.city, IF(j.state != '', CONCAT(', ', j.state), '')) AS location
    FROM jobs j
    LEFT JOIN departments d ON j.department_id = d.id
    WHERE j.company_id = $company_id
    ORDER BY d.name, j.title
");
if ($jobs === false) {
    die('Jobs query error: ' . mysqli_error($conn));
}
while ($job = mysqli_fetch_assoc($jobs)) {
    $dept = $job['department_name'] ?: 'Other';
    $jobs_by_dept[$dept][] = $job;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title><?= htmlspecialchars($company['name']) ?> - Company Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <?php include '../include/navbar.php'; ?>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded shadow mt-8">
        <div class="flex items-center gap-6 mb-6">
            <?php
            $logo_web_path = !empty($company['logo']) ? $company['logo'] : '';
            $logo_file_path = !empty($company['logo']) ? $_SERVER['DOCUMENT_ROOT'] . '/jobvacancyresult/' . $company['logo'] : '';
            ?>
            <?php if (!empty($company['logo']) && file_exists($logo_file_path)): ?>
                <img src="/jobvacancyresult/<?= $logo_web_path ?>" alt="Logo" class="w-20 h-20 object-contain rounded bg-gray-100" />
            <?php else: ?>
                <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500">No Logo</div>
            <?php endif; ?>
            <div>
                <h1 class="text-2xl font-bold text-blue-800"><?= htmlspecialchars($company['name']) ?></h1>
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
                <div class="flex flex-wrap gap-2 mt-1 text-xs text-gray-500">
                    <?php if (!empty($company['founded'])): ?>
                        <span>Founded: <?= htmlspecialchars($company['founded']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($company['company_size'])): ?>
                        <span>Size: <?= htmlspecialchars($company['company_size']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if (!empty($company['description'])): ?>
            <div class="mb-6 text-gray-700 text-sm"><?= nl2br(htmlspecialchars($company['description'])) ?></div>
        <?php endif; ?>

        <h2 class="text-lg font-semibold text-blue-700 mb-3">Jobs at <?= htmlspecialchars($company['name']) ?></h2>
        <?php if (!empty($jobs_by_dept)): ?>
            <?php foreach ($jobs_by_dept as $dept => $jobs): ?>
                <div class="mb-4">
                    <div class="font-semibold text-blue-600 flex items-center">
                        <?= htmlspecialchars($dept) ?>
                        <span class="ml-2 bg-blue-100 text-blue-700 rounded-full px-2 py-0.5 text-xs font-bold"><?= count($jobs) ?></span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 mt-1">
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
            <div class="text-xs text-gray-400 italic">No jobs listed for this company.</div>
        <?php endif; ?>
        <div class="mt-6">
            <a href="companies.php" class="text-blue-700 hover:underline">&larr; Back to Companies</a>
        </div>
    </div>
</body>

</html>