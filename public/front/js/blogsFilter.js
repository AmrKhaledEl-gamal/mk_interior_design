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

  const dir = document.documentElement.getAttribute("dir") || "ltr";
  const isArabic = dir.toLowerCase() === "rtl";

  const products = [...document.querySelectorAll("#allBlogs > .container > a")];
  const paginationNumbers = document.getElementById("paginationNumbers");
  const btnNext = document.getElementById("next");
  const btnPrev = document.getElementById("prev");
  const productAmount = document.getElementById("productAmount");

  let perPage = 6;
  let currentPage = 1;
  let filteredProducts = [...products]; // always full list

  /* ---------------------- Update Amount Text ---------------------- */
  function updateAmountText() {
    const total = filteredProducts.length;
    const start = (currentPage - 1) * perPage + 1;
    const end = Math.min(start + perPage - 1, total);

    productAmount.textContent = isArabic
      ? `عرض ${start}-${end} من ${total}`
      : `Showing ${start}-${end} of ${total}`;
  }

  /* ---------------------- Main Page Render ---------------------- */
  function showPage(page) {
    const start = (page - 1) * perPage;
    const end = start + perPage;

    products.forEach(p => p.style.display = "none");

    filteredProducts.slice(start, end).forEach((p, i) => {
      p.style.display = "flex";
      p.style.opacity = "0";
      p.style.transform = "scale(0.9)";
      p.style.transition = "opacity .75s ease, transform .75s ease";

      setTimeout(() => {
        p.style.opacity = "1";
        p.style.transform = "scale(1)";
      }, i * 80);
    });

    updatePagination();
    updateAmountText();

    requestAnimationFrame(() => {
  requestAnimationFrame(() => scrollToTop());
    });
  }

  /* ---------------------- Pagination Buttons ---------------------- */
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

    const addBtn = (num, active = false) => {
      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = "paginationNumber";
      btn.textContent = num;
      if (active) btn.classList.add("active");
      btn.onclick = () => {
        currentPage = num;
        showPage(currentPage);
      };
      paginationNumbers.appendChild(btn);
    };

    const addDots = () => {
      const dots = document.createElement("button");
      dots.type = "button";
      dots.className = "paginationNumber dots";
      dots.textContent = "...";
      dots.disabled = true;
      paginationNumbers.appendChild(dots);
    };

    if (!isMobile) {
      if (totalPages <= maxVisible) {
        for (let i = 1; i <= totalPages; i++)
          addBtn(i, i === currentPage);
      } else if (currentPage <= 3) {
        for (let i = 1; i <= 3; i++)
          addBtn(i, i === currentPage);
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
    btnNext.style.display = currentPage === totalPages ? "none" : "inline-block";
  }

  /* ---------------------- Prev / Next Click ---------------------- */
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

  /* ---------------------- Init ---------------------- */
  showPage(currentPage);
});
