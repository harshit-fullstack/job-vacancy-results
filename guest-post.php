<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Post Submissions - Write for Job Vacancy Results</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease forwards',
                        'pulse-soft': 'pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(30px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease forwards; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="font-inter bg-white text-gray-800">
    <!-- Header -->
    <?php include 'include/header.php'; ?>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-blue-600 via-purple-600 to-orange-500 text-white pt-32 pb-20 px-4 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-5xl mx-auto text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">Share Your Expertise</h1>
            <p class="text-xl md:text-3xl mb-8 opacity-90">Write for Job Vacancy Results</p>
            <p class="text-base md:text-xl mb-10 opacity-80 max-w-4xl mx-auto leading-relaxed">
                Join our community of career experts, HR professionals, and industry leaders. Share your knowledge 
                and help millions of job seekers advance their careers through compelling, insightful content.
            </p>
            <button onclick="openSubmissionModal()" class="inline-flex items-center gap-2 bg-white text-blue-600 px-8 py-4 rounded-full text-lg font-semibold transition-all duration-300 hover:bg-gray-50 hover:-translate-y-0.5 shadow-xl hover:shadow-2xl">
                Submit Your Article
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </button>
        </div>
    </section>

    <!-- Who We Are Section -->
    <section id="about" class="py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Who We Are</h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Job Vacancy Results is a leading career platform that connects millions of job seekers with 
                        opportunities worldwide. We're passionate about providing valuable, actionable content that 
                        helps professionals at every stage of their career journey make informed decisions and achieve success.
                    </p>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        Our platform reaches over 2 million monthly readers seeking career advice, job search strategies, 
                        industry insights, and professional development resources.
                    </p>
                </div>
                <div class="relative">
                    <img src="https://images.pexels.com/photos/3184291/pexels-photo-3184291.jpeg?auto=compress&cs=tinysrgb&w=800" 
                         alt="Professional team collaborating in modern office space" 
                         class="w-full h-auto rounded-2xl shadow-2xl">
                </div>
            </div>
        </div>
    </section>

    <!-- What We Do Section -->
    <section class="py-20 px-4 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-4">What We Do</h2>
            <p class="text-lg text-center text-gray-600 mb-16 max-w-3xl mx-auto">
                We publish comprehensive career content that empowers professionals to succeed
            </p>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up">
                    <p class="text-gray-700 font-medium">Publish expert career advice and job search strategies</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up">
                    <p class="text-gray-700 font-medium">Share industry insights and market trends</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up">
                    <p class="text-gray-700 font-medium">Provide professional development resources</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up">
                    <p class="text-gray-700 font-medium">Connect job seekers with valuable opportunities</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Write for Us Section -->
    <section id="topics" class="py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-4">Why Write for Us?</h2>
            <p class="text-lg text-center text-gray-600 mb-16 max-w-3xl mx-auto">
                Share your expertise and make a meaningful impact while building your professional brand
            </p>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-900">Massive Reach</h3>
                    <p class="text-gray-600 leading-relaxed">Get your content in front of 2+ million monthly readers actively seeking career guidance.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M12 1v6m0 6v6m11-7h-6m-6 0H1"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-900">Build Authority</h3>
                    <p class="text-gray-600 leading-relaxed">Establish yourself as a thought leader in your industry and expand your professional network.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                            <polyline points="15,3 21,3 21,9"/>
                            <line x1="10" y1="14" x2="21" y2="3"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-900">Quality Backlinks</h3>
                    <p class="text-gray-600 leading-relaxed">Earn high-quality backlinks to your website or LinkedIn profile to boost your SEO.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="9" cy="9" r="2"/>
                            <path d="M21 15l-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-900">Social Promotion</h3>
                    <p class="text-gray-600 leading-relaxed">We actively promote accepted articles across our social media channels with 500K+ followers.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="16 18 22 12 16 6"/>
                            <polyline points="8 6 2 12 8 18"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-900">Portfolio Building</h3>
                    <p class="text-gray-600 leading-relaxed">Add published articles to your portfolio and resume as proof of your expertise and writing skills.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                            <line x1="8" y1="21" x2="16" y2="21"/>
                            <line x1="12" y1="17" x2="12" y2="21"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-900">Make an Impact</h3>
                    <p class="text-gray-600 leading-relaxed">Help job seekers worldwide by sharing your knowledge and contributing to their career success.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- What We're Looking For Section -->
    <section class="py-20 px-4 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-4">What We're Looking For</h2>
            <p class="text-lg text-center text-gray-600 mb-16 max-w-3xl mx-auto">
                We seek high-quality, original content from experienced professionals and industry experts
            </p>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 border border-blue-200 p-6 rounded-xl font-medium text-blue-800 hover:border-blue-300 hover:-translate-y-0.5 transition-all duration-300">
                    Career Strategy Articles: Job search tips, interview strategies, career transitions
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 border border-blue-200 p-6 rounded-xl font-medium text-blue-800 hover:border-blue-300 hover:-translate-y-0.5 transition-all duration-300">
                    Industry Insights: Trends, market analysis, future of work predictions
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 border border-blue-200 p-6 rounded-xl font-medium text-blue-800 hover:border-blue-300 hover:-translate-y-0.5 transition-all duration-300">
                    Professional Development: Skills training, certifications, upskilling guides
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 border border-blue-200 p-6 rounded-xl font-medium text-blue-800 hover:border-blue-300 hover:-translate-y-0.5 transition-all duration-300">
                    Resume & LinkedIn Optimization: Modern best practices and templates
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 border border-blue-200 p-6 rounded-xl font-medium text-blue-800 hover:border-blue-300 hover:-translate-y-0.5 transition-all duration-300">
                    Workplace Culture: Remote work, team management, company culture insights
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 border border-blue-200 p-6 rounded-xl font-medium text-blue-800 hover:border-blue-300 hover:-translate-y-0.5 transition-all duration-300">
                    Salary Negotiation: Compensation strategies, benefits evaluation
                </div>
            </div>

            <div class="text-center">
                <img src="https://images.pexels.com/photos/3184338/pexels-photo-3184338.jpeg?auto=compress&cs=tinysrgb&w=1200" 
                     alt="Professional writers creating content for career development" 
                     class="w-full max-w-4xl mx-auto h-auto rounded-2xl shadow-2xl">
                <p class="mt-4 text-gray-500 italic">Quality content that inspires and educates professionals worldwide.</p>
            </div>
        </div>
    </section>

    <!-- Submission Guidelines -->
    <section class="py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-16">Submission Guidelines</h2>
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-600 hover:shadow-lg hover:translate-x-1 transition-all duration-300">
                    <strong class="text-gray-900">Original Content:</strong> Articles must be 100% original and not published elsewhere
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-600 hover:shadow-lg hover:translate-x-1 transition-all duration-300">
                    <strong class="text-gray-900">Word Count:</strong> 1,500-3,000 words with proper headings and structure
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-600 hover:shadow-lg hover:translate-x-1 transition-all duration-300">
                    <strong class="text-gray-900">Expert Knowledge:</strong> Content must demonstrate deep industry knowledge and expertise
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-600 hover:shadow-lg hover:translate-x-1 transition-all duration-300">
                    <strong class="text-gray-900">Actionable Insights:</strong> Provide practical, actionable advice readers can implement
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-600 hover:shadow-lg hover:translate-x-1 transition-all duration-300">
                    <strong class="text-gray-900">Proper Research:</strong> Include credible sources, statistics, and up-to-date information
                </div>
            </div>
        </div>
    </section>

    <!-- How to Submit Section -->
    <section class="py-20 px-4 bg-gray-50">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8">How to Submit</h2>
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-8 rounded-3xl shadow-2xl mb-8">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="bg-white/20 p-4 rounded-2xl">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-2xl font-semibold mb-2">submissions@jobvacancyresults.com</h3>
                        <p class="text-lg opacity-90 mb-2"><strong>Subject:</strong> Guest Post Submission - [Your Topic]</p>
                        <p class="opacity-80">Include your article draft, brief author bio, and headshot.</p>
                    </div>
                </div>
            </div>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                We review all submissions within <strong class="text-blue-600">5-7 business days</strong> and provide 
                feedback on acceptance or suggestions for improvement.
            </p>
            <button onclick="openSubmissionModal()" class="bg-blue-600 text-white px-8 py-4 rounded-full text-lg font-semibold transition-all duration-300 hover:bg-blue-700 hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
                Submit Your Article
            </button>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-16">Why Write for Job Vacancy Results?</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="7"/>
                            <polyline points="8.21,13.89 7,23 12,20 17,23 15.79,13.88"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-900">Industry Recognition</h3>
                    <p class="text-gray-600 leading-relaxed">Gain recognition as a thought leader in the career and HR industry.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-900">Massive Exposure</h3>
                    <p class="text-gray-600 leading-relaxed">Reach millions of professionals worldwide through our platform.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-900">Professional Growth</h3>
                    <p class="text-gray-600 leading-relaxed">Enhance your writing skills and professional credibility.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-900">Networking</h3>
                    <p class="text-gray-600 leading-relaxed">Connect with industry professionals and expand your network.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 animate-fade-in-up">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 20V10"/>
                            <path d="M12 20V4"/>
                            <path d="M6 20v-6"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-900">SEO Benefits</h3>
                    <p class="text-gray-600 leading-relaxed">Quality backlinks and increased online visibility for your brand.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- What We Don't Accept -->
    <section class="py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-900 mb-16">What We Don't Accept</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-red-50 border border-red-200 p-6 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    <p class="text-red-800 font-medium">Plagiarized or previously published content</p>
                </div>
                <div class="bg-red-50 border border-red-200 p-6 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    <p class="text-red-800 font-medium">Overly promotional or sales-focused articles</p>
                </div>
                <div class="bg-red-50 border border-red-200 p-6 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    <p class="text-red-800 font-medium">Generic content without actionable insights</p>
                </div>
                <div class="bg-red-50 border border-red-200 p-6 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    <p class="text-red-800 font-medium">Articles under 1,500 words or poorly structured</p>
                </div>
                <div class="bg-red-50 border border-red-200 p-6 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    <p class="text-red-800 font-medium">Content with excessive external links or affiliate marketing</p>
                </div>
                <div class="bg-red-50 border border-red-200 p-6 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    <p class="text-red-800 font-medium">Political, controversial, or off-topic content</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section id="submit" class="relative bg-gradient-to-r from-gray-800 to-blue-600 text-white py-20 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-8">Ready to Share Your Expertise?</h2>
            <p class="text-xl mb-8 opacity-90 max-w-2xl mx-auto leading-relaxed">
                Join our community of career experts and help millions of professionals advance their careers 
                through your valuable insights and knowledge.
            </p>
            <p class="text-lg mb-8 opacity-80">
                We believe great content deserves great exposure. Let's work together to create something amazing 
                that will inspire and guide professionals worldwide on their career journeys.
            </p>
            <p class="text-lg mb-4 opacity-80">
                Questions about submissions? Reach out at 
                <a href="mailto:submissions@jobvacancyresults.com" class="text-blue-200 underline font-medium hover:text-blue-100">submissions@jobvacancyresults.com</a>
            </p>
            <p class="text-2xl font-semibold">We look forward to featuring your expertise!</p>
        </div>
    </section>

    <!-- Submission Modal -->
    <div id="submissionModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-8 pb-4 border-b border-gray-200">
                <h3 class="text-2xl font-bold text-gray-900">Submit Your Article</h3>
                <button onclick="closeSubmissionModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition-all duration-200">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <form id="submissionForm" class="p-8">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="firstName" class="block font-medium text-gray-700">First Name</label>
                        <input type="text" id="firstName" name="firstName" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                    <div class="space-y-2">
                        <label for="lastName" class="block font-medium text-gray-700">Last Name</label>
                        <input type="text" id="lastName" name="lastName" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>
                <div class="mt-6 space-y-2">
                    <label for="email" class="block font-medium text-gray-700">Email Address</label>
                    <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>
                <div class="mt-6 space-y-2">
                    <label for="topic" class="block font-medium text-gray-700">Article Topic</label>
                    <select id="topic" name="topic" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="">Select a topic...</option>
                        <option value="career-strategy">Career Strategy & Job Search</option>
                        <option value="industry-insights">Industry Insights & Trends</option>
                        <option value="professional-development">Professional Development</option>
                        <option value="resume-linkedin">Resume & LinkedIn Optimization</option>
                        <option value="workplace-culture">Workplace Culture & Management</option>
                        <option value="salary-negotiation">Salary Negotiation & Benefits</option>
                        <option value="other">Other (please specify in pitch)</option>
                    </select>
                </div>
                <div class="mt-6 space-y-2">
                    <label for="expertise" class="block font-medium text-gray-700">Your Expertise Level</label>
                    <select id="expertise" name="expertise" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="">Select your level...</option>
                        <option value="hr-professional">HR Professional</option>
                        <option value="career-coach">Career Coach/Counselor</option>
                        <option value="industry-expert">Industry Expert/Executive</option>
                        <option value="freelancer">Freelance Writer</option>
                        <option value="consultant">Business Consultant</option>
                        <option value="other">Other Professional</option>
                    </select>
                </div>
                <div class="mt-6 space-y-2">
                    <label for="pitch" class="block font-medium text-gray-700">Article Pitch</label>
                    <textarea id="pitch" name="pitch" rows="5" required placeholder="Provide a brief outline of your proposed article, key points you'll cover, and why it would be valuable to our readers..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-vertical"></textarea>
                </div>
                <div class="flex flex-col md:flex-row gap-4 mt-8">
                    <button type="button" onclick="closeSubmissionModal()" class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                        Submit Pitch
                    </button>
                </div>
            </form>
        </div>
    </div> 
      <!-- Footer -->
   <?php include 'include/footer.php'; ?>

    <script>
        // Modal Functions
        function openSubmissionModal() {
            document.getElementById('submissionModal').classList.remove('hidden');
            document.getElementById('submissionModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeSubmissionModal() {
            document.getElementById('submissionModal').classList.add('hidden');
            document.getElementById('submissionModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
            document.getElementById('submissionForm').reset();
        }

        // Form Submission
        document.getElementById('submissionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Simulate submission
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.textContent = 'Submitting...';
            submitButton.disabled = true;
            
            setTimeout(() => {
                alert('Article pitch submitted successfully! We will review your submission and get back to you within 5-7 business days with feedback or next steps.');
                closeSubmissionModal();
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            }, 1500);
        });

        // Close modal when clicking outside
        document.getElementById('submissionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSubmissionModal();
            }
        });

        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
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

        // Header background change on scroll
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.classList.remove('bg-white/95');
                header.classList.add('bg-white');
                header.classList.add('shadow-sm');
            } else {
                header.classList.add('bg-white/95');
                header.classList.remove('bg-white');
                header.classList.remove('shadow-sm');
            }
        });

        // Animation observer for cards
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationDelay = `${Math.random() * 0.3}s`;
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        }, observerOptions);

        // Observe cards for animation
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.animate-fade-in-up');
            cards.forEach(card => observer.observe(card));
        });
    </script>
</body>
</html>