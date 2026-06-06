<?php
require_once 'include/db.php';

// Helper to create slugs
function slugify($string)
{
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    return trim($string, '-');
}





// Sample filteredJobs array (replace with your actual job-fetching logic)
$filteredJobs = $filteredJobs ?? []; // ensure it's set

// === Pagination Setup ===
$limit = 6; // Jobs per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$totalJobs = count($filteredJobs);
$totalPages = ceil($totalJobs / $limit);
$offset = ($page - 1) * $limit;
$currentJobs = array_slice($filteredJobs, $offset, $limit);

// Function to preserve other GET parameters
function buildPageUrl($pageNum)
{
    $query = $_GET;
    $query['page'] = $pageNum;
    return '?' . http_build_query($query);
}

// Fetch jobs with company info and logo and salary
$jobs = [];
$search = $_GET['search'] ?? '';
$location = $_GET['location'] ?? '';
$jobtype = $_GET['jobtype'] ?? [];
$worktype = $_GET['worktype'] ?? [];
$experiencelevel = $_GET['experiencelevel'] ?? [];
$min_experience = $_GET['min_experience'] ?? '';
$max_experience = $_GET['max_experience'] ?? '';
$min_salary = $_GET['min_salary'] ?? '';
$max_salary = $_GET['max_salary'] ?? '';
$industry = $_GET['industry'] ?? [];
$locationfilter = $_GET['locationfilter'] ?? [];
$sort = $_GET['sort'] ?? '';

// Normalize inputs to arrays if single string
if (!is_array($jobtype)) $jobtype = $jobtype ? [$jobtype] : [];
if (!is_array($worktype)) $worktype = $worktype ? [$worktype] : [];
if (!is_array($experiencelevel)) $experiencelevel = $experiencelevel ? [$experiencelevel] : [];
if (!is_array($industry)) $industry = $industry ? [$industry] : [];
if (!is_array($locationfilter)) $locationfilter = $locationfilter ? [$locationfilter] : [];

$whereClauses = [];
$params = [];

if ($search !== '') {
    $whereClauses[] = "(j.title LIKE CONCAT('%', ?, '%') OR c.name LIKE CONCAT('%', ?, '%'))";
    $params[] = $search;
    $params[] = $search;
}
if ($location !== '') {
    $whereClauses[] = "(j.state LIKE CONCAT('%', ?, '%') OR j.city LIKE CONCAT('%', ?, '%'))";
    $params[] = $location;
    $params[] = $location;
}
if (!empty($jobtype)) {
    $placeholders = implode(',', array_fill(0, count($jobtype), '?'));
    $whereClauses[] = "j.job_type IN ($placeholders)";
    foreach ($jobtype as $jt) {
        $params[] = $jt;
    }
}
if (!empty($worktype)) {
    $placeholders = implode(',', array_fill(0, count($worktype), '?'));
    $whereClauses[] = "j.work_mode IN ($placeholders)";
    foreach ($worktype as $wt) {
        $params[] = $wt;
    }
}
if (!empty($experiencelevel)) {
    $experienceConditions = [];
    foreach ($experiencelevel as $el) {
        $experienceConditions[] = "j.experience LIKE CONCAT('%', ?, '%')";
        $params[] = $el;
    }
    $whereClauses[] = '(' . implode(' OR ', $experienceConditions) . ')';
}
if ($min_experience !== '') {
    $whereClauses[] = "CAST(SUBSTRING_INDEX(j.experience, ' ', 1) AS UNSIGNED) >= ?";
    $params[] = (int)$min_experience;
}
if ($max_experience !== '') {
    $whereClauses[] = "CAST(SUBSTRING_INDEX(j.experience, ' ', 1) AS UNSIGNED) <= ?";
    $params[] = (int)$max_experience;
}
// Salary filtering removed from SQL, handled in PHP

if (!empty($industry)) {
    $placeholders = implode(',', array_fill(0, count($industry), '?'));
    $whereClauses[] = "j.industry_id IN ($placeholders)";
    foreach ($industry as $ind) {
        $params[] = $ind;
    }
}
if (!empty($locationfilter)) {
    $placeholders = implode(',', array_fill(0, count($locationfilter), '?'));
    $locationConditions = [];
    $locationConditions[] = "CONCAT(j.state, ', ', j.city) IN ($placeholders)";
    if (in_array('Remote', $locationfilter)) {
        $locationConditions[] = "j.city = 'Remote'";
    }
    $whereClauses[] = '(' . implode(' OR ', $locationConditions) . ')';
    foreach ($locationfilter as $loc) {
        $params[] = $loc;
    }
}
//    state-city-filter
$state = $_GET['state'] ?? '';
$city = $_GET['city'] ?? [];
if (!is_array($city)) $city = $city ? [$city] : [];

