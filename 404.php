<?php
$base_url = "/jobvacancyresult"; // Update this if the project is moved

// Ensure the logs directory exists
$log_dir = __DIR__ . '/logs';
if (!is_dir($log_dir)) {
    mkdir($log_dir, 0777, true); // Create the directory with write permissions
}

// Log the error
$error_message = "404 Error: Page not found - " . $_SERVER['REQUEST_URI'];
error_log($error_message, 3, $log_dir . '/error.log'); // Log the error
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="robots" content="noindex, nofollow" />
  <title>404 - Page Not Found</title>
    <link rel="icon" href="/jvr-logo.jpg" width="32">

  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
  <link href="<?php echo $base_url; ?>/assets/css/styles.css" rel="stylesheet" />
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "404 - Page Not Found",
    "description": "The page you are looking for does not exist or has been moved.",
    "potentialAction": {
      "@type": "SearchAction",
      "target": "<?php echo $base_url; ?>/search?q={search_term_string}",
      "query-input": "required name=search_term_string"
    }
  }
  </script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

  <div class="flex flex-col items-center justify-center min-h-screen text-center px-4 fade-in">
    <a href="https://jobvacancyresult.com/">
      <img src="/jvr-logo.jpg" alt="Job Vacancy Result Website Logo" class="w-32 mb-6 animate-pulse" />
    </a>

    <div class="text-6xl font-bold text-blue-600 mb-2"><i class="fas fa-exclamation-triangle"></i> 404</div>
    <h1 class="text-2xl font-semibold mb-2">Page Not Found</h1>
    <p class="text-gray-600 max-w-md mb-6">
      Sorry, the page you're looking for doesn't exist or has been moved.<br>
      The site administrator has been notified.
    </p>

    <div class="flex flex-col sm:flex-row gap-4">
      <a href="https://jobvacancyresult.com/" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
        <i class="fas fa-home mr-2"></i> Home Page
      </a>
      <a href="javascript:history.length > 1 ? history.go(-1) : window.location.href='<?php echo $base_url; ?>/index';" 
         class="inline-flex items-center px-6 py-3 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
        <i class="fas fa-arrow-left mr-2"></i> Go Back
      </a>
    </div>
  </div>

</body>
</html>