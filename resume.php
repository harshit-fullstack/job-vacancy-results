<!DOCTYPE html>
<html lang="en" class="transition-colors">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <?php
  $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
  $canonicalUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?');
  if (!empty($_SERVER['QUERY_STRING'])) {
      $canonicalUrl .= '?' . htmlspecialchars($_SERVER['QUERY_STRING']);
  }
  $seoTitle = 'Resume Builder | JobVacancyResult';
  $desc = 'Create and download your professional resume with JobVacancyResult\'s free resume builder.';
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

  <!-- Tailwind -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <style>
    html, body {
      margin: 0;
      padding: 0;
    }
    /* Custom input styles (instead of @apply) */
    .input-field {
      width: 100%;
      border: 1px solid #d1d5db; /* gray-300 */
      background: #fff;
      color: #111827; /* gray-900 */
      border-radius: 0.75rem; /* rounded-xl */
      padding: 1rem;
    }
    .input-field:focus {
      outline: none;
      border-color: #60a5fa; /* blue-400 */
      box-shadow: 0 0 0 2px #60a5fa;
    }
  </style>

  <script defer>
    function updatePreview(name) {
      const input = document.getElementsByName(name)[0];
      const output = document.getElementById("p-" + name);
      if (output) output.textContent = input.value;
      localStorage.setItem(name, input.value);
      input.classList.remove("border-red-500");
      autoResize(input);
    }

    function autoResize(el) {
      el.style.height = "auto";
      el.style.height = el.scrollHeight + "px";
    }

    function loadData() {
      const fields = ["name", "title", "email", "phone", "linkedin", "website", "summary", "skills", "experience", "education", "certifications", "languages"];
      fields.forEach(name => {
        const val = localStorage.getItem(name);
        if (val) {
          const el = document.getElementsByName(name)[0];
          const out = document.getElementById("p-" + name);
          el.value = val;
          autoResize(el);
          if (out) out.textContent = val;
        }
      });

      const imageData = localStorage.getItem("profileImage");
      if (imageData) document.getElementById("profile-img").src = imageData;
    }

    function exportPDF() {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
      let y = 10;
      const image = document.getElementById("profile-img").src;

      if (image && image.startsWith("data:image")) {
        doc.addImage(image, 'JPEG', 150, 10, 40, 40);
        y = Math.max(y, 60);
      }

      function addSection(title, content) {
        if (!content) return;
        if (y > 270) { doc.addPage(); y = 10; }
        doc.setFontSize(14);
        doc.setFont(undefined, 'bold');
        doc.text(title, 10, y);
        y += 8;
        doc.setFontSize(12);
        doc.setFont(undefined, 'normal');
        content.split('\n').forEach(line => {
          if (y > 280) { doc.addPage(); y = 10; }
          doc.text(line, 10, y);
          y += 6;
        });
        y += 4;
      }

      const ids = ["name", "title", "email", "phone", "linkedin", "website", "summary", "skills", "experience", "education", "certifications", "languages"];
      ids.forEach(id => addSection(id.charAt(0).toUpperCase() + id.slice(1), document.getElementById("p-" + id)?.textContent));

      doc.save("resume.pdf");
      showSuccess();
    }

    function showSuccess() {
      const popup = document.getElementById("success-popup");
      popup.classList.remove("hidden");
      setTimeout(() => popup.classList.add("hidden"), 3000);
    }

    function toggleDarkMode() {
      document.documentElement.classList.toggle('dark');
      document.body.classList.toggle('bg-gray-900');
      document.body.classList.toggle('text-white');
    }

    function clearAll() {
      if (confirm("Are you sure you want to clear all data? This cannot be undone.")) {
        localStorage.clear();
        location.reload();
      }
    }

    function previewImage(event) {
      const file = event.target.files[0];
      if (!file?.type.startsWith("image/")) {
        alert("Please select a valid image file.");
        return;
      }
      const reader = new FileReader();
      reader.onload = function () {
        const dataURL = reader.result;
        document.getElementById("profile-img").src = dataURL;
        localStorage.setItem("profileImage", dataURL);
      };
      reader.readAsDataURL(file);
    }

    function setupDragDrop() {
      const dropArea = document.getElementById("image-drop");
      dropArea.addEventListener("dragover", e => {
        e.preventDefault();
        dropArea.classList.add("border-blue-500");
      });
      dropArea.addEventListener("dragleave", () => {
        dropArea.classList.remove("border-blue-500");
      });
      dropArea.addEventListener("drop", e => {
        e.preventDefault();
        dropArea.classList.remove("border-blue-500");
        const file = e.dataTransfer.files[0];
        previewImage({ target: { files: [file] } });
      });
    }

    function validateForm() {
      const requiredFields = ["name", "title"];
      let valid = true;
      requiredFields.forEach(id => {
        const input = document.getElementsByName(id)[0];
        if (!input.value.trim()) {
          input.classList.add("border-red-500");
          valid = false;
        }
      });
      if (valid) exportPDF();
    }

    function switchLayout(layout) {
      document.body.setAttribute("data-layout", layout);
      localStorage.setItem("resumeLayout", layout);
    }

    window.onload = function () {
      loadData();
      setupDragDrop();
      const savedLayout = localStorage.getItem("resumeLayout") || "classic";
      switchLayout(savedLayout);
    }
  </script>
