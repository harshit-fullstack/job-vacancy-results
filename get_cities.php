<?php
require_once 'include/db.php';
header('Content-Type: application/json');

$state = $_GET['state'] ?? '';
$cities = [];

if ($state) {
    $stateEsc = mysqli_real_escape_string($conn, $state);
    $result = mysqli_query($conn, "
        SELECT DISTINCT city FROM jobs
        WHERE state = '$stateEsc' AND city IS NOT NULL AND city != ''
        ORDER BY city ASC
    ");
    while ($row = mysqli_fetch_assoc($result)) {
        $cities[] = $row['city'];
    }
}

echo json_encode($cities);
?>
