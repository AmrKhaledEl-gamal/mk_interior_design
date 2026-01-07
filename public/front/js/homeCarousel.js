document.addEventListener("DOMContentLoaded", () => {
  const slides = [...document.querySelectorAll(".heroSwiperSlide")];
  const btnNext = document.querySelector("#swiperNext");
  const btnPrev = document.querySelector("#swiperPrev");
  const wrapper = document.querySelector(".heroSwiperWrapper");
  const pagination = document.querySelector(".heroSwiperPagination");

  let index = 0;
  let isBlocked = false;
  let isLocked = true; // <-- lock until first animation ends
  let autoplayId = null;

  // Create bullets dynamically
  slides.forEach((_, i) => {
    const b = document.createElement("div");
    b.className = "heroSwiperBullet";
    b.addEventListener("click", () => {
      if (isBlocked || isLocked || index === i) return;
      index = i;
      updateSlides();
      updateBullets();
      blockButtons();
      startAutoplay();
    });
    pagination.appendChild(b);
  });

  function updateBullets() {
    const bullets = [...document.querySelectorAll(".heroSwiperBullet")];
    bullets.forEach((b, i) => b.classList.toggle("active", i === index));
  }

  function blockButtons() {
    isBlocked = true;
    btnNext.style.pointerEvents = "none";
    btnPrev.style.pointerEvents = "none";
    btnNext.style.opacity = "0.5";
    btnPrev.style.opacity = "0.5";
    setTimeout(() => {
      isBlocked = false;
      btnNext.style.pointerEvents = "auto";
      btnPrev.style.pointerEvents = "auto";
      btnNext.style.opacity = "1";
      btnPrev.style.opacity = "1";
    }, 500);
  }

  function updateSlides(offset = 0) {
    slides.forEach(slide => slide.className = "heroSwiperSlide");

    const active = index;
    const left = (index - 1 + slides.length) % slides.length;
    const right = (index + 1) % slides.length;

    slides[active].classList.add("active");
    slides[left].classList.add("left");
    slides[right].classList.add("right");

    slides.forEach((slide, i) => {
      if (i !== active && i !== left && i !== right) slide.classList.add("hidden");
      slide.style.transform = "";
      slide.style.transition = "";
    });

    if (offset !== 0) slides[active].style.transform = `translateX(${offset}px) scale(1)`;

    updateBullets();
  }

  updateSlides();
  updateBullets();

  // -------------------- AUTOPLAY --------------------
  function startAutoplay() {
    if (autoplayId) clearInterval(autoplayId);
    autoplayId = setInterval(() => {
      if (!isBlocked && !isDragging && !isLocked) {
        index = (index + 1) % slides.length;
        updateSlides();
        blockButtons();
      }
    }, 3000);
  }

  function stopAutoplay() {
    if (autoplayId) clearInterval(autoplayId);
  }

  startAutoplay();

  // -------------------- BUTTONS --------------------
  btnPrev.addEventListener("click", () => {
    if (isBlocked || isLocked) return;
    index = (index - 1 + slides.length) % slides.length;
    updateSlides();
    blockButtons();
    startAutoplay();
  });

  btnNext.addEventListener("click", () => {
    if (isBlocked || isLocked) return;
    index = (index + 1) % slides.length;
    updateSlides();
    blockButtons();
    startAutoplay();
  });

  // -------------------- KEYBOARD --------------------
  document.addEventListener("keydown", (e) => {
    if (isBlocked || isLocked) return;
    if (e.key === "ArrowRight") {
      index = (index + 1) % slides.length;
      updateSlides();
      blockButtons();
      startAutoplay();
    } else if (e.key === "ArrowLeft") {
      index = (index - 1 + slides.length) % slides.length;
      updateSlides();
      blockButtons();
      startAutoplay();
    }
  });

  // -------------------- DRAG & TOUCH --------------------
  let startX = 0;
  let currentX = 0;
  let offset = 0;
  let isDragging = false;
  let rafId = null;

  function startDrag(x) {
    if (isBlocked || isLocked) return;
    stopAutoplay();
    startX = x;
    isDragging = true;
    offset = 0;
    slides[index].style.transition = "transform 0.25s ease-out";
    if (rafId) cancelAnimationFrame(rafId);
    updateDrag();
  }

  function moveDrag(x) {
    if (!isDragging) return;
    currentX = x;
    offset = Math.max(Math.min((currentX - startX) * 1.3, 250), -250);
  }

  function updateDrag() {
    if (!isDragging) return;
    slides[index].style.transform = `translateX(${offset}px) scale(1)`;
    rafId = requestAnimationFrame(updateDrag);
  }

  function endDrag(x) {
    if (!isDragging) return;
    isDragging = false;
    if (rafId) cancelAnimationFrame(rafId);
    slides[index].style.transition = "";
    const diff = x - startX;
    if (Math.abs(diff) > 50) index = diff < 0 ? (index + 1) % slides.length : (index - 1 + slides.length) % slides.length;
    blockButtons();
    updateSlides(0);
    startAutoplay();
  }

  wrapper.addEventListener("mousedown", e => startDrag(e.clientX));
  wrapper.addEventListener("mousemove", e => moveDrag(e.clientX));
  wrapper.addEventListener("mouseup", e => endDrag(e.clientX));
  wrapper.addEventListener("mouseleave", () => { if (isDragging) endDrag(currentX); });
  wrapper.addEventListener("touchstart", e => startDrag(e.touches[0].clientX));
  wrapper.addEventListener("touchmove", e => moveDrag(e.touches[0].clientX));
  wrapper.addEventListener("touchend", e => endDrag(e.changedTouches[0].clientX));

  // -------------------- HOVER PAUSE --------------------
  wrapper.addEventListener("mouseenter", stopAutoplay);
  wrapper.addEventListener("mouseleave", startAutoplay);

  // -------------------- FIRST LOAD ANIMATION --------------------
  slides[0].classList.add("loadSwipeAnim");
  slides[0].addEventListener("animationend", () => {
    slides[0].classList.remove("loadSwipeAnim");
    slides.forEach(slide => slide.style.opacity = "1");
    isLocked = false; // <-- unlock now
  });
});
