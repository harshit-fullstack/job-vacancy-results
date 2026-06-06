<?php
require_once '../include/auth.php';
require_once '../include/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: add_company.php');
    exit;
}

$id = (int)$_GET['id'];
$message = '';

// Fetch dropdowns for IDs
$industries = mysqli_query($conn, "SELECT id, name FROM industries ORDER BY name ASC");
$company_types = mysqli_query($conn, "SELECT id, name FROM company_types ORDER BY name ASC");
$natures = mysqli_query($conn, "SELECT id, name FROM nature_of_business ORDER BY name ASC");

// Fetch company data
$company = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM companies WHERE id = $id"));
if (!$company) {
    header('Location: add_company.php');
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $industry_ids = isset($_POST['industry_id']) ? explode(',', $_POST['industry_id']) : [];
    $industry_ids_string = implode(',', array_map('intval', $industry_ids));

    $company_type_id = isset($_POST['company_type_id']) ? (int)$_POST['company_type_id'] : null;

    $nature_ids = isset($_POST['nature_of_business_id']) ? explode(',', $_POST['nature_of_business_id']) : [];
    $nature_ids_string = implode(',', array_map('intval', $nature_ids));

    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, trim($_POST['description'])) : '';
    $founded = isset($_POST['founded']) ? (int)$_POST['founded'] : null;
    $company_size = isset($_POST['company_size']) ? mysqli_real_escape_string($conn, trim($_POST['company_size'])) : '';
    $website = isset($_POST['website']) ? mysqli_real_escape_string($conn, trim($_POST['website'])) : '';
    $logoPath = $company['logo'];

    // Handle file upload
    if (!empty($_FILES['logo']['name'])) {
        $minFileSize = 1 * 1024; // 1KB
        $maxFileSize = 60 * 1024; // 60KB
        $fileSize = $_FILES['logo']['size'];

        if ($fileSize < $minFileSize) {
            $message = "Logo file size must be at least 1KB.";
        } elseif ($fileSize > $maxFileSize) {
            $message = "Logo file size must not exceed 60KB.";
        } else {
            $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/jobvacancyresult/uploads/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $fileName = uniqid() . '_' . basename($_FILES['logo']['name']);
            $targetFile = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
                    // Delete old logo if exists
                    if (!empty($company['logo']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/jobvacancyresult/' . $company['logo'])) {
                        unlink($_SERVER['DOCUMENT_ROOT'] . '/jobvacancyresult/' . $company['logo']);
                    }
                    $logoPath = 'uploads/' . $fileName; // Update logo path
                } else {
                    $message = "Error uploading logo.";
                }
            } else {
                $message = "Only image files (JPG, PNG, GIF, WEBP) are allowed.";
            }
        }
    }

    // Update database
    if ($name && !$message) {
        $sql = "UPDATE companies SET
            name = '$name',
            industry_ids = '$industry_ids_string',
            company_type_id = " . ($company_type_id ? $company_type_id : "NULL") . ",
            nature_of_business_ids = '$nature_ids_string',
            description = " . ($description ? "'$description'" : "NULL") . ",
            founded = " . ($founded ? $founded : "NULL") . ",
            company_size = " . ($company_size ? "'$company_size'" : "NULL") . ",
            website = " . ($website ? "'$website'" : "NULL") . ",
            logo = " . ($logoPath ? "'$logoPath'" : "NULL") . "
            WHERE id = $id";
        if (mysqli_query($conn, $sql)) {
            $message = "Company updated successfully.";
            // Refresh company data
            $company = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM companies WHERE id = $id"));
        } else {
            $message = "Error updating company: " . mysqli_error($conn);
        }
    } elseif (!$name) {
        $message = "Please enter a name.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Edit Company - JVR</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <?php include '../include/navbar.php'; ?>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded shadow mt-8">
        <h2 class="text-xl font-semibold mb-4">Edit Company</h2>
        <?php if ($message): ?>
            <div id="messageBox" class="mb-4 text-center text-sm <?php echo (str_starts_with($message, 'Error') || str_starts_with($message, 'Please')) ? 'text-red-600' : 'text-green-600'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" class="space-y-4">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Company Name -->
                <div class="flex-1">
                    <label for="name" class="block mb-1 text-sm font-medium text-gray-700">Company Name</label>
                    <input type="text" name="name" id="name" value="<?= htmlspecialchars($company['name'] ?? '') ?>" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                </div>
                <!-- Logo -->
                <div class="flex-1">
                    <label for="logo" class="block mb-1 text-sm font-medium text-gray-700">Logo</label>
                    <?php
                    $logo_web_path = !empty($company['logo']) ? $company['logo'] : '';
                    $logo_file_path = !empty($company['logo']) ? $_SERVER['DOCUMENT_ROOT'] . '/jobvacancyresult/' . ltrim($company['logo'], '/') : '';

                    if (!empty($company['logo']) && file_exists($logo_file_path)) {
                        echo '<img src="/jobvacancyresult/' . htmlspecialchars($logo_web_path) . '" alt="Logo" class="w-14 h-14 object-contain rounded mb-2" />';
                    } else {
                        echo '<div class="w-14 h-14 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500 mb-2">No Logo</div>';
                    }
                    ?>
                    <input type="file" name="logo" id="logo"
                        class="w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded file:border-0
                          file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                          hover:file:bg-blue-100" />
                    <p class="text-xs text-gray-500 mt-1">Allowed size: 1KB - 60KB</p>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Industry -->
                <div class="flex-1">
                    <label for="industry_id" class="block mb-1 text-sm font-medium text-gray-700">Industry</label>
                    <input type="hidden" name="industry_id" id="industryInput" value="<?= htmlspecialchars($company['industry_ids'] ?? '') ?>" />
                    <div id="industryBox" class="w-full border border-gray-300 rounded px-3 py-2 cursor-pointer">
                        <div id="selectedIndustries" class="text-sm text-gray-700">
                            <?php
                            $selectedIndustryNames = [];
                            if (!empty($company['industry_ids'])) {
                                $industryIds = explode(',', $company['industry_ids']);
                                mysqli_data_seek($industries, 0);
                                while ($row = mysqli_fetch_assoc($industries)) {
                                    if (in_array($row['id'], $industryIds)) {
                                        $selectedIndustryNames[] = htmlspecialchars($row['name']);
                                    }
                                }
                            }
                            echo implode(', ', $selectedIndustryNames);
                            ?>
                        </div>
                    </div>
                    <div id="industryDropdown" class="absolute bg-white border border-gray-300 rounded shadow-lg mt-1 hidden max-h-40 overflow-y-auto z-10 ">
                        <?php mysqli_data_seek($industries, 0);
                        while ($row = mysqli_fetch_assoc($industries)) : ?>
                            <div class="px-3 py-2 cursor-pointer hover:bg-gray-100" data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['name']) ?>">
                                <?= htmlspecialchars($row['name']) ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <!-- Company Type -->
                <div class="flex-1">
                    <label for="company_type_id" class="block mb-1 text-sm font-medium text-gray-700">Company Type</label>
                    <select id="company_type_id" name="company_type_id" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Type</option>
                        <?php mysqli_data_seek($company_types, 0);
                        while ($row = mysqli_fetch_assoc($company_types)) : ?>
                            <option value="<?= $row['id'] ?>" <?= ($company['company_type_id'] == $row['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <!-- Nature of Business -->
                <div class="flex-1">
                    <label for="nature_of_business_id" class="block mb-1 text-sm font-medium text-gray-700">Nature of Business</label>
                    <input type="hidden" name="nature_of_business_id" id="natureInput" value="<?= htmlspecialchars($company['nature_of_business_ids'] ?? '') ?>" />
                    <div id="natureBox" class="w-full border border-gray-300 rounded px-3 py-2 cursor-pointer">
                        <div id="selectedNatures" class="text-sm text-gray-700">
                            <?php
                            $selectedNatureNames = [];
                            if (!empty($company['nature_of_business_ids'])) {
                                $natureIds = explode(',', $company['nature_of_business_ids']);
                                mysqli_data_seek($natures, 0);
                                while ($row = mysqli_fetch_assoc($natures)) {
                                    if (in_array($row['id'], $natureIds)) {
                                        $selectedNatureNames[] = htmlspecialchars($row['name']);
                                    }
                                }
                            }
                            echo implode(', ', $selectedNatureNames);
                            ?>
                        </div>
                    </div>
                    <div id="natureDropdown" class="absolute bg-white border border-gray-300 rounded shadow-lg mt-1 hidden max-h-40 overflow-y-auto z-10 ">
                        <?php mysqli_data_seek($natures, 0);
                        while ($row = mysqli_fetch_assoc($natures)) : ?>
                            <div class="px-3 py-2 cursor-pointer hover:bg-gray-100" data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['name']) ?>">
                                <?= htmlspecialchars($row['name']) ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
            <!-- Description -->
            <div class="md:col-span-3">
                <label for="description" class="block mb-1 text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" required
                    oninput="this.style.height='auto';this.style.height=this.scrollHeight + 'px';"
                    class="w-full border border-gray-300 rounded px-2 py-1 resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                    style="height:auto;"><?= htmlspecialchars($company['description'] ?? '') ?></textarea>
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Founded -->
                <div class="flex-1">
                    <label for="founded" class="block mb-1 text-sm font-medium text-gray-700">Founded</label>
                    <input type="number" name="founded" id="founded" min="1800" max="<?= date('Y') ?>"
                        value="<?= htmlspecialchars($company['founded'] ?? '') ?>"
                        placeholder="e.g. 2005"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                </div>
                <!-- Company Size -->
                <div class="flex-1">
                    <label for="company_size" class="block mb-1 text-sm font-medium text-gray-700">Company Size</label>
                    <input type="text" name="company_size" id="company_size"
                        value="<?= htmlspecialchars($company['company_size'] ?? '') ?>"
                        placeholder="e.g. 50-200 employees"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                </div>
                <!-- Website -->
                <div class="flex-1">
                    <label for="website" class="block mb-1 text-sm font-medium text-gray-700">Website</label>
                    <input type="url" name="website" id="website"
                        value="<?= htmlspecialchars($company['website'] ?? '') ?>"
                        placeholder="https://example.com"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                </div>
            </div>
            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 transition mt-3">
                    Update
                </button>
            </div>
        </form>
        <div class="mt-4">
            <a href="add_company.php" class="text-blue-700 hover:underline">&larr; Back to Companies</a>
        </div>
    </div>
    <script>
    // Utility function to create tag
    function createTag(id, name, color = 'blue', container, idsArray, input) {
        const tag = document.createElement('span');
        tag.className = `inline-block bg-${color}-100 text-${color}-700 rounded px-2 py-0.5 text-xs mr-1 mb-1 cursor-pointer`;
        tag.textContent = name;
        tag.setAttribute('data-id', id);
        tag.title = "Click to remove";

        tag.addEventListener('click', () => {
            // Remove from DOM
            container.removeChild(tag);
            // Remove from selected IDs
            const index = idsArray.indexOf(id);
            if (index > -1) idsArray.splice(index, 1);
            input.value = idsArray.join(',');
        });

        container.appendChild(tag);
    }

    // INDUSTRY
    const industryBox = document.getElementById('industryBox');
    const industryDropdown = document.getElementById('industryDropdown');
    const selectedIndustries = document.getElementById('selectedIndustries');
    const industryInput = document.getElementById('industryInput');
    let selectedIndustryIds = industryInput.value ? industryInput.value.split(',') : [];

    // Render existing selections
    selectedIndustryIds.forEach(id => {
        const el = industryDropdown.querySelector(`[data-id="${id}"]`);
        if (el) createTag(id, el.dataset.name, 'blue', selectedIndustries, selectedIndustryIds, industryInput);
    });

    industryBox.addEventListener('click', () => {
        industryDropdown.classList.toggle('hidden');
    });

    industryDropdown.addEventListener('click', (e) => {
        const target = e.target.closest('[data-id]');
        if (target) {
            const id = target.getAttribute('data-id');
            const name = target.getAttribute('data-name');

            if (!selectedIndustryIds.includes(id)) {
                selectedIndustryIds.push(id);
                createTag(id, name, 'blue', selectedIndustries, selectedIndustryIds, industryInput);
                industryInput.value = selectedIndustryIds.join(',');
            }
        }
    });

    // NATURE OF BUSINESS
    const natureBox = document.getElementById('natureBox');
    const natureDropdown = document.getElementById('natureDropdown');
    const selectedNatures = document.getElementById('selectedNatures');
    const natureInput = document.getElementById('natureInput');
    let selectedNatureIds = natureInput.value ? natureInput.value.split(',') : [];

    // Render existing selections
    selectedNatureIds.forEach(id => {
        const el = natureDropdown.querySelector(`[data-id="${id}"]`);
        if (el) createTag(id, el.dataset.name, 'purple', selectedNatures, selectedNatureIds, natureInput);
    });

    natureBox.addEventListener('click', () => {
        natureDropdown.classList.toggle('hidden');
    });

    natureDropdown.addEventListener('click', (e) => {
        const target = e.target.closest('[data-id]');
        if (target) {
            const id = target.getAttribute('data-id');
            const name = target.getAttribute('data-name');

            if (!selectedNatureIds.includes(id)) {
                selectedNatureIds.push(id);
                createTag(id, name, 'purple', selectedNatures, selectedNatureIds, natureInput);
                natureInput.value = selectedNatureIds.join(',');
            }
        }
    });
</script>

</body>

</html>