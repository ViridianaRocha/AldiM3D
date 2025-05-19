document.addEventListener("DOMContentLoaded", () => {
  
  const carousel = document.getElementById("carousel");
  const slides = document.querySelectorAll(".item-slide");
  const leftBtn = document.querySelector(".nav.left");
  const rightBtn = document.querySelector(".nav.right");

  let currentIndex = 0;
  const totalSlides = slides.length;

  function updateCarousel() {
    const width = slides[0].clientWidth;
    carousel.style.transform = `translateX(-${currentIndex * width}px)`;
  }

  rightBtn.addEventListener("click", () => {
    currentIndex = (currentIndex + 1) % totalSlides; // vuelve a 0 al final
    updateCarousel();
  });

  leftBtn.addEventListener("click", () => {
    currentIndex = (currentIndex - 1 + totalSlides) % totalSlides; // va al final si es < 0
    updateCarousel();
  });

  window.addEventListener("resize", updateCarousel); // Ajuste responsivo
});

