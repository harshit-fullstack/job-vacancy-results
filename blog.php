<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <?php
  $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
  $canonicalUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
  if (!empty($_SERVER['QUERY_STRING'])) {
      $canonicalUrl .= '?' . htmlspecialchars($_SERVER['QUERY_STRING']);
  }
  $seoTitle = 'JVR Blogs | JobVacancyResult';
  $desc = 'Read updates on JVR\'s products, corporate initiatives, and partnerships. Get insight into the world\'s work marketplace.';
  ?>
  <title><?= $seoTitle ?></title>
    <link rel="icon" href="/jobvacancyresult/jvr-logo.jpg" width="32">

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
    
    <!-- Schema Markup -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Blog",
      "name": "JVR Blogs | JobVacancyResult",
      "description": "Read updates on JVR's products, corporate initiatives, and partnerships. Get insight into the world's work marketplace.",
      "url": "<?= $canonicalUrl ?>",
      "publisher": {
        "@type": "Organization",
        "name": "JobVacancyResult",
        "logo": {
          "@type": "ImageObject",
          "url": "<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jvr-logo.jpg"
        }
      }
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
          "name": "Blog",
          "item": "<?= $canonicalUrl ?>"
        }
      ]
    }
    </script>
    
    <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Varela Round', sans-serif;
    }
  </style>
</head>

