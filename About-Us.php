<?php
require_once 'include/db.php';

    function slugify($string)
    {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
        $string = preg_replace('/[\s-]+/', '-', $string);
        return trim($string, '-');
    }



// Fetch latest 8 featured jobs with company logo
$featuredJobs = [];
$jobQuery = "
  SELECT 
    j.id, j.title, j.city, j.created_at, j.salary,
    c.name AS company_name, c.logo
  FROM jobs j
  INNER JOIN companies c ON j.company_id = c.id
  WHERE j.title IS NOT NULL
  ORDER BY j.created_at DESC
  LIMIT 8
";

$jobResult = mysqli_query($conn, $jobQuery);
while ($row = mysqli_fetch_assoc($jobResult)) {
  $row['logo'] = !empty($row['logo']) ? '/' . $row['logo'] : 'https://placehold.co/80x80/png?text=Logo';
  $row['created_at_formatted'] = date('F j, Y ⁃ g:i a', strtotime($row['created_at']));
  $featuredJobs[] = $row;
}

// Fetch top 4 locations with most jobs
$topLocations = [];
$locationSql = "
  SELECT j.city, j.state, COUNT(*) as job_count
  FROM jobs j
  WHERE j.city IS NOT NULL AND j.state IS NOT NULL AND j.city != '' AND j.state != ''
  GROUP BY j.city, j.state
  ORDER BY job_count DESC
  LIMIT 4
";
$locationResult = mysqli_query($conn, $locationSql);
while ($row = mysqli_fetch_assoc($locationResult)) {
  $topLocations[] = $row;
}
?>

<html>

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <?php
  $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
  $canonicalUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
  if (!empty($_SERVER['QUERY_STRING'])) {
      $canonicalUrl .= '?' . htmlspecialchars($_SERVER['QUERY_STRING']);
  }
  $seoTitle = 'About Us | JobVacancyResult';
  $desc = 'Learn more about JobVacancyResult, our mission, vision, and the team dedicated to connecting talent with opportunity.';
  ?>
  <title><?= $seoTitle ?></title>
  <meta name="description" content="<?= $desc ?>" />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="<?= $canonicalUrl ?>" />
  <!-- Open Graph Meta -->
  <meta property="og:title" content="<?= $seoTitle ?>" />
  <meta property="og:description" content="<?= $desc ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?= $canonicalUrl ?>" />
  <meta property="og:site_name" content="JobVacancyResult" />
  <meta property="og:image" content="<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jvr-logo.jpg" />
  <!-- Twitter Card Meta -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?= $seoTitle ?>" />
  <meta name="twitter:description" content="<?= $desc ?>" />
  <meta name="twitter:image" content="<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jvr-logo.jpg" />
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/courses.css">
    <link rel="icon" href="/jvr-logo.jpg" width="32">

    <!-- Schema Markup -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "AboutPage",
      "name": "About Us | JobVacancyResult",
      "description": "Learn more about JobVacancyResult, our mission, vision, and the team dedicated to connecting talent with opportunity.",
      "url": "<?= $canonicalUrl ?>"
    }
    </script>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jobvacancyresult/"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "About Us",
          "item": "<?= $canonicalUrl ?>"
        }
      ]
    }
    </script>

</head>

