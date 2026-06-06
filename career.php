<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Our Innovation Team | JobVacancyResult</title>
    <meta name="description" content="Be part of JobVacancyResult's innovation team. Explore exciting career opportunities, grow with us, and shape the future of job search and recruitment." />
    <meta name="robots" content="index, follow" />
    <link rel="canonical" href="https://www.jobvacancyresult.com/career" />

    <!-- Open Graph Meta -->
    <meta property="og:title" content="Join Our Innovation Team | JobVacancyResult" />
    <meta property="og:description" content="Be part of JobVacancyResult's innovation team. Explore exciting career opportunities, grow with us, and shape the future of job search and recruitment." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.jobvacancyresult.com/career" />
    <meta property="og:site_name" content="JobVacancyResult" />
    <meta property="og:image" content="https://www.jobvacancyresult.com/jvr-logo.jpg" />

    <!-- Twitter Card Meta -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Join Our Innovation Team | JobVacancyResult" />
    <meta name="twitter:description" content="Be part of JobVacancyResult's innovation team. Explore exciting career opportunities, grow with us, and shape the future of job search and recruitment." />
    <meta name="twitter:image" content="https://www.jobvacancyresult.com/jvr-logo.jpg" />

    <!-- Schema Markup -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebPage",
            "name": "Careers | JobVacancyResult",
            "description": "Explore career opportunities at JobVacancyResult. Join our team and help shape the future of job search and recruitment.",
            "url": "<?= $canonicalUrl ?>"
        }
    </script>

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [{
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Home",
                    "item": "<?= $protocol . '://' . $_SERVER['HTTP_HOST'] ?>/jobvacancyresult/"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "Careers",
                    "item": "<?= $canonicalUrl ?>"
                }
            ]
        }
    </script>



    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="/jvr-logo.jpg" width="32">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#6366F1',
                        'secondary': '#EC4899',
                        'accent': '#10B981',
                        'dark': '#1F2937',
                        'light-gray': '#F9FAFB',
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s infinite',
                        'bounce-slow': 'bounce 2s infinite',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #6366F1, #EC4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-purple-50 via-white to-pink-50">
    <!-- Header -->
    <?php include 'include/header.php'; ?>



    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center justify-center relative overflow-hidden pt-20">
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-primary/20 rounded-full animate-float"></div>
        <div class="absolute top-40 right-20 w-16 h-16 bg-secondary/20 rounded-full animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-40 left-20 w-12 h-12 bg-accent/20 rounded-full animate-float" style="animation-delay: 4s;"></div>

        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div class="text-center lg:text-left">
                <h1 class="text-5xl lg:text-7xl font-bold mb-6">
                    <span class="gradient-text">Shape the Future</span>
                    <br>
                    <span class="text-dark">With Innovation</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    Join our dynamic team of visionaries, creators, and problem-solvers.
                    Together, we're building tomorrow's technology today.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a class="bg-gradient-to-r from-primary to-secondary text-white px-8 py-4 rounded-full text-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300" href="/jobvacancyresult/job">
                        Explore Opportunities
                    </a>
                    <button class="border-2 border-primary text-primary px-8 py-4 rounded-full text-lg font-semibold hover:bg-primary hover:text-white transition-all duration-300">
                        Watch Our Story
                    </button>
                </div>
            </div>

            <div class="relative">
                <div class="relative z-10">
                    <img src="https://images.pexels.com/photos/3184291/pexels-photo-3184291.jpeg?auto=compress&cs=tinysrgb&w=600&h=600&fit=crop"
                        alt="Team collaboration"
                        class="rounded-3xl shadow-2xl w-full max-w-lg mx-auto">
                </div>
                <!-- Decorative elements -->
                <div class="absolute -top-6 -right-6 w-24 h-24 bg-gradient-to-br from-secondary to-pink-400 rounded-full opacity-70 animate-pulse-slow"></div>
                <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-gradient-to-br from-accent to-green-400 rounded-full opacity-50 animate-bounce-slow"></div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold gradient-text mb-2">500+</div>
                    <div class="text-gray-600">Team Members</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold gradient-text mb-2">50+</div>
                    <div class="text-gray-600">Countries</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold gradient-text mb-2">10M+</div>
                    <div class="text-gray-600">Users Impacted</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold gradient-text mb-2">95%</div>
                    <div class="text-gray-600">Satisfaction Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Join Us Section -->
    <section class="py-20 bg-gradient-to-r from-light-gray to-purple-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold gradient-text mb-4">Why Choose Us?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    We believe in creating an environment where innovation thrives and every team member can reach their full potential.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Benefit 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary to-purple-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">Innovation First</h3>
                    <p class="text-gray-600">Work on cutting-edge projects that push the boundaries of technology and make a real impact.</p>
                </div>

                <!-- Benefit 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-secondary to-pink-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">Competitive Rewards</h3>
                    <p class="text-gray-600">Enjoy top-tier compensation, equity options, and comprehensive benefits package.</p>
                </div>

                <!-- Benefit 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-accent to-green-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">Work-Life Balance</h3>
                    <p class="text-gray-600">Flexible schedules, remote options, and unlimited PTO to maintain your well-being.</p>
                </div>

                <!-- Benefit 4 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">Continuous Learning</h3>
                    <p class="text-gray-600">Access to courses, conferences, and mentorship programs to accelerate your growth.</p>
                </div>

                <!-- Benefit 5 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">Diverse Community</h3>
                    <p class="text-gray-600">Join a global team that celebrates diversity and fosters inclusive collaboration.</p>
                </div>

                <!-- Benefit 6 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-dark mb-3">Health & Wellness</h3>
                    <p class="text-gray-600">Comprehensive healthcare, mental health support, and wellness programs for you and your family.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Culture Section -->
    <section id="culture" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold gradient-text mb-4">Our Culture</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    We've built a culture that empowers creativity, celebrates achievements, and supports every team member's journey.
                </p>
            </div>

            <!-- Culture Value 1 -->
            <div class="grid lg:grid-cols-2 gap-12 items-center mb-20">
                <div>
                    <h3 class="text-3xl font-bold text-dark mb-6">Collaborate & Create</h3>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        Our open workspace design and collaborative tools ensure that great ideas can come from anywhere.
                        We believe that the best solutions emerge when diverse minds work together towards a common goal.
                    </p>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Cross-functional teams</span>
                    </div>
                </div>
                <div class="relative">
                    <img src="https://images.pexels.com/photos/3184306/pexels-photo-3184306.jpeg?auto=compress&cs=tinysrgb&w=600&h=400&fit=crop"
                        alt="Team collaboration"
                        class="rounded-2xl shadow-xl w-full">
                    <div class="absolute -bottom-4 -right-4 w-20 h-20 bg-gradient-to-br from-secondary to-pink-500 rounded-full opacity-80"></div>
                </div>
            </div>

            <!-- Culture Value 2 -->
            <div class="grid lg:grid-cols-2 gap-12 items-center mb-20">
                <div class="lg:order-2">
                    <h3 class="text-3xl font-bold text-dark mb-6">Innovation Mindset</h3>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        We encourage experimentation and learning from failure. Our innovation labs and hackathons
                        provide the perfect environment for breakthrough ideas to flourish and transform into reality.
                    </p>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-accent/10 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Monthly innovation challenges</span>
                    </div>
                </div>
                <div class="lg:order-1 relative">
                    <img src="https://images.pexels.com/photos/3184360/pexels-photo-3184360.jpeg?auto=compress&cs=tinysrgb&w=600&h=400&fit=crop"
                        alt="Innovation workspace"
                        class="rounded-2xl shadow-xl w-full">
                    <div class="absolute -top-4 -left-4 w-16 h-16 bg-gradient-to-br from-primary to-purple-500 rounded-full opacity-80"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Job Openings Section -->

    <!-- <section id="jobs" class="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold gradient-text mb-4">Open Positions</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Ready to make an impact? Explore our current openings and find your perfect role.
                </p>
            </div>

           
            <div class="mb-12">
                
                <div class="mb-12">
                    <h3 class="text-2xl font-bold text-dark mb-8 flex items-center">
                        <div class="w-8 h-8 bg-primary rounded-lg mr-3"></div>
                        Engineering
                    </h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-primary">
                            <h4 class="text-lg font-bold text-dark mb-2">Senior Full Stack Developer</h4>
                            <p class="text-gray-600 mb-4">Build scalable web applications using React, Node.js, and cloud technologies.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        5+ years
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Remote
                                    </span>
                                </div>
                                <button class="text-primary hover:text-purple-700 font-semibold">Apply →</button>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-primary">
                            <h4 class="text-lg font-bold text-dark mb-2">DevOps Engineer</h4>
                            <p class="text-gray-600 mb-4">Manage cloud infrastructure and CI/CD pipelines for high-scale applications.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        3+ years
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Hybrid
                                    </span>
                                </div>
                                <button class="text-primary hover:text-purple-700 font-semibold">Apply →</button>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-primary">
                            <h4 class="text-lg font-bold text-dark mb-2">Mobile App Developer</h4>
                            <p class="text-gray-600 mb-4">Create amazing mobile experiences using React Native and Flutter.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        4+ years
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        On-site
                                    </span>
                                </div>
                                <button class="text-primary hover:text-purple-700 font-semibold">Apply →</button>
                            </div>
                        </div>
                    </div>
                </div>

               
                <div class="mb-12">
                    <h3 class="text-2xl font-bold text-dark mb-8 flex items-center">
                        <div class="w-8 h-8 bg-secondary rounded-lg mr-3"></div>
                        Design
                    </h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-secondary">
                            <h4 class="text-lg font-bold text-dark mb-2">Senior UX Designer</h4>
                            <p class="text-gray-600 mb-4">Lead user research and design intuitive experiences for our products.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        5+ years
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Remote
                                    </span>
                                </div>
                                <button class="text-secondary hover:text-pink-700 font-semibold">Apply →</button>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-secondary">
                            <h4 class="text-lg font-bold text-dark mb-2">Product Designer</h4>
                            <p class="text-gray-600 mb-4">Shape product vision through user-centered design and prototyping.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        3+ years
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Hybrid
                                    </span>
                                </div>
                                <button class="text-secondary hover:text-pink-700 font-semibold">Apply →</button>
                            </div>
                        </div>
                    </div>
                </div>

              
                <div class="mb-12">
                    <h3 class="text-2xl font-bold text-dark mb-8 flex items-center">
                        <div class="w-8 h-8 bg-accent rounded-lg mr-3"></div>
                        Marketing
                    </h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-accent">
                            <h4 class="text-lg font-bold text-dark mb-2">Digital Marketing Manager</h4>
                            <p class="text-gray-600 mb-4">Drive growth through innovative digital marketing strategies and campaigns.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        4+ years
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Remote
                                    </span>
                                </div>
                                <button class="text-accent hover:text-green-700 font-semibold">Apply →</button>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-accent">
                            <h4 class="text-lg font-bold text-dark mb-2">Content Strategist</h4>
                            <p class="text-gray-600 mb-4">Create compelling content that engages our audience and drives conversions.</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        2+ years
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Hybrid
                                    </span>
                                </div>
                                <button class="text-accent hover:text-green-700 font-semibold">Apply →</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button class="bg-gradient-to-r from-primary to-secondary text-white px-8 py-4 rounded-full text-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                    View All Positions
                </button>
            </div>
        </div>
    </section> -->

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-primary to-secondary text-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">Ready to Start Your Journey?</h2>
            <p class="text-xl mb-8 opacity-90">
                Join thousands of innovators who are already shaping the future with us.
                Your next career adventure starts here.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a class="bg-white text-primary px-8 py-4 rounded-full text-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300" href="/jobvacancyresult/job">
                    Browse All Jobs
                </a>
                <a class="border-2 border-white text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-white hover:text-primary transition-all duration-300" href="/jobvacancyresult/About-us">
                    Learn About Us
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <!-- Footer -->
    <?php include 'include/footer.php'; ?>

    <!-- Back to Top Button -->
    <button id="back-to-top" onclick="scrollToTop()" class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all transform hover:scale-110 z-40 opacity-0 pointer-events-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll effect to navigation
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 100) {
                nav.classList.add('bg-white/90');
                nav.classList.remove('glass-effect');
            } else {
                nav.classList.remove('bg-white/90');
                nav.classList.add('glass-effect');
            }
        });

        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all job cards and benefit cards
        document.querySelectorAll('.bg-white').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });

        // Add click handlers for apply buttons
        document.querySelectorAll('button').forEach(button => {
            if (button.textContent.includes('Apply')) {
                button.addEventListener('click', function() {
                    alert('Application form would open here! This is a demo.');
                });
            }
        });
    </script>
</body>

</html>