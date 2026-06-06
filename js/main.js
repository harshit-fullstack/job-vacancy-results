 
    const slider = document.getElementById('jobsSlider');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    const scrollAmount = 300; // pixels per click

    nextBtn.addEventListener('click', () => {
      slider.scrollBy({
        left: scrollAmount,
        behavior: 'smooth'
      });
    });

    prevBtn.addEventListener('click', () => {
      slider.scrollBy({
        left: -scrollAmount,
        behavior: 'smooth'
      });
    });
  

 
    // Data
   const testimonials = [
  {
    id: 1,
    name: "Ayesha Rahman",
    role: "Marketing Graduate",
    image: "https://example.com/pexels-ayesha.jpg", // Replace with real URL from Pexels or your chosen source
    text: "JobVacancyResult helped me land my first marketing role within weeks. The job alerts were spot-on, and I felt more confident applying through the platform."
  },
  {
    id: 2,
    name: "Daniel Smith",
    role: "Software Engineer",
    image: "https://example.com/freeimages-daniel.jpg", // Use your curated free image link
    text: "What I loved most was how easy it was to search and apply. The site filtered exactly what I was looking for—no more wasting time scrolling through irrelevant postings."
  },
  {
    id: 3,
    name: "Nusrat Jahan",
    role: "HR Professional",
    image: "https://example.com/pexels-nusrat.jpg", // Insert another royalty-free portrait
    text: "After months of looking elsewhere, I finally found a company that valued my skills. The application process through JobVacancyResult was smooth and professional."
  },
  
];


    const categories = [{
        id: 1,
        name: "Development & IT",
        positions: 16,
        icon: "fas fa-code"
      },
      {
        id: 2,
        name: "Design & Creative",
        positions: 12,
        icon: "fas fa-lightbulb"
      },
      {
        id: 3,
        name: "Marketing & Sales",
        positions: 9,
        icon: "fas fa-bullhorn"
      },
      {
        id: 4,
        name: "Writing & Translation",
        positions: 2,
        icon: "fas fa-pen-nib"
      }
    ];

    // Pagination variables replaced by infinite scroll

    const JOBS_PER_PAGE = 6;
    let currentPage = 1;
    let jobs = [];
    let filteredJobs = [];
    let loadingMore = false;
    let allJobsLoaded = false;

    // Add this variable to store all jobs
    let allJobs = [];

    // Favorites storage key
    const FAVORITES_KEY = 'jvr_favorites';
    // Recent searches storage key
    const RECENT_SEARCHES_KEY = 'jvr_recent_searches';
    // Recent viewed jobs key
    const RECENT_VIEWED_KEY = 'jvr_recent_viewed';

    // Elements
    const jobsGrid = document.getElementById('jobsGrid');
    const categoriesGrid = document.getElementById('categoriesGrid');
    const testimonialsGrid = document.getElementById('testimonialsGrid');
    const backToTopBtn = document.getElementById('backToTopBtn');
    const darkModeToggle = document.getElementById('darkModeToggle');
    const jobInput = document.getElementById('job');
    const locationInput = document.getElementById('location');
    const experienceInput = document.getElementById('experience');
    const recentSearchesContainer = document.getElementById('recentSearches');
    const favoritesContainer = document.getElementById('favoritesContainer');
    const favoritesGrid = document.getElementById('favoritesGrid');
    const jobAlertSubscribeBtn = document.getElementById('jobAlertSubscribeBtn');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalCloseBtn = document.getElementById('modalCloseBtn');
    const jobAlertForm = document.getElementById('jobAlertForm');
    const emailInput = document.getElementById('emailInput');
    const emailHelp = document.getElementById('emailHelp');
    const searchForm = document.getElementById('searchForm');

    // Chatbot elements
    const chatbotBtn = document.getElementById('chatbotBtn');
    const chatbotWindow = document.getElementById('chatbotWindow');
    const chatbotCloseBtn = document.getElementById('chatbotCloseBtn');
    const chatbotMessages = document.getElementById('chatbotMessages');
    const chatbotInput = document.getElementById('chatbotInput');
    const chatbotSendBtn = document.getElementById('chatbotSendBtn');

    // Utility: debounce
    function debounce(func, wait) {
      let timeout;
      return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
      };
    }

    // Toast notifications
    function showToast(message, duration = 4000) {
      let toastContainer = document.getElementById('toast-container');
      if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        document.body.appendChild(toastContainer);
      }
      const toast = document.createElement('div');
      toast.className = 'toast';
      toast.textContent = message;
      toastContainer.appendChild(toast);
      setTimeout(() => {
        toast.remove();
      }, duration);
    }

    // Dark mode toggle
    function loadTheme() {
      const savedTheme = localStorage.getItem('jvr_theme');
      if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
        updateDarkModeIcon(savedTheme);
      } else {
        // Default light
        document.documentElement.setAttribute('data-theme', 'light');
        updateDarkModeIcon('light');
      }
    }

    function updateDarkModeIcon(theme) {
      if (!darkModeToggle) return;
      if (theme === 'dark') {
        darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        darkModeToggle.setAttribute('aria-label', 'Switch to light mode');
        darkModeToggle.setAttribute('title', 'Switch to light mode');
      } else {
        darkModeToggle.innerHTML = '<i class="fas fa-moon"></i>';
        darkModeToggle.setAttribute('aria-label', 'Switch to dark mode');
        darkModeToggle.setAttribute('title', 'Switch to dark mode');
      }
    }

    if (darkModeToggle) {
      darkModeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('jvr_theme', newTheme);
        updateDarkModeIcon(newTheme);
      });
    }

    // Sticky search bar on scroll
    if (searchForm) {
      const stickyOffset = searchForm.offsetTop;
      window.addEventListener('scroll', () => {
        if (window.pageYOffset > stickyOffset) {
          searchForm.classList.add('sticky', 'top-0', 'bg-white', 'z-40', 'shadow-md');
        } else {
          searchForm.classList.remove('sticky', 'top-0', 'bg-white', 'z-40', 'shadow-md');
        }
      });
    }

    // Render categories
    function renderCategories() {
      categoriesGrid.innerHTML = '';
      categories.forEach(category => {
        const catCard = document.createElement('button');
        catCard.className = 'category-card scroll-reveal';
        catCard.setAttribute('aria-label', `${category.name} category with ${category.positions} open position${category.positions !== 1 ? 's' : ''}`);
        catCard.type = 'button';
        catCard.innerHTML = `
          <div class="category-icon"><i class="${category.icon}"></i></div>
          <p class="text-lg font-semibold text-blue-900 mb-2">${category.name}</p>
          <p class="text-xs text-blue-700 mt-2">${category.positions} position${category.positions !== 1 ? 's' : ''}</p>
        `;
        catCard.addEventListener('click', () => {
          // Filter by category name in title or badges
          filteredJobs = jobs.filter(job =>
            (job.title && job.title.toLowerCase().includes(category.name.toLowerCase())) ||
            (job.badges && job.badges.some(b => b.toLowerCase() === category.name.toLowerCase()))
          );
          currentPage = 1;
          allJobsLoaded = false;
          renderJobsPage(currentPage, false);
          window.scrollTo({
            top: 0,
            behavior: 'smooth'
          });
        });
        categoriesGrid.appendChild(catCard);
      });
      applyScrollReveal();
    }

    // Scroll reveal animations using Intersection Observer
    function applyScrollReveal() {
      const revealElements = document.querySelectorAll('.scroll-reveal:not(.visible)');
      const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            obs.unobserve(entry.target);
          }
        });
      }, {
        threshold: 0.15
      });
      revealElements.forEach(el => observer.observe(el));
    }

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

    // Save and load recent searches
    function saveRecentSearch(search) {
      if (!search) return;
      let recent = JSON.parse(localStorage.getItem(RECENT_SEARCHES_KEY)) || [];
      recent = recent.filter(s => s.toLowerCase() !== search.toLowerCase());
      recent.unshift(search);
      if (recent.length > 5) recent.pop();
      localStorage.setItem(RECENT_SEARCHES_KEY, JSON.stringify(recent));
      renderRecentSearches();
    }

    function renderRecentSearches() {
      const recent = JSON.parse(localStorage.getItem(RECENT_SEARCHES_KEY)) || [];
      if (recent.length === 0) {
        recentSearchesContainer.innerHTML = '';
        return;
      }
      recentSearchesContainer.innerHTML = '<span class="font-semibold mr-2">Recent Searches:</span>' + recent.map(s => `<button type="button" aria-label="Search for ${s}" class="text-blue-700 underline hover:text-blue-900">${s}</button>`).join('');
      recentSearchesContainer.querySelectorAll('button').forEach(btn => {
        btn.addEventListener('click', () => {
          jobInput.value = btn.textContent;
          handleSubmit(new Event('submit'));
        });
      });
    }

    // Favorite jobs management
    function getFavorites() {
      return JSON.parse(localStorage.getItem(FAVORITES_KEY)) || [];
    }

    function saveFavorites(favs) {
      localStorage.setItem(FAVORITES_KEY, JSON.stringify(favs));
    }

    function toggleFavorite(jobId) {
      let favs = getFavorites();
      const idx = favs.indexOf(jobId);
      if (idx === -1) {
        favs.push(jobId);
        showToast('Added to favorites ❤️');
      } else {
        favs.splice(idx, 1);
        showToast('Removed from favorites');
      }
      saveFavorites(favs);
      updateFavoriteButtons();
      renderFavorites();
    }

    function updateFavoriteButtons() {
      const favs = getFavorites();
      document.querySelectorAll('.favorite-btn').forEach(btn => {
        const card = btn.closest('.card');
        if (!card) return;
        const jobId = parseInt(card.dataset.jobId, 10);
        if (favs.includes(jobId)) {
          btn.classList.add('favorited');
          btn.title = 'Remove from favorites';
          btn.querySelector('i').classList.remove('far');
          btn.querySelector('i').classList.add('fas');
        } else {
          btn.classList.remove('favorited');
          btn.title = 'Add to favorites';
          btn.querySelector('i').classList.remove('fas');
          btn.querySelector('i').classList.add('far');
        }
      });
    }

    function restoreFavorites() {
      // Attach event listeners to favorite buttons
      document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.onclick = () => {
          const card = btn.closest('.card');
          if (!card) return;
          const jobId = parseInt(card.dataset.jobId, 10);
          toggleFavorite(jobId);
        };
      });
      updateFavoriteButtons();
    }

    // Render favorites section
    function renderFavorites() {
      const favs = getFavorites();
      if (favs.length === 0) {
        favoritesContainer.hidden = true;
        favoritesGrid.innerHTML = '';
        return;
      }
      favoritesContainer.hidden = false;
      favoritesGrid.innerHTML = '';
      favs.forEach(id => {
        const job = jobs.find(j => j.id === id);
        if (!job) return;
        const favCard = document.createElement('div');
        favCard.className = 'card group bg-white rounded-lg shadow-md p-4 flex flex-col justify-between';
        favCard.setAttribute('tabindex', '0');
        favCard.setAttribute('aria-label', `${job.title} at ${job.company_id} in ${job.location}, ${job.type} job`);
        favCard.dataset.jobId = job.id;
        favCard.innerHTML = `
          <div class="flex justify-between mb-3 relative z-20 flex-wrap gap-1 items-center">
            <div class="flex space-x-2 items-center flex-wrap gap-1">
              ${(job.badges||[]).map(badge => `<span class="badge-${(badge||'').toLowerCase().replace(' ', '')}">${badge||''}</span>`).join('')}
            </div>
            <button aria-label="Remove favorite for ${job.title} at ${job.company}" class="favorite-btn favorited" title="Remove from favorites" type="button">
              <i class="fas fa-heart"></i>
            </button>
          </div>
          <div class="flex justify-center mb-4 relative z-20">
            <img alt="${job.company_id} logo" class="w-20 h-20 object-contain rounded-md shadow-md transition-transform duration-300 group-hover:scale-110" height="80" src="${job.logo || 'https://placehold.co/80x80/png?text=Logo'}" width="80" />
          </div>
          <p class="text-center text-xs font-semibold mb-1 relative z-20 text-blue-800">${job.company_id}</p>
          <p class="text-center text-sm font-semibold mb-2 relative z-20 text-blue-900">${job.title}</p>
          <p class="text-center text-xs text-gray-600 mb-2 flex justify-center items-center gap-1 relative z-20">
            <i class="fas fa-map-marker-alt text-xs text-blue-600"></i> ${job.location}
          </p>
          <p class="text-center text-xs text-gray-600 mb-3 relative z-20 font-semibold">Experience: ${job.experience}</p>
          <div class="flex justify-center gap-2 mb-4 flex-wrap relative z-20">
            ${(job.skills||[]).map(skill => `<span class="text-[9px] border border-blue-300 rounded px-2 py-[2px] text-blue-700 uppercase tracking-wide hover:bg-blue-600 hover:text-white transition transform hover:scale-110 cursor-pointer">${skill}</span>`).join('')}
          </div>
          <div class="flex justify-between items-center text-sm font-semibold relative z-20 text-blue-900">
            <span><span class="font-bold">${job.salary}</span> \\Year</span>
            <a href="${job.job_link || '#'}" target="_blank" class="bg-gradient-to-r from-green-400 to-green-600 text-white text-xs rounded px-3 py-1 uppercase tracking-wide font-semibold hover:from-green-500 hover:to-green-700 transition shadow-sm transform hover:scale-105" type="button">
              Quick Apply
            </a>
          </div>
        `;
        favoritesGrid.appendChild(favCard);
      });
      // Attach event listeners to favorite buttons in favorites section
      favoritesGrid.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.onclick = () => {
          const card = btn.closest('.card');
          if (!card) return;
          const jobId = parseInt(card.dataset.jobId, 10);
          toggleFavorite(jobId);
        };
      });
    }


    // Attach social share buttons
    function attachShareButtons() {
      document.querySelectorAll('.social-share button').forEach(btn => {
        btn.onclick = (e) => {
          const card = e.target.closest('.card');
          if (!card) return;
          const jobId = card.dataset.jobId;
          const job = jobs.find(j => j.id.toString() === jobId);
          if (!job) return;
          const url = encodeURIComponent(window.location.href);
          const title = encodeURIComponent(`${job.title} at ${job.company}`);
          let shareUrl = '';
          if (btn.classList.contains('share-facebook')) {
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
          } else if (btn.classList.contains('share-twitter')) {
            shareUrl = `https://twitter.com/intent/tweet?text=${title}&url=${url}`;
          } else if (btn.classList.contains('share-linkedin')) {
            shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
          }
          if (shareUrl) {
            window.open(shareUrl, '_blank', 'width=600,height=400');
          }
        };
      });
    }

    // Track recently viewed jobs in localStorage
    function attachJobViewTracking() {
      document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('click', () => {
          const jobId = parseInt(card.dataset.jobId, 10);
          if (!jobId) return;
          let recentViewed = JSON.parse(localStorage.getItem(RECENT_VIEWED_KEY)) || [];
          recentViewed = recentViewed.filter(id => id !== jobId);
          recentViewed.unshift(jobId);
          if (recentViewed.length > 5) recentViewed.pop();
          localStorage.setItem(RECENT_VIEWED_KEY, JSON.stringify(recentViewed));
        });
      });
    }

    // Chatbot logic (fake simple assistant)
    chatbotBtn.addEventListener('click', () => {
      chatbotWindow.style.display = 'flex';
      chatbotInput.focus();
    });

    chatbotCloseBtn.addEventListener('click', () => {
      chatbotWindow.style.display = 'none';
    });

    function addChatMessage(text, sender = 'bot') {
      const msg = document.createElement('div');
      msg.className = `chatbot-message ${sender}`;
      msg.textContent = text;
      chatbotMessages.appendChild(msg);
      chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    function handleChatInput() {
      const text = chatbotInput.value.trim();
      if (!text) return;
      addChatMessage(text, 'user');
      chatbotInput.value = '';
      setTimeout(() => {
        // Simple canned responses
        const lower = text.toLowerCase();
        if (lower.includes('hello') || lower.includes('hi')) {
          addChatMessage('Hello! How can I assist you with your job search today?');
        } else if (lower.includes('job') || lower.includes('apply')) {
          addChatMessage('You can search for jobs using the search bar above. Let me know if you need help filtering jobs.');
        } else if (lower.includes('contact')) {
          addChatMessage('You can contact us via the Contact page or email support@jobvacancyresult.com.');
        } else {
          addChatMessage('Sorry, I am a simple assistant. Please try asking about job search or contact info.');
        }
      }, 800);
    }

    chatbotSendBtn.addEventListener('click', handleChatInput);
    chatbotInput.addEventListener('keydown', e => {
      if (e.key === 'Enter') {
        e.preventDefault();
        handleChatInput();
      }
    });

    // Job alert modal
    jobAlertSubscribeBtn.addEventListener('click', () => {
      modalOverlay.classList.add('active');
      emailInput.value = '';
      emailHelp.classList.add('hidden');
      emailInput.focus();
    });

    modalCloseBtn.addEventListener('click', () => {
      modalOverlay.classList.remove('active');
    });

    modalOverlay.addEventListener('click', e => {
      if (e.target === modalOverlay) {
        modalOverlay.classList.remove('active');
      }
    });

    jobAlertForm.addEventListener('submit', e => {
      e.preventDefault();
      const email = emailInput.value.trim();
      if (!validateEmail(email)) {
        emailHelp.classList.remove('hidden');
        emailInput.focus();
        return;
      }
      emailHelp.classList.add('hidden');
      modalOverlay.classList.remove('active');
      showToast('Subscribed to job alerts!');
    });

    function validateEmail(email) {
      // Simple email regex
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // Initialize all
    function init() {
      loadTheme();
      renderCategories();
      renderTestimonials();
      renderFavorites();
      renderRecentSearches();
    }

    // Event listeners
    if (searchForm) {
      searchForm.addEventListener('submit', handleSubmit);
    }
    document.addEventListener('DOMContentLoaded', init);
  
 
    // Add this after your other JS variables
    const clearSearchBtn = document.getElementById('clearSearchBtn');

    if (clearSearchBtn) {
      clearSearchBtn.addEventListener('click', () => {
        // Clear input values
        if (jobInput) jobInput.value = '';
        if (locationInput) locationInput.value = '';
        if (experienceInput) experienceInput.value = '';
        // Remove query params and reload page
        window.location.href = window.location.pathname;
      });
    }
  
 
    document.querySelectorAll('.share-job').forEach(button => {
      button.addEventListener('click', () => {
        const card = button.closest('.card');
        if (!card) return;

        const jobTitle = card.querySelector('p.text-sm.font-semibold').textContent.trim();
        const companyName = card.querySelector('p.text-xs.font-semibold').textContent.trim();
        const jobLocation = card.querySelector('p.text-xs.text-gray-600').textContent.trim();
        const jobLink = card.querySelector('a').href;

        const shareData = {
          title: `${jobTitle} at ${companyName}`,
          text: `Check out this job opportunity at ${companyName} located in ${jobLocation}.`,
          url: jobLink
        };

        if (navigator.share) {
          navigator.share(shareData)
            .then(() => console.log('Job shared successfully'))
            .catch(error => console.error('Error sharing job:', error));
        } else {
          alert('Sharing is not supported on this browser.');
        }
      });
    });
  

 
    // Modern Courses Data and Functionality