</head>
<body data-layout="classic" class="flex flex-col min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 transition-colors">

  <?php include 'include/header.php'; ?>

  <!-- MAIN CONTENT -->
  <main class="flex-grow px-8 py-12">
    <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow hidden" id="success-popup">PDF downloaded successfully!</div>

    <div class="flex justify-between items-center mb-6 max-w-7xl mx-auto">
      <h1 class="text-3xl font-bold text-gray-800 ">Resume Builder</h1>
      <div class="space-x-2">
        <select onchange="switchLayout(this.value)" class="input-field w-auto">
          <option value="classic">Classic</option>
          <option value="modern">Modern</option>
          <option value="minimal">Minimal</option>
        </select>
        <button onclick="toggleDarkMode()" class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">Toggle Dark Mode</button>
        <button onclick="clearAll()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Clear All</button>
      </div>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10">
      <!-- FORM -->
      <form onsubmit="event.preventDefault(); validateForm();" class="space-y-4">
        <input type="text" name="name" placeholder="Full Name" class="input-field" oninput="updatePreview('name')">
        <input type="text" name="title" placeholder="Job Title" class="input-field" oninput="updatePreview('title')">
        <input type="email" name="email" placeholder="Email Address" class="input-field" oninput="updatePreview('email')">
        <input type="tel" name="phone" placeholder="Phone Number" class="input-field" oninput="updatePreview('phone')">
        <input type="text" name="linkedin" placeholder="LinkedIn Profile" class="input-field" oninput="updatePreview('linkedin')">
        <input type="text" name="website" placeholder="Portfolio/Website" class="input-field" oninput="updatePreview('website')">
        <textarea name="summary" placeholder="Professional Summary" rows="3" class="input-field" oninput="updatePreview('summary')"></textarea>
        <textarea name="skills" placeholder="Skills (comma-separated)" rows="3" class="input-field" oninput="updatePreview('skills')"></textarea>
        <textarea name="experience" placeholder="Work Experience" rows="5" class="input-field" oninput="updatePreview('experience')"></textarea>
        <textarea name="education" placeholder="Education" rows="4" class="input-field" oninput="updatePreview('education')"></textarea>
        <textarea name="certifications" placeholder="Certifications" rows="3" class="input-field" oninput="updatePreview('certifications')"></textarea>
        <textarea name="languages" placeholder="Languages" rows="2" class="input-field" oninput="updatePreview('languages')"></textarea>
        <div id="image-drop" class="border-2 border-dashed border-gray-400 p-4 rounded text-center hover:border-blue-400 transition cursor-pointer">
          <p class="text-sm text-gray-500">Drag & drop your image here or click to select</p>
          <input type="file" accept="image/*" onchange="previewImage(event)" class="mt-2 block mx-auto text-sm">
        </div>
        <button type="submit" class="w-full py-3 bg-blue-600 text-white font-semibold rounded-xl shadow hover:bg-blue-700 transition">Download PDF</button>
      </form>

      <!-- PREVIEW -->
      <div class="bg-white p-6 rounded-xl shadow space-y-4">
        <img id="profile-img" src="" alt="Profile Photo" class="w-32 h-32 object-cover rounded-full mb-4">
        <h2 class="text-2xl font-bold" id="p-name"></h2>
        <p class="text-lg text-blue-600" id="p-title"></p>
        <p class="text-sm text-gray-600" id="p-email"></p>
        <p class="text-sm text-gray-600" id="p-phone"></p>
        <p class="text-sm text-blue-500" id="p-linkedin"></p>
        <p class="text-sm text-blue-500" id="p-website"></p>
        <div><h3 class="text-lg font-semibold">Summary</h3><p class="text-sm" id="p-summary"></p></div>
        <div><h3 class="text-lg font-semibold">Skills</h3><p class="text-sm" id="p-skills"></p></div>
        <div><h3 class="text-lg font-semibold">Experience</h3><p class="text-sm whitespace-pre-line" id="p-experience"></p></div>
        <div><h3 class="text-lg font-semibold">Education</h3><p class="text-sm whitespace-pre-line" id="p-education"></p></div>
        <div><h3 class="text-lg font-semibold">Certifications</h3><p class="text-sm whitespace-pre-line" id="p-certifications"></p></div>
        <div><h3 class="text-lg font-semibold">Languages</h3><p class="text-sm whitespace-pre-line" id="p-languages"></p></div>
      </div>
    </div>
  </main>

  <?php include 'include/footer.php'; ?>

</body>
</html>
