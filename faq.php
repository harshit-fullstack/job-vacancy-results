<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <?php
  $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
  $canonicalUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
  if (!empty($_SERVER['QUERY_STRING'])) {
      $canonicalUrl .= '?' . htmlspecialchars($_SERVER['QUERY_STRING']);
  }
  $seoTitle = 'FAQ | JobVacancyResult';
  $desc = 'Frequently asked questions about JobVacancyResult. Find answers to common queries about jobs, companies, and more.';
  ?>
  <title><?= $seoTitle ?></title>
  <meta name="description" content="<?= $desc ?>" />
    <link rel="icon" href="/jobvacancyresult/jvr-logo.jpg" width="32">

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
    "@type": "FAQPage",
    "mainEntity": [
      {
        "@type": "Question",
        "name": "How do I create an effective profile?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Complete your profile with a professional photo, detailed work history, and clear goals. Include relevant keywords."
        }
      },
      {
        "@type": "Question",
        "name": "How do I apply for jobs?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Click 'Apply Now' on any listing and follow the instructions. You can use a saved resume or upload a new one."
        }
      },
      {
        "@type": "Question",
        "name": "Can I save jobs to apply later?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Yes, use the heart icon to save jobs to your profile dashboard."
        }
      },
      {
        "@type": "Question",
        "name": "How do job alerts work?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Set your preferences and receive job alerts by email daily or weekly."
        }
      },
      {
        "@type": "Question",
        "name": "How can I track my applications?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Go to your dashboard > Applications to view your job submissions."
        }
      },
      {
        "@type": "Question",
        "name": "Can I upload multiple resumes?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Yes. You can upload and manage different versions for various roles."
        }
      },
      {
        "@type": "Question",
        "name": "Can I edit submitted applications?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Unfortunately no. You can withdraw and reapply with updates."
        }
      },
      {
        "@type": "Question",
        "name": "Do I need a cover letter?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "It's optional but recommended for better chances."
        }
      },
      {
        "@type": "Question",
        "name": "Are all jobs verified?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Yes, our team reviews listings to prevent scams."
        }
      },
      {
        "@type": "Question",
        "name": "How do I delete my account?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Visit Settings > Delete Account. It's irreversible."
        }
      }
    ]
  }
  </script>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="styles.css" />
