<?php
session_start();
require_once '../include/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = md5(trim($_POST['password']));

  $result = mysqli_query($conn, "SELECT * FROM admins WHERE username='$username' AND password='$password'");
  if (mysqli_num_rows($result) === 1) {
    $_SESSION['admin'] = $username;
    header('Location: dashboard.php');
    exit;
  } else {
    $error = "Invalid username or password.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Login - Job Vacancy Result (JVR)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-tr from-blue-100 to-blue-200 min-h-screen flex items-center justify-center">

  <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md border border-gray-200">
    <div class="text-center mb-6">
      <div class="flex justify-center mb-4">
        <!-- Optional logo icon -->
        <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-lg">
          J
        </div>
      </div>
      <h2 class="text-2xl font-bold text-gray-800">Admin Login</h2>
      <p class="text-xl text-gray-500">Job Vacancy Result (JVR)</p>
    </div>

    <?php if (isset($error)) : ?>
      <div class="bg-red-100 text-red-700 border border-red-300 rounded px-4 py-2 mb-4 text-center">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-5">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
        <input
          name="username"
          placeholder="Enter your username"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input
          name="password"
          type="password"
          placeholder="Enter your password"
          required
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <button
        type="submit"
        class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition duration-200"
      >
        Login
      </button>
    </form>
  </div>

</body>
</html>
