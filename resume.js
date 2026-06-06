// Resume Builder JavaScript
class ResumeBuilder {
  constructor() {
    this.experienceCount = 0;
    this.educationCount = 0;
    this.certificationCount = 0;
    this.projectCount = 0;
    this.profileImageUrl = '';
    
    this.initializeEventListeners();
    this.addInitialExperience();
    this.addInitialEducation();
  }

  initializeEventListeners() {
    // Form submission
    document.getElementById('resumeForm').addEventListener('submit', (e) => {
      e.preventDefault();
      this.generateResume();
    });

    // Add section buttons
    document.getElementById('addExperienceBtn').addEventListener('click', () => {
      this.addExperience();
    });

    document.getElementById('addEducationBtn').addEventListener('click', () => {
      this.addEducation();
    });

    document.getElementById('addCertificationBtn').addEventListener('click', () => {
      this.addCertification();
    });

    document.getElementById('addProjectBtn').addEventListener('click', () => {
      this.addProject();
    });

    // Modal controls
    document.getElementById('closeModalBtn').addEventListener('click', () => {
      this.closeModal();
    });

    document.getElementById('resumeModal').addEventListener('click', (e) => {
      if (e.target.id === 'resumeModal') {
        this.closeModal();
      }
    });

    // Action buttons
    document.getElementById('downloadPdfBtn').addEventListener('click', () => {
      this.downloadPDF();
    });

    document.getElementById('printBtn').addEventListener('click', () => {
      this.printResume();
    });

    // Profile image upload
    document.getElementById('profileImageUpload').addEventListener('change', (e) => {
      this.handleImageUpload(e);
    });

    // Escape key to close modal
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        this.closeModal();
      }
    });
  }

  addInitialExperience() {
    this.addExperience();
  }

  addInitialEducation() {
    this.addEducation();
  }

  addExperience() {
    this.experienceCount++;
    const container = document.getElementById('experienceContainer');
    const experienceDiv = document.createElement('div');
    experienceDiv.className = 'experience-entry border border-gray-200 rounded-lg p-4 bg-gray-50';
    experienceDiv.innerHTML = `
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Experience ${this.experienceCount}</h3>
        <button type="button" class="text-red-600 hover:text-red-800 focus:outline-none" onclick="resumeBuilder.removeExperience(this)">
          <i class="fas fa-trash"></i>
        </button>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-700 font-medium mb-1">Job Title</label>
          <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                 name="expJobTitle[]" placeholder="Senior Developer" required>
        </div>
        <div>
          <label class="block text-gray-700 font-medium mb-1">Company</label>
          <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                 name="expCompany[]" placeholder="Tech Corp" required>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
          <label class="block text-gray-700 font-medium mb-1">Start Date</label>
          <input type="month" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                 name="expStartDate[]" required>
        </div>
        <div>
          <label class="block text-gray-700 font-medium mb-1">End Date</label>
          <input type="month" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                 name="expEndDate[]">
          <div class="mt-2">
            <label class="inline-flex items-center">
              <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600" name="expCurrent[]" onchange="resumeBuilder.toggleCurrentJob(this)">
              <span class="ml-2 text-sm text-gray-600">Current Position</span>
            </label>
          </div>
        </div>
      </div>
      <div class="mt-4">
        <label class="block text-gray-700 font-medium mb-1">Job Description</label>
        <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" 
                  name="expDescription[]" rows="3" 
                  placeholder="• Led development of web applications using React and Node.js&#10;• Collaborated with cross-functional teams to deliver projects on time&#10;• Mentored junior developers and conducted code reviews"></textarea>
      </div>
    `;
    container.appendChild(experienceDiv);
  }

  addProject() {
    this.projectCount++;
    const container = document.getElementById('projectsContainer');
    const projectDiv = document.createElement('div');
    projectDiv.className = 'project-entry border border-gray-200 rounded-lg p-4 bg-gray-50';
    projectDiv.innerHTML = `
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Project ${this.projectCount}</h3>
        <button type="button" class="text-red-600 hover:text-red-800 focus:outline-none" onclick="resumeBuilder.removeProject(this)">
          <i class="fas fa-trash"></i>
        </button>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-700 font-medium mb-1">Project Name</label>
          <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" 
                 name="projectName[]" placeholder="E-commerce Platform" required>
        </div>
        <div>
          <label class="block text-gray-700 font-medium mb-1">Technologies Used</label>
          <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" 
                 name="projectTech[]" placeholder="React, Node.js, MongoDB" required>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
          <label class="block text-gray-700 font-medium mb-1">Project URL (Optional)</label>
          <input type="url" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" 
                 name="projectUrl[]" placeholder="https://project-demo.com">
        </div>
        <div>
          <label class="block text-gray-700 font-medium mb-1">GitHub URL (Optional)</label>
          <input type="url" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500" 
                 name="projectGithub[]" placeholder="https://github.com/username/project">
        </div>
      </div>
      <div class="mt-4">
        <label class="block text-gray-700 font-medium mb-1">Project Description</label>
        <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 resize-none" 
                  name="projectDescription[]" rows="3" 
                  placeholder="• Built a full-stack e-commerce platform with user authentication&#10;• Implemented payment processing using Stripe API&#10;• Deployed on AWS with CI/CD pipeline"></textarea>
      </div>
    `;
    container.appendChild(projectDiv);
  }

  addEducation() {
    this.educationCount++;
    const container = document.getElementById('educationContainer');
    const educationDiv = document.createElement('div');
    educationDiv.className = 'education-entry border border-gray-200 rounded-lg p-4 bg-gray-50';
    educationDiv.innerHTML = `
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Education ${this.educationCount}</h3>
        <button type="button" class="text-red-600 hover:text-red-800 focus:outline-none" onclick="resumeBuilder.removeEducation(this)">
          <i class="fas fa-trash"></i>
        </button>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-700 font-medium mb-1">Degree</label>
          <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" 
                 name="eduDegree[]" placeholder="Bachelor of Science" required>
        </div>
        <div>
          <label class="block text-gray-700 font-medium mb-1">Field of Study</label>
          <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" 
                 name="eduField[]" placeholder="Computer Science" required>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
          <label class="block text-gray-700 font-medium mb-1">School/University</label>
          <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" 
                 name="eduSchool[]" placeholder="University of Technology" required>
        </div>
        <div>
          <label class="block text-gray-700 font-medium mb-1">Graduation Year</label>
          <input type="number" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" 
                 name="eduYear[]" min="1950" max="2030" placeholder="2020">
        </div>
      </div>
      <div class="mt-4">
        <label class="block text-gray-700 font-medium mb-1">Additional Details (Optional)</label>
        <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 resize-none" 
                  name="eduDetails[]" rows="2" 
                  placeholder="GPA: 3.8/4.0, Dean's List, Relevant coursework: Data Structures, Algorithms"></textarea>
      </div>
    `;
    container.appendChild(educationDiv);
  }

  addCertification() {
    this.certificationCount++;
    const container = document.getElementById('certificationsContainer');
    const certificationDiv = document.createElement('div');
    certificationDiv.className = 'certification-entry border border-gray-200 rounded-lg p-4 bg-gray-50';
    certificationDiv.innerHTML = `
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Certification ${this.certificationCount}</h3>
        <button type="button" class="text-red-600 hover:text-red-800 focus:outline-none" onclick="resumeBuilder.removeCertification(this)">
          <i class="fas fa-trash"></i>
        </button>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-gray-700 font-medium mb-1">Certification Name</label>
          <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" 
                 name="certName[]" placeholder="AWS Certified Developer" required>
        </div>
        <div>
          <label class="block text-gray-700 font-medium mb-1">Issuing Organization</label>
          <input type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" 
                 name="certIssuer[]" placeholder="Amazon Web Services" required>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
          <label class="block text-gray-700 font-medium mb-1">Issue Date</label>
          <input type="month" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" 
                 name="certDate[]">
        </div>
        <div>
          <label class="block text-gray-700 font-medium mb-1">Expiry Date (Optional)</label>
          <input type="month" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" 
                 name="certExpiry[]">
        </div>
      </div>
    `;
    container.appendChild(certificationDiv);
  }

  removeExperience(button) {
    button.closest('.experience-entry').remove();
  }

  removeEducation(button) {
    button.closest('.education-entry').remove();
  }

  removeCertification(button) {
    button.closest('.certification-entry').remove();
  }

  removeProject(button) {
    button.closest('.project-entry').remove();
  }

  toggleCurrentJob(checkbox) {
    const endDateInput = checkbox.closest('.experience-entry').querySelector('input[name="expEndDate[]"]');
    if (checkbox.checked) {
      endDateInput.value = '';
      endDateInput.disabled = true;
      endDateInput.style.backgroundColor = '#f3f4f6';
    } else {
      endDateInput.disabled = false;
      endDateInput.style.backgroundColor = 'white';
    }
  }

  handleImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        this.profileImageUrl = e.target.result;
        const preview = document.getElementById('profileImagePreview');
        preview.src = this.profileImageUrl;
        preview.classList.remove('hidden');
      };
      reader.readAsDataURL(file);
    }
  }

  formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString + '-01');
    return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
  }

  generateResume() {
    const formData = new FormData(document.getElementById('resumeForm'));
    
    // Validate required fields
    const requiredFields = ['fullName', 'jobTitle'];
    let isValid = true;
    
    requiredFields.forEach(field => {
      const input = document.getElementById(field);
      if (!input.value.trim()) {
        input.classList.add('border-red-500');
        isValid = false;
      } else {
        input.classList.remove('border-red-500');
      }
    });

    if (!isValid) {
      alert('Please fill in all required fields (marked with *)');
      return;
    }

    // Generate resume HTML
    const resumeHTML = this.buildResumeHTML(formData);
    
    // Show in modal
    document.getElementById('resumePreview').innerHTML = resumeHTML;
    document.getElementById('resumeModal').classList.remove('hidden');
    
    // Enable action buttons
    document.getElementById('downloadPdfBtn').disabled = false;
    document.getElementById('printBtn').disabled = false;
  }

  buildResumeHTML(formData) {
    const fullName = formData.get('fullName');
    const jobTitle = formData.get('jobTitle');
    const location = formData.get('location');
    const email = formData.get('email');
    const phone = formData.get('phone');
    const linkedin = formData.get('linkedin');
    const github = formData.get('github');
    const website = formData.get('website');
    const summary = formData.get('summary');
    const skills = formData.get('skills');

    let html = `
      <div class="profile-section">
        ${this.profileImageUrl ? `<img src="${this.profileImageUrl}" alt="Profile" class="profile-image">` : ''}
        <div class="profile-text">
          <h1>${fullName}</h1>
          <div class="job-title">${jobTitle}</div>
          <div class="contact-info">
            ${location ? `<div><i class="fas fa-map-marker-alt"></i> ${location}</div>` : ''}
            ${email ? `<div><i class="fas fa-envelope"></i> <a href="mailto:${email}">${email}</a></div>` : ''}
            ${phone ? `<div><i class="fas fa-phone"></i> ${phone}</div>` : ''}
            ${linkedin ? `<div><i class="fab fa-linkedin"></i> <a href="${linkedin}" target="_blank">LinkedIn</a></div>` : ''}
            ${github ? `<div><i class="fab fa-github"></i> <a href="${github}" target="_blank">GitHub</a></div>` : ''}
            ${website ? `<div><i class="fas fa-globe"></i> <a href="${website}" target="_blank">Portfolio</a></div>` : ''}
          </div>
        </div>
      </div>
    `;

    if (summary) {
      html += `
        <div class="section-title">Professional Summary</div>
        <p>${summary.replace(/\n/g, '<br>')}</p>
      `;
    }

    if (skills) {
      const skillsArray = skills.split(',').map(skill => skill.trim()).filter(skill => skill);
      html += `
        <div class="section-title">Skills</div>
        <div class="skills-list">
          ${skillsArray.map(skill => `<span class="skill-tag">${skill}</span>`).join('')}
        </div>
      `;
    }

    // Experience section
    const expJobTitles = formData.getAll('expJobTitle[]');
    if (expJobTitles.length > 0 && expJobTitles[0]) {
      html += `<div class="section-title">Professional Experience</div>`;
      
      const expCompanies = formData.getAll('expCompany[]');
      const expStartDates = formData.getAll('expStartDate[]');
      const expEndDates = formData.getAll('expEndDate[]');
      const expCurrents = formData.getAll('expCurrent[]');
      const expDescriptions = formData.getAll('expDescription[]');

      expJobTitles.forEach((title, index) => {
        if (title && expCompanies[index]) {
          const startDate = this.formatDate(expStartDates[index]);
          const endDate = expCurrents.includes('on') && expCurrents[index] ? 'Present' : this.formatDate(expEndDates[index]);
          const duration = startDate + (endDate ? ` - ${endDate}` : '');

          html += `
            <div class="experience-item">
              <div class="item-header">
                <div>
                  <div class="item-title">${title}</div>
                  <div class="item-company">${expCompanies[index]}</div>
                </div>
                <div class="item-duration">${duration}</div>
              </div>
              ${expDescriptions[index] ? `<div class="item-description">${expDescriptions[index].replace(/\n/g, '<br>')}</div>` : ''}
            </div>
          `;
        }
      });
    }

    // Projects section
    const projectNames = formData.getAll('projectName[]');
    if (projectNames.length > 0 && projectNames[0]) {
      html += `<div class="section-title">Projects</div>`;
      
      const projectTechs = formData.getAll('projectTech[]');
      const projectUrls = formData.getAll('projectUrl[]');
      const projectGithubs = formData.getAll('projectGithub[]');
      const projectDescriptions = formData.getAll('projectDescription[]');

      projectNames.forEach((name, index) => {
        if (name && projectTechs[index]) {
          html += `
            <div class="project-item">
              <div class="item-header">
                <div>
                  <div class="item-title">${name}</div>
                  <div class="project-tech">${projectTechs[index]}</div>
                </div>
                <div class="project-links">
                  ${projectUrls[index] ? `<a href="${projectUrls[index]}" target="_blank" class="project-link"><i class="fas fa-external-link-alt"></i> Demo</a>` : ''}
                  ${projectGithubs[index] ? `<a href="${projectGithubs[index]}" target="_blank" class="project-link"><i class="fab fa-github"></i> Code</a>` : ''}
                </div>
              </div>
              ${projectDescriptions[index] ? `<div class="item-description">${projectDescriptions[index].replace(/\n/g, '<br>')}</div>` : ''}
            </div>
          `;
        }
      });
    }

    // Education section
    const eduDegrees = formData.getAll('eduDegree[]');
    if (eduDegrees.length > 0 && eduDegrees[0]) {
      html += `<div class="section-title">Education</div>`;
      
      const eduFields = formData.getAll('eduField[]');
      const eduSchools = formData.getAll('eduSchool[]');
      const eduYears = formData.getAll('eduYear[]');
      const eduDetails = formData.getAll('eduDetails[]');

      eduDegrees.forEach((degree, index) => {
        if (degree && eduSchools[index]) {
          html += `
            <div class="education-item">
              <div class="item-header">
                <div>
                  <div class="item-title">${degree}${eduFields[index] ? ` in ${eduFields[index]}` : ''}</div>
                  <div class="item-company">${eduSchools[index]}</div>
                </div>
                ${eduYears[index] ? `<div class="item-duration">${eduYears[index]}</div>` : ''}
              </div>
              ${eduDetails[index] ? `<div class="item-description">${eduDetails[index].replace(/\n/g, '<br>')}</div>` : ''}
            </div>
          `;
        }
      });
    }

    // Certifications section
    const certNames = formData.getAll('certName[]');
    if (certNames.length > 0 && certNames[0]) {
      html += `<div class="section-title">Certifications</div>`;
      
      const certIssuers = formData.getAll('certIssuer[]');
      const certDates = formData.getAll('certDate[]');
      const certExpiries = formData.getAll('certExpiry[]');

      certNames.forEach((name, index) => {
        if (name && certIssuers[index]) {
          const issueDate = this.formatDate(certDates[index]);
          const expiryDate = this.formatDate(certExpiries[index]);
          const dateRange = issueDate + (expiryDate ? ` - ${expiryDate}` : '');

          html += `
            <div class="certification-item">
              <div>
                <div class="certification-name">${name}</div>
                <div class="certification-issuer">${certIssuers[index]}</div>
              </div>
              ${dateRange ? `<div class="certification-date">${dateRange}</div>` : ''}
            </div>
          `;
        }
      });
    }

    return html;
  }

  closeModal() {
    document.getElementById('resumeModal').classList.add('hidden');
  }

  async downloadPDF() {
    const resumeElement = document.getElementById('resumePreview');
    const fullName = document.getElementById('fullName').value || 'Resume';
    
    try {
      // Show loading state
      const downloadBtn = document.getElementById('downloadPdfBtn');
      const originalText = downloadBtn.innerHTML;
      downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Generating PDF...';
      downloadBtn.disabled = true;

      // Use html2canvas to capture the resume
      const canvas = await html2canvas(resumeElement, {
        scale: 2,
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#ffffff'
      });

      // Create PDF with jsPDF
      const { jsPDF } = window.jspdf;
      const imgData = canvas.toDataURL('image/png');
      
      // Calculate dimensions
      const imgWidth = 210; // A4 width in mm
      const pageHeight = 295; // A4 height in mm
      const imgHeight = (canvas.height * imgWidth) / canvas.width;
      let heightLeft = imgHeight;

      const doc = new jsPDF('p', 'mm', 'a4');
      let position = 0;

      // Add first page
      doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
      heightLeft -= pageHeight;

      // Add additional pages if needed
      while (heightLeft >= 0) {
        position = heightLeft - imgHeight;
        doc.addPage();
        doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;
      }

      // Save the PDF
      doc.save(`${fullName.replace(/\s+/g, '_')}_Resume.pdf`);

      // Restore button state
      downloadBtn.innerHTML = originalText;
      downloadBtn.disabled = false;

    } catch (error) {
      console.error('Error generating PDF:', error);
      alert('Error generating PDF. Please try again.');
      
      // Restore button state
      const downloadBtn = document.getElementById('downloadPdfBtn');
      downloadBtn.innerHTML = '<i class="fas fa-download mr-3"></i>Download PDF';
      downloadBtn.disabled = false;
    }
  }

  printResume() {
    const resumeContent = document.getElementById('resumePreview').innerHTML;
    const printWindow = window.open('', '_blank');
    
    printWindow.document.write(`
      <!DOCTYPE html>
      <html>
        <head>
          <title>Resume</title>
          <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
          <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
          <style>
            body {
              font-family: 'Inter', sans-serif;
              margin: 0;
              padding: 20px;
              line-height: 1.6;
              color: #1f2937;
            }
            
            .resume-content {
              background: white;
              padding: 0;
              font-family: 'Inter', sans-serif;
              line-height: 1.6;
              color: #1f2937;
            }

            .resume-content h1 {
              font-size: 2.5rem;
              font-weight: 700;
              color: #1f2937;
              margin-bottom: 0.5rem;
            }

            .resume-content .job-title {
              font-size: 1.25rem;
              color: #4f46e5;
              font-weight: 600;
              margin-bottom: 1rem;
            }

            .resume-content .contact-info {
              display: flex;
              flex-wrap: wrap;
              gap: 1rem;
              margin-bottom: 2rem;
              color: #6b7280;
            }

            .resume-content .contact-info > div {
              display: flex;
              align-items: center;
              gap: 0.5rem;
            }

            .resume-content .section-title {
              font-size: 1.5rem;
              font-weight: 600;
              color: #1f2937;
              border-bottom: 2px solid #4f46e5;
              padding-bottom: 0.5rem;
              margin: 2rem 0 1rem 0;
            }

            .resume-content .experience-item,
            .resume-content .education-item,
            .resume-content .project-item {
              margin-bottom: 1.5rem;
              page-break-inside: avoid;
            }

            .resume-content .item-header {
              display: flex;
              justify-content: space-between;
              align-items: flex-start;
              margin-bottom: 0.5rem;
            }

            .resume-content .item-title {
              font-weight: 600;
              color: #1f2937;
            }

            .resume-content .item-company {
              color: #4f46e5;
              font-weight: 500;
            }

            .resume-content .project-tech {
              color: #f59e0b;
              font-weight: 500;
              font-style: italic;
            }

            .resume-content .item-duration {
              color: #6b7280;
              font-size: 0.9rem;
            }

            .resume-content .item-description {
              color: #4b5563;
              margin-top: 0.5rem;
            }

            .resume-content .skills-list {
              display: flex;
              flex-wrap: wrap;
              gap: 0.5rem;
            }

            .resume-content .skill-tag {
              background-color: #f3f4f6;
              color: #374151;
              padding: 0.25rem 0.75rem;
              border-radius: 9999px;
              font-size: 0.875rem;
              font-weight: 500;
            }

            .resume-content .profile-section {
              display: flex;
              align-items: flex-start;
              gap: 2rem;
              margin-bottom: 2rem;
            }

            .resume-content .profile-image {
              width: 120px;
              height: 120px;
              border-radius: 50%;
              object-fit: cover;
              border: 3px solid #e5e7eb;
            }

            .resume-content .profile-text {
              flex: 1;
            }

            .resume-content a {
              color: #2563eb;
              text-decoration: none;
            }

            .resume-content .certification-item {
              display: flex;
              justify-content: space-between;
              align-items: center;
              padding: 0.75rem 0;
              border-bottom: 1px solid #f3f4f6;
            }

            .resume-content .certification-item:last-child {
              border-bottom: none;
            }

            .resume-content .certification-name {
              font-weight: 600;
              color: #1f2937;
            }

            .resume-content .certification-issuer {
              color: #6b7280;
              font-size: 0.9rem;
            }

            .resume-content .certification-date {
              color: #6b7280;
              font-size: 0.9rem;
            }

            .resume-content .project-links {
              display: flex;
              gap: 1rem;
            }

            .resume-content .project-link {
              color: #2563eb;
              text-decoration: none;
              font-size: 0.9rem;
            }

            @media print {
              body { margin: 0; padding: 0; }
              .resume-content { padding: 0; }
              .section-title { break-after: avoid; }
              .experience-item, .education-item, .project-item { break-inside: avoid; }
            }
          </style>
        </head>
        <body>
          <div class="resume-content">${resumeContent}</div>
        </body>
      </html>
    `);
    
    printWindow.document.close();
    
    // Wait for content to load then print
    printWindow.onload = function() {
      printWindow.print();
    };
  }
}

// Initialize the resume builder when the page loads
const resumeBuilder = new ResumeBuilder();

// Make resumeBuilder globally accessible for onclick handlers
window.resumeBuilder = resumeBuilder;