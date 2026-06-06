<?php
require_once '../include/auth.php';
 require_once  '../include/db.php'; 

$message = '';

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $del_sql = "DELETE FROM skills WHERE id = $delete_id";
    if (mysqli_query($conn, $del_sql)) {
        $message = "Skill deleted successfully.";
    } else {
        $message = "Error deleting skill: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    if ($name) {
        $sql = "INSERT INTO skills (name) VALUES ('$name')";
        if (mysqli_query($conn, $sql)) {
            $message = "Skill '$name' added successfully.";
        } else {
            $message = (mysqli_errno($conn) == 1062) ? "Skill '$name' already exists." : "Error: " . mysqli_error($conn);
        }
    } else {
        $message = "Please enter a name.";
    }
}

$skills = [];
$result = mysqli_query($conn, "SELECT * FROM skills ORDER BY name");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $skills[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add Skill - JVR</title>
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

    <!-- First row: Add Skill form -->
    <form method="post" class="flex flex-col sm:flex-row gap-3 w-full mb-4">
        <input type="text" name="name" placeholder="Skill name" required
            class="flex-grow border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
        <button type="submit" class="bg-blue-700 text-white px-5 py-2 rounded hover:bg-blue-800 transition">Add Skill</button>
    </form>

    <!-- Second row: Search and Toggle -->
    <div class="mb-4 flex flex-col sm:flex-row items-center gap-3">
        <input type="text" id="skillSearch" placeholder="Search skill..." class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
        <button id="toggleViewBtn" type="button" class="bg-blue-100 text-blue-700 px-3 py-2 rounded font-semibold hover:bg-blue-200 transition">Toggle View</button>
    </div>

    <h2 class="mt-8 mb-3 text-xl font-semibold border-b border-gray-200 pb-2">Existing Skills
        <span class="ml-2 text-base font-normal text-blue-600 align-middle bg-blue-100 px-2 py-0.5 rounded-full">
        (<?php echo count($skills); ?>)
        </span>
        
    </h2>
    
    <div id="skillsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <?php if (count($skills) > 0): ?>
            <?php foreach ($skills as $skill): ?>
                <div class="skill-card flex flex-col items-center border border-gray-100 rounded p-4 bg-gray-50">
                    <span class="font-semibold mb-2"><?php echo htmlspecialchars($skill['name']); ?></span>
                    <a href="?delete_id=<?php echo $skill['id']; ?>" onclick="return confirm('Delete this skill?');"
                        class="text-red-600 hover:text-red-800 text-sm font-semibold">Delete</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="italic text-gray-500 col-span-full">No skills added yet.</div>
        <?php endif; ?>
    </div>
</div>

<script>
const searchInput = document.getElementById('skillSearch');
const skillsGrid = document.getElementById('skillsGrid');
const skillCards = Array.from(skillsGrid.getElementsByClassName('skill-card'));
const toggleBtn = document.getElementById('toggleViewBtn');
let showAll = true;

searchInput.addEventListener('input', function() {
    const val = this.value.trim().toLowerCase();
    skillCards.forEach(card => {
        const name = card.querySelector('span').textContent.toLowerCase();
        card.style.display = name.includes(val) ? '' : 'none';
    });
});

toggleBtn.addEventListener('click', function() {
    showAll = !showAll;
    if (showAll) {
        skillCards.forEach(card => card.style.display = '');
        toggleBtn.textContent = 'Show Less';
    } else {
        skillCards.forEach((card, idx) => {
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
