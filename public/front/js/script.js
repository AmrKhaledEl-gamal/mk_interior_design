document.addEventListener("DOMContentLoaded", () => {
    // === NAV MENU ===
    const menu = document.querySelector("#menu");
    const asideOverlay = document.querySelector("#asideOverlay");
    const sideLinks = document.querySelector("#sideLinks");
    const closeSidebarBtn = document.querySelector("#closeSidebar");

    menu?.addEventListener("click", () => {
        asideOverlay.classList.add("show_overlay");
    });

    const closeSidebar = () => {
        asideOverlay.classList.remove("show_overlay");
    };

    asideOverlay?.addEventListener("click", (e) => {
        if (!sideLinks.contains(e.target)) {
            closeSidebar();
        }
    });

    closeSidebarBtn.addEventListener("click", closeSidebar);
    // Pressing Esc closes dropdown
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeSidebar();
        }
    });

    // Handle touch back button / popstate
    window.addEventListener("popstate", () => {
        closeSidebar();
    });

    // === NAV DROPDOWN HOLDERS (hover + click lock) ===
    const navDropDownHolders = document.querySelectorAll(".navDropDownHolder");
    navDropDownHolders.forEach((holder) => {
        const dropDown = holder.querySelector(".navDropDown");
        const arrow = holder.querySelector("svg path");
        let locked = false;

        holder.addEventListener("mouseenter", () => {
            if (!locked) {
                dropDown?.classList.add("show_dropdown");
                arrow?.classList.add("arrowDownUp");
                dropDown && (dropDown.style.overflow = "auto");
            }
        });

        holder.addEventListener("mouseleave", () => {
            if (!locked) {
                dropDown?.classList.remove("show_dropdown");
                arrow?.classList.remove("arrowDownUp");
                dropDown && (dropDown.style.overflow = "hidden");
            }
        });

        holder.addEventListener("click", (e) => {
            e.stopPropagation();
            locked = !locked;

            if (locked) {
                navDropDownHolders.forEach((other) => {
                    if (other !== holder) {
                        const otherDrop = other.querySelector(".navDropDown");
                        const otherArrow = other.querySelector("svg path");
                        otherDrop?.classList.remove("show_dropdown");
                        otherDrop && (otherDrop.style.overflow = "hidden");
                        otherArrow?.classList.remove("arrowDownUp");
                        other.locked = false;
                    }
                });
            }

            if (locked) {
                dropDown?.classList.add("show_dropdown");
                arrow?.classList.add("arrowDownUp");
                dropDown && (dropDown.style.overflow = "auto");
            } else {
                dropDown?.classList.remove("show_dropdown");
                arrow?.classList.remove("arrowDownUp");
                dropDown && (dropDown.style.overflow = "hidden");
            }
        });
    });

    document.addEventListener("click", () => {
        navDropDownHolders.forEach((holder) => {
            const dropDown = holder.querySelector(".navDropDown");
            const arrow = holder.querySelector("svg path");
            holder.locked = false;
            dropDown?.classList.remove("show_dropdown");
            dropDown && (dropDown.style.overflow = "hidden");
            arrow?.classList.remove("arrowDownUp");
        });
    });

    // === SIDEBAR DROPDOWNS ===
    const menuDropDownHolders = document.querySelectorAll(
        ".menuDropDownHolder"
    );
    menuDropDownHolders.forEach((holder) => {
        holder.addEventListener("click", (e) => {
            e.stopPropagation();
            const dropDown =
                holder.querySelector(".menuDropDown") ||
                holder.nextElementSibling;
            const isOpen = dropDown?.classList.contains("show_dropdown");

            // Close all first
            document
                .querySelectorAll(".menuDropDown")
                .forEach((drop) => drop.classList.remove("show_dropdown"));

            // Toggle current
            if (!isOpen) dropDown?.classList.add("show_dropdown");
        });
    });

    // Optional: click outside closes all
    document.addEventListener("click", () => {
        document
            .querySelectorAll(".menuDropDown")
            .forEach((drop) => drop.classList.remove("show_dropdown"));
        document
            .querySelectorAll(".sideMenuDropDown")
            .forEach((drop) => drop.classList.remove("show_dropdown"));
    });

    // === SIDEBAR BUTTONS for nested dropdowns ===
    const dropdownButtons = document.querySelectorAll(".menuDropDownOpener");
    dropdownButtons.forEach((button) => {
        button.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();

            const dropdown = button.closest(
                "figure, .menuDropDownHolder"
            )?.nextElementSibling;
            if (dropdown && dropdown.classList.contains("sideMenuDropDown")) {
                dropdown.classList.toggle("show_dropdown");
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const dropdownPopup = document.getElementById("dropdownPopup");
    if (!dropdownPopup) return;

    const linkToFigureId = {
        companyHolder: "companyDropDown",
        productsHolder: "productsDropDown",
        milestonesHolder: "milestonesDropDown",
        careersHolder: "careersDropDown",
        contactHolder: "contactDropDown",
    };

    const root = document.documentElement;
    const originalNavGray =
        getComputedStyle(root).getPropertyValue("--navGray");

    const isTouchDevice =
        "ontouchstart" in window ||
        navigator.maxTouchPoints > 0 ||
        navigator.msMaxTouchPoints > 0;

    /* ==========================
     CORE FUNCTIONS
  ========================== */
    function closeDropdown() {
        dropdownPopup.classList.remove("show_dropdown");
        Object.keys(linkToFigureId).forEach((id) => {
            document.getElementById(id)?.classList.remove("show_dropdown");
            document
                .getElementById(linkToFigureId[id])
                ?.classList.remove("show_dropdown");
        });
        root.style.setProperty("--navGray", originalNavGray);
    }

    function openDropdown(linkId) {
        closeDropdown();
        document.getElementById(linkId)?.classList.add("show_dropdown");
        dropdownPopup.classList.add("show_dropdown");
        document
            .getElementById(linkToFigureId[linkId])
            ?.classList.add("show_dropdown");
        root.style.setProperty("--navGray", "transparent");
    }

    /* ==========================
     NAV LINKS
  ========================== */
    Object.keys(linkToFigureId).forEach((linkId) => {
        const link = document.getElementById(linkId);
        if (!link) return;

        if (isTouchDevice) {
            link.addEventListener("click", (e) => {
                e.stopPropagation();
                link.classList.contains("show_dropdown")
                    ? closeDropdown()
                    : openDropdown(linkId);
            });
        } else {
            link.addEventListener("mouseenter", () => openDropdown(linkId));
        }
    });

    /* ==========================
     CLOSE ON HOVER EMPTY SPACE
  ========================== */
    if (!isTouchDevice) {
        dropdownPopup.addEventListener("mousemove", (e) => {
            // If hovering directly over the container, not a figure
            if (e.target === dropdownPopup) {
                closeDropdown();
            }
        });
    }

    /* ==========================
     OUTSIDE CLICK (TOUCH)
  ========================== */
    document.addEventListener("click", (e) => {
        if (
            isTouchDevice &&
            dropdownPopup.classList.contains("show_dropdown") &&
            !dropdownPopup.contains(e.target) &&
            !Object.keys(linkToFigureId).some((id) =>
                document.getElementById(id)?.contains(e.target)
            )
        ) {
            closeDropdown();
        }
    });

    /* ==========================
     ESC + BACK
  ========================== */
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeDropdown();
    });

    window.addEventListener("popstate", closeDropdown);
});

