// =======================
// YOUR ORIGINAL CODE (UNTOUCHED)
// =======================
const slides = document.querySelectorAll(".myMainSwiper .swiper-slide img");
const totalImages = slides.length;
// MAIN SWIPER
const mainSwiper = new Swiper(".myMainSwiper", {
  slidesPerView: 1,
  loop: true,
  speed: 500,
  navigation: {
    nextEl: ".next",
    prevEl: ".prev",
  },
});

// THUMBNAILS
const thumbContainer = document.querySelector(".thumbnails");
const allThumbs = Array.from(thumbContainer.querySelectorAll(".thumbnailImage"));
const totalThumbs = allThumbs.length;
const popupPrev = document.querySelector(".popupPrev");
const popupNext = document.querySelector(".popupNext");


function updateThumbnails() {
    if (totalImages <= 1) return;
  const currentIndex = mainSwiper.realIndex;

  const indexes = [
    (currentIndex + 1) % totalThumbs,
    (currentIndex + 2) % totalThumbs,
  ];

  thumbContainer.innerHTML = "";

  indexes.forEach((i) => {
    const thumbClone = allThumbs[i].cloneNode(true);
    thumbContainer.appendChild(thumbClone);

    thumbClone.querySelector("img").onclick = () => {
      mainSwiper.slideToLoop(i);
    };
  });
}
if (totalImages <= 1) {
  // Hide thumbnails
  document.querySelector(".mainTourImage").classList.add("full-span");
  document.querySelector(".thumbnails").style.display = "none";

  // Remove navigation arrows (optional)
  document.querySelector(".prev").style.display = "none";
  document.querySelector(".next").style.display = "none";

  // No need to update thumbnails
  mainSwiper.on("slideChange", () => {});
}

// INITIAL render
updateThumbnails();

// Update thumbnails on slide change
mainSwiper.on("slideChange", () => {
  updateThumbnails();
});


const popup = document.querySelector('#imagePopup')
const closeBtn = document.querySelector('#closeBtn')
const popupBtn = document.querySelector('#zoomBtn')
const popupThumbWrapper = document.querySelector(".myPopupThumbs .swiper-wrapper");


slides.forEach((slide)=>{
  slide.addEventListener('click',()=>{
    popup.classList.add('active')
    document.body.style.overflow = 'hidden'
    const activeIndex = mainSwiper.realIndex;
  })
})

popupBtn.addEventListener('click', () => {
  popup.classList.add('active')
  document.body.style.overflow = 'hidden'
  document.querySelector('main').style.overflow = 'hidden'

  const activeIndex = mainSwiper.realIndex;
})

const closePopup = () => {
  popup.classList.remove('active');
  document.body.style.overflow = ''
  document.querySelector('main').style.overflow = ''
}

closeBtn.addEventListener('click', closePopup)


popup.addEventListener("click", (e) => {
  const blockedSelectors = [
    "#closeBtn",
    ".popupMainSwiper",
    ".popupMainSwiper *",
    ".popupPrev",
    ".popupNext",
    ".myPopupThumbs",
    ".myPopupThumbs *",
    ".popup-thumb",
    ".popup-thumb *",
    ".swiper-slide",
    ".swiper-slide img",
    ".swiper-button-next",
    ".swiper-button-prev",
  ];

  const clickedInsideBlocked = blockedSelectors.some(sel =>
    e.target.closest(sel)
  );

  if (!clickedInsideBlocked) {
    closePopup();
  }
});






// ===============================
// ADDED CODE — POPUP THUMB SLIDER
// (Does NOT replace any of your code)
// ===============================

let popupMainSwiper;

// Build big popup slides
function buildPopupMainSlides() {
  const wrapper = document.querySelector(".popupMainSwiper .swiper-wrapper");
  wrapper.innerHTML = "";

  slides.forEach(img => {
    wrapper.innerHTML += `
      <div class="swiper-slide"><img src="${img.src}"></div>
    `;
  });



}
function initPopupMainSwiper(startIndex) {
  popupMainSwiper = new Swiper(".popupMainSwiper", {
    initialSlide: startIndex,
    loop: false,
    speed: 400,
    navigation: {
      nextEl: ".popupNext",
      prevEl: ".popupPrev",
    },
    on: {
      slideChange() {
        // ✅ Guard against null
        if (!popupMainSwiper) return;

        const i = popupMainSwiper.realIndex;
        const thumbsPerPage = getThumbsPerPage();
        const batch = Math.floor(i / thumbsPerPage);

        if (batch !== currentThumbPage) {
          currentThumbPage = batch;
          buildPopupThumbsPage(batch);
        }

        highlightActiveThumb(i);
        updatePopupMainNav();
      }
    }
  });
}


// Elements for popup thumbnails
let popupThumbSwiper;

// Build popup thumbnails
function getThumbsPerPage() {
  const w = window.innerWidth;
  if (w >= 1140) return 16;
  if (w >= 768) return 10;
  if (w >= 640) return 8;
  if (w >= 480) return 6;

  return 4;
}
let currentThumbPage = 0;

