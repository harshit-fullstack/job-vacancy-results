<nav class="bg-blue-700 text-white px-6 py-4 flex justify-between items-center shadow-md">
  <div class="text-2xl font-bold tracking-wide select-none">
    <span class="text-yellow-300">JVR</span> - Job Vacancy Result
  </div>
  <div class="space-x-6 text-lg flex items-center">
    <a href="../admin/dashboard.php" class="hover:text-yellow-300 font-medium transition duration-200">Dashboard</a>
    <!-- Jobs Dropdown -->
    <div class="relative inline-block">
      <button id="jobsDropdownBtn" class="hover:text-yellow-300 font-medium transition duration-200 focus:outline-none flex items-center">
        Jobs
        <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
        </svg>
      </button>
      <div id="jobsDropdown" class="absolute left-0 mt-2 w-48 bg-white text-blue-900 rounded shadow-lg hidden z-50">
        <a href="../admin/add-job.php" class="block px-4 py-2 hover:bg-blue-100">Add Job</a>
      </div>
    </div>
    <!-- Master Data Dropdown -->
    <div class="relative inline-block">
      <button id="masterDropdownBtn" class="hover:text-yellow-300 font-medium transition duration-200 focus:outline-none flex items-center">
        Master Data
        <svg class="inline w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
        </svg>
      </button>
      <div id="masterDropdown" class="absolute left-0 mt-2 w-56 bg-white text-blue-900 rounded shadow-lg hidden z-50">
        <a href="../admin/add_company.php" class="block px-4 py-2 hover:bg-blue-100">Companies</a>
        <a href="../admin/add_company_type.php" class="block px-4 py-2 hover:bg-blue-100">Company Types</a>
        <a href="../admin/add_industry.php" class="block px-4 py-2 hover:bg-blue-100">Industries</a>
        <a href="../admin/add_department.php" class="block px-4 py-2 hover:bg-blue-100">Departments</a>
        <a href="../admin/add_nature_of_business.php" class="block px-4 py-2 hover:bg-blue-100">Nature of Business</a>
        <a href="../admin/add_skill.php" class="block px-4 py-2 hover:bg-blue-100">Skills</a>
        <a href="../admin/add_role_category.php" class="block px-4 py-2 hover:bg-blue-100">Role Categories</a>
      </div>
    </div>
    <a href="../admin/login.php" class="hover:text-yellow-300 font-medium transition duration-200">Logout</a>
  </div>
</nav>
<hr class="border-yellow-300" />

<script>
  // Jobs Dropdown
  const jobsBtn = document.getElementById('jobsDropdownBtn');
  const jobsDropdown = document.getElementById('jobsDropdown');
  jobsBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    jobsDropdown.classList.toggle('hidden');
    masterDropdown.classList.add('hidden');
  });

  // Master Data Dropdown
  const masterBtn = document.getElementById('masterDropdownBtn');
  const masterDropdown = document.getElementById('masterDropdown');
  masterBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    masterDropdown.classList.toggle('hidden');
    jobsDropdown.classList.add('hidden');
  });

  // Close dropdowns when clicking outside
  document.addEventListener('click', function() {
    jobsDropdown.classList.add('hidden');
    masterDropdown.classList.add('hidden');
  });
</script>