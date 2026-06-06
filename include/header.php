<?php
$base_url = "http://".$_SERVER['HTTP_HOST'];
$root_path = strpos($_SERVER['REQUEST_URI'], '/jobvacancyresult') !== false ? '/jobvacancyresult' : '';
$base = $base_url . $root_path;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>
    Job Vacancy Result - Find Your Dream Job
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />

  <link rel="icon" href="/jobvacancyresult/jvr-logo.jpg" width="32">
  <style>
    /* Smooth dropdown fade */
    .group:hover>ul,
    .group:focus-within>ul {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
      transition: opacity 0.2s ease, transform 0.2s ease;
    }

    ul.dropdown-menu {
      opacity: 0;
      visibility: hidden;
      transform: translateY(5px);
      transition: opacity 0.2s ease, transform 0.2s ease;
    }

    /* Hover effect for desktop menu items */
    nav a:hover,
    nav button:hover {
      color: rgb(78, 78, 239);
      /* Tailwind gray-800 */
    }

    nav a:focus,
    nav button:focus {
      outline: 2px solid #2563eb;
      /* Tailwind blue-600 */
      outline-offset: 2px;
    }

    /* Mobile submenu open icon rotation */
    .rotate-180 {
      transform: rotate(180deg);
      transition: transform 0.3s ease;
    }
  </style>
</head>

