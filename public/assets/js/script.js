// Chart Global Defaults
Chart.defaults.color = "#94a3b8";
Chart.defaults.font.family = "Outfit, sans-serif";

// Revenue Chart (Dashboard)
const revenueChartEl = document.getElementById("revenueChart");
if (revenueChartEl) {
  const revenueCtx = revenueChartEl.getContext("2d");
  const gradientFill = revenueCtx.createLinearGradient(0, 0, 0, 400);
  gradientFill.addColorStop(0, "rgba(99, 102, 241, 0.5)"); // Accent color with opacity
  gradientFill.addColorStop(1, "rgba(99, 102, 241, 0.0)");

  new Chart(revenueCtx, {
    type: "line",
    data: {
      labels: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],
      datasets: [
        {
          label: "Revenue",
          data: [
            12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000, 45000,
            42000, 55000, 60000,
          ],
          borderColor: "#6366f1",
          backgroundColor: gradientFill,
          borderWidth: 3,
          tension: 0.4,
          fill: true,
          pointBackgroundColor: "#1e293b",
          pointBorderColor: "#6366f1",
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
        tooltip: {
          backgroundColor: "#1e293b",
          titleColor: "#f8fafc",
          bodyColor: "#cbd5e1",
          borderColor: "rgba(255, 255, 255, 0.1)",
          borderWidth: 1,
          padding: 10,
          displayColors: false,
        },
      },
      scales: {
        x: {
          grid: {
            display: false,
            drawBorder: false,
          },
          ticks: {
            font: {
              size: 12,
            },
          },
        },
        y: {
          grid: {
            color: "rgba(255, 255, 255, 0.05)",
            drawBorder: false,
          },
          ticks: {
            callback: function (value) {
              return "$" + value / 1000 + "k";
            },
          },
        },
      },
    },
  });
}

// Traffic Sources Chart (Dashboard)
const trafficChartEl = document.getElementById("trafficChart");
if (trafficChartEl) {
  const trafficCtx = trafficChartEl.getContext("2d");

  new Chart(trafficCtx, {
    type: "doughnut",
    data: {
      labels: ["Direct", "Social", "Referral", "Organic"],
      datasets: [
        {
          data: [35, 25, 20, 20],
          backgroundColor: [
            "#6366f1", // Indigo
            "#3b82f6", // Blue
            "#10b981", // Emerald
            "#f59e0b", // Amber
          ],
          borderWidth: 0,
          hoverOffset: 4,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: "bottom",
          labels: {
            boxWidth: 12,
            padding: 20,
          },
        },
      },
      cutout: "70%",
    },
  });
}

// Analytics Page Charts
const sessionChartEl = document.getElementById("sessionChart");
if (sessionChartEl) {
  new Chart(sessionChartEl, {
    type: "bar",
    data: {
      labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      datasets: [
        {
          label: "Sessions",
          data: [120, 190, 300, 500, 200, 300, 450],
          backgroundColor: "#3b82f6",
          borderRadius: 4,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: { grid: { color: "rgba(255, 255, 255, 0.05)" } },
        x: { grid: { display: false } },
      },
    },
  });
}

// Sidebar Toggle Logic
const sidebarToggle = document.getElementById("sidebar-toggle");
const sidebar = document.getElementById("sidebar");

// Add responsive toggle capability
if (window.innerWidth <= 768) {
  sidebarToggle.style.display = "flex";
}

window.addEventListener("resize", () => {
  if (window.innerWidth <= 768) {
    sidebarToggle.style.display = "flex";
  } else {
    sidebarToggle.style.display = "none";
    sidebar.classList.remove("active");
  }
});

sidebarToggle.addEventListener("click", () => {
  sidebar.classList.toggle("active");
});

// Close sidebar when clicking outside on mobile
document.addEventListener("click", (e) => {
  if (window.innerWidth <= 768) {
    if (
      !sidebar.contains(e.target) &&
      !sidebarToggle.contains(e.target) &&
      sidebar.classList.contains("active")
    ) {
      sidebar.classList.remove("active");
    }
  }
});

// Kanban Drag and Drop Logic
const draggables = document.querySelectorAll(".task-card");
const containers = document.querySelectorAll(".kanban-tasks");

draggables.forEach((draggable) => {
  draggable.addEventListener("dragstart", () => {
    draggable.classList.add("dragging");
  });

  draggable.addEventListener("dragend", () => {
    draggable.classList.remove("dragging");
  });
});

containers.forEach((container) => {
  container.addEventListener("dragover", (e) => {
    e.preventDefault(); // Enable dropping
    const afterElement = getDragAfterElement(container, e.clientY);
    const draggable = document.querySelector(".dragging");

    if (afterElement == null) {
      container.appendChild(draggable);
    } else {
      container.insertBefore(draggable, afterElement);
    }

    updateTaskCounts();
  });
});

function getDragAfterElement(container, y) {
  const draggableElements = [
    ...container.querySelectorAll(".task-card:not(.dragging)"),
  ];

  return draggableElements.reduce(
    (closest, child) => {
      const box = child.getBoundingClientRect();
      const offset = y - box.top - box.height / 2;

      if (offset < 0 && offset > closest.offset) {
        return { offset: offset, element: child };
      } else {
        return closest;
      }
    },
    { offset: Number.NEGATIVE_INFINITY }
  ).element;
}

function updateTaskCounts() {
  containers.forEach((container) => {
    const count = container.querySelectorAll(".task-card").length;
    const header = container
      .closest(".kanban-column")
      .querySelector(".task-count");
    if (header) {
      header.innerText = count;
    }
  });
}
