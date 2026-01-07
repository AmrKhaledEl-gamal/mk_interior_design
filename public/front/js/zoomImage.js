// =======================
// MAIN SWIPER
// =======================
const mainSwiper = new Swiper(".myMainSwiper", {
  slidesPerView: 1,
  loop: true,
  speed: 500,
  navigation: {
    nextEl: ".next",
    prevEl: ".prev",
  },
});

// =======================
// THUMBNAILS SWIPER
// =======================
const thumbSwiper = new Swiper(".myThumbsSwiper", {
  slidesPerView: 5,
  spaceBetween: 10,
  watchSlidesProgress: true,
  watchSlidesVisibility: true,
  speed: 400,
  breakpoints: {
    640: { slidesPerView: 4 },
    350: { slidesPerView: 3 },
    0: { slidesPerView: 2 },
  },
});

// =======================
// HIGHLIGHT ACTIVE THUMB
// =======================
function highlightActiveThumb(index) {
  thumbSwiper.slides.forEach(slide => slide.classList.remove("activeThumb"));
  const activeSlide = thumbSwiper.slides[index];
  if (activeSlide) activeSlide.classList.add("activeThumb");
}

// =======================
// SYNC MAIN AND THUMBNAILS
// =======================
mainSwiper.on("slideChange", () => {
  const index = mainSwiper.realIndex;
  highlightActiveThumb(index);
  thumbSwiper.slideTo(index);
});

// Clicking a thumbnail moves main swiper
thumbSwiper.slides.forEach((slide, index) => {
  slide.addEventListener("click", () => mainSwiper.slideToLoop(index));
});

// INITIAL RENDER
highlightActiveThumb(mainSwiper.realIndex);
thumbSwiper.slideTo(mainSwiper.realIndex);

// =======================
// POPUP LOGIC
// =======================
const popup = document.getElementById("imagePopup");
const popupImg = document.getElementById("popupImg");
const closeBtn = document.getElementById("closeBtn");
// =======================
// POPUP SWIPER LOGIC
// =======================
let popupSwiper = null;

// Build popup slides dynamically from main swiper images
function buildPopupSlides() {
  const wrapper = document.querySelector(".popupMainSwiper .swiper-wrapper");
  wrapper.innerHTML = "";
  mainSwiper.slides.forEach(slide => {
    const imgSrc = slide.querySelector("img").src;
    wrapper.innerHTML += `<div class="swiper-slide"><img src="${imgSrc}" alt="Product Image"></div>`;
  });
}

// Initialize popup swiper
function initPopupSwiper(startIndex = mainSwiper.realIndex) {
  if (popupSwiper) popupSwiper.destroy(true, true); // destroy old instance

  popupSwiper = new Swiper(".popupMainSwiper", {
    initialSlide: startIndex,
    loop: true,
    speed: 400,
    navigation: {
      nextEl: ".popupNext",
      prevEl: ".popupPrev",
    },
    keyboard: true, // enable keyboard navigation
    simulateTouch: true, // allow swipe on touch devices
    slidesPerView: 1,
  });
}

// Open popup
function openPopup() {
  buildPopupSlides();
  initPopupSwiper();
  popup.classList.add("active");
  document.body.style.overflow = "hidden";
}

// Open popup when clicking main swiper image
document.querySelectorAll(".myMainSwiper .swiper-slide img").forEach(img => {
  img.addEventListener("click", openPopup);
});

// Close popup function
function closePopup() {
  popup.classList.remove("active");
  document.body.style.overflow = "";
}
closeBtn.addEventListener("click", closePopup);

// Close when clicking outside the popup swiper
popup.addEventListener("click", e => {
  if (e.target === popup || e.target === document.querySelector(".popupMainSwiper")) {
    closePopup();
  }
});

// Keyboard navigation for popup
document.addEventListener("keydown", e => {
  if (popup.classList.contains("active") && popupSwiper) {
    if (e.key === "Escape") closePopup();
    if (e.key === "ArrowRight") popupSwiper.slideNext();
    if (e.key === "ArrowLeft") popupSwiper.slidePrev();
  } else {
    if (e.key === "ArrowRight") mainSwiper.slideNext();
    if (e.key === "ArrowLeft") mainSwiper.slidePrev();
  }
});