<body class="bg-white text-gray-900">

  <?php include 'include/header.php'; ?>


  <div class="max-w-7xl mx-auto px-4 py-10">
    <!-- Header -->
    <header class="text-center max-w-3xl mx-auto mb-10">
      <h1 class="text-4xl sm:text-5xl font-semibold mb-4">JVR Blogs</h1>
      <p class="text-gray-700 text-base sm:text-lg leading-relaxed">
        Read updates on JVR's products, corporate initiatives, and partnerships to get insight<br />
        into the world's work marketplace.
      </p>
    </header>

    <!-- Filter Buttons -->
    <nav class="flex flex-wrap justify-center gap-3 mb-10 border-b border-gray-300 pb-3">
      <button data-filter="all" class="filter-btn bg-blue-600 text-white px-4 py-2 rounded-full shadow hover:bg-blue-700">All</button>
      <button data-filter="Company News" class="filter-btn text-gray-700 hover:text-blue-600 px-4 py-2 rounded-full">Company News</button>
      <button data-filter="Product & Innovation" class="filter-btn text-gray-700 hover:text-blue-600 px-4 py-2 rounded-full">Product & Innovation</button>
      <button data-filter="People & Culture" class="filter-btn text-gray-700 hover:text-blue-600 px-4 py-2 rounded-full">People & Culture</button>
      <button data-filter="Research & Reports" class="filter-btn text-gray-700 hover:text-blue-600 px-4 py-2 rounded-full">Research & Reports</button>
    </nav>

    <!-- Blog Cards -->
    <section id="blog-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

      <article class="blog-card" data-category="Research & Reports">
        <a href="/jobvacancyresult/blog-pages/research-pages/skilled"><img class="w-full h-48 object-cover" src="https://storage.googleapis.com/a1aa/image/a38cb12f-fdc3-4cbe-bbc7-7584f1f7e682.jpg" alt="Workforce Transformation">
        <div class="p-5">
          <p class="text-blue-600 font-semibold text-sm mb-2">Research & Reports</p>
          <h2 class="text-lg font-semibold mb-3">Skilled, Adaptive, and AI-Enabled Workforce</h2>
          <time class="text-gray-500 text-xs">April 23, 2025</time>
        </div></a>
      </article>

      <article class="blog-card" data-category="Company News">
        <img class="w-full h-48 object-cover" src="https://storage.googleapis.com/a1aa/image/0cd9c093-404d-4c11-9ad0-6b5264307128.jpg" alt="Trust and Safety">
        <div class="p-5">
          <p class="text-blue-600 font-semibold text-sm mb-2">Company News</p>
          <h2 class="text-lg font-semibold mb-3">Building a Trusted Work Marketplace</h2>
          <time class="text-gray-500 text-xs">May 22, 2025</time>
        </div>
      </article>

      <article class="blog-card" data-category="Tech at Work">
        <img class="w-full h-48 object-cover" src="https://storage.googleapis.com/a1aa/image/3e509fa4-de5c-41c8-ae64-77b1f04e5998.jpg" alt="AI & Technical Blog">
        <div class="p-5">
          <p class="text-blue-600 font-semibold text-sm mb-2">Tech at Work</p>
          <h2 class="text-lg font-semibold mb-3">Welcome to JVR's AI & Technical Blog</h2>
          <time class="text-gray-500 text-xs">May 22, 2025</time>
        </div>
      </article>

      <article class="blog-card" data-category="People & Culture">
        <img class="w-full h-48 object-cover" src="https://storage.googleapis.com/a1aa/image/1d02fb31-9809-4433-b97f-02c59f258956.jpg" alt="Culture">
        <div class="p-5">
          <p class="text-blue-600 font-semibold text-sm mb-2">People & Culture</p>
          <h2 class="text-lg font-semibold mb-3">JVR’s Global Impact in 2025</h2>
          <time class="text-gray-500 text-xs">May 22, 2025</time>
        </div>
      </article>

      <article class="blog-card" data-category="Product & Innovation">
        <img class="w-full h-48 object-cover" src="https://storage.googleapis.com/a1aa/image/bb7a695b-1e0a-498b-fa81-c4c7608ea784.jpg" alt="Innovation">
        <div class="p-5">
          <p class="text-blue-600 font-semibold text-sm mb-2">Product & Innovation</p>
          <h2 class="text-lg font-semibold mb-3">Upwork Helps Small Businesses Grow</h2>
          <time class="text-gray-500 text-xs">May 22, 2025</time>
        </div>
      </article>

      <article class="blog-card" data-category="Product & Innovation">
        <img class="w-full h-48 object-cover" src="https://storage.googleapis.com/a1aa/image/a92e905b-9b11-4550-f851-353d01fdc3ae.jpg" alt="Safe AI">
        <div class="p-5">
          <p class="text-blue-600 font-semibold text-sm mb-2">Product & Innovation</p>
          <h2 class="text-lg font-semibold mb-3">Building Safe and Transparent AI</h2>
          <time class="text-gray-500 text-xs">May 22, 2025</time>
        </div>
      </article>

    </section>

    <!-- Latest News Section -->
    <section class="text-center max-w-3xl mx-auto mb-8 px-2">
      <h2 class="text-3xl font-semibold mb-3">Latest News About JVR</h2>
      <p class="text-gray-700 text-base sm:text-lg leading-relaxed">
        Supporting you with data, trends, and insights you need to succeed today and prepare for tomorrow.
      </p>
    </section>
    <hr class="border-gray-300 mb-8" />

    <!-- Tabs -->
    <div id="news-tabs" class="flex justify-center gap-12 text-sm sm:text-base font-semibold text-gray-700 mb-6" role="tablist">
      <button class="tab-btn border-b-2 border-blue-600 pb-1 text-blue-600" data-tab="press" role="tab" aria-selected="true">Press releases</button>
      <button class="tab-btn pb-1 hover:text-blue-600 transition" data-tab="research" role="tab" aria-selected="false">Research</button>
      <button class="tab-btn pb-1 hover:text-blue-600 transition" data-tab="news" role="tab" aria-selected="false">JVR in the news</button>
    </div>
    <hr class="border-gray-300 mb-10" />

    <!-- Tab Contents -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
      <!-- Press Tab -->
      <div class="tab-content" data-content="press" role="tabpanel">
        <article class="bg-white rounded-lg shadow-md p-5 flex flex-col justify-between" tabindex="0">
          <p class="text-blue-600 font-semibold mb-3">Press</p>
          <p class="text-gray-800 leading-relaxed mb-5 text-sm sm:text-base">JVR Appoints New CTO to Lead Product Innovation</p>
          <a class="text-blue-600 font-semibold hover:underline text-sm sm:text-base" href="#">Read News →</a>
        </article>
        <!-- duplicate if needed -->
      </div>

      <!-- Research Tab -->
      <div class="tab-content hidden" data-content="research" role="tabpanel">
        <article class="bg-white rounded-lg shadow-md p-5 flex flex-col justify-between" tabindex="0">
          <p class="text-blue-600 font-semibold mb-3">Research</p>
          <p class="text-gray-800 leading-relaxed mb-5 text-sm sm:text-base">JVR's Workforce 2030 Report Reveals New Trends in Employment</p>
          <a class="text-blue-600 font-semibold hover:underline text-sm sm:text-base" href="#">Read Research →</a>
        </article>
      </div>

      <!-- News Tab -->
      <div class="tab-content hidden" data-content="news" role="tabpanel">
        <article class="bg-white rounded-lg shadow-md p-5 flex flex-col justify-between" tabindex="0">
          <p class="text-blue-600 font-semibold mb-3">In the News</p>
          <p class="text-gray-800 leading-relaxed mb-5 text-sm sm:text-base">JVR Featured in Forbes as a Top Remote-First Company</p>
          <a class="text-blue-600 font-semibold hover:underline text-sm sm:text-base" href="#">Read Article →</a>
        </article>
      </div>
    </section>

    <!-- Back to Top Button -->
  <button aria-label="Back to top" class="fixed bottom-6 right-6 z-50 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-full p-3 shadow-lg hidden hover:from-blue-700 hover:to-blue-600 transition transform hover:scale-110" id="backToTopBtn" type="button">
    <i class="fas fa-arrow-up text-lg">
    </i>
  </button>
    <!-- Join the world’s work marketplace -->
    <section class="text-center mb-10 px-2">
      <h2 class="text-3xl sm:text-4xl font-semibold">
        Join the world’s work marketplace
      </h2>
    </section>
    <hr class="border-gray-300 mb-10" />
    <!-- Two Big Cards side by side -->
    <section aria-label="Join the marketplace options" class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto px-2">
      <!-- Big Card 1 -->
      <article aria-label="Find talent your way and get things done" class="relative rounded-lg overflow-hidden shadow-lg cursor-pointer group" tabindex="0">
        <img alt="A diverse group of professionals collaborating in an office environment representing finding talent your way" class="w-full h-64 sm:h-80 object-cover" height="400" loading="lazy" src="https://storage.googleapis.com/a1aa/image/35a6d98d-3174-4154-ac51-c83dd0783f4b.jpg" width="800" />
        <div class="absolute inset-0 bg-blue-900 bg-opacity-60 flex flex-col justify-center items-center p-6 text-center text-white transition-opacity duration-300 group-hover:bg-opacity-70">
          <p class="text-xl sm:text-2xl font-semibold mb-4">
            Find talent your way and get things done.
          </p>
          <button class="bg-white text-blue-900 font-semibold px-6 py-2 rounded-full shadow hover:bg-gray-100 transition">
            Find Talent
          </button>
        </div>
      </article>
      <!-- Big Card 2 -->
      <article aria-label="Find work you love with like-minded clients" class="relative rounded-lg overflow-hidden shadow-lg cursor-pointer group" tabindex="0">
        <img alt="A happy freelancer working on a laptop in a cozy environment representing finding work you love with like-minded clients" class="w-full h-64 sm:h-80 object-cover" height="400" loading="lazy" src="https://storage.googleapis.com/a1aa/image/aa90bea0-0f9c-403c-f0d9-4e43dc515d1a.jpg" width="800" />
        <div class="absolute inset-0 bg-blue-900 bg-opacity-60 flex flex-col justify-center items-center p-6 text-center text-white transition-opacity duration-300 group-hover:bg-opacity-70">
          <p class="text-xl sm:text-2xl font-semibold mb-4">
            Find work you love with like-minded clients.
          </p>
          <button class="bg-white text-blue-900 font-semibold px-6 py-2 rounded-full shadow hover:bg-gray-100 transition">
            Find Work
          </button>
        </div>
      </article>
    </section>
  </div>
  </div>
  

  <!-- footer  -->
  <?php include 'include/footer.php'; ?>

  <script src="js/main.js"></script>
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

  <!-- Filter Script -->
  <script>
    const filterButtons = document.querySelectorAll('.filter-btn');
    const blogCards = document.querySelectorAll('.blog-card');

    filterButtons.forEach(button => {
      button.addEventListener('click', () => {
        const category = button.getAttribute('data-filter');

        // Highlight selected
        filterButtons.forEach(btn => btn.classList.remove('bg-blue-600', 'text-white'));
        button.classList.add('bg-blue-600', 'text-white');

        blogCards.forEach(card => {
          const cardCat = card.getAttribute('data-category');
          if (category === 'all' || cardCat === category) {
            card.classList.remove('hidden');
          } else {
            card.classList.add('hidden');
          }
        });
      });
    });
  </script>

  <script>
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const tab = btn.getAttribute('data-tab');

      // Remove active styles and aria-selected from all buttons
      document.querySelectorAll('.tab-btn').forEach((b, i) => {
        b.classList.remove('border-blue-600', 'text-blue-600');
        b.classList.add('text-gray-700');
        b.setAttribute('aria-selected', 'false');
      });

      // Add active styles and aria-selected to clicked button
      btn.classList.add('border-blue-600', 'text-blue-600');
      btn.classList.remove('text-gray-700');
      btn.setAttribute('aria-selected', 'true');

      // Hide all tab contents
      document.querySelectorAll('.tab-content').forEach(tc => {
        tc.classList.add('hidden');
      });

      // Show selected tab content
      document.querySelector(`.tab-content[data-content="${tab}"]`).classList.remove('hidden');
    });
  });
</script>


</body>

</html>