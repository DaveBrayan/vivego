/**
 * Vive Go - App Engine
 * Preloader, Event Slider Carousel, Live Countdown, Intereses Toggle
 */

document.addEventListener('DOMContentLoaded', () => {
  initPagePreloader();
  initEventCarousel();
  initFilterCapsules();
  initInterestsToggle();
});

window.addEventListener('load', () => {
  initPagePreloader();
});

/**
 * Smooth Preloader Fade Out
 */
function initPagePreloader() {
  const preloader = document.getElementById('pagePreloader');
  if (!preloader) return;

  preloader.classList.add('fade-out');
  setTimeout(() => {
    if (preloader && preloader.parentNode) {
      preloader.parentNode.removeChild(preloader);
    }
  }, 600);
}

/**
 * Event Carousel Hero Slider
 */
function initEventCarousel() {
  const slides = document.querySelectorAll('.carousel-slide');
  const dots = document.querySelectorAll('.carousel-dot');
  const prevBtn = document.getElementById('carouselPrev');
  const nextBtn = document.getElementById('carouselNext');

  if (!slides.length) return;

  let currentIdx = 0;
  let autoTimer;

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
    });

    dots.forEach((dot, i) => {
      dot.classList.toggle('active', i === index);
    });

    currentIdx = index;
  }

  function next() {
    let nextIdx = (currentIdx + 1) % slides.length;
    showSlide(nextIdx);
  }

  function prev() {
    let prevIdx = (currentIdx - 1 + slides.length) % slides.length;
    showSlide(prevIdx);
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      next();
      resetTimer();
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      prev();
      resetTimer();
    });
  }

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      showSlide(i);
      resetTimer();
    });
  });

  function startTimer() {
    autoTimer = setInterval(next, 4500);
  }

  function resetTimer() {
    clearInterval(autoTimer);
    startTimer();
  }

  showSlide(0);
  startTimer();
}

/**
 * Filter Capsules
 */
function initFilterCapsules() {
  const capsules = document.querySelectorAll('.filter-capsule');
  capsules.forEach(cap => {
    cap.addEventListener('click', () => {
      capsules.forEach(c => c.classList.remove('active'));
      cap.classList.add('active');
    });
  });
}

/**
 * Descubre tus Intereses Toggle (Mostrar más / Mostrar menos)
 */
function initInterestsToggle() {
  const btn = document.getElementById('btnToggleInterests');
  const extraItems = document.querySelectorAll('.interest-extra');
  const textSpan = document.getElementById('toggleInterestsText');
  const iconSpan = document.getElementById('toggleInterestsIcon');

  if (!btn || !extraItems.length) return;

  let isExpanded = true;

  btn.addEventListener('click', () => {
    isExpanded = !isExpanded;
    extraItems.forEach(item => {
      item.style.display = isExpanded ? 'flex' : 'none';
    });

    textSpan.textContent = isExpanded ? 'Mostrar menos' : 'Mostrar más';
    iconSpan.textContent = isExpanded ? '▲' : '▼';
  });
}
