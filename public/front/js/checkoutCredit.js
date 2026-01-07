document.addEventListener("DOMContentLoaded", () => {
  // ---------------------------
  //  NAME LIVE UPDATE
  // ---------------------------
  const nameInput = document.getElementById("cardName");
  const nameModel = document.getElementById("creditName");

  nameInput.addEventListener("input", () => {
    nameModel.textContent = nameInput.value.trim() || "Name Surname";
  });


 // ---------------------------
//  CREDIT NUMBER LIVE UPDATE + VALIDATION
// ---------------------------
const numberInput = document.getElementById("creditNumber");
const creditBlocks = document.querySelectorAll("#creditSerialNumber span");

numberInput.addEventListener("input", () => {
  let value = numberInput.value.replace(/\D/g, ""); // only digits

  // ----------------------------------------
  // PREVENT INVALID PREFIXES WHILE TYPING
  // ----------------------------------------

  // If length 1 → allow only possible first digits: 3, 4, 5, 2
  if (value.length === 1) {
    if (!/[2345]/.test(value)) {
      value = ""; // invalid first digit → remove
    }
  }

  // If length 2 → check prefixes that must match card rules
  if (value.length === 2) {
    const first2 = value.slice(0, 2);

    // If starts with 4 (Visa) → second digit can be anything
    if (value[0] === "4") {
      // always valid
    }

    // If starts with 3 → only 34 or 37 allowed (Amex)
    else if (value[0] === "3") {
      if (!/(34|37)/.test(first2)) {
        value = value[0]; // keep only "3" and block second digit
      }
    }

    // If starts with 5 → Mastercard 51–55
    else if (value[0] === "5") {
      if (!/^5[1-5]$/.test(first2)) {
        value = value[0]; // keep only "5"
      }
    }

    // If starts with 2 → Mastercard 2221–2720 (complex)
    else if (value[0] === "2") {
      // For now only allow digits that could still form a valid MC range:
      if (!/^[2][0-7]?$/.test(value)) {
        value = value[0]; // keep only "2"
      }
    }
  }

  // (You can extend the 2221–2720 validation deeper if needed)

  // Apply result back to input
  numberInput.value = value;


  // Fill the credit card preview
  let padded = value.padEnd(16, "0");
  for (let i = 0; i < 4; i++) {
    creditBlocks[i].textContent = padded.slice(i * 4, i * 4 + 4);
  }

if (numberInput.value.length > 0) {
    detectCardType(padded);
} else {
    detectCardType(""); // treat as empty
}});



  // ---------------------------
  //  EXPIRY DATE LIVE UPDATE
  // ---------------------------
  const monthSelect = document.getElementById("month");
  const yearSelect = document.getElementById("year");
  const monthModel = document.getElementById("expireMonth");
  const yearModel = document.getElementById("expireYear");


monthSelect.addEventListener("change", () => {
  monthModel.textContent = monthSelect.value || "MM";
});

yearSelect.addEventListener("change", () => {
  yearModel.textContent = yearSelect.value || "YYYY";
});

  // ---------------------------
  //  CARD TYPE DETECTOR
  // ---------------------------
function detectCardType(num) {
  const visa = document.querySelector('#visa');
  const amex = document.querySelector('#amex');
  const mc = document.querySelector('#mastercard');
  const invalidText = document.getElementById("invalidCardText");

  const images = [visa, amex, mc];
  images.forEach(img => img.classList.remove("activeCreditCard"));

  // -----------------------------
  // 1. EMPTY → "ENTER SERIAL NUMBER"
  // -----------------------------
  if (!num || /^0+$/.test(num)) {
    invalidText.style.display = "block";
    invalidText.textContent = "ENTER SERIAL NUMBER";
    invalidText.style.color = "#007bff"; // BLUE
    return;
  }

  // -----------------------------
  // 2. VALID CARD TYPES
  // -----------------------------

  // Visa
  if (/^4/.test(num)) {
    invalidText.style.display = "none";
    visa.classList.add("activeCreditCard");
    return;
  }

  // Amex
  if (/^(34|37)/.test(num)) {
    invalidText.style.display = "none";
    amex.classList.add("activeCreditCard");
    return;
  }

  // Mastercard
  if (/^5[1-5]/.test(num) || /^2(2[2-9][1-9]|[3-6]\d{2}|7[01]\d|720)/.test(num)) {
    invalidText.style.display = "none";
    mc.classList.add("activeCreditCard");
    return;
  }

  // -----------------------------
  // 3. INVALID PREFIX → ERROR
  // -----------------------------
  invalidText.style.display = "block";
  invalidText.textContent = "INVALID SERIAL NUMBER";
  invalidText.style.color = "#c31b1b"; // RED
}












// ---------------------------
// FORM VALIDATION + SUBMIT
// ---------------------------
const form = document.getElementById("personalDetails");

form.addEventListener("submit", (e) => {
  e.preventDefault(); // stop default submission

  // Name validation
  const nameInput = document.getElementById("cardName");
  if (!nameInput.value.trim()) {
    alert("الرجاء إدخال الأسم على البطاقة");
    nameInput.focus();
    return;
  }

  // Card number validation
  const numberInput = document.getElementById("creditNumber");
  const cardValue = numberInput.value.trim();
  if (!/^\d{16}$/.test(cardValue)) {
    alert("الرجاء إدخال رقم البطاقة صحيح (16 رقم)");
    numberInput.focus();
    return;
  }
  if (!/^4/.test(cardValue) && !/^(34|37)/.test(cardValue) &&
      !/^5[1-5]/.test(cardValue) && !/^2(2[2-9][1-9]|[3-6]\d{2}|7[01]\d|720)/.test(cardValue)) {
    alert("رقم البطاقة غير صالح");
    numberInput.focus();
    return;
  }

  // Expiry date validation
  const monthSelect = document.getElementById("month");
  const yearSelect = document.getElementById("year");
  if (!monthSelect.value || !yearSelect.value) {
    alert("الرجاء اختيار تاريخ انتهاء صالح");
    return;
  }
  const expMonth = parseInt(monthSelect.value, 10);
  const expYear = parseInt(yearSelect.value, 10);
  const today = new Date();
  if (expYear < today.getFullYear() || (expYear === today.getFullYear() && expMonth < (today.getMonth() + 1))) {
    alert("تاريخ الانتهاء غير صالح");
    return;
  }

  // CVC validation
  const cvcInput = document.getElementById("CVC");
  if (!/^\d{3}$/.test(cvcInput.value.trim())) {
    alert("الرجاء إدخال CVC صحيح (3 أرقام)");
    cvcInput.focus();
    return;
  }

  // All valid → redirect
});


});
