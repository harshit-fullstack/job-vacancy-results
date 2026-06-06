<?php
require_once '../include/auth.php';
require_once '../include/db.php';

function generateLogoImage($companyName, $targetDir) {
    $firstChar = strtoupper(mb_substr($companyName, 0, 1));
    $bgColors = ['#FF6B6B', '#6BCB77', '#4D96FF', '#FFC75F', '#C34A36', '#7E57C2'];
    $bgColorHex = $bgColors[crc32($companyName) % count($bgColors)];

    $width = 100;
    $height = 100;
    $image = imagecreatetruecolor($width, $height);

    // Convert HEX to RGB
    sscanf($bgColorHex, "#%02x%02x%02x", $r, $g, $b);
    $bgColor = imagecolorallocate($image, $r, $g, $b);
    $textColor = imagecolorallocate($image, 255, 255, 255);

    // Fill background
    imagefill($image, 0, 0, $bgColor);

    // Font
    $font = __DIR__ . '/../assets/fonts/arial.ttf';
    if (!file_exists($font)) {
        $font = dirname(__FILE__) . '/arial.ttf';
    }

    $fontSize = 36;
    $bbox = imagettfbbox($fontSize, 0, $font, $firstChar);
    $x = ($width - ($bbox[2] - $bbox[0])) / 2;
    $y = ($height + ($bbox[1] - $bbox[7])) / 2;

    imagettftext($image, $fontSize, 0, $x, $y, $textColor, $font, $firstChar);

    $fileName = uniqid('logo_') . '.png';
    $filePath = $targetDir . $fileName;

    imagepng($image, $filePath);
    imagedestroy($image);

    return 'uploads/' . $fileName;
}

$message = '';

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $logoResult = mysqli_query($conn, "SELECT logo FROM companies WHERE id = $delete_id");
    if ($logoResult && mysqli_num_rows($logoResult) > 0) {
        $logoRow = mysqli_fetch_assoc($logoResult);
        if (!empty($logoRow['logo']) && file_exists($logoRow['logo'])) {
            unlink($logoRow['logo']);
        }
    }
    $del_sql = "DELETE FROM companies WHERE id = $delete_id";
    $message = mysqli_query($conn, $del_sql) ? "Company deleted successfully." : "Error deleting company: " . mysqli_error($conn);
}

$industries = mysqli_query($conn, "SELECT id, name FROM industries ORDER BY name ASC");
$company_types = mysqli_query($conn, "SELECT id, name FROM company_types ORDER BY name ASC");
$natures = mysqli_query($conn, "SELECT id, name FROM nature_of_business ORDER BY name ASC");

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
    
    $logoPath = '';
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/jobvacancyresult/uploads/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (!empty($_FILES['logo']['name'])) {
        $minFileSize = 1 * 1024;
        $maxFileSize = 60 * 1024;
        $fileSize = $_FILES['logo']['size'];

        if ($fileSize < $minFileSize) {
            $message = "Logo file size must be at least 1KB.";
        } elseif ($fileSize > $maxFileSize) {
            $message = "Logo file size must not exceed 60KB.";
        } else {
            $fileName = uniqid() . '_' . basename($_FILES['logo']['name']);
            $targetFile = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
                    $logoPath = 'uploads/' . $fileName;
                } else {
                    $message = "Error uploading logo.";
                }
            } else {
                $message = "Only image files (JPG, PNG, GIF, WEBP) are allowed.";
            }
        }
    }

    if (empty($_FILES['logo']['name']) && !$message && !empty($name)) {
        $logoPath = generateLogoImage($name, $targetDir);
    }

    if ($name && !$message) {
        $sql = "INSERT INTO companies 
            (name, industry_ids, company_type_id, nature_of_business_ids, description, founded, company_size, website, logo) 
            VALUES (
                '$name',
                '$industry_ids_string',
                " . ($company_type_id ? $company_type_id : "NULL") . ",
                '$nature_ids_string',
                " . ($description ? "'$description'" : "NULL") . ",
                " . ($founded ? $founded : "NULL") . ",
                " . ($company_size ? "'$company_size'" : "NULL") . ",
                " . ($website ? "'$website'" : "NULL") . ",
                " . ($logoPath ? "'$logoPath'" : "NULL") . "
            )";
        $message = mysqli_query($conn, $sql)
            ? "Company '$name' added successfully."
            : (mysqli_errno($conn) == 1062
                ? "Company '$name' already exists."
                : "Error: " . mysqli_error($conn));
    } elseif (!$name) {
        $message = "Please enter a name.";
    }
}