if ($state !== '') {
    $whereClauses[] = "j.state = ?";
    $params[] = $state;
}
if (!empty($city)) {
    $placeholders = implode(',', array_fill(0, count($city), '?'));
    $whereClauses[] = "j.city IN ($placeholders)";
    foreach ($city as $ct) {
        $params[] = $ct;
    }
}



$whereSql = '';
if (count($whereClauses) > 0) {
    $whereSql = 'WHERE ' . implode(' AND ', $whereClauses);
}

$orderSql = 'ORDER BY j.created_at DESC'; // default
if ($sort === 'oldest') {
    $orderSql = 'ORDER BY j.created_at ASC';
} elseif ($sort === 'salary-high') {
    $orderSql = 'ORDER BY j.salary DESC';
} elseif ($sort === 'salary-low') {
    $orderSql = 'ORDER BY j.salary ASC';
} elseif ($sort === 'relevance') {
    $orderSql = 'ORDER BY j.created_at DESC'; // fallback
}

$sql = "
        SELECT 
            j.id,
            j.title,
            j.description,
            j.job_type,
            j.work_mode,
            j.experience,
            j.created_at,
            j.salary,
            j.industry_id,
            j.state,
            j.city,
            c.name AS company,
            c.logo
        FROM jobs j
        INNER JOIN companies c ON j.company_id = c.id
        $whereSql
        $orderSql
    ";

$stmt = mysqli_prepare($conn, $sql);
if ($stmt === false) {
    // error_log('SQL Prepare Error: ' . mysqli_error($conn));
    die('An unexpected error occurred. Please try again later.');
}

if (count($params) > 0) {
    $types = str_repeat('s', count($params));
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($result === false) {
    // error_log('SQL Error: ' . mysqli_error($conn));
    die('An unexpected error occurred. Please try again later.');
}

while ($row = mysqli_fetch_assoc($result)) {
    $row['logo'] = !empty($row['logo']) ? '' . $row['logo'] : 'https://placehold.co/48x48/png?text=Logo';
    $row['logoAlt'] = $row['company'] . ' logo';
    $row['bgColor'] = '#fff';
    $row['tags'] = [];
    if ($row['job_type']) $row['tags'][] = $row['job_type'];
    if ($row['work_mode']) $row['tags'][] = $row['work_mode'];
    if ($row['experience']) $row['tags'][] = $row['experience'] . ' Exp';
    if ($row['industry_id'] && isset($industryCounts[$row['industry_id']])) $row['tags'][] = $industryCounts[$row['industry_id']];
    $jobs[] = $row;
}

// Filter jobs by min_salary in PHP using improved parsing
if ($min_salary !== '') {
    $filteredJobs = [];
    foreach ($jobs as $job) {
        $salaryStr = $job['salary'] ?? '';
        $salaryMin = 0;
        $salaryMax = 0;
        if (preg_match('/(\d[\d,]*)\D*-\D*(\d[\d,]*)/', $salaryStr, $salaryMatches)) {
            $salaryMin = (int)str_replace(',', '', $salaryMatches[1]);
            $salaryMax = (int)str_replace(',', '', $salaryMatches[2]);
        } else {
            if (preg_match('/(\d[\d,]*)/', $salaryStr, $singleMatch)) {
                $salaryMin = (int)str_replace(',', '', $singleMatch[1]);
            }
        }
        if ($salaryMin >= (int)$min_salary) {
            $filteredJobs[] = $job;
        }
    }
} else {
    $filteredJobs = $jobs;
}

// Re-apply sort to PHP filtered result (if needed)
if (in_array($sort, ['salary-high', 'salary-low'])) {
    usort($filteredJobs, function ($a, $b) use ($sort) {
        $parseSalary = function ($str) {
            if (preg_match('/(\d[\d,]*)\D*-\D*(\d[\d,]*)/', $str, $m)) {
                return (int)str_replace(',', '', $m[2]);
            } elseif (preg_match('/(\d[\d,]*)/', $str, $m)) {
                return (int)str_replace(',', '', $m[1]);
            }
            return 0;
        };
        $aSalary = $parseSalary($a['salary']);
        $bSalary = $parseSalary($b['salary']);
        return $sort === 'salary-high' ? $bSalary <=> $aSalary : $aSalary <=> $bSalary;
    });
}

$jobTypeCounts = [];
$jobTypesResult = mysqli_query($conn, "SELECT DISTINCT job_type FROM jobs WHERE job_type IS NOT NULL AND job_type != ''");
while ($row = mysqli_fetch_assoc($jobTypesResult)) {
    $jobTypeCounts[$row['job_type']] = 0;
}
foreach ($jobs as $job) {
    if (isset($jobTypeCounts[$job['job_type']])) {
        $jobTypeCounts[$job['job_type']]++;
    }
}

$workTypeCounts = [];
$workTypesResult = mysqli_query($conn, "SELECT DISTINCT work_mode FROM jobs WHERE work_mode IS NOT NULL AND work_mode != ''");
while ($row = mysqli_fetch_assoc($workTypesResult)) {
    $workTypeCounts[$row['work_mode']] = 0;
}
foreach ($jobs as $job) {
    if (isset($workTypeCounts[$job['work_mode']])) {
        $workTypeCounts[$job['work_mode']]++;
    }
}

$experienceLevelCounts = [];
$experienceLevelsResult = mysqli_query($conn, "SELECT DISTINCT experience FROM jobs WHERE experience IS NOT NULL AND experience != ''");
while ($row = mysqli_fetch_assoc($experienceLevelsResult)) {
    $experienceLevelCounts[$row['experience']] = 0;
}
foreach ($jobs as $job) {
    if (isset($job['experience']) && $job['experience'] !== null) {
        if (isset($experienceLevelCounts[$job['experience']])) {
            $experienceLevelCounts[$job['experience']]++;
        } else {
            $experienceLevelCounts[$job['experience']] = 1;
        }
    }
}

$industryCounts = [];
$industriesResult = mysqli_query($conn, "SELECT i.id, i.name FROM industries i ORDER BY i.name ASC");
while ($row = mysqli_fetch_assoc($industriesResult)) {
    $industryCounts[$row['id']] = $row['name'];
}
foreach ($jobs as $job) {
    if (isset($industryCounts[$job['industry_id']])) {
        if (!isset($industryCounts[$job['industry_id'] . '_count'])) {
            $industryCounts[$job['industry_id'] . '_count'] = 0;
        }
        $industryCounts[$job['industry_id'] . '_count']++;
    }
}

$totalJobs = count($jobs);
$count = count($filteredJobs);

function timeAgo($datetime)
{
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) {
        return $diff . ' seconds ago';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' days ago';
    } elseif ($diff < 2592000) {
        return floor($diff / 604800) . ' weeks ago';
    } elseif ($diff < 31536000) {
        return floor($diff / 2592000) . ' months ago';
    } else {
        return floor($diff / 31536000) . ' years ago';
    }
}
?>


