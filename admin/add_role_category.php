<?php
require_once '../include/auth.php';
require_once  '../include/db.php'; 

$message = '';

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $del_sql = "DELETE FROM role_categories WHERE id = $delete_id";
    if (mysqli_query($conn, $del_sql)) {
        $message = "Role Category deleted successfully.";
    } else {
        $message = "Error deleting role category: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    if ($name) {
        $sql = "INSERT INTO role_categories (name) VALUES ('$name')";
        if (mysqli_query($conn, $sql)) {
            $message = "Role Category '$name' added successfully.";
        } else {
            $message = (mysqli_errno($conn) == 1062) ? "Role Category '$name' already exists." : "Error: " . mysqli_error($conn);
        }
    } else {
        $message = "Please enter a name.";
    }
}

$role_categories = [];
$result = mysqli_query($conn, "SELECT * FROM role_categories ORDER BY name");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $role_categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add Role Category - JVR</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

<?php include '../include/navbar.php'; ?>

<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow mt-8">

    <?php if ($message): ?>
        <div id="messageBox" class="mb-4 text-center text-sm <?php echo (str_starts_with($message, 'Error') || str_starts_with($message, 'Please')) ? 'text-red-600' : 'text-green-600'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- First row: Add Role Category form -->
    <form method="post" class="flex gap-3 w-full mb-4">
        <input type="text" name="name" placeholder="Role Category name" required
            class="flex-grow border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
        <button type="submit" class="bg-blue-700 text-white px-5 py-2 rounded hover:bg-blue-800 transition">Add Role Category</button>
    </form>

    <!-- Second row: Search and Toggle -->
    <div class="mb-4 flex flex-col sm:flex-row items-center gap-3">
        <input type="text" id="roleCatSearch" placeholder="Search role category..." class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
        <button id="toggleViewBtn" type="button" class="bg-blue-100 text-blue-700 px-3 py-2 rounded font-semibold hover:bg-blue-200 transition">Toggle View</button>
    </div>

    <h2 class="mt-8 mb-3 text-xl font-semibold border-b border-gray-200 pb-2">Existing Role Categories
        <span class="ml-2 text-base font-normal text-blue-600 align-middle bg-blue-100 px-2 py-0.5 rounded-full">
        (<?php echo count($role_categories); ?>)
    </span>
    </h2>
    <div id="roleCategoriesGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <?php if (count($role_categories) > 0): ?>
            <?php foreach ($role_categories as $role_cat): ?>
                <div class="role-cat-card flex flex-col items-center border border-gray-100 rounded p-4 bg-gray-50">
                    <span class="font-semibold mb-2"><?php echo htmlspecialchars($role_cat['name']); ?></span>
                    <a href="?delete_id=<?php echo $role_cat['id']; ?>" onclick="return confirm('Delete this role category?');"
                        class="text-red-600 hover:text-red-800 text-sm font-semibold">Delete</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="italic text-gray-500 col-span-full">No role categories added yet.</div>
        <?php endif; ?>
    </div>
</div>

<script>
const searchInput = document.getElementById('roleCatSearch');
const grid = document.getElementById('roleCategoriesGrid');
const cards = Array.from(grid.getElementsByClassName('role-cat-card'));
const toggleBtn = document.getElementById('toggleViewBtn');
let showAll = true;

searchInput.addEventListener('input', function() {
    const val = this.value.trim().toLowerCase();
    cards.forEach(card => {
        const name = card.querySelector('span').textContent.toLowerCase();
        card.style.display = name.includes(val) ? '' : 'none';
    });
});

toggleBtn.addEventListener('click', function() {
    showAll = !showAll;
    if (showAll) {
        cards.forEach(card => card.style.display = '');
        toggleBtn.textContent = 'Show Less';
    } else {
        cards.forEach((card, idx) => {
            card.style.display = (idx < 5) ? '' : 'none';
        });
        toggleBtn.textContent = 'Show All';
    }
});

// On page load, set button text
toggleBtn.textContent = 'Show Less';

// Hide success message after 3 seconds
const messageBox = document.getElementById('messageBox');
if (messageBox && messageBox.classList.contains('text-green-600')) {
    setTimeout(() => {
        messageBox.style.display = 'none';
    }, 3000);
}
</script>

</body>
</html>
