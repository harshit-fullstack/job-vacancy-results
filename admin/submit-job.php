<?php
require_once '../include/auth.php';
require_once '../include/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Collect and sanitize fields using correct foreign key names
  $fields = [
    'title', 'company_id', 'department_id', 'description', 'additional', 'state', 'city',
    'industry_id', 'role_category_id', 'skills', 'job_type', 'education', 'work_mode', 'link'
  ];
  $data = [];
  foreach ($fields as $field) {
    $data[$field] = mysqli_real_escape_string($conn, $_POST[$field] ?? '');
  }

  // Handle is_featured checkbox
  $is_featured = isset($_POST['is_featured']) ? 1 : 0;

  // Experience (e.g. "1 - 3 years")
  $exp_min = isset($_POST['exp_min']) ? (int)$_POST['exp_min'] : 0;
  $exp_max = isset($_POST['exp_max']) ? (int)$_POST['exp_max'] : 0;
  $experience = "$exp_min - $exp_max years";

  // Salary
  if (isset($_POST['salary_not_disclosed'])) {
    $salary = 'Not Disclosed';
  } else {
    $salary_min = isset($_POST['salary_min']) ? (int)$_POST['salary_min'] : 0;
    $salary_max = isset($_POST['salary_max']) ? (int)$_POST['salary_max'] : 0;
    $salary = "$salary_min - $salary_max per annum";
  }

  // Insert into database using correct columns
  $sql = "INSERT INTO jobs (
      title, company_id, department_id, description, additional_info, state, city,
      industry_id, role_category_id, skills, job_type, education, work_mode, experience, salary, job_link, is_featured
    ) VALUES (
      '{$data['title']}',
      '{$data['company_id']}',
      " . ($data['department_id'] ? "'{$data['department_id']}'" : "NULL") . ",
      '{$data['description']}',
      '{$data['additional']}',
      '{$data['state']}',
      '{$data['city']}',
      '{$data['industry_id']}',
      '{$data['role_category_id']}',
      '{$data['skills']}',
      '{$data['job_type']}',
      '{$data['education']}',
      '{$data['work_mode']}',
      '$experience',
      '$salary',
      '{$data['link']}',
      '$is_featured'
    )";

  if (mysqli_query($conn, $sql)) {
    header('Location: add-job.php');
    exit;
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}
?>
