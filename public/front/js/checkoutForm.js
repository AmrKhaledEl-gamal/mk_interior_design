document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".checkoutInputs");
  const termsCheckbox = document.getElementById("termsAndConditions");
  const termsPopup = document.getElementById("termsPopup");

  if (!form || !termsCheckbox || !termsPopup) {
    console.error("Checkout elements not found");
    return;
  }

  form.addEventListener("submit", e => {

    if (!termsCheckbox.checked) {
      e.preventDefault();

      termsPopup.classList.add("show");

      setTimeout(() => {
        termsPopup.classList.remove("show");
      }, 6000);
    }
  });
});