<body>
  <?php include 'include/header.php'; ?>
  <!-- Main Hero Section -->
 <section class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 mt-12 flex flex-col-reverse lg:flex-row items-center gap-12">
    <div class="flex-1 text-center lg:text-left">
      <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight text-gray-900">
        Find Your
        <br />
        Dream
        <span class="text-blue-600">
          Job Today
        </span>
      </h1>
      <p class="mt-6 text-lg text-gray-600 leading-relaxed">
        Search thousands of verified job postings across industries.  
        <br />
        Connect with top companies and recruiters.  
        <br />
        Start your career journey with JobVacancyResult.
      </p>
      <div class="mt-8 flex justify-center lg:justify-start gap-6 flex-wrap">
        <a href="/job">
          <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-md shadow-md transition">
            Search Jobs
          </button>
        </a>
      </div>
    </div>
    <div class="flex-1 relative max-w-md w-full">
      <img alt="Decorative shape" aria-hidden="true" class="absolute -top-10 -left-10 w-48 h-48 object-contain opacity-30" src="https://storage.googleapis.com/a1aa/image/dedf5a0b-2345-4550-74d7-0c154ae18aa4.jpg" />
      <div class="border-8 border-blue-600 rounded-xl overflow-hidden shadow-lg relative z-10">
        <img alt="Professional person working on a laptop" class="w-full h-auto object-cover" src="https://storage.googleapis.com/a1aa/image/c51935e6-b0a9-4fb9-d8b7-3885d34a2c4f.jpg" />
      </div>
    </div>
  </section>
  <!-- Trusted Companies Section -->
  <!-- Companies Section -->

  <section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">
          Trusted by Leading Companies
        </h2>
        <p class="text-gray-600">
          Thousands of employers use JobVacancyResult to find top talent
        </p>
      </div>
      <!-- /* clients section scroller -->


      <div class="logos clients1">
        <div class="logos-slide">
          <img src="images/new company/1.png" alt="1" />
          <img src="images/new company/2.png" alt="2" />
          <img src="images/new company/3.png" alt="3" />
          <img src="images/new company/19.png" alt="19" />
          <img src="images/new company/4.png" alt="4" />
          <img src="images/new company/5.png" alt="5"/>
          <img src="images/new company/6.png" alt="6"/>
          <img src="images/new company/20.png" alt="20" />
          <img src="images/new company/7.png" alt="7" />
          <img src="images/new company/8.png" alt="8" />
          <img src="images/new company/21.png" alt="21"/>
          <img src="images/new company/9.png" alt="9" />
          <img src="images/new company/10.png" alt="10" />
          <img src="images/new company/11.png" alt="11" />
          <img src="images/new company/12.png" alt="12" />
          <img src="images/new company/13.png" alt="13" />
          <img src="images/new company/22.png" alt="22" />
          <img src="images/new company/14.png" alt="14" />
          <img src="images/new company/15.png" alt="15" />
          <img src="images/new company/16.png" alt="16" />
          <img src="images/new company/17.png" alt="17" />
          <img src="images/new company/18.png" alt="18"/>
          <img src="images/new company/1.png" alt="1"/>
          <img src="images/new company/2.png" alt="2"/>
          <img src="images/new company/3.png" alt="3"/>
          <img src="images/new company/19.png" alt="19"/>
          <img src="images/new company/4.png" alt="4"/>
          <img src="images/new company/5.png" alt="5"/>
          <img src="images/new company/6.png" alt="6"/>
          <img src="images/new company/20.png" alt="20"/>
          <img src="images/new company/7.png" alt="7"/>
          <img src="images/new company/8.png" alt="8"/>
          <img src="images/new company/21.png" alt="21"/>
          <img src="images/new company/9.png" alt="9"/>
          <img src="images/new company/10.png" alt="10"/>
          <img src="images/new company/11.png" alt="11"/>
          <img src="images/new company/12.png" alt="12"/>
          <img src="images/new company/13.png" alt="13"/>
          <img src="images/new company/22.png" alt="22"/>
          <img src="images/new company/14.png" alt="14"/>
          <img src="images/new company/15.png" alt="15"/>
          <img src="images/new company/16.png" alt="16"/>
          <img src="images/new company/17.png" alt="17"/>
          <img src="images/new company/18.png" alt="18"/>

        </div>
      </div>
      <hr>
      <div class="logos2 clients2">
        <div class="logos-slide2">
         <img src="images/new company/1.png" alt="1" />
          <img src="images/new company/2.png" alt="2" />
          <img src="images/new company/3.png" alt="3" />
          <img src="images/new company/19.png" alt="19" />
          <img src="images/new company/4.png" alt="4" />
          <img src="images/new company/5.png" alt="5"/>
          <img src="images/new company/6.png" alt="6"/>
          <img src="images/new company/20.png" alt="20" />
          <img src="images/new company/7.png" alt="7" />
          <img src="images/new company/8.png" alt="8" />
          <img src="images/new company/21.png" alt="21"/>
          <img src="images/new company/9.png" alt="9" />
          <img src="images/new company/10.png" alt="10" />
          <img src="images/new company/11.png" alt="11" />
          <img src="images/new company/12.png" alt="12" />
          <img src="images/new company/13.png" alt="13" />
          <img src="images/new company/22.png" alt="22" />
          <img src="images/new company/14.png" alt="14" />
          <img src="images/new company/15.png" alt="15" />
          <img src="images/new company/16.png" alt="16" />
          <img src="images/new company/17.png" alt="17" />
          <img src="images/new company/18.png" alt="18"/>
          <img src="images/new company/1.png" alt="1"/>
          <img src="images/new company/2.png" alt="2"/>
          <img src="images/new company/3.png" alt="3"/>
          <img src="images/new company/19.png" alt="19"/>
          <img src="images/new company/4.png" alt="4"/>
          <img src="images/new company/5.png" alt="5"/>
          <img src="images/new company/6.png" alt="6"/>
          <img src="images/new company/20.png" alt="20"/>
          <img src="images/new company/7.png" alt="7"/>
          <img src="images/new company/8.png" alt="8"/>
          <img src="images/new company/21.png" alt="21"/>
          <img src="images/new company/9.png" alt="9"/>
          <img src="images/new company/10.png" alt="10"/>
          <img src="images/new company/11.png" alt="11"/>
          <img src="images/new company/12.png" alt="12"/>
          <img src="images/new company/13.png" alt="13"/>
          <img src="images/new company/22.png" alt="22"/>
          <img src="images/new company/14.png" alt="14"/>
          <img src="images/new company/15.png" alt="15"/>
          <img src="images/new company/16.png" alt="16"/>
          <img src="images/new company/17.png" alt="17"/>
          <img src="images/new company/18.png" alt="18"/>

        </div>
      </div>


      <!-- /* clients section scroller */ -->
      <hr>
  </section>
  <!-- Three Steps Section -->