</head>
<body class="bg-white text-gray-900 transition-colors duration-300">
  <?php include 'include/header.php'; ?>

  <!-- Hero Section -->
  <section class="relative py-20 overflow-hidden">
    <div class="absolute inset-0 hero-gradient opacity-10"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h1 class="text-5xl sm:text-6xl font-extrabold leading-tight mb-6">
        <span class="text-gradient">Frequently Asked Questions</span>
      </h1>
      <p class="text-xl text-gray-600">
        Need help? Start here. Find answers to the most common questions about our platform.
      </p>
    </div>
  </section>

  <!-- Category Tabs -->
  <section class="py-8 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-wrap justify-center gap-4">
        <button onclick="filterFAQ('all')" class="faq-tab active bg-blue-600 text-white px-6 py-3 rounded-full font-semibold transition-all">
          All Questions
        </button>
        <button onclick="filterFAQ('job-seekers')" class="faq-tab bg-white text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-blue-50 transition-all">
          For Job Seekers
        </button>
        <button onclick="filterFAQ('employers')" class="faq-tab bg-white text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-blue-50 transition-all">
          For Employers
        </button>
        <button onclick="filterFAQ('payments')" class="faq-tab bg-white text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-blue-50 transition-all">
          Payments & Billing
        </button>
        <button onclick="filterFAQ('technical')" class="faq-tab bg-white text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-blue-50 transition-all">
          Technical Support
        </button>
      </div>
    </div>
  </section>
  <!-- Back to Top Button -->
  <button aria-label="Back to top" class="fixed bottom-6 right-6 z-50 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-full p-3 shadow-lg hidden hover:from-blue-700 hover:to-blue-600 transition transform hover:scale-110" id="backToTopBtn" type="button">
    <i class="fas fa-arrow-up text-lg">
    </i>
  </button>

  <!-- FAQ Accordion -->
  <section class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div id="faq-container" class="space-y-6">
        <?php
        function faq_item($category, $question, $answer) {
          echo "<details class=\"faq-item bg-white rounded-2xl p-6 shadow-lg hover-lift scroll-reveal\" data-category=\"$category\">
            <summary class=\"font-semibold text-gray-900 text-lg cursor-pointer flex items-center justify-between\">
              $question
              <svg class=\"w-5 h-5 text-gray-500 transition-transform details-icon\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 9l-7 7-7-7\"></path>
              </svg>
            </summary>
            <div class=\"mt-4 text-gray-600 leading-relaxed\">$answer</div>
          </details>";
        }

        $faq_data = [
          'job-seekers' => [
            ["How do I create an effective profile?", "Complete your profile with a professional photo, detailed work history, and clear goals. Include relevant keywords."],
            ["How do I apply for jobs?", "Click 'Apply Now' on any listing and follow the instructions. You can use a saved resume or upload a new one."],
            ["Can I save jobs to apply later?", "Yes, use the heart icon to save jobs to your profile dashboard."],
            ["How do job alerts work?", "Set your preferences and receive job alerts by email daily or weekly."],
            ["How can I track my applications?", "Go to your dashboard > Applications to view your job submissions."],
            ["Can I upload multiple resumes?", "Yes. You can upload and manage different versions for various roles."],
            ["Can I edit submitted applications?", "Unfortunately no. You can withdraw and reapply with updates."],
            ["Do I need a cover letter?", "It’s optional but recommended for better chances."],
            ["Are all jobs verified?", "Yes, our team reviews listings to prevent scams."],
            ["How do I delete my account?", "Visit Settings > Delete Account. It’s irreversible."],
          ],
          'employers' => [
            ["How do I post a job listing?", "Log in as an employer, go to Dashboard > Post Job, and fill out the form."],
            ["How can I attract better candidates?", "Write a clear job description and offer competitive pay."],
            ["What plans do you offer?", "Basic ($99), Professional ($299/month), and Enterprise (custom)."],
            ["Can I edit my job listing?", "Yes. Go to Dashboard > My Listings and click Edit."],
            ["Can I duplicate listings?", "Yes. Use the duplicate option in job management."],
            ["How do I close a job early?", "Click the Close button under My Listings."],
            ["Can I contact applicants directly?", "Yes, use our messaging tool or contact email provided by candidates."],
            ["How do I upgrade my plan?", "Go to Settings > Billing and select a new plan."],
            ["Can I get an invoice?", "Invoices are available under Billing History in your account."],
            ["Is there a refund policy?", "Yes. See our Refund Policy for terms."],
          ],
          'payments' => [
            ["What payment methods are accepted?", "Credit cards, PayPal, UPI, and Net Banking."],
            ["Is my payment secure?", "Yes. We use SSL encryption and secure gateways."],
            ["Do you store payment info?", "No, we use third-party secure processors."],
            ["How do I get a receipt?", "Go to Billing > History and download your invoice."],
            ["Can I switch payment methods?", "Yes. Go to Billing Settings > Payment Options."],
            ["Why was my payment declined?", "Check with your bank or use another card."],
            ["Can I set auto-renewal?", "Yes. It's enabled by default on subscriptions."],
            ["Can I cancel auto-renewal?", "Yes. Go to Billing > Subscriptions and turn it off."],
            ["When will I be charged?", "You’ll be charged immediately upon subscription."],
            ["Is GST included?", "Yes, GST is included in all prices shown."],
          ],
          'technical' => [
            ["How do I reset my password?", "Click 'Forgot Password' on the login page and follow instructions."],
            ["Why am I not receiving emails?", "Check your spam folder or verify email in profile settings."],
            ["Can I change my email?", "Yes. Go to Settings > Account Info."],
            ["Which browsers are supported?", "Chrome, Firefox, Safari, and Edge."],
            ["Do you have a mobile app?", "Currently no, but the site is mobile-friendly."],
            ["How do I report a bug?", "Use the contact form or email support@jobvacancyresult.com"],
            ["My resume won’t upload!", "Ensure it's in PDF, DOC, or DOCX format under 5MB."],
            ["Why is the site slow?", "Check your internet connection and browser version."],
            ["How do I delete my profile picture?", "Go to Profile > Edit > Remove Photo."],
            ["Is my data shared with others?", "No. We never share data without consent."],
          ],
        ];

        foreach ($faq_data as $cat => $items) {
          foreach ($items as [$q, $a]) {
            faq_item($cat, $q, $a);
          }
        }
        ?>
      </div>
    </div>
  </section>

  <!-- Still Need Help -->
  <section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <div class="bg-white rounded-3xl p-12 shadow-2xl">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Still Need Help?</h2>
        <p class="text-xl text-gray-600 mb-8">Can't find the answer you're looking for? Our support team is here to help.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <a href="contact" class="btn-primary text-white bg-blue-600 px-8 py-4 rounded-xl font-semibold">Contact Support</a>
          
        </div>
      </div>
    </div>
  </section>

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


  <!-- JS -->
  <script>
    function filterFAQ(category) {
      const faqItems = document.querySelectorAll('.faq-item');
      const tabs = document.querySelectorAll('.faq-tab');
      tabs.forEach(tab => tab.classList.remove('active', 'bg-blue-600', 'text-white'));
      event.target.classList.add('active', 'bg-blue-600', 'text-white');
      faqItems.forEach(item => {
        item.style.display = category === 'all' || item.dataset.category === category ? 'block' : 'none';
      });
    }

    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('details').forEach(detail => {
        detail.addEventListener('toggle', function () {
          const icon = this.querySelector('.details-icon');
          icon.style.transform = this.open ? 'rotate(180deg)' : 'rotate(0deg)';
        });
      });
    });
  </script>
</body>
</html>