<body class="bg-white font-inter">
  <nav class="w-full border-b border-gray-200 sticky top-0 z-50 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <a class="flex items-center" href="https://jobvacancyresult.com/">
          <img alt="Job Vacancy Result logo, stylized letters JVR in blue and black" class="h-8 w-8" height="32" src="/jvr-logo.jpg" width="32" />
          <span class="ml-1 font-bold text-2xl text-black select-none">
            Job Vacancy Result
          </span>
        </a>
        <!-- Desktop Menu -->
        <div class="hidden md:flex space-x-6 text-gray-700 text-sm font-normal">
          <div class="relative group">
            <a class="inline-flex items-center px-3 py-2 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded" href="https://jobvacancyresult.com" role="menuitem" tabindex="0">
              Home
            </a>
          </div>
          <div class="relative group">
            <a class="inline-flex items-center px-3 py-2 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded" href="<?php echo $base; ?>/job" role="menuitem" tabindex="0">
              Find Jobs
            </a>
          </div>
          <div class="relative group">
            <a class="inline-flex items-center px-3 py-2 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded" href="/companies" role="menuitem" tabindex="0">
              Companies
            </a>
          </div>
          <div class="relative group">
            <a class="inline-flex items-center px-3 py-2 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded" href="/courses" role="menuitem" tabindex="0">
              Courses
            </a>
          </div>
          <div class="relative group">
            <button aria-expanded="false" aria-haspopup="true" class="inline-flex items-center px-3 py-2 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded" tabindex="0" type="button">
              Pages
              <i class="fas fa-chevron-down ml-1 text-xs">
              </i>
            </button>
            <ul aria-label="Pages submenu" class="dropdown-menu absolute left-0 mt-2 w-40 bg-white border border-gray-200 rounded-md shadow-lg z-10" role="menu">
              <li>
                <a class="block px-4 py-2 text-gray-700 hover:bg-gray-100 text-sm transition-colors duration-150" href="/About-Us" role="menuitem" tabindex="0">
                  About Us
                </a>
              </li>
              <?php $base = "https://jobvacancyresult.com"; ?>

              <li>
                <a class="block px-4 py-2 text-gray-700 hover:bg-gray-100 text-sm transition-colors duration-150" href="/contact" role="menuitem" tabindex="0">
                  Contact
                </a>
              </li>
              <li>
                <a class="block px-4 py-2 text-gray-700 hover:bg-gray-100 text-sm transition-colors duration-150" href="/faq" role="menuitem" tabindex="0">
                  FAQ
                </a>
              </li>
            </ul>
          </div>
          <div class="relative group">
            <a class="inline-flex items-center px-3 py-2 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded" href="/blog" role="menuitem" tabindex="0">
              Blogs
            </a>
          </div>
        </div>
        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center">
          <button aria-expanded="false" aria-label="Toggle menu" class="text-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-700" id="mobile-menu-button">
            <i class="fas fa-bars fa-lg">
            </i>
          </button>
        </div>
      </div>
    </div>
    <!-- Mobile menu -->
    <div aria-label="Mobile menu" class="md:hidden hidden border-t border-gray-200 bg-white" id="mobile-menu" role="menu">
      <ul class="flex flex-col space-y-1 px-4 py-3 text-gray-700 text-sm font-normal">
        <li>
          <a class="block px-4 py-2 hover:bg-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-blue-600" href="https://jobvacancyresult.com/" role="menuitem" tabindex="0">
            Home
          </a>
        </li>
        <li>
          <a class="block px-4 py-2 hover:bg-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-blue-600" href="/job" role="menuitem" tabindex="0">
            Find Jobs
          </a>
        </li>
        <li>
          <a class="block px-4 py-2 hover:bg-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-blue-600" href="/companies" role="menuitem" tabindex="0">
            Companies
          </a>
        </li>
        <li>
          <a class="block px-4 py-2 hover:bg-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-blue-600" href="/courses" role="menuitem" tabindex="0">
            Courses
          </a>
        </li>
        <li>
          <button aria-expanded="false" aria-haspopup="true" class="w-full flex justify-between items-center py-2 focus:outline-none hover:text-blue-700 transition-colors duration-150" data-dropdown-target="mobile-pages-dropdown" type="button">
            <span>
              Pages
            </span>
            <i class="fas fa-chevron-down text-xs transition-transform duration-300">
            </i>
          </button>
          <ul aria-label="Pages submenu" class="hidden pl-4 mt-1 space-y-1 border-l border-gray-300" id="mobile-pages-dropdown" role="menu">
            <li>
              <a class="block py-1 hover:underline hover:text-blue-700 transition-colors duration-150" href="/About-Us" role="menuitem" tabindex="0">
                About Us
              </a>
            </li>
            <li>
              <a class="block py-1 hover:underline hover:text-blue-700 transition-colors duration-150" href="/contact" role="menuitem" tabindex="0">
                Contact
              </a>
            </li>
            <li>
              <a class="block py-1 hover:underline hover:text-blue-700 transition-colors duration-150" href="/faq" role="menuitem" tabindex="0">
                FAQ
              </a>
            </li>
          </ul>
        </li>
        <li>
          <a class="block px-4 py-2 hover:bg-gray-100 rounded focus:outline-none focus:ring-2 focus:ring-blue-600" href="/blog" role="menuitem" tabindex="0">
            Blog
          </a>
        </li>
      </ul>
    </div>
  </nav>
  <script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById("mobile-menu-button");
    const mobileMenu = document.getElementById("mobile-menu");

    mobileMenuButton.addEventListener("click", () => {
      const expanded = mobileMenuButton.getAttribute("aria-expanded") === "true" || false;
      mobileMenuButton.setAttribute("aria-expanded", !expanded);
      mobileMenu.classList.toggle("hidden");
      
      // Update button icon based on menu state
      const icon = mobileMenuButton.querySelector("i.fas");
      if (icon) {
        if (mobileMenu.classList.contains("hidden")) {
          icon.classList.remove("fa-times");
          icon.classList.add("fa-bars");
        } else {
          icon.classList.remove("fa-bars");
          icon.classList.add("fa-times");
        }
      }
    });

    // Mobile dropdown toggles with icon rotation
    const dropdownButtons = document.querySelectorAll('[data-dropdown-target]');

    dropdownButtons.forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        const targetId = btn.getAttribute("data-dropdown-target");
        const dropdown = document.getElementById(targetId);
        const expanded = btn.getAttribute("aria-expanded") === "true" || false;
        
        btn.setAttribute("aria-expanded", !expanded);
        if (dropdown) {
          dropdown.classList.toggle("hidden");
        }
        
        // Rotate icon
        const icon = btn.querySelector("i.fas.fa-chevron-down");
        if (icon) {
          icon.classList.toggle("rotate-180");
        }
      });
    });

    // Close dropdowns when clicking outside
    document.addEventListener("click", (e) => {
      if (!e.target.closest('[data-dropdown-target]') && !e.target.closest('.dropdown-menu')) {
        dropdownButtons.forEach((btn) => {
          const targetId = btn.getAttribute("data-dropdown-target");
          const dropdown = document.getElementById(targetId);
          if (dropdown && !dropdown.classList.contains("hidden")) {
            dropdown.classList.add("hidden");
            btn.setAttribute("aria-expanded", "false");
            
            // Reset icon rotation
            const icon = btn.querySelector("i.fas.fa-chevron-down");
            if (icon) {
              icon.classList.remove("rotate-180");
            }
          }
        });
      }
    });

    // Keyboard navigation for accessibility
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        // Close all dropdowns on Escape key
        dropdownButtons.forEach((btn) => {
          const targetId = btn.getAttribute("data-dropdown-target");
          const dropdown = document.getElementById(targetId);
          if (dropdown && !dropdown.classList.contains("hidden")) {
            dropdown.classList.add("hidden");
            btn.setAttribute("aria-expanded", "false");
            
            const icon = btn.querySelector("i.fas.fa-chevron-down");
            if (icon) {
              icon.classList.remove("rotate-180");
            }
          }
        });
        
        // Close mobile menu on Escape key
        if (!mobileMenu.classList.contains("hidden")) {
          mobileMenu.classList.add("hidden");
          mobileMenuButton.setAttribute("aria-expanded", "false");
          
          const icon = mobileMenuButton.querySelector("i.fas");
          if (icon) {
            icon.classList.remove("fa-times");
            icon.classList.add("fa-bars");
          }
        }
      }
    });
  </script>
</body>

</html>