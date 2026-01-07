

document.addEventListener("DOMContentLoaded", () => {
  const counters = document.querySelectorAll(".statis h2");
  let started = false; // detect if animation is running

  function animateCounter(el, duration = 2500) {
    const target = +el.dataset.target;
    const startTime = performance.now();

    function update(now) {
      const elapsed = now - startTime;
      const progress = Math.min(elapsed / duration, 1);

      // FAST → SLOW → SLOWER → SLOWEST
      // Strong ease-out (cubic-bezier style)
      const eased = 1 - Math.pow(1 - progress, 6);

      el.textContent = Math.floor(eased * target);

      if (progress < 1) requestAnimationFrame(update);
      else el.textContent = target;
    }

    requestAnimationFrame(update);
  }

  // Observe enter and leave
  const observer = new IntersectionObserver(
    (entries) => {
      const entry = entries[0];

      if (entry.isIntersecting) {
        if (!started) {
          counters.forEach((counter) => animateCounter(counter));
          started = true;
        }
      } else {
        // On leave: RESET to 0 and allow to run again
        counters.forEach((c) => (c.textContent = "0"));
        started = false;
      }
    },
    { threshold: 0.3 }
  );

  observer.observe(document.getElementById("statsSection"));
});

