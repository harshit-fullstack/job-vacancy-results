<?php
require_once '../include/auth.php';
require_once '../include/db.php';
require_once '../include/constants.php';

if (!isset($_SESSION['admin'])) {
  header('Location: login.php');
  exit;
}

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM jobs WHERE id = $id");
$job = mysqli_fetch_assoc($result);

// Fetch dynamic dropdown data (use IDs for foreign keys)
$companies = mysqli_query($conn, "SELECT id, name FROM companies ORDER BY name");
$industries = mysqli_query($conn, "SELECT id, name FROM industries ORDER BY name");
$roleCategories = mysqli_query($conn, "SELECT id, name FROM role_categories ORDER BY name");
$departments = mysqli_query($conn, "SELECT id, name FROM departments ORDER BY name");
$skillsQuery = mysqli_query($conn, "SELECT name FROM skills ORDER BY name");

$states = [
  "Andhra Pradesh",
  "Arunachal Pradesh",
  "Assam",
  "Bihar",
  "Chhattisgarh",
  "Goa",
  "Gujarat",
  "Haryana",
  "Himachal Pradesh",
  "Jharkhand",
  "Karnataka",
  "Kerala",
  "Madhya Pradesh",
  "Maharashtra",
  "Manipur",
  "Meghalaya",
  "Mizoram",
  "Nagaland",
  "Odisha",
  "Punjab",
  "Rajasthan",
  "Sikkim",
  "Tamil Nadu",
  "Telangana",
  "Tripura",
  "Uttar Pradesh",
  "Uttarakhand",
  "West Bengal",
  "Delhi",
  "Jammu & Kashmir",
  "Andaman and Nicobar Islands",
  "Ladakh",
  "Lakshadweep",
  "Puducherry"
];
$stateCityMap = [
  "Andhra Pradesh" => ["Visakhapatnam", "Vijayawada", "Guntur", "Nellore", "Rajamahendravaram", "Kurnool", "Kakinada", "Kadapa", "Tirupati", "Mangalagiri-Tadepalli", "Anantapuram", "Ongole", "Vizianagaram", "Eluru", "Proddatur", "Nandyal", "Adoni", "Madanapalle", "Machilipatnam", "Chittoor", "Hindupur", "Srikakulam", "Bhimavaram", "Tadepalligudem", "Tenali", "Guntakal", "Dharmavaram", "Gudivada", "Narasaraopet", "Kadiri", "Tadipatri", "Chilakaluripet"],
      "Arunachal Pradesh"=> ["Itanagar", "Naharlagun", "Pasighat", "Tawang", "Bomdila", "Ziro", "Aalo (Along)", "Roing", "Tezu", "Khonsa", "Daporijo", "Seppa", "Yingkiong", "Anini", "Namsai"],
      "Assam"=> ["Guwahati", "Silchar", "Dibrugarh", "Jorhat", "Nagaon", "Tinsukia", "Tezpur", "Bongaigaon", "Karimganj", "Dhubri", "Sivasagar", "Diphu", "North Lakhimpur", "Barpeta", "Goalpara", "Haflong", "Kokrajhar", "Morigaon", "Nalbari", "Mangaldoi"],
      "Bihar"=> ["Patna", "Gaya", "Bhagalpur", "Muzaffarpur", "Purnia", "Darbhanga", "Bihar Sharif", "Arrah", "Begusarai", "Katihar", "Munger", "Chhapra", "Hajipur", "Dehri", "Bettiah", "Motihari", "Siwan", "Sasaram", "Samastipur", "Nawada", "Buxar", "Jamalpur", "Sitamarhi", "Kishanganj", "Jehanabad"],
      "Chhattisgarh"=> ["Raipur", "Bilaspur", "Durg", "Bhilai", "Korba", "Rajnandgaon", "Raigarh", "Jagdalpur", "Ambikapur", "Dhamtari", "Mahasamund", "Janjgir", "Kawardha (Kabirdham)", "Kanker", "Bastar"],
      "Goa"=> ["Panaji (Panjim)", "Margao", "Vasco da Gama", "Mapusa", "Ponda", "Bicholim", "Curchorem", "Sanquelim", "Cuncolim", "Valpoi"],
      "Gujarat"=> ["Ahmedabad", "Surat", "Vadodara", "Rajkot", "Bhavnagar", "Jamnagar", "Gandhinagar", "Junagadh", "Nadiad", "Anand", "Morbi", "Mehsana", "Bharuch", "Valsad", "Navsari"],
      "Haryana"=> ["Faridabad", "Gurugram (Gurgaon)", "Panipat", "Ambala", "Yamunanagar", "Rohtak", "Hisar", "Karnal", "Sonipat", "Panchkula", "Bhiwani", "Bahadurgarh", "Sirsa", "Rewari", "Kaithal"],
      "Himachal Pradesh"=> ["Shimla", "Dharamshala", "Mandi", "Solan", "Bilaspur", "Hamirpur", "Una", "Chamba", "Kullu", "Nahan", "Palampur", "Baddi", "Kangra", "Sundernagar", "Parwanoo"],
      "Jharkhand"=> ["Ranchi", "Jamshedpur", "Dhanbad", "Bokaro Steel City", "Hazaribagh", "Deoghar", "Giridih", "Ramgarh", "Chaibasa", "Dumka", "Palamu (Daltonganj)", "Sahibganj", "Lohardaga", "Jamtara", "Latehar"],
      "Delhi"=> ["New Delhi", "Central Delhi", "South Delhi", "North Delhi", "East Delhi", "West Delhi", "Shahdara", "Rohini", "Dwarka", "Najafgarh", "Karol Bagh", "Laxmi Nagar", "Saket", "Connaught Place", "Mayur Vihar"],
      "Karnataka"=> ["Bengaluru", " Mysuru", "Mangaluru", "Hubballi", "Dharwad", "Belagavi", "Kalaburagi", "Ballari", "Davanagere", "Shivamogga", "Tumakuru", "Vijayapura", "Raichur", "Hassan", "Bidar"],
      "Kerala"=> ["Thiruvananthapuram", "Kochi", "Kozhikode", "Kollam", "Thrissur", "Alappuzha", "Palakkad", "Malappuram", "Kannur", "Kottayam", "Pathanamthitta", "Kasaragod", "Idukki", "Varkala", "Nedumangad"],

      "Madhya Pradesh"=> ["Bhopal", "Indore", "Jabalpur", "Gwalior", "Ujjain", "Sagar", "Satna", "Ratlam", "Rewa", "Khandwa", "Chhindwara", "Vidisha", "Burhanpur", "Katni", "Dewas"],
      "Maharashtra"=> ["Mumbai", "Pune", "Nagpur", "Nashik", "Thane", "Aurangabad", "Navi Mumbai", "Solapur", "Amravati", "Kolhapur", "Sangli", "Jalgaon", "Akola", "Latur", "Ahmednagar"],
      "Manipur"=> ["Imphal", "Thoubal", "Bishnupur", "Churachandpur", "Kakching", "Ukhrul", "Senapati", "Tamenglong", "Moirang", "Jiribam"],
      "Meghalaya"=> ["Shillong", "Tura", "Nongstoin", "Jowai", "Baghmara", "Williamnagar", "Resubelpara", "Mairang", "Sohra (Cherrapunji)", "Mawkyrwat"],
      "Mizoram"=> ["Aizawl", "Lunglei", "Champhai", "Serchhip", "Kolasib", "Lawngtlai", "Saiha", "Mamit", "Khawzawl", "Hnahthial"],
      "Nagaland"=> ["Kohima", "Dimapur", "Mokokchung", "Tuensang", "Wokha", "Zunheboto", "Mon", "Phek", "Longleng", "Kiphire"],
      "Odisha"=> ["Bhubaneswar", "Cuttack", "Rourkela", "Sambalpur", "Berhampur", "Puri", "Balasore", "Baripada", "Bhadrak", "Angul", "Jharsuguda", "Kendrapara", "Jeypore", "Koraput", "Dhenkanal"],
      "Punjab"=> ["Ludhiana", "Amritsar", "Jalandhar", "Patiala", "Bathinda", "Mohali", "Hoshiarpur", "Pathankot", "Moga", "Phagwara", "Abohar", "Malerkotla", "Firozpur", "Barnala", "Kapurthala"],
      "Rajasthan"=> ["Jaipur", "Jodhpur", "Kota", "Udaipur", "Bikaner", "Ajmer", "Alwar", "Bhilwara", "Sikar", "Pali", "Bharatpur", "Sri Ganganagar", "Hanumangarh", "Barmer", "Churu"],
      "Sikkim"=> ["Gangtok", "Namchi", "Geyzing (Gyalshing)", "Mangan", "Rangpo", "Singtam", "Jorethang", "Pakyong", "Soreng", "Chungthang"],
      "Tamil Nadu"=> ["Chennai", "Coimbatore", "Madurai", "Tiruchirappalli (Trichy)", "Salem", "Tirunelveli", "Erode", "Vellore", "Thoothukudi (Tuticorin)", "Tiruppur", "Nagercoil", "Dindigul", "Hosur", "Cuddalore", "Kanchipuram"],
      "Telangana"=> ["Hyderabad", "Warangal", "Nizamabad", "Khammam", "Karimnagar", "Ramagundam", "Mahbubnagar", "Nalgonda", "Adilabad", "Suryapet", "Miryalaguda", "Siddipet", "Mancherial", "Jagtial", "Bodhan"],
      "Tripura"=> ["Agartala", "Udaipur", "Dharmanagar", "Kailasahar", "Belonia", "Ambassa", "Khowai", "Kumarghat", "Sonamura", "Teliamura"],
      "Uttar Pradesh"=> ["Lucknow", "Kanpur", "Ghaziabad", "Agra", "Varanasi", "Prayagraj (Allahabad)", "Meerut", "Bareilly", "Aligarh", "Moradabad", "Saharanpur", "Gorakhpur", "Noida", "Jhansi", "Mathura"],
      "Uttrakhand"=> ["Dehradun", "Haridwar", "Haldwani", "Roorkee", "Rudrapur", "Kashipur", "Rishikesh", "Nainital", "Pithoragarh", "Almora"],
      "West Bengal"=> ["Kolkata", "Asansol", "Siliguri", "Durgapur", "Howrah", "Bardhaman", "Malda", "Kharagpur", "Jalpaiguri", "Haldia", "Berhampore", "Chandannagar", "Balurghat", "Cooch Behar", "Darjeeling"],
      "Jammu & Kashmir"=> ["Srinagar", "Jammu", "Anantnag", "Baramulla", "Udhampur", "Kathua", "Sopore", "Kupwara", "Rajouri", "Pulwama", "Poonch", "Kulgam", "Ganderbal", "Bandipora", "Handwara"],
      "Andaman and Nicobar Islands"=> ["Port Blair", "Diglipur", "Mayabunder", "Rangat", "Neil Island (Shaheed Dweep)", "Havelock Island (Swaraj Dweep)", "Campbell Bay", "Car Nicobar", "Hut Bay", "Nancowry"],
      "Ladakh"=> ["Leh", "Kargil", "Nubra (Diskit)", "Padum (Zanskar Valley)", "Dras", "Tangtse", "Nyoma", "Chuchot", "Sankoo", "Choglamsar"],
      "Lakshadweep"=> ["Kavaratti (Capital)", "Agatti", "Amini", "Androth", "Kalpeni", "Kadmat", "Minicoy", "Kiltan", "Chetlat", "Bitra"],
      "Puducherry"=> ["Puducherry (Pondicherry)", "Karaikal", "Mahe", "Yana"]
];