<section class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 mt-20 grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
    <div class="space-y-4">
      <img alt="Create profile" class="mx-auto w-24 h-24 object-contain" src="https://storage.googleapis.com/a1aa/image/7d796380-0794-493e-c0b3-4bfaaec32a19.jpg" />
      <h2 class="text-2xl font-semibold text-gray-900">
        Create Your Profile
      </h2>
      <p class="text-gray-600 leading-relaxed">
        Build a professional profile and showcase your skills.  
        Make it easy for recruiters to find you.
      </p>
    </div>
    <div class="space-y-4">
      <img alt="Search jobs" class="mx-auto w-24 h-24 object-contain" src="https://storage.googleapis.com/a1aa/image/a3adeecf-058d-4afe-2f2b-f738c8455659.jpg" />
      <h2 class="text-2xl font-semibold text-gray-900">
        Explore Opportunities
      </h2>
      <p class="text-gray-600 leading-relaxed">
        Search and filter jobs by location, skills, or industry.  
        Find the perfect match for your career.
      </p>
    </div>
    <div class="space-y-4">
      <img alt="Connect with employers" class="mx-auto w-24 h-24 object-contain" src="https://storage.googleapis.com/a1aa/image/8d4a8b9a-c97c-45a5-db81-dc1820bc152a.jpg" />
      <h2 class="text-2xl font-semibold text-gray-900">
        Connect & Apply
      </h2>
      <p class="text-gray-600 leading-relaxed">
        Apply directly and chat with hiring managers.  
        Get closer to landing your dream job.
      </p>
    </div>
  </section>
  <section class="bg-blue-50 mt-24 py-16">
    <div class="max-w-7xl mx-auto px-6 sm:px-12 lg:px-16">
      <div class="flex flex-col md:flex-row justify-between items-center mb-12">
        <div>
          <h1 class="text-4xl font-extrabold text-blue-700 mb-2">Featured Jobs</h1>
          <p class="text-blue-900 max-w-xl">Check out some of the most recent and in-demand job postings.</p>
        </div>
        <a href="/job" class="mt-6 md:mt-0 text-blue-700 font-semibold hover:underline flex items-center gap-2">View All Listings</a>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <?php foreach ($featuredJobs as $job): ?>
          <article class="bg-white rounded-lg shadow-md p-6 flex flex-col">
            <img src="<?= htmlspecialchars($job['logo']) ?>" 
                 alt="<?= htmlspecialchars($job['company_name']) ?> logo" 
                 class="w-20 h-20 object-contain mb-4" />
            <h2 class="text-xl font-semibold text-gray-900 mb-1">
                <?= htmlspecialchars($job['title']) ?>
            </h2>
            <p class="text-gray-500 text-sm mb-4">
                <?= $job['created_at_formatted'] ?>
            </p>
            <?php
                $slugTitle = slugify($job['title']);
                $slugCity = slugify($job['city'] ?? '');
                $jobId = $job['id'];
            ?>
            <a href="/jobs/<?= $slugTitle ?>-in-<?= $slugCity ?>-<?= $jobId ?>"
               class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-200 text-sm font-medium text-center"
               aria-label="View details about <?= htmlspecialchars($job['title']) ?>">
               View Details
            </a>
          </article>
        <?php endforeach; ?>
      </div>

      <!-- <p class="text-center text-xl font-semibold text-gray-700 mb-8">Top Hiring Locations</p>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 max-w-4xl mx-auto">
        <?php foreach ($topLocations as $loc):
          $city = htmlspecialchars($loc['city']);
          $state = htmlspecialchars($loc['state']);
          $image = "https://source.unsplash.com/300x200/?city," . urlencode($city);
        ?>
          <div class="relative rounded-lg overflow-hidden shadow-lg">
            <a href="/jobs.php?state=<?= urlencode($state) ?>&city[]=<?= urlencode($city) ?>">
              <img src="<?= $image ?>" alt="<?= "$city, $state" ?>" class="w-full h-48 object-cover" />
              <h3 class="absolute bottom-2 left-2 text-white font-bold text-lg bg-black bg-opacity-50 rounded px-2 py-1"><?= "$city, $state" ?></h3>
            </a>
          </div>
        <?php endforeach; ?>
      </div> -->
    </div>
  </section>

    <!-- Back to Top Button -->
  <button aria-label="Back to top" class="fixed bottom-6 right-6 z-50 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-full p-3 shadow-lg hidden hover:from-blue-700 hover:to-blue-600 transition transform hover:scale-110" id="backToTopBtn" type="button">
    <i class="fas fa-arrow-up text-lg">
    </i>
  </button>
  <!-- Simple to get started Section -->
  <section class="bg-white max-w-7xl mx-auto px-6 sm:px-12 lg:px-16 py-20">
    <h1 class="text-4xl font-extrabold text-gray-900 text-center mb-4">
      Get Started in 5 Easy Steps
    </h1>
    <p class="text-center text-gray-600 max-w-2xl mx-auto mb-12 leading-relaxed">
      Whether you’re a fresh graduate or an experienced professional,  
      JobVacancyResult makes job hunting simple and effective.
    </p>
    <div class="flex flex-col md:flex-row items-center justify-center gap-12 max-w-5xl mx-auto">
      <div class="relative w-48 h-48 flex-shrink-0">
        <img alt="Decorative orange circle" class="w-full h-full object-contain" src="https://storage.googleapis.com/a1aa/image/15e7315d-9dfa-4b34-8a27-ece9501ea412.jpg" />
      </div>
      <div class="flex-1 space-y-6">
        <div>
          <h2 class="text-xl font-bold text-orange-600 mb-1">01. Sign up for free</h2>
          <div class="h-1 w-20 bg-orange-600 rounded"></div>
        </div>
        <div>
          <h2 class="text-xl font-semibold text-gray-900 mb-1">02. Create your profile</h2>
          <div class="h-1 w-20 bg-gray-300 rounded"></div>
        </div>
        <div>
          <h2 class="text-xl font-semibold text-gray-900 mb-1">03. Browse jobs that fit you</h2>
          <div class="h-1 w-20 bg-gray-300 rounded"></div>
        </div>
        <div>
          <h2 class="text-xl font-semibold text-gray-900 mb-1">04. Apply directly to employers</h2>
          <div class="h-1 w-20 bg-gray-300 rounded"></div>
        </div>
        <div>
          <h2 class="text-xl font-semibold text-gray-900 mb-1">05. Get hired faster</h2>
          <div class="h-1 w-20 bg-gray-300 rounded"></div>
        </div>
      </div>
    </div>
  </section>
  <script>
    const menuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    menuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
  </script>
  <?php include 'include/footer.php'; ?>

    <script>
    const backToTopBtn = document.getElementById('backToTopBtn');
    // Back to top button
    window.addEventListener('scroll', () => {
      if (window.scrollY > 300) {
        backToTopBtn.classList.remove('hidden');
      } else {
        backToTopBtn.classList.add('hidden');
      }
    });

    backToTopBtn.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  </script>


</body>

</html>