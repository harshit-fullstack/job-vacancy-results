<?php
require_once '../include/auth.php';
require_once '../include/db.php';
$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM jobs WHERE id = $id");
header('Location: dashboard.php');
?>