function buildPopupThumbsPage(page = 0) {
  const thumbsPerPage = getThumbsPerPage();
  popupThumbWrapper.innerHTML = "";

  const start = page * thumbsPerPage;
  const end = Math.min(start + thumbsPerPage, slides.length);

  for (let i = start; i < end; i++) {
    const thumb = document.createElement("div");
    thumb.className = "popup-thumb";
    thumb.innerHTML = `<img src="${slides[i].src}">`;

  thumb.querySelector("img").addEventListener("click", () => {
  if (popupMainSwiper) popupMainSwiper.slideTo(i);
  highlightActiveThumb(i);

  const batch = Math.floor(i / thumbsPerPage);
  if (batch !== currentThumbPage) {
    currentThumbPage = batch;
    buildPopupThumbsPage(batch);
  }
});


    popupThumbWrapper.appendChild(thumb);
  }

  // Highlight active thumb only if swiper exists
  if (popupMainSwiper) highlightActiveThumb(popupMainSwiper.realIndex);
}



// Initialize popup thumb swiper
function initPopupThumbSwiper() {
  popupThumbSwiper = new Swiper(".myPopupThumbs", {
    slidesPerView: 8,
    spaceBetween: 5,
    loop: false,
    freeMode: false,
    navigation: {
      nextEl: ".nextThumb",
      prevEl: ".prevThumb",
    },
    watchSlidesProgress: true,
    slideToClickedSlide: false, // we handle the slide manually
  });
}
function updatePopupMainNav() {
  if (!popupMainSwiper) return; // Skip if swiper not initialized

  const total = popupMainSwiper.slides.length;
  const index = popupMainSwiper.realIndex;

  popupPrev.style.visibility = index === 0 ? "hidden" : "visible";
  popupPrev.style.opacity = index === 0 ? "0" : "1";
  popupPrev.style.pointerEvents = index === 0 ? "none" : "auto";

  popupNext.style.visibility = index === total - 1 ? "hidden" : "visible";
  popupNext.style.opacity = index === total - 1 ? "0" : "1";
  popupNext.style.pointerEvents = index === total - 1 ? "none" : "auto";
}



// Highlight active thumbnail
function highlightActiveThumb(index) {
  const thumbs = document.querySelectorAll(".popup-thumb");
  thumbs.forEach(t => t.classList.remove("active-thumb"));

  const thumbsPerPage = getThumbsPerPage();
  const start = currentThumbPage * thumbsPerPage;

  const pos = index - start;
  if (thumbs[pos]) thumbs[pos].classList.add("active-thumb");
}

// Extend your open popup functions (WITHOUT modifying them)
function openPopupExtras() {
  const i = mainSwiper.realIndex; // Always match main swiper

  // Destroy previous popup swiper if it exists
  if (popupMainSwiper) {
    popupMainSwiper.destroy(true, true);
    popupMainSwiper = null;
  }

  // Build large slider
  buildPopupMainSlides();
  initPopupMainSwiper(i);

  // Reset thumbnail page
  currentThumbPage = Math.floor(i / getThumbsPerPage());
  buildPopupThumbsPage(currentThumbPage);

  highlightActiveThumb(i);
  updatePopupMainNav();

  // Open popup
  popup.classList.add('active');
  document.body.style.overflow = 'hidden';
  document.querySelector('main').style.overflow = 'hidden';
}

// When a slide opens popup
slides.forEach(slide => {
  slide.addEventListener("click", openPopupExtras);
});

// When zoom button opens popup
popupBtn.addEventListener("click", openPopupExtras);


document.querySelector('.popupNext').addEventListener('click', () => {
  const thumbsPerPage = getThumbsPerPage();
  if ((currentThumbPage + 1) * thumbsPerPage < slides.length) {
    currentThumbPage++;
    buildPopupThumbsPage(currentThumbPage);
    ;
  }
});

document.querySelector('.popupPrev').addEventListener('click', () => {
  if (currentThumbPage > 0) {
    currentThumbPage--;
    buildPopupThumbsPage(currentThumbPage);
    ;
  }
});

// KEYBOARD
document.addEventListener("keydown", (e) => {
  // If popup is open → control popup slider
  if (popup.classList.contains("active") && popupMainSwiper) {
    if (e.key === "ArrowRight") popupMainSwiper.slideNext();
    if (e.key === "ArrowLeft") popupMainSwiper.slidePrev();
    if (e.key === "Escape"  || e.key === "Esc") closePopup();
    return;
  }

  // If popup is NOT open → control main slider
  if (e.key === "ArrowRight") mainSwiper.slideNext();
  if (e.key === "ArrowLeft") mainSwiper.slidePrev();
});

window.addEventListener("resize", () => {
  buildPopupThumbsPage(currentThumbPage);
});



updatePopupMainNav();

