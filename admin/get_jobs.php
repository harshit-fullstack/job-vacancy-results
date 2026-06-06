<?php
header('Content-Type: application/json');
require_once '../include/db.php';
require_once '../include/auth.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$experience = isset($_GET['experience']) ? trim($_GET['experience']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

$where = [];
$params = [];

if ($search !== '') {
    $where[] = "(jobs.title LIKE ? OR jobs.company LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($location !== '') {
    $where[] = "(jobs.city LIKE ? OR jobs.state LIKE ?)";
    $params[] = "%$location%";
    $params[] = "%$location%";
}
if ($experience !== '') {
    $where[] = "jobs.experience LIKE ?";
    $params[] = "%$experience%";
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "SELECT jobs.*, companies.logo 
        FROM jobs 
        LEFT JOIN companies ON jobs.company = companies.name 
        $whereSql 
        ORDER BY jobs.created_at DESC 
        LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($sql);

if ($params) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$jobs = [];
while ($row = $result->fetch_assoc()) {
    $skills = array_filter(array_map('trim', explode(',', $row['skills'] ?? '')));
    $jobs[] = [
        'id' => (int)$row['id'],
        'company' => $row['company'],
        'title' => $row['title'],
        'location' => trim($row['city'] . ', ' . $row['state'], ', '),
        'salary' => $row['salary'] ?: 'Not Disclosed',
        'type' => $row['job_type'] ?: '',
        'badges' => [$row['job_type']],
        'logo' => $row['logo'] ?: '',
        'skills' => $skills,
        'experience' => $row['experience'] ?: '',
        'job_link' => $row['job_link'] ?: ''
    ];
}
echo json_encode($jobs);