<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <?php
    // Canonical URL logic (cleaned)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $base = $protocol . '://' . $host . strtok($_SERVER['REQUEST_URI'], '?');

    // Filter out empty query parameters
    $filteredParams = array_filter($_GET, function ($value) {
        return !empty($value);
    });
    $query = http_build_query($filteredParams);
    $canonicalUrl = $base . ($query ? '?' . htmlspecialchars($query) : '');

    // SEO title/description logic
    $parts = [];

    if (!empty($_GET['state'])) {
        $parts[] = 'Jobs in ' . htmlspecialchars(ucwords(strtolower($_GET['state'])));
    }

    if (!empty($_GET['city'])) {
        $city = is_array($_GET['city']) ? implode(', ', $_GET['city']) : $_GET['city'];
        $parts[] = 'City: ' . htmlspecialchars(ucwords(strtolower($city)));
    }

    if (!empty($_GET['industry'])) {
        $inds = is_array($_GET['industry']) ? $_GET['industry'] : [$_GET['industry']];
        $industryNames = array_map(function ($ind) use ($industryCounts) {
            return $industryCounts[$ind] ?? $ind;
        }, $inds);
        $parts[] = 'Industry: ' . htmlspecialchars(implode(', ', array_map('ucwords', $industryNames)));
    }

    if (!empty($_GET['jobtype'])) {
        $types = is_array($_GET['jobtype']) ? $_GET['jobtype'] : [$_GET['jobtype']];
        $parts[] = 'Type: ' . htmlspecialchars(implode(', ', array_map('ucwords', $types)));
    }

    $seoTitle = ($parts ? implode(' | ', $parts) . ' - ' : '') . 'JobVacancyResult';

    $desc = 'Browse the latest job openings';
    if (!empty($_GET['state'])) {
        $desc .= ' in ' . htmlspecialchars(ucwords(strtolower($_GET['state'])));
    }
    if (!empty($_GET['city'])) {
        $desc .= ', ' . htmlspecialchars(ucwords(strtolower($city)));
    }
    if (!empty($_GET['industry'])) {
        $desc .= ' for industry ' . htmlspecialchars(implode(', ', array_map('ucwords', $industryNames)));
    }
    if (!empty($_GET['jobtype'])) {
        $desc .= ' of type ' . htmlspecialchars(implode(', ', array_map('ucwords', $types)));
    }
    $desc .= '. Find your next opportunity on JobVacancyResult.';
    ?>

    <title><?= htmlspecialchars($seoTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($desc) ?>" />
    <meta name="robots" content="index, follow" />
    <link rel="icon" href="/jvr-logo.jpg" width="32">

    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>" />

    <!-- Open Graph Meta -->
    <meta property="og:title" content="<?= htmlspecialchars($seoTitle) ?>" />
    <meta property="og:description" content="<?= htmlspecialchars($desc) ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>" />
    <meta property="og:site_name" content="JobVacancyResult" />
    <meta property="og:image" content="<?= $protocol . '://' . $host ?>/jvr-logo.jpg" />

    <!-- Twitter Card Meta -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= htmlspecialchars($seoTitle) ?>" />
    <meta name="twitter:description" content="<?= htmlspecialchars($desc) ?>" />
    <meta name="twitter:image" content="<?= $protocol . '://' . $host ?>/jvr-logo.jpg" />

    <!-- Tailwind & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <!-- Custom Tailwind Animations -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'scale-in': 'scaleIn 0.3s ease-out',
                        'bounce-slow': 'bounce 2s infinite',
                        'pulse-slow': 'pulse 3s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        slideUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(30px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        scaleIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'scale(0.9)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'scale(1)'
                            }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-white    transition-colors duration-300">
    <!-- Header -->
    <?php include 'include/header.php'; ?>

    <?php
    // Add JobPosting schema for the first job in the results (if available)
    if (!empty($filteredJobs)) {
        $firstJob = $filteredJobs[0];
        $jobPosting = [
            "@context" => "https://schema.org",
            "@type" => "JobPosting",
            "title" => $firstJob['title'],
            "description" => strip_tags($firstJob['description']),
            "datePosted" => isset($firstJob['created_at']) ? date('c', strtotime($firstJob['created_at'])) : date('c'),
            "hiringOrganization" => [
                "@type" => "Organization",
                "name" => $firstJob['company'],
                "logo" => $firstJob['logo'] ?? ''
            ],
            "jobLocation" => [
                "@type" => "Place",
                "address" => [
                    "@type" => "PostalAddress",
                    "addressLocality" => $firstJob['city'] ?? '',
                    "addressRegion" => $firstJob['state'] ?? '',
                    "addressCountry" => "IN"
                ]
            ],
            "employmentType" => $firstJob['job_type'] ?? '',
            "industry" => $firstJob['industry_id'] ?? '',
            "validThrough" => date('c', strtotime('+30 days', strtotime($firstJob['created_at'] ?? 'now')))
        ];
        echo '<script type="application/ld+json">' . json_encode($jobPosting, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
    // Add BreadcrumbList schema
    $breadcrumb = [
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => [
            [
                "@type" => "ListItem",
                "position" => 1,
                "name" => "Home",
                "item" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . '://' . $_SERVER['HTTP_HOST'] . '/index.php'
            ],
            [
                "@type" => "ListItem",
                "position" => 2,
                "name" => "Jobs",
                "item" => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . '://' . $_SERVER['HTTP_HOST'] . '/job.php'
            ]
        ]
    ];
    echo '<script type="application/ld+json">' . json_encode($breadcrumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    ?>



    <!-- Page Header -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Jobs </h1>
                <p class="text-xl opacity-90 mb-8">Discover amazing career opportunities from top companies</p>

                <!-- Search Bar -->
                <div class="max-w-4xl mx-auto">
                    <form method="get" onsubmit="handleSearch(event)"
                        class="flex flex-col sm:flex-row gap-4 bg-white/10 backdrop-blur-md rounded-2xl p-4">
                        <div class="flex-1 relative">
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white/70 w-5 h-5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input id="search-input" name="search" type="text"
                                class="w-full pl-12 pr-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-white/70 focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all"
                                placeholder="Job title, keywords, or company" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </div>
                        <div class="relative">
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white/70 w-5 h-5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            <input id="location-input" name="location" type="text"
                                class="w-full sm:w-48 pl-12 pr-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-white/70 focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all"
                                placeholder="Location" value="<?= htmlspecialchars($_GET['location'] ?? '') ?>">
                        </div>
                        <button type="submit"
                            class="bg-white text-blue-600 font-semibold px-8 py-3 rounded-xl hover:bg-gray-100 transition-colors">
                            Search Jobs
                        </button>
                    </form>
                </div>
            </div>
    </section>

    <!-- Main Content -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Filter Sidebar -->
                <!-- Filter Sidebar -->
                <aside class="lg:w-80 flex-shrink-0">
                    <!-- Mobile Toggle -->
                    <div class="lg:hidden mb-4">
                        <button onclick="document.getElementById('mobile-filters').classList.toggle('hidden')"
                            class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold">
                            Toggle Filters
                        </button>
                    </div>

                    <!-- Filter Container -->
                    <div id="mobile-filters"
                        class="bg-white rounded-2xl shadow-lg p-6 lg:sticky lg:top-24 lg:max-h-[calc(100vh-6rem)] lg:overflow-y-auto hidden lg:block">

                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold">Filters</h2>
                            <button onclick="clearFilters()" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                Clear All
                            </button>
                        </div>

                        <!-- Your Existing Form -->
                        <form method="get" id="filter-form">
                            <div class="mb-6">
                                <h3 class="font-semibold mb-3">Job Type</h3>
                                <div class="space-y-2"> <?php foreach ($jobTypeCounts as $type => $countType): ?> <label class="flex items-center cursor-pointer"> <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" name="jobtype" value="<?= htmlspecialchars($type) ?>" <?= (isset($_GET['jobtype']) && in_array($type, (array)$_GET['jobtype'])) ? 'checked' : '' ?>> <span class="ml-2 text-gray-700"><?= htmlspecialchars($type) ?></span> </label> <?php endforeach; ?> </div>
                            </div> <!-- Work Type Filter -->
                            <div class="mb-6">
                                <h3 class="font-semibold mb-3">Work Type</h3>
                                <div class="space-y-2"> <?php foreach ($workTypeCounts as $type => $countType): ?> <label class="flex items-center cursor-pointer"> <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" name="worktype" value="<?= htmlspecialchars($type) ?>" <?= (isset($_GET['worktype']) && in_array($type, (array)$_GET['worktype'])) ? 'checked' : '' ?>> <span class="ml-2 text-gray-700"><?= htmlspecialchars($type) ?></span> </label> <?php endforeach; ?> </div>
                            </div> <!-- Location Filter: State & City Dropdowns -->
                            <div class="mb-6">
                                <h3 class="font-semibold mb-3">Location</h3> <!-- State Select -->
                                <div class="mb-3"> <label for="state" class="block mb-1 font-medium">State</label> <select name="state" id="state" class="w-full border rounded px-2 py-1" onchange="fetchCities(this.value)">
                                        <option value="">-- Select State --</option> <?php $selectedState = $_GET['state'] ?? '';
                                                                                        $statesResult = mysqli_query($conn, "SELECT DISTINCT state FROM jobs WHERE state IS NOT NULL AND state != '' ORDER BY state ASC");
                                                                                        while ($row = mysqli_fetch_assoc($statesResult)) {
                                                                                            $sel = ($row['state'] === $selectedState) ? 'selected' : '';
                                                                                            echo "<option value=\"" . htmlspecialchars($row['state']) . "\" $sel>" . htmlspecialchars($row['state']) . "</option>";
                                                                                        } ?>
                                    </select> </div> <!-- City Select -->
                                <div class="mb-3" id="city-wrapper" style="<?= !empty($selectedState) ? '' : 'display:none;' ?>"> <label for="city" class="block mb-1 font-medium">City</label> <select name="city" id="city" class="w-full border rounded px-2 py-1" onchange="document.getElementById('filter-form').submit();">
                                        <option value="">-- Select City --</option> <?php $selectedCity = $_GET['city'] ?? '';
                                                                                    if ($selectedState) {
                                                                                        $stateEsc = mysqli_real_escape_string($conn, $selectedState);
                                                                                        $citiesResult = mysqli_query($conn, " SELECT DISTINCT city FROM jobs WHERE state = '$stateEsc' AND city IS NOT NULL AND city != '' ORDER BY city ASC ");
                                                                                        while ($row = mysqli_fetch_assoc($citiesResult)) {
                                                                                            $sel = ($row['city'] === $selectedCity) ? 'selected' : '';
                                                                                            echo "<option value=\"" . htmlspecialchars($row['city']) . "\" $sel>" . htmlspecialchars($row['city']) . "</option>";
                                                                                        }
                                                                                    } ?>
                                    </select> </div>
                            </div>
                            <script>
                                function fetchCities(state) {
                                    const cityWrapper = document.getElementById('city-wrapper');
                                    const citySelect = document.getElementById('city');
                                    const currentCity = new URLSearchParams(window.location.search).get('city');
                                    if (!state) {
                                        cityWrapper.style.display = 'none';
                                        return;
                                    }
                                    fetch('get_cities.php?state=' + encodeURIComponent(state)).then(response => response.json()).then(data => {
                                        citySelect.innerHTML = '<option value="">-- Select City --</option>';
                                        data.forEach(city => {
                                            const opt = document.createElement('option');
                                            opt.value = city;
                                            opt.textContent = city;
                                            if (city === currentCity) opt.selected = true;
                                            citySelect.appendChild(opt);
                                        });
                                        cityWrapper.style.display = 'block';
                                    });
                                }
                            </script> <!-- Experience Level Filter -->
                            <div class="mb-6">
                                <h3 class="font-semibold mb-3">Experience Level</h3> <!-- <div class="space-y-2"> <?php foreach ($experienceLevelCounts as $exp => $countExp): ?> <label class="flex items-center cursor-pointer"> <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" name="experiencelevel[]" value="<?= htmlspecialchars($exp) ?>" <?= (isset($_GET['experiencelevel']) && in_array($exp, (array)$_GET['experiencelevel'])) ? 'checked' : '' ?>> <span class="ml-2 text-gray-700"><?= htmlspecialchars(ucfirst($exp)) ?></span> </label> <?php endforeach; ?> </div> -->
                                <div class="mt-4 flex gap-2"> <input type="number" name="min_experience" min="0" placeholder="Min years..." value="<?= htmlspecialchars($_GET['min_experience'] ?? '') ?>" class="rounded border-black-300 px-2 py-1 w-32" style=" border: 1px solid;" /> <input type="number" name="max_experience" min="0" placeholder="Max years..." value="<?= htmlspecialchars($_GET['max_experience'] ?? '') ?>" class="rounded border-black-300 px-2 py-1 w-32" style=" border: 1px solid;" /> </div>
                            </div> <!-- Salary Range -->
                            <div class="mb-6">
                                <h3 class="font-semibold mb-3">Salary Range</h3>
                                <div class="space-y-4">
                                    <div> <input type="range" id="salary-range" name="min_salary" min="0" max="200000" step="10000" value="<?= htmlspecialchars($_GET['min_salary'] ?? '0') ?>" class="w-full" onchange="document.getElementById('filter-form').submit(); updateSalaryRange();">
                                        <div class="flex justify-between text-sm text-gray-600 mt-1"> <span>Not Disclosed</span> <span id="salary-value">₹<?= number_format(intval($_GET['min_salary'] ?? 100000)) ?>+</span> <span>₹200,000+</span> </div>
                                    </div>
                                </div>
                            </div> <!-- Industry Filter with Popup -->
                            <div class="mb-6">
                                <h3 class="font-semibold mb-3 flex items-center justify-between"> Industry <button type="button" id="open-industry-popup" aria-label="Select Industries" class="text-blue-600 hover:text-blue-700 font-semibold text-sm focus:outline-none"> Select </button> </h3>
                                <div class="flex flex-wrap gap-2"> <?php if (!empty($industry)) {
                                                                        foreach ($industry as $indSel) {
                                                                            $indName = $industryCounts[$indSel] ?? $indSel;
                                                                            echo '<span class="bg-blue-100 text-blue-600 text-xs font-medium px-2 py-1 rounded-full">' . htmlspecialchars($indName) . '</span>';
                                                                        }
                                                                    } else {
                                                                        echo '<span class="text-gray-500 text-sm">No industry selected</span>';
                                                                    } ?> </div> <!-- ✅ Hidden inputs to preserve industry selection --> <?php if (!empty($industry)): ?> <?php foreach ($industry as $indSel): ?> <input type="hidden" name="industry" value="<?= htmlspecialchars($indSel) ?>"> <?php endforeach; ?> <?php endif; ?>
                            </div>
                        </form>

                        <!-- Apply Button -->
                        <div class="mt-4">
                            <button type="submit" form="filter-form"
                                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </aside>


                <!-- Job Listings -->
                <main class="flex-1">
                    <!-- Results Header -->

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold   mb-2">Job Listings</h2>
                            <?php
                            $startJob = $offset + 1;
                            $endJob = min($offset + $limit, $count);
                            ?>
                            <p class="text-gray-600" id="results-count">
                                Showing <?= $startJob ?>–<?= $endJob ?> of <?= $count ?> jobs
                            </p>

                        </div>

                        <div class="flex items-center gap-4 mt-4 sm:mt-0">
                            <label class="text-sm text-gray-600 ">Sort by:</label>
                            <select onchange="sortJobs()" id="sort-select" name="sort"
                                class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white    focus:ring-2 focus:ring-blue-500">
                                <option value="newest" <?= (isset($_GET['sort']) && $_GET['sort'] === 'newest') ? 'selected' : '' ?>>Newest First</option>
                                <option value="oldest" <?= (isset($_GET['sort']) && $_GET['sort'] === 'oldest') ? 'selected' : '' ?>>Oldest First</option>
                                <option value="salary-high" <?= (isset($_GET['sort']) && $_GET['sort'] === 'salary-high') ? 'selected' : '' ?>>Salary: High to Low</option>
                                <option value="salary-low" <?= (isset($_GET['sort']) && $_GET['sort'] === 'salary-low') ? 'selected' : '' ?>>Salary: Low to High</option>
                                <option value="relevance" <?= (isset($_GET['sort']) && $_GET['sort'] === 'relevance') ? 'selected' : '' ?>>Most Relevant</option>
                            </select>
                        </div>
                    </div>

                    <!-- Job Cards Grid -->
                    <!-- Job Cards Grid -->
                    <div id="jobs-container" class="space-y-6">
                        <?php
                        // Paginate filtered jobs
                        $limit = 6;
                        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
                        $totalFiltered = count($filteredJobs);
                        $totalPages = ceil($totalFiltered / $limit);
                        $offset = ($page - 1) * $limit;
                        $currentJobs = array_slice($filteredJobs, $offset, $limit);

                        if (empty($currentJobs)) {
                            echo "<p class='text-center text-gray-500'>No jobs found.</p>";
                        }

                        foreach ($currentJobs as $job):
                            $slugTitle = slugify($job['title']);
                            $slugCity = slugify($job['city']);
                            $jobId = (int)$job['id'];
                        ?>
                            <div class="job-card bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300">
                                <div class="flex flex-col sm:flex-row gap-6">
                                    <div class="flex-shrink-0">
                                        <img src="/<?= htmlspecialchars($job['logo']) ?>" alt="<?= htmlspecialchars($job['logoAlt']) ?>" class="w-16 h-16 rounded-xl object-contain" />
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold mb-1"><?= htmlspecialchars($job['title']) ?></h3>
                                                <p class="text-lg font-semibold text-gray-700 mb-2"><?= htmlspecialchars($job['company']) ?></p>
                                                <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-3">
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        </svg>
                                                        <span><?= htmlspecialchars(trim(($job['city'] ?? '') . ', ' . ($job['state'] ?? ''), ', ')) ?></span>
                                                    </div>
                                                    <?php if (!empty($job['salary'])): ?>
                                                        <div class="flex items-center gap-1">
                                                            <span><?= htmlspecialchars($job['salary']) ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span><?= htmlspecialchars(timeAgo($job['created_at'])) ?></span>
                                                    </div>
                                                </div>
                                                <p class="text-gray-600 mb-4 line-clamp-2"><?= htmlspecialchars($job['description']) ?></p>
                                                <div class="flex flex-wrap gap-2 mb-4">
                                                    <?php foreach (array_slice($job['tags'] ?? [], 0, 3) as $tag): ?>
                                                        <span class="bg-blue-100 text-blue-600 text-xs font-medium px-2 py-1 rounded-full"><?= htmlspecialchars($tag) ?></span>
                                                    <?php endforeach; ?>
                                                    <?php if (count($job['tags'] ?? []) > 3): ?>
                                                        <span class="text-xs text-gray-500">+<?= count($job['tags']) - 3 ?> more</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="flex flex-col gap-3 sm:items-end">
                                                <a href="/jobs/<?= $slugTitle ?>-in-<?= $slugCity ?>-<?= $jobId ?>" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-200 text-sm font-medium text-center">
                                                    View Details
                                                </a>
                                                <!--<a href="/apply.php?job_id=<?= $jobId ?>" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-2 rounded-xl transition hover:scale-105 text-sm font-semibold text-center">-->
                                                <!--    Apply Now-->
                                                <!--</a>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination Links -->
                    <?php if ($totalPages > 1): ?>
                        <div class="mt-10 flex justify-center items-center gap-2 flex-wrap text-sm font-medium">
                            <?php
                            $start = max(1, $page - 2);
                            $end = min($totalPages, $page + 2);
                            if ($page > 1) {
                                echo '<a href="?' . http_build_query(['search' => $search, 'page' => $page - 1]) . '" class="px-3 py-2 rounded border bg-white text-blue-600 hover:bg-blue-50">Prev</a>';
                            }
                            if ($start > 1) {
                                echo '<a href="?' . http_build_query(['search' => $search, 'page' => 1]) . '" class="px-3 py-2 rounded border bg-white text-blue-600 hover:bg-blue-50">1</a>';
                                if ($start > 2) echo '<span class="px-3 py-2 text-gray-400">...</span>';
                            }
                            for ($i = $start; $i <= $end; $i++) {
                                $active = $i === $page ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 hover:bg-blue-50';
                                echo '<a href="?' . http_build_query(['search' => $search, 'page' => $i]) . "\" class=\"px-3 py-2 rounded border $active\">$i</a>";
                            }
                            if ($end < $totalPages) {
                                if ($end < $totalPages - 1) echo '<span class="px-3 py-2 text-gray-400">...</span>';
                                echo '<a href="?' . http_build_query(['search' => $search, 'page' => $totalPages]) . '" class="px-3 py-2 rounded border bg-white text-blue-600 hover:bg-blue-50">' . $totalPages . '</a>';
                            }
                            if ($page < $totalPages) {
                                echo '<a href="?' . http_build_query(['search' => $search, 'page' => $page + 1]) . '" class="px-3 py-2 rounded border bg-white text-blue-600 hover:bg-blue-50">Next</a>';
                            }
                            ?>
                        </div>
                    <?php endif; ?>


                    <!-- Pagination -->

                </main>
            </div>
        </div>
    </section>

    <!-- Industry Selection Popup -->
    <div id="industry-popup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden" aria-modal="true" role="dialog">
        <div class="bg-white rounded-2xl max-w-md w-full max-h-[80vh] flex flex-col p-0 relative">

            <button id="close-industry-popup" aria-label="Close Industry Selection"
                class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 focus:outline-none z-20">
                <i class="fas fa-times fa-lg"></i>
            </button>

            <h3 class="text-xl font-semibold mb-4 px-6 pt-6">Select Industries</h3>

            <form id="industry-form" class="flex-1 overflow-y-auto px-6 pb-28 space-y-3" onsubmit="applyIndustryFilter(event)">
                <?php foreach ($industryCounts as $ind => $countInd): ?>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            name="industry" value="<?= htmlspecialchars($ind) ?>"
                            <?= (isset($_GET['industry']) && in_array($ind, (array)$_GET['industry'])) ? 'checked' : '' ?>>
                        <span class="ml-2 text-gray-700"><?= htmlspecialchars($industryCounts[$ind] ?? $ind) ?></span>
                    </label>
                <?php endforeach; ?>
            </form>
            <div class="sticky bottom-0 left-0 w-full bg-white border-t border-gray-200 px-6 py-4 flex justify-end gap-3 z-10">
                <button type="button" id="cancel-industry"
                    class="px-4 py-2 rounded bg-gray-300 text-gray-700 hover:bg-gray-400 transition-colors">Cancel</button>
                <button type="submit" form="industry-form"
                    class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition-colors">Apply</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'include/footer.php'; ?>


    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-6 left-6 z-50 flex flex-col gap-3 pointer-events-none">
        <!-- Toasts will be added here -->
    </div>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                themeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>';
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                themeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.485-9h1M3 12h1m15.364 6.364l.707.707M4.222 4.222l.707.707m12.02 12.02l.707.707M4.222 19.778l.707.707M12 7a5 5 0 100 10 5 5 0 000-10z"></path>';
            }
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        function clearFilters() {
            window.location.href = 'job.php';
        }

        function sortJobs() {
            const sortSelect = document.getElementById('sort-select');
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('sort', sortSelect.value);
            window.location.search = urlParams.toString();
        }

        function previousPage() {
            // Implement pagination previous page logic
        }

        function nextPage() {
            // Implement pagination next page logic
        }

        function handleSearch(event) {
            // Implement search form submission logic if needed
        }

        // Industry popup logic
        const industryPopup = document.getElementById('industry-popup');
        const openIndustryBtn = document.getElementById('open-industry-popup');
        const closeIndustryBtn = document.getElementById('close-industry-popup');
        const cancelIndustryBtn = document.getElementById('cancel-industry');

        if (openIndustryBtn && industryPopup) {
            openIndustryBtn.addEventListener('click', () => {
                industryPopup.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        }
        if (closeIndustryBtn && industryPopup) {
            closeIndustryBtn.addEventListener('click', () => {
                industryPopup.classList.add('hidden');
                document.body.style.overflow = '';
            });
        }
        if (cancelIndustryBtn && industryPopup) {
            cancelIndustryBtn.addEventListener('click', () => {
                industryPopup.classList.add('hidden');
                document.body.style.overflow = '';
            });
        }
        if (industryPopup) {
            industryPopup.addEventListener('click', (e) => {
                if (e.target === industryPopup) {
                    industryPopup.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        }

        // Update salary range display
        const salaryRange = document.getElementById('salary-range');
        const salaryValue = document.getElementById('salary-value');

        function updateSalaryRange() {
            salaryValue.textContent = `₹${Number(salaryRange.value).toLocaleString()}+`;
        }
        updateSalaryRange();

        // On page load, set theme from localStorage
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('theme') === 'dark') {
                document.documentElement.classList.add('dark');
                const themeIcon = document.getElementById('theme-icon');
                themeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.485-9h1M3 12h1m15.364 6.364l.707.707M4.222 4.222l.707.707m12.02 12.02l.707.707M4.222 19.778l.707.707M12 7a5 5 0 100 10 5 5 0 000-10z"></path>';
            }
        });
    </script>
    <script>
        function applyIndustryFilter(event) {
            event.preventDefault();

            const mainForm = document.getElementById('filter-form');

            // Remove existing industry inputs from the main form
            document.querySelectorAll('#filter-form input[name="industry"]').forEach(e => e.remove());

            // Add all checked industry checkboxes from the popup to the main form
            document.querySelectorAll('#industry-form input[name="industry"]:checked').forEach(checkbox => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'industry';
                hiddenInput.value = checkbox.value;
                mainForm.appendChild(hiddenInput);
            });

            // Submit the main filter form with all filters
            mainForm.submit();
        }
    </script>

</body>

</html>