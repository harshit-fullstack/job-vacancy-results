// Swiper for documents
// var swiper = new Swiper(".mySwipers", {
//     slidesPerView: 5,
//     spaceBetween: 5, // Adjusted spacing
//     loop: true, // Enables infinite looping
//     autoplay: {
//         delay: 2000, // Delay between slides
//         disableOnInteraction: false, // Continue autoplay after user interaction
//     },
//     pagination: {
//         el: ".swiper-pagination",
//         clickable: true, // Allows pagination bullets to be clickable
//     },
//     breakpoints: {
//         0: {
//             slidesPerView: 1, // 1 slide for small screens
//         },
//         520: {
//             slidesPerView: 2, // 2 slides for medium screens
//         },
//         950: {
//             slidesPerView: 3, // 3 slides for larger screens
//         },
//         1200: {
//             slidesPerView: 4, // 4 slides for extra-large screens
//         },
//     },
// });

// // Modal logic for documents

// const thumbnails = document.querySelectorAll('.thumbnail');
// const modal = document.querySelector('.image-modal');
// const modalContent = document.getElementById('modal-image');
// const closeButton = document.querySelector('.close-button');

// thumbnails.forEach(thumbnail => {
//     thumbnail.addEventListener('click', () => {
//         if (thumbnail.src) { // Ensure the thumbnail has a valid src
//             modal.style.display = 'block';
//             modalContent.src = thumbnail.src;
//         }
//     });
// });

// closeButton.addEventListener('click', () => {
//     modal.style.display = 'none';
// });

// window.addEventListener('click', (event) => {
//     if (event.target === modal) {
//         modal.style.display = 'none';
//     }
// });

// // Close modal on Escape key
// window.addEventListener('keydown', (event) => {
//     if (event.key === 'Escape' && modal.style.display === 'block') {
//         modal.style.display = 'none';
//     }
// });