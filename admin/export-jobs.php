<?php
require_once '../include/auth.php';
require_once '../include/db.php';

// Filters
$search = $_GET['search'] ?? '';
$industryFilter = $_GET['industry'] ?? '';
$jobTypeFilter = $_GET['job_type'] ?? '';
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';
$salaryMin = $_GET['salary_min'] ?? '';
$salaryMax = $_GET['salary_max'] ?? '';

// Build query with all required joins
$query = "
    SELECT 
        jobs.*, 
        companies.name AS company_name,
        industries.name AS industry_name,
        role_categories.name AS role_category_name
    FROM jobs
    LEFT JOIN companies ON jobs.company_id = companies.id
    LEFT JOIN industries ON jobs.industry_id = industries.id
    LEFT JOIN role_categories ON jobs.role_category_id = role_categories.id
    WHERE 1=1
";

// Apply filters
if (!empty($search)) {
    $searchEsc = mysqli_real_escape_string($conn, $search);
    $query .= " AND (
        jobs.title LIKE '%$searchEsc%' OR
        jobs.city LIKE '%$searchEsc%' OR
        jobs.state LIKE '%$searchEsc%' OR
        companies.name LIKE '%$searchEsc%'
    )";
}

if (!empty($industryFilter)) {
    $query .= " AND jobs.industry_id = " . intval($industryFilter);
}

if (!empty($jobTypeFilter)) {
    $jobTypeEsc = mysqli_real_escape_string($conn, $jobTypeFilter);
    $query .= " AND jobs.job_type = '$jobTypeEsc'";
}

if (!empty($dateFrom)) {
    $query .= " AND jobs.created_at >= '" . mysqli_real_escape_string($conn, $dateFrom) . " 00:00:00'";
}

if (!empty($dateTo)) {
    $query .= " AND jobs.created_at <= '" . mysqli_real_escape_string($conn, $dateTo) . " 23:59:59'";
}

if (is_numeric($salaryMin)) {
    $query .= " AND jobs.salary >= " . (float)$salaryMin;
}

if (is_numeric($salaryMax)) {
    $query .= " AND jobs.salary <= " . (float)$salaryMax;
}

$query .= " ORDER BY jobs.industry_id ASC, jobs.created_at DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die('Database query error: ' . mysqli_error($conn));
}

// CSV headers
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=jobs_export_' . date('Y-m-d') . '.csv');

// Open output
$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, [
    'ID',
    'Title',
    'Company Name',
    'Job Description',
    'Additional Info',
    'State',
    'City',
    'Industry',
    'Role Category',
    'Skills',
    'Type of Jobs',
    'Education',
    'Work Mode',
    'Experience',
    'Salary',
    'Job Link',
    'Created At'
]);

// Write job rows
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['title'],
        $row['company_name'] ?? 'N/A',
        $row['description'],
        $row['additional_info'],
        $row['state'],
        $row['city'],
        $row['industry_name'] ?? 'N/A',
        $row['role_category_name'] ?? 'N/A',
        $row['skills'],
        $row['job_type'],
        $row['education'],
        $row['work_mode'],
        $row['experience'],
        $row['salary'],
        $row['job_link'],
        $row['created_at']
    ]);
}

fclose($output);
exit;