$jobTypes = ['Full-Time', 'Part-Time', 'Freelance', 'Internship', 'Apprenticeship', 'Commission-Based', 'Contract', 'Temporary', 'On-Roll', 'Off-Roll'];
// $educationLevels = ['High School', 'Associate Degree', "Bachelor's Degree", "Master's Degree", 'Doctorate'];
$workModes = ['On-site', 'Remote', 'Hybrid', 'Rotational Shift'];

// Prepare skills array for JS autocomplete
$skills_array = [];
while ($row = mysqli_fetch_assoc($skillsQuery)) {
  $skills_array[] = $row['name'];
}

// Parse experience safely
$expMin = '';
$expMax = '';
if (preg_match('/(\d+)\s*-\s*(\d+)/', $job['experience'], $expMatches)) {
  $expMin = $expMatches[1];
  $expMax = $expMatches[2];
}

// Parse salary safely
$salaryMin = '';
$salaryMax = '';
if (preg_match('/(\d[\d,]*)\D*-\D*(\d[\d,]*)/', $job['salary'], $salaryMatches)) {
  $salaryMin = str_replace(',', '', $salaryMatches[1]);
  $salaryMax = str_replace(',', '', $salaryMatches[2]);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Edit Job - JVR Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen relative">
  <?php include '../include/navbar.php'; ?>

  <main class="max-w-6xl mx-auto p-4">
    <h2 class="text-2xl font-semibold mb-4">Edit Job - Job Vacancy Result</h2>

    <form method="POST" action="update-job" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 bg-white p-6 rounded shadow-lg text-sm">
      <input type="hidden" name="id" value="<?= htmlspecialchars($job['id']) ?>" />

      <!-- Job Title -->
      <div class="flex flex-col">
        <label for="title" class="mb-1 font-semibold">Job Title</label>
        <input id="title" name="title" type="text" required value="<?= htmlspecialchars($job['title']) ?>"
          class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <!-- Company (dropdown) -->
      <div class="flex flex-col">
        <label for="company_id" class="mb-1 font-semibold">Company</label>
        <select id="company_id" name="company_id" required class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="">Select company</option>
          <?php mysqli_data_seek($companies, 0);
          while ($row = mysqli_fetch_assoc($companies)) : ?>
            <option value="<?= $row['id'] ?>" <?= ($job['company_id'] == $row['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($row['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <!-- Department (dropdown) -->
      <div class="flex flex-col">
        <label for="department_id" class="mb-1 font-semibold">Department</label>
        <select id="department_id" name="department_id" class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="">Select department</option>
          <?php mysqli_data_seek($departments, 0);
          while ($row = mysqli_fetch_assoc($departments)) : ?>
            <option value="<?= $row['id'] ?>" <?= ($job['department_id'] == $row['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($row['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <!-- State -->
      <div class="flex flex-col">
        <label for="state" class="mb-1 font-semibold">State</label>
        <input id="state" name="state" list="state-list" required
          class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          value="<?= htmlspecialchars($job['state']) ?>"
          placeholder="Select or type state" />
        <datalist id="state-list">
          <?php foreach ($states as $state): ?>
            <option value="<?= $state ?>"></option>
          <?php endforeach; ?>
        </datalist>
      </div>

      <!-- City -->
      <div class="flex flex-col">
        <label for="city" class="mb-1 font-semibold">City</label>
        <input id="city" name="city" list="city-list" required
          class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          value="<?= htmlspecialchars($job['city']) ?>"
          placeholder="Select or type city" />
        <datalist id="city-list"></datalist>
      </div>

      <!-- Industry (dropdown) -->
      <div class="flex flex-col">
        <label for="industry_id" class="mb-1 font-semibold">Industry</label>
        <select id="industry_id" name="industry_id" required class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="">Select industry</option>
          <?php mysqli_data_seek($industries, 0);
          while ($row = mysqli_fetch_assoc($industries)) : ?>
            <option value="<?= $row['id'] ?>" <?= ($job['industry_id'] == $row['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($row['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <!-- Role Category (dropdown) -->
      <div class="flex flex-col">
        <label for="role_category_id" class="mb-1 font-semibold">Role Category</label>
        <select id="role_category_id" name="role_category_id" required class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="">Select role category</option>
          <?php mysqli_data_seek($roleCategories, 0);
          while ($row = mysqli_fetch_assoc($roleCategories)) : ?>
            <option value="<?= $row['id'] ?>" <?= ($job['role_category_id'] == $row['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($row['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <!-- Skills (text input with multi-autocomplete) -->
      <div class="flex flex-col col-span-2 relative">
        <label for="skills" class="mb-1 font-semibold">Skills</label>
        <input id="skills" name="skills" type="text" autocomplete="off"
          placeholder="e.g. PHP, JavaScript, SQL" required
          value="<?= htmlspecialchars($job['skills']) ?>"
          class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        <ul id="skills-suggestions" class="bg-white border rounded mt-1 absolute z-10 hidden max-h-40 overflow-y-auto text-sm shadow-lg w-full"></ul>
      </div>

      <!-- Type of Job -->
      <div class="flex flex-col">
        <label for="job_type" class="mb-1 font-semibold">Type of Job</label>
        <select id="job_type" name="job_type" required
          class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="">Select Job Type</option>
          <?php foreach ($jobTypes as $type): ?>
            <option value="<?= $type ?>" <?= ($job['job_type'] === $type) ? 'selected' : '' ?>><?= $type ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Education -->
      <div class="flex flex-col">
        <label for="education" class="mb-1 font-semibold">Education</label>
        <select id="education" name="education" required
          class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="">Select Education</option>
          <?php foreach ($educationLevels as $edu): ?>
            <option value="<?= $edu ?>" <?= ($job['education'] === $edu) ? 'selected' : '' ?>><?= $edu ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Work Mode -->
      <div class="flex flex-col">
        <label for="work_mode" class="mb-1 font-semibold">Work Mode</label>
        <select id="work_mode" name="work_mode" required
          class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="">Select Work Mode</option>
          <?php foreach ($workModes as $mode): ?>
            <option value="<?= $mode ?>" <?= ($job['work_mode'] === $mode) ? 'selected' : '' ?>><?= $mode ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Job Link -->
      <div class="flex flex-col ">
        <label for="job_link" class="mb-1 font-semibold">Job Link (URL)</label>
        <input id="job_link" name="job_link" type="url"
          value="<?= htmlspecialchars($job['job_link']) ?>"
          class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <!-- Experience (min/max inputs + live display) -->
      <div class="flex flex-col col-span-2">
        <label class="mb-1 font-semibold">Experience (Years)</label>
        <div class="flex space-x-2">
          <input type="number" id="exp_min" name="exp_min" min="0" max="10" placeholder="Min"
            value="<?= htmlspecialchars($expMin) ?>"
            class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500" required />
          <input type="number" id="exp_max" name="exp_max" min="0" max="10" placeholder="Max"
            value="<?= htmlspecialchars($expMax) ?>"
            class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500" required />
        </div>
        <p id="exp_output" class="text-gray-600 text-sm mt-1"></p>
      </div>

      <!-- Salary Range with Not Disclosed checkbox -->
      <div class="flex flex-col col-span-2">
        <label class="mb-1 font-semibold">Salary Range (₹)</label>
        <div class="flex space-x-2">
          <input id="salary_min" name="salary_min" type="number" min="0" placeholder="Min salary (₹)" required
            value="<?= htmlspecialchars($salaryMin) ?>"
            class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500" />
          <input id="salary_max" name="salary_max" type="number" min="0" placeholder="Max salary (₹)" required
            value="<?= htmlspecialchars($salaryMax) ?>"
            class="w-full border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <label class="text-sm flex items-center space-x-2 mt-2 cursor-pointer select-none">
          <input type="checkbox" id="salary_not_disclosed" name="salary_not_disclosed" <?= stripos($job['salary'], 'not disclosed') !== false ? 'checked' : '' ?> />
          <span>Salary is not disclosed</span>
        </label>
      </div>

      <!-- Job Description -->
      <div class="flex flex-col col-span-2">
        <label for="description" class="mb-1 font-semibold">Job Description</label>
        <textarea id="description" name="description" rows="4" oninput="this.style.height='auto';this.style.height=this.scrollHeight + 'px';"
          class="border border-gray-300 rounded px-2 py-1 resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
          style="height:auto;"><?= htmlspecialchars($job['description']) ?></textarea>
      </div>

      <!-- Additional Information -->
      <div class="flex flex-col col-span-2">
        <label for="additional_info" class="mb-1 font-semibold">Additional Information</label>
        <textarea id="additional_info" name="additional_info" rows="4"
          oninput="this.style.height='auto';this.style.height=this.scrollHeight + 'px';"
          class="border border-gray-300 rounded px-2 py-1 resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
          style="height:auto;"><?= htmlspecialchars($job['additional_info']) ?></textarea>
      </div>

      <!-- Is Featured -->
      <div class="flex flex-col col-span-2">
        <label class="mb-1 font-semibold flex items-center space-x-2 cursor-pointer">
          <input type="checkbox" id="is_featured" name="is_featured" value="1" <?= ($job['is_featured'] ?? 0) ? 'checked' : '' ?> />
          <span>Is Featured</span>
        </label>
        <p class="text-sm text-gray-600">Check this to mark the job as featured. Featured jobs will be prominently displayed on the homepage.</p>
      </div>

      <!-- Submit -->
      <div class="col-span-1 sm:col-span-2 lg:col-span-4 w-full">
        <button type="submit"
          class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition">Update Job</button>
      </div>
    </form>
  </main>

  <script>
    const stateCityMap = <?= json_encode($stateCityMap) ?>;
    const stateInput = document.getElementById('state');
    const cityInput = document.getElementById('city');
    const cityList = document.getElementById('city-list');

    function populateCities(selectedCity = '') {
      const state = stateInput.value;
      cityList.innerHTML = '';
      if (!state || !stateCityMap[state]) return;
      stateCityMap[state].forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        cityList.appendChild(option);
      });
      if (selectedCity) cityInput.value = selectedCity;
    }

    stateInput.addEventListener('input', () => populateCities());
    populateCities("<?= htmlspecialchars($job['city']) ?>");

    // Experience range display
    const expMin = document.getElementById('exp_min');
    const expMax = document.getElementById('exp_max');
    const expOutput = document.getElementById('exp_output');

    function updateExpOutput() {
      const min = expMin.value;
      const max = expMax.value;
      if (min && max && Number(min) <= Number(max)) {
        expOutput.textContent = `${min} - ${max} years`;
      } else if (min && !max) {
        expOutput.textContent = `From ${min} years`;
      } else if (!min && max) {
        expOutput.textContent = `Up to ${max} years`;
      } else {
        expOutput.textContent = '';
      }
    }
    expMin.addEventListener('input', updateExpOutput);
    expMax.addEventListener('input', updateExpOutput);
    updateExpOutput();

    // Salary not disclosed toggle
    const salaryCheckbox = document.getElementById('salary_not_disclosed');
    const salaryMin = document.getElementById('salary_min');
    const salaryMax = document.getElementById('salary_max');

    function toggleSalaryFields() {
      const disabled = salaryCheckbox.checked;
      salaryMin.disabled = disabled;
      salaryMax.disabled = disabled;
      salaryMin.classList.toggle('bg-gray-100', disabled);
      salaryMax.classList.toggle('bg-gray-100', disabled);
    }
    salaryCheckbox.addEventListener('change', toggleSalaryFields);
    toggleSalaryFields();

    // Skills autocomplete multi-input
    const skillsList = <?= json_encode($skills_array); ?>;
    const skillsInput = document.getElementById('skills');
    const suggestionsBox = document.getElementById('skills-suggestions');

    skillsInput.addEventListener('input', () => {
      const parts = skillsInput.value.split(',');
      const last = parts[parts.length - 1].trim().toLowerCase();
      suggestionsBox.innerHTML = '';
      if (!last) return suggestionsBox.classList.add('hidden');
      const matched = skillsList.filter(skill => skill.toLowerCase().startsWith(last));
      matched.forEach(skill => {
        const li = document.createElement('li');
        li.textContent = skill;
        li.className = 'px-3 py-1 cursor-pointer hover:bg-gray-200';
        li.onclick = () => {
          parts[parts.length - 1] = skill;
          skillsInput.value = parts.map(p => p.trim()).join(', ') + ', ';
          suggestionsBox.classList.add('hidden');
        };
        suggestionsBox.appendChild(li);
      });
      suggestionsBox.classList.remove('hidden');
    });

    document.addEventListener('click', (e) => {
      if (!skillsInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
        suggestionsBox.classList.add('hidden');
      }
    });
  </script>

</body>

</html>