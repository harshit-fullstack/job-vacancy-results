<?php
require_once '../include/auth.php';
require_once '../include/db.php';

$message = '';
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $del_sql = "DELETE FROM departments WHERE id = $delete_id";
    if (mysqli_query($conn, $del_sql)) {
        $message = "Department deleted successfully.";
    } else {
        $message = "Error deleting department: " . mysqli_error($conn);
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    if ($name) {
        $sql = "INSERT INTO departments (name) VALUES ('$name')";
        if (mysqli_query($conn, $sql)) {
            $message = "Department '$name' added successfully.";
        } else {
            $message = (mysqli_errno($conn) == 1062) ? "Department '$name' already exists." : "Error: " . mysqli_error($conn);
        }
    } else {
        $message = "Please enter a name.";
    }
}
$departments = [];
$result = mysqli_query($conn, "SELECT * FROM departments ORDER BY name");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $departments[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add Department</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
<?php include '../include/navbar.php'; ?>
<div class="max-w-xl mx-auto p-6 bg-white rounded shadow mt-8">
    <?php if ($message): ?>
        <div id="messageBox" class="mb-4 text-center text-sm <?php echo (str_starts_with($message, 'Error') || str_starts_with($message, 'Please')) ? 'text-red-600' : 'text-green-600'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <form method="post" class="flex gap-3 w-full mb-4">
        <input type="text" name="name" placeholder="Department name" required
            class="flex-grow border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
        <button type="submit" class="bg-blue-700 text-white px-5 py-2 rounded hover:bg-blue-800 transition">Add</button>
    </form>
    <div class="mb-4 flex flex-col sm:flex-row items-center gap-3">
        <input type="text" id="typeSearch" placeholder="Search department..." class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
        <button id="toggleViewBtn" type="button" class="bg-blue-100 text-blue-700 px-3 py-2 rounded font-semibold hover:bg-blue-200 transition">Toggle View</button>
    </div>
    <h2 class="mt-8 mb-3 text-xl font-semibold border-b border-gray-200 pb-2">Existing Departments
        <span class="ml-2 text-base font-normal text-blue-600 align-middle bg-blue-100 px-2 py-0.5 rounded-full">
            (<?php echo count($departments); ?>)
        </span>
    </h2>
    <div id="typesGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        <?php if (count($departments) > 0): ?>
            <?php foreach ($departments as $dept): ?>
                <div class="type-card flex flex-col items-center border border-gray-200 rounded p-4 bg-gray-50 shadow-sm hover:shadow-md transition">
                    <span class="font-semibold mb-2 text-center break-words w-full" title="<?php echo htmlspecialchars($dept['name']); ?>">
                        <?php echo htmlspecialchars($dept['name']); ?>
                    </span>
                    <a href="?delete_id=<?php echo $dept['id']; ?>" onclick="return confirm('Delete this department?');"
                        class="text-red-600 hover:text-red-800 text-sm font-semibold">Delete</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="italic text-gray-500 col-span-full text-center">No departments added yet.</div>
        <?php endif; ?>
    </div>
</div>
<script>
const searchInput = document.getElementById('typeSearch');
const typesGrid = document.getElementById('typesGrid');
const typeCards = Array.from(typesGrid.getElementsByClassName('type-card'));
const toggleBtn = document.getElementById('toggleViewBtn');
let showAll = true;
searchInput.addEventListener('input', function() {
    const val = this.value.trim().toLowerCase();
    typeCards.forEach(card => {
        const name = card.querySelector('span').textContent.toLowerCase();
        card.style.display = name.includes(val) ? '' : 'none';
    });
});
toggleBtn.addEventListener('click', function() {
    showAll = !showAll;
    if (showAll) {
        typeCards.forEach(card => card.style.display = '');
        toggleBtn.textContent = 'Show Less';
    } else {
        typeCards.forEach((card, idx) => {
            card.style.display = (idx < 5) ? '' : 'none';
        });
        toggleBtn.textContent = 'Show All';
    }
});
toggleBtn.textContent = 'Show Less';
const messageBox = document.getElementById('messageBox');
if (messageBox && messageBox.classList.contains('text-green-600')) {
    setTimeout(() => {
        messageBox.style.display = 'none';
    }, 3000);
}
</script>
</body>
</html>