$companies = [];
$result = mysqli_query($conn, "
    SELECT c.*, 
        GROUP_CONCAT(DISTINCT i.name SEPARATOR ', ') AS industry_names, 
        t.name AS company_type_name, 
        GROUP_CONCAT(DISTINCT n.name SEPARATOR ', ') AS nature_names
    FROM companies c
    LEFT JOIN industries i ON FIND_IN_SET(i.id, c.industry_ids)
    LEFT JOIN company_types t ON c.company_type_id = t.id
    LEFT JOIN nature_of_business n ON FIND_IN_SET(n.id, c.nature_of_business_ids)
    GROUP BY c.id
    ORDER BY c.name
");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $companies[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Add Company - JVR</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <?php include '../include/navbar.php'; ?>

    <div class="max-w-3xl mx-auto p-6 bg-white rounded shadow mt-8">

        <?php if ($message): ?>
            <div id="messageBox" class="mb-4 text-center text-sm <?php echo (str_starts_with($message, 'Error') || str_starts_with($message, 'Please')) ? 'text-red-600' : 'text-green-600'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Add Company Form -->
        <form method="post" enctype="multipart/form-data" class="space-y-4">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Company Name -->
                <div class="flex-1">
                    <label for="name" class="block mb-1 text-sm font-medium text-gray-700">Company Name</label>
                    <input type="text" name="name" id="name" placeholder="Enter company name" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                </div>
                <!-- Logo -->
                <div class="flex-1">
                    <label for="logo" class="block mb-1 text-sm font-medium text-gray-700">Add Logo</label>
                    <input type="file" name="logo" id="logo"
                        class="w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded file:border-0
                          file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                          hover:file:bg-blue-100" />
                    <p class="text-xs text-gray-500 mt-1">Allowed size: 1KB - 60KB</p>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Industry -->
                <div class="flex-1 relative">
                    <label for="industry_id" class="block mb-1 text-sm font-medium text-gray-700">Industry</label>
                    <div id="industryBox" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <span id="selectedIndustries" class="text-sm text-gray-700">Select industries...</span>
                    </div>
                    <div id="industryDropdown" class="absolute bg-white border border-gray-300 rounded shadow-lg mt-1 hidden max-h-40 overflow-y-auto z-10 w-full">
                        <?php mysqli_data_seek($industries, 0); while ($row = mysqli_fetch_assoc($industries)) : ?>
                            <div class="px-3 py-2 cursor-pointer hover:bg-gray-100" data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['name']) ?>">
                                <?= htmlspecialchars($row['name']) ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <input type="hidden" name="industry_id" id="industryInput" />
                </div>
                <!-- Company Type -->
                <div class="flex-1">
                    <label for="company_type_id" class="block mb-1 text-sm font-medium text-gray-700">Company Type</label>
                    <select id="company_type_id" name="company_type_id" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Type</option>
                        <?php mysqli_data_seek($company_types, 0); while ($row = mysqli_fetch_assoc($company_types)) : ?>
                            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <!-- Nature of Business -->
                <div class="flex-1 relative">
                    <label for="nature_of_business_id" class="block mb-1 text-sm font-medium text-gray-700">Nature of Business</label>
                    <div id="natureBox" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <span id="selectedNatures" class="text-sm text-gray-700">Select nature of business...</span>
                    </div>
                    <div id="natureDropdown" class="absolute bg-white border border-gray-300 rounded shadow-lg mt-1 hidden max-h-40 overflow-y-auto z-10 w-full">
                        <?php mysqli_data_seek($natures, 0); while ($row = mysqli_fetch_assoc($natures)) : ?>
                            <div class="px-3 py-2 cursor-pointer hover:bg-gray-100" data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['name']) ?>">
                                <?= htmlspecialchars($row['name']) ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <input type="hidden" name="nature_of_business_id" id="natureInput" />
                </div>
            </div>
            <!-- Description -->
            <div class="md:col-span-3">
                <label for="description" class="block mb-1 text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3"
                   required
                   oninput="this.style.height='auto';this.style.height=this.scrollHeight + 'px';"
                   class="w-full border border-gray-300 rounded px-2 py-1 resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                   style="height:auto;"></textarea>
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Founded -->
                <div class="flex-1">
                    <label for="founded" class="block mb-1 text-sm font-medium text-gray-700">Founded</label>
                    <input type="number" name="founded" id="founded" min="1800" max="<?= date('Y') ?>"
                        placeholder="e.g. 2005"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                </div>
                <!-- Company Size -->
                <div class="flex-1">
                    <label for="company_size" class="block mb-1 text-sm font-medium text-gray-700">Company Size</label>
                    <input type="text" name="company_size" id="company_size" placeholder="e.g. 50-200 employees"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                </div>
                <!-- Website -->
                <div class="flex-1">
                    <label for="website" class="block mb-1 text-sm font-medium text-gray-700">Website</label>
                    <input type="url" name="website" id="website" placeholder="https://example.com"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
                </div>
            </div>
            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 transition mt-3">
                    Add
                </button>
            </div>
        </form>

        <!-- Existing Companies -->
        <h2 class="mt-8 mb-3 text-xl font-semibold border-b border-gray-200 pb-2">
            Existing Companies
            <span class="ml-2 text-base font-normal text-blue-600 align-middle bg-blue-100 px-2 py-0.5 rounded-full">
                (<?php echo count($companies); ?>)
            </span>
        </h2>

        <!-- Search Section -->
        <div class="mb-6 flex flex-col sm:flex-row items-center gap-3">
            <input type="text" id="companySearch" placeholder="Search company..." class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-400" />
            <button id="toggleViewBtn" type="button" class="bg-blue-100 text-blue-700 px-3 py-2 rounded font-semibold hover:bg-blue-200 transition">Toggle View</button>
        </div>
        <div id="companiesGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <?php if (count($companies) > 0): ?>
                <?php foreach ($companies as $company): ?>
                    <div class="company-card flex flex-col items-center border border-gray-100 rounded p-4 bg-gray-50 text-center">
                        <?php
                        $logo_web_path = !empty($company['logo']) ? $company['logo'] : '';
                        $logo_file_path = !empty($company['logo']) ? $_SERVER['DOCUMENT_ROOT'] . '/jobvacancyresult/' . $company['logo'] : '';
                        ?>
                        <?php if (!empty($company['logo']) && file_exists($logo_file_path)): ?>
                            <img src="/jobvacancyresult/<?php echo $logo_web_path; ?>" alt="Logo" class="w-14 h-14 object-contain rounded mb-2" />
                        <?php else: ?>
                            <div class="w-14 h-14 bg-gray-200 rounded flex items-center justify-center text-xs text-gray-500 mb-2">No Logo</div>
                        <?php endif; ?>
                        <span class="font-semibold mb-2"><?php echo htmlspecialchars($company['name']); ?></span>
                        <?php if (!empty($company['industry_names'])): ?>
                            <span class="text-xs text-blue-700 mb-1"><?php echo htmlspecialchars($company['industry_names']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($company['company_type_name'])): ?>
                            <span class="text-xs text-green-700 mb-1"><?php echo htmlspecialchars($company['company_type_name']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($company['nature_names'])): ?>
                            <span class="text-xs text-purple-700 mb-1"><?php echo htmlspecialchars($company['nature_names']); ?></span>
                        <?php endif; ?>
                        <div class="flex gap-2">
                            <a href="edit_company.php?id=<?php echo $company['id']; ?>" class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Edit</a>
                            <a href="?delete_id=<?php echo $company['id']; ?>" onclick="return confirm('Delete this company?');"
                                class="text-red-600 hover:text-red-800 text-xs font-semibold">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="italic text-gray-500 col-span-full">No companies added yet.</div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('companySearch');
        const companiesGrid = document.getElementById('companiesGrid');
        const companyCards = Array.from(companiesGrid.getElementsByClassName('company-card'));
        const toggleBtn = document.getElementById('toggleViewBtn');
        let showAll = true;

        searchInput.addEventListener('input', function() {
            const val = this.value.trim().toLowerCase();
            companyCards.forEach(card => {
                const name = card.querySelector('span').textContent.toLowerCase();
                card.style.display = name.includes(val) ? '' : 'none';
            });
        });

        toggleBtn.addEventListener('click', function() {
            showAll = !showAll;
            if (showAll) {
                companyCards.forEach(card => card.style.display = '');
                toggleBtn.textContent = 'Show Less';
            } else {
                companyCards.forEach((card, idx) => {
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

        // Helper function to create tags for selected items
        function createTag(id, name, color, container, selectedIds, inputElement) {
            const tag = document.createElement('span');
            tag.className = `inline-block bg-${color}-100 text-${color}-700 rounded px-2 py-0.5 text-xs mr-1`;
            tag.textContent = name;

            const removeBtn = document.createElement('button');
            removeBtn.className = 'ml-1 text-xs text-red-500 hover:text-red-700';
            removeBtn.textContent = 'x';
            removeBtn.addEventListener('click', () => {
                selectedIds.splice(selectedIds.indexOf(id), 1);
                inputElement.value = selectedIds.join(',');
                container.removeChild(tag);
            });

            tag.appendChild(removeBtn);
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

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!industryBox.contains(e.target) && !industryDropdown.contains(e.target)) {
                industryDropdown.classList.add('hidden');
            }
            if (!natureBox.contains(e.target) && !natureDropdown.contains(e.target)) {
                natureDropdown.classList.add('hidden');
            }
        });
    </script>

</body>
</html>