document.addEventListener("DOMContentLoaded", () => {
    const searchIcon = document.getElementById("searchIcon");
    const searchEnginePopup = document.getElementById("searchEnginePopup");
    const searchEngineForm = document.getElementById("searchEngineForm");

    searchEngineForm.addEventListener("click", (e) => {
        e.stopPropagation();
    });

    searchIcon.addEventListener("click", () => {
        searchEnginePopup.classList.toggle("show_popup");
    });

    const closePopup = () => {
        searchEnginePopup.classList.remove("show_popup");
    };

    searchEnginePopup.addEventListener("click", (e) => {
        if (
            !searchEngineForm.contains(e.target) &&
            !e.target.classList.contains("arrow")
        ) {
            closePopup();
        }
    });

    const form = document.getElementById("searchEngineForm");
    const input = document.getElementById("searchEngine");

    if (!form || !input) return;
    // Pre-fill input from URL param 'q' if present
    const urlParams = new URLSearchParams(window.location.search);
    const q = urlParams.get("q");
    if (q) {
        input.value = q;
    }

    form.addEventListener("submit", (e) => {
        e.preventDefault(); // stop default submit

        const value = input.value.trim();
        if (!value) return;

        // Use the exposed route
        if (window.productsIndexUrl) {
            window.location.href =
                window.productsIndexUrl + "?q=" + encodeURIComponent(value);
        } else {
            // Fallback or static behavior if needed
            console.error("productsIndexUrl not defined");
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const scrollable = document.getElementById("sideCart");

    scrollable.addEventListener("wheel", (e) => {
        const delta = e.deltaY;
        const canScrollUp = scrollable.scrollTop > 0;
        const canScrollDown =
            scrollable.scrollTop + scrollable.clientHeight <
            scrollable.scrollHeight;

        if ((delta < 0 && canScrollUp) || (delta > 0 && canScrollDown)) {
            e.stopPropagation(); // prevent Lenis from scrolling the page
        }
    });

    const cartIcon = document.querySelector("#cartIcon");
    const sideCartOverlay = document.querySelector("#sideCartOverlay");
    const sideCart = document.querySelector("#sideCart");
    const closeSideCart = document.querySelector("#closeSideCart");

    const hideSideCart = () => {
        sideCartOverlay.classList.remove("show_sideCart");
    };

    cartIcon.addEventListener("click", () => {
        sideCartOverlay.classList.add("show_sideCart");
    });

    sideCartOverlay.addEventListener("click", (e) => {
        if (!sideCart.contains(e.target)) {
            hideSideCart();
        }
    });
    closeSideCart.addEventListener("click", hideSideCart);
});
