
document.addEventListener("DOMContentLoaded", () => {
  function scrollToTop() {
  if (window.lenis) {
  window.lenis.scrollTo(0, {
  duration: 2.2,
  lerp: 0.08,
});
  } else {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }
}

    const filterIcon = document.getElementById('showFilters');
    const filterSideBar =  document.getElementById('filter');
    const filterOptions =  document.getElementById('filterOptions');
    const filterSection =  document.querySelector('.productFilter');
    const closeSidebarIcon = document.getElementById('closeSidebarIcon');


    filterIcon.addEventListener('click',()=>{
        filterSection.style.zIndex = '1000';
        filterSideBar.classList.add('showSidebar')
    }
)


const closeSidebar = ()=>{
      filterSection.style.zIndex = 'auto';
        filterSideBar.classList.remove('showSidebar')
}

filterSideBar.addEventListener('click',(e)=>{
    if (!filterOptions.contains(e.target)){
        closeSidebar()
    }
})

closeSidebarIcon.addEventListener('click',closeSidebar)



const categoriesDropdownLabel = document.getElementById('categoriesDropdownLabel')
const categoriesDropdown = document.getElementById('categoriesDropdown')

categoriesDropdownLabel.addEventListener('click',()=>{
    categoriesDropdownLabel.classList.toggle('active')
    categoriesDropdown.classList.toggle('active')

})


  document.querySelectorAll(".tag").forEach(tag => {
    tag.addEventListener("click", () => {
        // Toggle the activeTag class
        tag.classList.toggle("activeTag");

        // Get the <i> icon inside the tag
        const icon = tag.querySelector(".tagSign i");

        // Change icon based on active state
        if (tag.classList.contains("activeTag")) {
            icon.classList.remove("fa-plus");
            icon.classList.add("fa-minus");
        } else {
            icon.classList.remove("fa-minus");
            icon.classList.add("fa-plus");
        }
    });
});



  const minRange = document.getElementById("minRange");
const maxRange = document.getElementById("maxRange");

const minLabel = document.getElementById("minLabel");
const maxLabel = document.getElementById("maxLabel");

const rangeBar = document.querySelector(".range");

const RANGE_MIN = Number(minRange.min);
const RANGE_MAX = Number(maxRange.max);

function updateUI() {
  const minVal = Number(minRange.value);
  const maxVal = Number(maxRange.value);

  minLabel.textContent = minVal;
  maxLabel.textContent = maxVal;

  const left = (minVal / RANGE_MAX) * 100;
  const width = ((maxVal - minVal) / RANGE_MAX) * 100;

  rangeBar.style.left = left + "%";
  rangeBar.style.width = width + "%";
}

minRange.addEventListener("input", () => {
  let minVal = Number(minRange.value);
  let maxVal = Number(maxRange.value);

  // allow equality, but prevent crossing
  if (minVal > maxVal) {
    minVal = maxVal;
    minRange.value = minVal;
  }

  updateUI();
});

maxRange.addEventListener("input", () => {
  let minVal = Number(minRange.value);
  let maxVal = Number(maxRange.value);

  if (maxVal < minVal) {
    maxVal = minVal;
    maxRange.value = maxVal;
  }

  updateUI();
});

updateUI();

  const dir = document.documentElement.getAttribute("dir") || "ltr";
  const isArabic = dir.toLowerCase() === "rtl";

  const products = [...document.querySelectorAll("#allProducts > a")];
  const paginationNumbers = document.getElementById("paginationNumbers");
  const btnNext = document.getElementById("next");
  const btnPrev = document.getElementById("prev");
  const productAmount = document.getElementById("productAmount");

  let filteredProducts = [...products]; // always same now
  const perPage = 6;
  let currentPage = 1;

  function updateAmountText() {
    const total = filteredProducts.length;
    const start = (currentPage - 1) * perPage + 1;
    const end = Math.min(start + perPage - 1, total);

    productAmount.textContent = isArabic
      ? `عرض ${start}-${end} من ${total}`
      : `Showing ${start}-${end} of ${total}`;
  }

  function showPage(page) {
  updateEmptyState();

  if (filteredProducts.length === 0) {
    paginationNumbers.innerHTML = "";
    btnPrev.style.display = "none";
    btnNext.style.display = "none";
    productAmount.textContent = "";
    return;
  }

  const start = (page - 1) * perPage;
  const end = start + perPage;

  products.forEach((product) => {
    product.style.display = "none";
  });

  filteredProducts.slice(start, end).forEach((product, i) => {
    product.style.display = "flex";
    product.style.opacity = "0";
    product.style.transform = "scale(0.9)";
    product.style.transition = "opacity 0.75s ease, transform 0.75s ease";

    setTimeout(() => {
      product.style.opacity = "1";
      product.style.transform = "scale(1)";
    }, i * 80);
  });

  updatePagination();
  updateAmountText();

  if (window.lenis && typeof window.lenis.resize === "function") {
    window.lenis.resize();
  }

  requestAnimationFrame(() => {
  requestAnimationFrame(() => scrollToTop());
  });
}


  function updatePagination() {
    paginationNumbers.innerHTML = "";

    const totalPages = Math.ceil(filteredProducts.length / perPage);
    if (totalPages <= 1) {
      btnPrev.style.display = "none";
      btnNext.style.display = "none";
      return;
    }

    const isMobile = window.innerWidth <= 768;
    const maxVisible = isMobile ? 1 : 5;

    const addBtn = (pageNum, active = false) => {
      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = "paginationNumber";
      btn.textContent = pageNum;
      if (active) btn.classList.add("active");
      btn.onclick = () => {
        currentPage = pageNum;
        showPage(currentPage);
      };
      paginationNumbers.appendChild(btn);
    };

    const addDots = () => {
      const dots = document.createElement("button");
      dots.className = "paginationNumber dots";
      dots.textContent = "...";
      dots.disabled = true;
      paginationNumbers.appendChild(dots);
    };

    if (!isMobile) {
      if (totalPages <= maxVisible) {
        for (let i = 1; i <= totalPages; i++) addBtn(i, i === currentPage);
      } else if (currentPage <= 3) {
        for (let i = 1; i <= 3; i++) addBtn(i, i === currentPage);
        addDots();
        addBtn(totalPages);
      } else if (currentPage >= totalPages - 2) {
        addBtn(1);
        addDots();
        for (let i = totalPages - 2; i <= totalPages; i++)
          addBtn(i, i === currentPage);
      } else {
        addBtn(1);
        addDots();
        addBtn(currentPage - 1);
        addBtn(currentPage, true);
        addBtn(currentPage + 1);
        addDots();
        addBtn(totalPages);
      }
    } else {
      addBtn(1, currentPage === 1);
      if (totalPages > 2) addDots();
      addBtn(totalPages, currentPage === totalPages);
    }

    btnPrev.style.display = currentPage === 1 ? "none" : "inline-block";
    btnNext.style.display =
      currentPage === totalPages ? "none" : "inline-block";
  }

  btnNext.onclick = () => {
    const totalPages = Math.ceil(filteredProducts.length / perPage);
    if (currentPage < totalPages) {
      currentPage++;
      showPage(currentPage);
    }
  };

  btnPrev.onclick = () => {
    if (currentPage > 1) {
      currentPage--;
      showPage(currentPage);
    }
  };


  function updateEmptyState() {
  const emptyMessage = document.getElementById("empty");

  if (filteredProducts.length === 0) {
    emptyMessage.style.display = "flex"; // show message
    products.forEach(p => (p.style.display = "none"));
  } else {
    emptyMessage.style.display = "none"; // hide message
  }
}


  showPage(currentPage);
});

