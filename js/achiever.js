document.addEventListener("DOMContentLoaded", function () {
  const slider = document.querySelector(".achievers-slider");
  const cards = document.querySelectorAll(".achiever-card");
  let cardWidth = cards[0].offsetWidth;
  let currentPosition = 0;

  // Clone cards for infinite scrolling
  cards.forEach((card) => {
    const clone = card.cloneNode(true);
    slider.appendChild(clone);
  });

  function updateCardWidth() {
    cardWidth = cards[0].offsetWidth;
  }

  function slide() {
    currentPosition -= 1; // always move left

    // Reset when we've scrolled through the original set
    if (Math.abs(currentPosition) >= cards.length * cardWidth) {
      currentPosition = 0; // jump back to start seamlessly
    }

    slider.style.transform = `translateX(${currentPosition}px)`;
  }

  setInterval(slide, 10);

  window.addEventListener("resize", updateCardWidth);
  updateCardWidth();
});
