<?php
require_once '../include/auth.php';
require_once '../include/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    if ($id <= 0) {
        die("Invalid job ID");
    }

    // Use correct field names matching your DB schema (foreign keys)
    $fields = [
        'title', 'company_id', 'department_id', 'description', 'additional_info', 'state', 'city',
        'industry_id', 'role_category_id', 'skills', 'job_type', 'education', 'work_mode', 'job_link'
    ];
    $data = [];
    foreach ($fields as $field) {
        $data[$field] = mysqli_real_escape_string($conn, $_POST[$field] ?? '');
    }

    // Handle is_featured checkbox
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // Experience
    $exp_min = isset($_POST['exp_min']) ? (int)$_POST['exp_min'] : 0;
    $exp_max = isset($_POST['exp_max']) ? (int)$_POST['exp_max'] : 0;
    $experience = "$exp_min - $exp_max years";

    // Salary
    if (isset($_POST['salary_not_disclosed'])) {
        $salary = 'Not Disclosed';
    } else {
        $salary_min = isset($_POST['salary_min']) ? (int)$_POST['salary_min'] : 0;
        $salary_max = isset($_POST['salary_max']) ? (int)$_POST['salary_max'] : 0;
        $salary = "₹$salary_min - ₹$salary_max per annum";
    }

    // Build UPDATE query with correct columns
    $sql = "UPDATE jobs SET
                title='{$data['title']}',
                company_id='{$data['company_id']}',
                department_id=" . ($data['department_id'] ? "'{$data['department_id']}'" : "NULL") . ",
                description='{$data['description']}',
                additional_info='{$data['additional_info']}',
                state='{$data['state']}',
                city='{$data['city']}',
                industry_id='{$data['industry_id']}',
                role_category_id='{$data['role_category_id']}',
                skills='{$data['skills']}',
                job_type='{$data['job_type']}',
                education='{$data['education']}',
                work_mode='{$data['work_mode']}',
                experience='$experience',
                salary='$salary',
                job_link='{$data['job_link']}',
                is_featured='$is_featured'
            WHERE id = $id";

    // Execute the query and handle the result
    if (mysqli_query($conn, $sql)) {
        $successMessage = "Job updated successfully!";
    } else {
        $errorMessage = "Error updating job: " . mysqli_error($conn);
    }
} else {
    $errorMessage = "Invalid request method.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Job</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <?php if (!empty($successMessage)): ?>
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                <?= htmlspecialchars($successMessage) ?>
            </div>
            <a href="dashboard" class="block text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Back to Dashboard
            </a>
        <?php elseif (!empty($errorMessage)): ?>
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
            <a href="edit-job.php?id=<?= $id ?>" class="block text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Back to Edit Job
            </a>
        <?php endif; ?>
    </div>
</body>

</html>