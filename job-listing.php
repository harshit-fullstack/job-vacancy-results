<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Job Listing</title>
    <link rel="icon" href="/jobvacancyresult/jvr-logo.jpg" width="32">

  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    rel="stylesheet"
  />
  <style>
    body {
      font-family: "Inter", sans-serif;
    }
    h2 {
      font-size: 2rem; /* Increased heading size */
    }
    .job-title {
      font-size: 1.25rem;
      font-weight: 700;
    }
    .job-meta,
    .job-meta p,
    .job-meta span,
    .job-meta i {
      font-size: 1rem;
    }
    .text-xs,
    .text-[8px],
    .text-[10px] {
      font-size: 1rem !important;
    }
    .text-base,
    .text-sm {
      font-size: 1.125rem !important;
    }
    .text-lg {
      font-size: 1.25rem !important;
    }
    .text-xl {
      font-size: 1.5rem !important;
    }
    label,
    input,
    button,
    .font-semibold {
      font-size: 1.125rem;
    }
    .modal-title,
    #modalTitle {
      font-size: 1.5rem;
    }
  </style>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap"
    rel="stylesheet"
  />
</head>
<body class="bg-blue-50 text-blue-900 min-h-screen flex items-center justify-center p-4">
  <div class="max-w-4xl w-full rounded-xl overflow-hidden shadow-lg bg-white">
    <img
      alt="People in meeting room discussing work, one person in green sweater gesturing with hand"
      class="w-full object-cover rounded-t-xl h-36 sm:h-48"
      height="192"
      src="https://storage.googleapis.com/a1aa/image/9112bafd-6682-4f5a-b362-25962008cdbd.jpg"
      width="1440"
    />
    <div class="px-6 py-4">
      <div
        class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
      >
        <div class="flex items-center gap-4">
          <img
            alt="Colorful abstract company logo with red, pink, and yellow shapes"
            class="w-16 h-16"
            height="64"
            src="https://storage.googleapis.com/a1aa/image/372ece4f-e2f3-4cc6-a99c-4c7955f27aba.jpg"
            width="64"
          />
          <div>
            <span
              class="inline-block bg-blue-200 text-blue-800 text-[10px] font-semibold rounded px-2 py-[2px] mb-1"
              >Full Time</span
            >
            <h2 class="text-blue-900 font-semibold text-base leading-5">
              Sr. Front-end Designer
            </h2>
            <p class="text-xs text-blue-600 flex items-center gap-1">
              <i class="fas fa-map-marker-alt text-blue-600 text-[10px]"></i>
              California, USA
            </p>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <button
            id="quickApplyBtn"
            class="bg-blue-700 text-white text-xs font-semibold rounded px-4 py-2 hover:bg-blue-800 transition"
          >
            Quick Apply
          </button>
          <button
            class="flex items-center gap-1 text-blue-600 text-xs font-normal border border-blue-300 rounded px-3 py-2 hover:bg-blue-100 transition"
          >
            <i class="far fa-heart"></i> Save Job
          </button>
        </div>
      </div>
      <div
        class="mt-3 border border-blue-300 rounded-md p-4 text-[10px] text-blue-700 font-semibold grid grid-cols-2 md:grid-cols-4 gap-y-2"
      >
        <div>
          <p class="text-[8px] text-blue-400 font-normal mb-1">Job Type</p>
          Full Time
        </div>
        <div>
          <p class="text-[8px] text-blue-400 font-normal mb-1">Experience</p>
          10 Years
        </div>
        <div>
          <p class="text-[8px] text-blue-400 font-normal mb-1">Salary</p>
          $800-1000/<span class="text-[8px]">month</span>
        </div>
        <div>
          <p class="text-[8px] text-blue-400 font-normal mb-1">Role</p>
          Developer
        </div>
      </div>
      <div class="mt-6 border-t border-blue-300">
        <nav
          class="flex gap-6 text-[10px] font-semibold border-b border-blue-700 pb-2 mt-2"
        >
          <button
            id="descTab"
            class="text-blue-900 border-b-2 border-blue-700 pb-1 focus:outline-none"
          >
            Job Description
          </button>
          <button
            id="reqTab"
            class="text-blue-700/60 font-normal hover:text-blue-900 focus:outline-none"
          >
            Job Requirements
          </button>
          <button
            id="revTab"
            class="text-blue-700/60 font-normal hover:text-blue-900 focus:outline-none"
          >
            Company Review
          </button>
        </nav>
      </div>
      <div class="mt-4 text-[10px] text-blue-700 leading-[14px] max-w-5xl min-h-[140px]">
        <div id="descContent">
          <p class="mb-3">
            Themezhub Web provides equal employment opportunities to all
            qualified individuals without regard to race, color, religion, sex,
            gender identity, sexual orientation, pregnancy, age, national
            origin, physical or mental disability, military or veteran status,
            genetic information, or any other protected classification. Equal
            employment opportunity includes, but is not limited to, hiring,
            training, promotion, demotion, transfer, leaves of absence, and
            termination. Thynk Web takes allegations of discrimination,
            harassment, and retaliation seriously, and will promptly
            investigate when such behavior is reported.
          </p>
          <p class="mb-3">
            Our company is seeking to hire a skilled Web Developer to help with
            the development of our current projects. Your duties will primarily
            revolve around building software by writing code, as well as
            modifying software to fix errors, adapt it to new hardware,
            improve its performance, or upgrade interfaces. You will also be
            involved in directing system testing and validation procedures, and
            also working with customers or departments on technical issues
            including software system design and maintenance.
          </p>
          <p>
            We are looking for a Senior Web Developer to build and maintain
            functional web pages and applications. Senior Web Developer will be
            leading junior developers, refining website specifications, and
            resolving technical issues. He/She should have extensive experience
            building web pages from scratch and in-depth knowledge of at least
            one of the following programming languages: Javascript, Ruby, or
            PHP. He/She will ensure our web pages are up and running and cover
            both internal and customer needs.
          </p>
        </div>
        <div id="reqContent" class="hidden">
          <p class="mb-3">
            - Proven experience as a Front-end Developer or similar role.<br />
            - Proficient understanding of web markup, including HTML5, CSS3.<br />
            - Basic understanding of server-side CSS pre-processing platforms,
            such as LESS and SASS.<br />
            - Proficient understanding of client-side scripting and JavaScript
            frameworks, including jQuery.<br />
            - Good understanding of asynchronous request handling, partial page
            updates, and AJAX.<br />
            - Familiarity with tools such as Gulp or Webpack.<br />
            - Excellent problem-solving skills and attention to detail.<br />
            - Strong communication skills and ability to work in a team.
          </p>
        </div>
        <div id="revContent" class="hidden">
          <p class="mb-3">
            - "Great company culture and supportive management."<br />
            - "Opportunities for growth and learning new technologies."<br />
            - "Flexible working hours and remote work options."<br />
            - "Collaborative and friendly team environment."<br />
            - "Competitive salary and benefits package."
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div
    id="modal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50"
  >
    <div
      class="bg-white rounded-lg max-w-md w-full p-6 relative shadow-lg"
      role="dialog"
      aria-modal="true"
      aria-labelledby="modalTitle"
    >
      <button
        id="closeModal"
        class="absolute top-3 right-3 text-blue-700 hover:text-blue-900 focus:outline-none"
        aria-label="Close modal"
      >
        <i class="fas fa-times text-xl"></i>
      </button>
      <h3
        id="modalTitle"
        class="text-blue-900 font-semibold text-lg mb-4 text-center"
      >
        Quick Apply
      </h3>
      <form id="applyForm" class="space-y-4 text-blue-900 text-sm" novalidate>
        <div>
          <label for="name" class="block mb-1 font-semibold">Full Name</label>
          <input
            id="name"
            name="name"
            type="text"
            class="w-full border border-blue-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Your full name"
            required
          />
          <p id="nameError" class="text-red-600 text-xs mt-1 hidden">Please enter your full name.</p>
        </div>
        <div>
          <label for="email" class="block mb-1 font-semibold">Email</label>
          <input
            id="email"
            name="email"
            type="email"
            class="w-full border border-blue-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="you@example.com"
            required
          />
          <p id="emailError" class="text-red-600 text-xs mt-1 hidden">Please enter a valid email address.</p>
        </div>
        <div>
          <label for="resume" class="block mb-1 font-semibold">Resume URL</label>
          <input
            id="resume"
            name="resume"
            type="url"
            class="w-full border border-blue-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Link to your resume"
          />
          <p id="resumeError" class="text-red-600 text-xs mt-1 hidden">Please enter a valid URL or leave blank.</p>
        </div>
        <button
          type="submit"
          class="w-full bg-blue-700 text-white font-semibold rounded py-2 hover:bg-blue-800 transition"
        >
          Submit Application
        </button>
      </form>
    </div>
  </div>

  <script>
    // Tabs
    const descTab = document.getElementById("descTab");
    const reqTab = document.getElementById("reqTab");
    const revTab = document.getElementById("revTab");

    const descContent = document.getElementById("descContent");
    const reqContent = document.getElementById("reqContent");
    const revContent = document.getElementById("revContent");

    function clearActiveTabs() {
      [descTab, reqTab, revTab].forEach((tab) => {
        tab.classList.remove("border-blue-700", "text-blue-900", "font-semibold");
        tab.classList.add("text-blue-700/60", "font-normal");
      });
    }

    function hideAllContents() {
      descContent.classList.add("hidden");
      reqContent.classList.add("hidden");
      revContent.classList.add("hidden");
    }

    descTab.addEventListener("click", () => {
      clearActiveTabs();
      descTab.classList.add("border-blue-700", "text-blue-900", "font-semibold");
      descTab.classList.remove("text-blue-700/60", "font-normal");
      hideAllContents();
      descContent.classList.remove("hidden");
    });

    reqTab.addEventListener("click", () => {
      clearActiveTabs();
      reqTab.classList.add("border-blue-700", "text-blue-900", "font-semibold");
      reqTab.classList.remove("text-blue-700/60", "font-normal");
      hideAllContents();
      reqContent.classList.remove("hidden");
    });

    revTab.addEventListener("click", () => {
      clearActiveTabs();
      revTab.classList.add("border-blue-700", "text-blue-900", "font-semibold");
      revTab.classList.remove("text-blue-700/60", "font-normal");
      hideAllContents();
      revContent.classList.remove("hidden");
    });

    // Modal
    const modal = document.getElementById("modal");
    const quickApplyBtn = document.getElementById("quickApplyBtn");
    const closeModalBtn = document.getElementById("closeModal");

    quickApplyBtn.addEventListener("click", () => {
      modal.classList.remove("hidden");
      document.body.style.overflow = "hidden";
    });

    closeModalBtn.addEventListener("click", () => {
      modal.classList.add("hidden");
      document.body.style.overflow = "";
      clearFormErrors();
      document.getElementById("applyForm").reset();
    });

    // Close modal on outside click
    modal.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.classList.add("hidden");
        document.body.style.overflow = "";
        clearFormErrors();
        document.getElementById("applyForm").reset();
      }
    });

    // Optional: close modal on ESC key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && !modal.classList.contains("hidden")) {
        modal.classList.add("hidden");
        document.body.style.overflow = "";
        clearFormErrors();
        document.getElementById("applyForm").reset();
      }
    });

    // Form validation and submission
    const applyForm = document.getElementById("applyForm");
    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const resumeInput = document.getElementById("resume");

    const nameError = document.getElementById("nameError");
    const emailError = document.getElementById("emailError");
    const resumeError = document.getElementById("resumeError");

    function clearFormErrors() {
      nameError.classList.add("hidden");
      emailError.classList.add("hidden");
      resumeError.classList.add("hidden");
    }

    function isValidEmail(email) {
      // Simple email regex
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidURL(url) {
      if (!url) return true; // empty is allowed
      try {
        new URL(url);
        return true;
      } catch {
        return false;
      }
    }

    applyForm.addEventListener("submit", (e) => {
      e.preventDefault();
      clearFormErrors();

      let valid = true;

      if (!nameInput.value.trim()) {
        nameError.classList.remove("hidden");
        valid = false;
      }

      if (!emailInput.value.trim() || !isValidEmail(emailInput.value.trim())) {
        emailError.classList.remove("hidden");
        valid = false;
      }

      if (!isValidURL(resumeInput.value.trim())) {
        resumeError.classList.remove("hidden");
        valid = false;
      }

      if (!valid) return;

      // Simulate form submission success
      alert(
        `Application submitted!\nName: ${nameInput.value.trim()}\nEmail: ${emailInput.value.trim()}\nResume: ${resumeInput.value.trim() || "N/A"}`
      );

      // Close modal and reset form
      modal.classList.add("hidden");
      document.body.style.overflow = "";
      applyForm.reset();
    });
  </script>
</body>
</html>