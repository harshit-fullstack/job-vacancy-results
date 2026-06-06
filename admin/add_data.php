<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'job_portal';
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'] ?? '';

    if (!empty($_POST['name'])) {
        $name = mysqli_real_escape_string($conn, trim($_POST['name']));

        switch ($type) {
            case 'industry':
                $table = 'industries';
                break;
            case 'company':
                $table = 'companies';
                break;
            case 'skill':
                $table = 'skills';
                break;
            case 'role_category':
                $table = 'role_categories';
                break;
            default:
                $message = "Invalid data type.";
                $table = '';
        }

        if ($table) {
            $sql = "INSERT INTO $table (name) VALUES ('$name')";
            if (mysqli_query($conn, $sql)) {
                $message = ucfirst(str_replace('_', ' ', $type)) . " '$name' added successfully.";
            } else {
                if (mysqli_errno($conn) == 1062) {
                    $message = ucfirst(str_replace('_', ' ', $type)) . " '$name' already exists.";
                } else {
                    $message = "Error: " . mysqli_error($conn);
                }
            }
        }
    } else {
        $message = "Please enter a name.";
    }
}

// Fetch all items from each table
function fetchAll($conn, $table) {
    $data = [];
    $res = mysqli_query($conn, "SELECT * FROM $table ORDER BY name ASC");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row['name'];
        }
    }
    return $data;
}

$industries = fetchAll($conn, 'industries');
$companies = fetchAll($conn, 'companies');
$skills = fetchAll($conn, 'skills');
$role_categories = fetchAll($conn, 'role_categories');
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Add Data - Job Portal</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

<!-- Navbar -->
<nav class="bg-white shadow-md sticky top-0 z-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16 items-center">
      <div class="flex-shrink-0 text-blue-600 font-bold text-xl">
        Job Vacancy Result (JVR)
      </div>
      <div class="space-x-4">
        <a href="#add-industry" class="text-gray-700 hover:text-blue-600 font-semibold">Add Industry</a>
        <a href="#add-company" class="text-gray-700 hover:text-blue-600 font-semibold">Add Company</a>
        <a href="#add-skill" class="text-gray-700 hover:text-blue-600 font-semibold">Add Skill</a>
        <a href="#add-role-category" class="text-gray-700 hover:text-blue-600 font-semibold">Add Role Category</a>
        <a href="#all-items" class="text-gray-700 hover:text-blue-600 font-semibold">All Items</a>
      </div>
    </div>
  </div>
</nav>

<!-- Main content -->
<main class="flex-grow max-w-3xl mx-auto p-6 space-y-8">

  <h1 class="text-3xl font-bold text-gray-800 mb-6">Add Data</h1>

  <?php if ($message): ?>
    <div
      class="p-4 rounded <?php echo (strpos($message, 'Error') === 0 || strpos($message, 'Please') === 0) ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
      <?php echo htmlspecialchars($message); ?>
    </div>
  <?php endif; ?>

  <!-- Industry Form -->
  <section id="add-industry" class="bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4">Add Industry</h2>
    <form method="post" class="flex gap-4 flex-wrap">
      <input type="hidden" name="type" value="industry" />
      <input
        type="text"
        name="name"
        placeholder="Industry name"
        required
        class="flex-grow px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
      <button
        type="submit"
        class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-md hover:bg-blue-700 transition"
      >
        Add Industry
      </button>
    </form>
  </section>

  <!-- Company Form -->
  <section id="add-company" class="bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4">Add Company</h2>
    <form method="post" class="flex gap-4 flex-wrap">
      <input type="hidden" name="type" value="company" />
      <input
        type="text"
        name="name"
        placeholder="Company name"
        required
        class="flex-grow px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
      <button
        type="submit"
        class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-md hover:bg-blue-700 transition"
      >
        Add Company
      </button>
    </form>
  </section>

  <!-- Skill Form -->
  <section id="add-skill" class="bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4">Add Skill</h2>
    <form method="post" class="flex gap-4 flex-wrap">
      <input type="hidden" name="type" value="skill" />
      <input
        type="text"
        name="name"
        placeholder="Skill name"
        required
        class="flex-grow px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
      <button
        type="submit"
        class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-md hover:bg-blue-700 transition"
      >
        Add Skill
      </button>
    </form>
  </section>

  <!-- Role Category Form -->
  <section id="add-role-category" class="bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4">Add Role Category</h2>
    <form method="post" class="flex gap-4 flex-wrap">
      <input type="hidden" name="type" value="role_category" />
      <input
        type="text"
        name="name"
        placeholder="Role category name"
        required
        class="flex-grow px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
      <button
        type="submit"
        class="bg-blue-600 text-white font-semibold px-6 py-2 rounded-md hover:bg-blue-700 transition"
      >
        Add Role Category
      </button>
    </form>
  </section>

  <!-- All Items Display -->
  <section id="all-items" class="bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">All Added Items</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
      
      <div>
        <h3 class="font-semibold text-lg text-blue-700 mb-2 border-b border-blue-200 pb-1">Industries</h3>
        <?php if (count($industries) > 0): ?>
          <ul class="list-disc list-inside max-h-48 overflow-auto text-gray-700">
            <?php foreach ($industries as $item): ?>
              <li><?php echo htmlspecialchars($item); ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="text-gray-500 italic">No industries added yet.</p>
        <?php endif; ?>
      </div>

      <div>
        <h3 class="font-semibold text-lg text-blue-700 mb-2 border-b border-blue-200 pb-1">Companies</h3>
        <?php if (count($companies) > 0): ?>
          <ul class="list-disc list-inside max-h-48 overflow-auto text-gray-700">
            <?php foreach ($companies as $item): ?>
              <li><?php echo htmlspecialchars($item); ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="text-gray-500 italic">No companies added yet.</p>
        <?php endif; ?>
      </div>

      <div>
        <h3 class="font-semibold text-lg text-blue-700 mb-2 border-b border-blue-200 pb-1">Skills</h3>
        <?php if (count($skills) > 0): ?>
          <ul class="list-disc list-inside max-h-48 overflow-auto text-gray-700">
            <?php foreach ($skills as $item): ?>
              <li><?php echo htmlspecialchars($item); ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="text-gray-500 italic">No skills added yet.</p>
        <?php endif; ?>
      </div>

      <div>
        <h3 class="font-semibold text-lg text-blue-700 mb-2 border-b border-blue-200 pb-1">Role Categories</h3>
        <?php if (count($role_categories) > 0): ?>
          <ul class="list-disc list-inside max-h-48 overflow-auto text-gray-700">
            <?php foreach ($role_categories as $item): ?>
              <li><?php echo htmlspecialchars($item); ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p class="text-gray-500 italic">No role categories added yet.</p>
        <?php endif; ?>
      </div>

    </div>
  </section>

</main>

<footer class="bg-white shadow-inner py-4 text-center text-gray-600 text-sm">
  &copy; <?php echo date('Y'); ?> Job Vacancy Result (JVR). All rights reserved.
</footer>

</body>
</html>
