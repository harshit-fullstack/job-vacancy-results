<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $canonicalUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
    if (!empty($_SERVER['QUERY_STRING'])) {
        $canonicalUrl .= '?' . htmlspecialchars($_SERVER['QUERY_STRING']);
    }
    $seoTitle = 'Contact Us | JobVacancyResult';
    $desc = 'Contact JobVacancyResult for support, inquiries, or feedback. We are here to help you with your job search and hiring needs.';
    ?>
    <title><?= $seoTitle ?></title>
    <meta name="description" content="<?= $desc ?>" />
    <link rel="icon" href="/jvr-logo.jpg" width="32">

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
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "ContactPage",
      "name": "Contact Us | JobVacancyResult",
      "description": "Contact JobVacancyResult for support, inquiries, or feedback. We are here to help you with your job search and hiring needs.",
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
          "name": "Contact Us",
          "item": "<?= $canonicalUrl ?>"
        }
      ]
    }
    </script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
    <script>

    </script>
</head>

<body class="bg-white text-gray-900 transition-colors duration-300">
    <!-- Header -->
    <?php include 'include/header.php'; ?>


    <!-- Hero Section -->
    <section class="relative py-20 overflow-hidden">
        <div class="absolute inset-0 hero-gradient opacity-10"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="animate-fade-in">
                <h1 class="text-5xl sm:text-6xl font-extrabold leading-tight mb-6">
                    <span class="text-gradient">Get in Touch</span>
                </h1>
                <p class="text-xl text-gray-600">
                    We're here to help you succeed. Reach out to our team for support, partnerships, or any questions you may have.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Contact Info -->
                <div class="lg:col-span-1 scroll-reveal">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">
                        Contact Information
                    </h2>

                    <div class="space-y-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Email Us</h3>
                                <p class="text-gray-600"><a href="mailto:info@jobvacancyresult.com">info@jobvacancyresult.com</a></p>
                                
                            </div>
                        </div>

                        

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Visit Us</h3>
                                <p class="text-gray-600">Shop No. 71, 1st Floor, GTB Nagar, Near GTB Metro Station, Delhi – 110009</p>
                            </div>
                        </div>

                        
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2 scroll-reveal">
                    <div class="bg-white rounded-3xl shadow-2xl p-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-8">
                            Send us a Message
                        </h2>

                        <form id="contactForm" action="https://formsubmit.co/ajax/sachinvivek23@gmail.com" method="POST" class="space-y-6">
                            <!-- Disable Captcha -->
                            <input type="hidden" name="_captcha" value="false">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="firstName" class="block text-sm font-semibold text-gray-700 mb-2">First Name *</label>
                                    <input type="text" id="firstName" name="First Name" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                </div>
                                <div>
                                    <label for="lastName" class="block text-sm font-semibold text-gray-700 mb-2">Last Name *</label>
                                    <input type="text" id="lastName" name="Last Name" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                                <input type="email" id="email" name="Email" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">Subject *</label>
                                <select id="subject" name="Subject" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="">Select a subject</option>
                                    <option value="General Inquiry">General Inquiry</option>
                                    <option value="Technical Support">Technical Support</option>
                                    <option value="Billing Question">Billing Question</option>
                                    <option value="Partnership Opportunity">Partnership Opportunity</option>
                                    <option value="Feedback">Feedback</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Message *</label>
                                <textarea id="message" name="Message" rows="5" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                    placeholder="Tell us how we can help you..."></textarea>
                            </div>

                            <div class="flex items-start">
                                <input type="checkbox" id="consent" name="Consent" required
                                    class="mt-1 mr-3 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="consent" class="text-sm text-gray-600">
                                    I agree to the <a href="terms-of-service" class="text-blue-600 hover:underline">Terms of Service</a> and
                                    <a href="privacy-policy" class="text-blue-600 hover:underline">Privacy Policy</a>
                                </label>
                            </div>

                            <button id="submitBtn" type="submit"
                                class="w-full bg-blue-600 text-white border border-blue-700 px-8 py-4 rounded-xl text-lg font-semibold 
           transition hover:bg-blue-700 hover:border-blue-800 disabled:opacity-50 disabled:cursor-not-allowed">
                                Send Message
                            </button>
                        </form>

                        <!-- Success & Error Messages -->
                        <div id="formSuccess" class="hidden mt-4 p-4 bg-green-100 text-green-700 rounded-xl">
                            ✅ Thank you! Your message has been sent.
                        </div>
                        <div id="formError" class="hidden mt-4 p-4 bg-red-100 text-red-700 rounded-xl">
                            ❌ Oops! Something went wrong. Please try again.
                        </div>

                        <script>
                            document.getElementById("contactForm").addEventListener("submit", async function(event) {
                                event.preventDefault();

                                let form = event.target;
                                let formData = new FormData(form);

                                try {
                                    let response = await fetch(form.action, {
                                        method: "POST",
                                        body: formData
                                    });

                                    if (response.ok) {
                                        form.reset();
                                        document.getElementById("formSuccess").classList.remove("hidden");
                                        document.getElementById("formError").classList.add("hidden");
                                    } else {
                                        throw new Error("Form submission failed");
                                    }
                                } catch (error) {
                                    document.getElementById("formError").classList.remove("hidden");
                                    document.getElementById("formSuccess").classList.add("hidden");
                                }
                            });
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 scroll-reveal">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Visit Our Office
                </h2>
                <p class="text-xl text-gray-600">
                    We'd love to meet you in person. Schedule a visit to our New York office.
                </p>
            </div>

            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden scroll-reveal">
                <div class="relative h-96 bg-gray-200">
                    <!-- Google Map Embed -->
                    <iframe
                        class="absolute inset-0 w-full h-full"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d94804.9167289811!2d77.16492318629807!3d28.711054440593045!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd94d658a1c5%3A0x89be794a5084b844!2sCulture%20of%20Internet%20-%20Best%20Digital%20Marketing%20Course%2C%20Graphic%20Designing%2C%20DataAnalyst%20Course%20Institute%20in%20Delhi%20GTB%20Nagar!5e0!3m2!1sen!2sin!4v1756100579717!5m2!1sen!2sin"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-500">
                        Shop No. 71, 2nd Floor, Kingsway Camp, GTB Nagar, New Delhi, Delhi 110009
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <?php include 'include/footer.php'; ?>

    <!-- Back to Top Button -->
    <button id="back-to-top" onclick="scrollToTop()" class="fixed bottom-6 right-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all transform hover:scale-110 z-40 opacity-0 pointer-events-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <script src="script.js"></script>
</body>

</html>