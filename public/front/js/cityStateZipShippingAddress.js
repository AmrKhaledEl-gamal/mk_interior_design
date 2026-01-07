function applyWheelLock(elShipping) {
  if (!elShipping) return;

  elShipping.addEventListener(
    "wheel",
    (e) => {
      const delta = e.deltaY;
      const canScrollUp =
        elShipping.scrollTop > 0;
      const canScrollDown =
        elShipping.scrollTop + elShipping.clientHeight <
        elShipping.scrollHeight;

      if ((delta < 0 && canScrollUp) || (delta > 0 && canScrollDown)) {
        e.stopPropagation();
      }
    },
    { passive: true }
  );
}

const zipInputShipping = document.getElementById("zipCodeShipping");
const stateSelectShipping = document.getElementById("stateShipping");
const cityInputShipping = document.getElementById("cityShipping");

let zipDataShipping = [];
let zipLookupShipping = {};

// ZIP suggestions box
const zipSuggestionBoxShipping = document.createElement("ul");
zipSuggestionBoxShipping.className = "zip-suggestions";
zipInputShipping.parentElement.appendChild(zipSuggestionBoxShipping);
applyWheelLock(zipSuggestionBoxShipping);

// City suggestions box
const citySuggestionBoxShipping = document.createElement("ul");
citySuggestionBoxShipping.className = "city-suggestions";
cityInputShipping.parentElement.appendChild(citySuggestionBoxShipping);
applyWheelLock(citySuggestionBoxShipping);

/* -----------------------------------------
   1. Fetch JSON once
------------------------------------------- */
fetch(
  "https://raw.githubusercontent.com/millbj92/US-Zip-Codes-JSON/refs/heads/master/USCities.json"
)
  .then((res) => res.json())
  .then((data) => {
    zipDataShipping = data;

    zipDataShipping.forEach((item) => {
      zipLookupShipping[item.zip_code] = item;
    });

    populateStatesShipping();

    stateSelectShipping.addEventListener("change", () => {
      const selectedStateShipping = stateSelectShipping.value;

      if (!selectedStateShipping) {
        zipInputShipping.value = "";
        cityInputShipping.value = "";
        return;
      }

      if (zipInputShipping.value) {
        const zipItemShipping =
          zipLookupShipping[zipInputShipping.value];
        if (!zipItemShipping || zipItemShipping.state !== selectedStateShipping) {
          zipInputShipping.value = "";
        }
      }

      if (cityInputShipping.value) {
        const cityItemShipping = zipDataShipping.find(
          (x) =>
            x.city.toLowerCase() === cityInputShipping.value.toLowerCase() &&
            x.state === selectedStateShipping
        );
        if (!cityItemShipping) {
          cityInputShipping.value = "";
        }
      }

      zipSuggestionBoxShipping.innerHTML = "";
      citySuggestionBoxShipping.innerHTML = "";
    });
  });

/* -----------------------------------------
   2. Populate States
------------------------------------------- */
function populateStatesShipping() {
  const statesShipping = [
    ...new Set(zipDataShipping.map((x) => x.state)),
  ].sort();

  statesShipping.forEach((code) => {
    const opt = document.createElement("option");
    opt.value = code;
    opt.textContent = `${stateNames[code]} (${code})`;
    stateSelectShipping.appendChild(opt);
  });
}

/* -----------------------------------------
   3. ZIP Suggestions
------------------------------------------- */
zipInputShipping.addEventListener("focus", () => {
  showZipSuggestionsShipping(zipInputShipping.value.trim() || "");
});

zipInputShipping.addEventListener("input", () => {
  showZipSuggestionsShipping(zipInputShipping.value.trim());
});

zipInputShipping.addEventListener("keydown", (e) => {
  if (e.key === "Enter") {
    e.preventDefault();
    selectClosestZipShipping(zipInputShipping.value.trim());
  }
});

function showZipSuggestionsShipping(valShipping) {
  if (
    zipInputShipping.readOnly ||
    zipInputShipping.disabled ||
    stateSelectShipping.disabled
  )
    return;

  zipSuggestionBoxShipping.innerHTML = "";

  let matchesShipping = zipDataShipping;

  if (stateSelectShipping.value) {
    matchesShipping = matchesShipping.filter(
      (x) => x.state === stateSelectShipping.value
    );
  }

  if (valShipping !== "") {
    matchesShipping = matchesShipping.filter((x) =>
      String(x.zip_code).startsWith(valShipping)
    );
  }

  matchesShipping = matchesShipping.slice(0, 10);

  if (matchesShipping.length === 0) {
    zipSuggestionBoxShipping.innerHTML = `<li class="no-results">No results</li>`;
    return;
  }

  matchesShipping.forEach((itemShipping) => {
    const fullStateShipping = stateNames[itemShipping.state] || "Other";
    const li = document.createElement("li");
    li.innerHTML = `
      <div class="main-line">ZIP Code: ${itemShipping.zip_code}</div>
      <div class="sub-line">State: ${fullStateShipping} (${itemShipping.state})</div>
      <div class="sub-line">City: ${itemShipping.city}</div>
    `;
    li.addEventListener("click", () =>
      selectZipShipping(itemShipping)
    );
    zipSuggestionBoxShipping.appendChild(li);
  });
}

function selectZipShipping(itemShipping) {
  zipInputShipping.value = itemShipping.zip_code;
  stateSelectShipping.value = itemShipping.state;
  cityInputShipping.value = itemShipping.city;
  zipSuggestionBoxShipping.innerHTML = "";
  citySuggestionBoxShipping.innerHTML = "";
}

function selectClosestZipShipping(valShipping) {
  if (!valShipping) return;
  const closestShipping = zipDataShipping.find((x) =>
    String(x.zip_code).startsWith(valShipping)
  );
  if (closestShipping) selectZipShipping(closestShipping);
}

/* -----------------------------------------
   4. City Suggestions
------------------------------------------- */
cityInputShipping.addEventListener("focus", () => {
  showCitySuggestionsShipping(cityInputShipping.value.trim() || "");
});

cityInputShipping.addEventListener("input", () => {
  showCitySuggestionsShipping(cityInputShipping.value.trim());
});

cityInputShipping.addEventListener("keydown", (e) => {
  if (e.key === "Enter") {
    e.preventDefault();
    selectClosestCityShipping(cityInputShipping.value.trim());
  }
});

function showCitySuggestionsShipping(valShipping) {
  if (
    cityInputShipping.readOnly ||
    cityInputShipping.disabled ||
    stateSelectShipping.disabled
  )
    return;

  citySuggestionBoxShipping.innerHTML = "";

  let matchesShipping = zipDataShipping;

  if (stateSelectShipping.value) {
    matchesShipping = matchesShipping.filter(
      (x) => x.state === stateSelectShipping.value
    );
  }

  if (valShipping !== "") {
    matchesShipping = matchesShipping.filter((x) =>
      x.city.toLowerCase().startsWith(valShipping.toLowerCase())
    );
  }

  matchesShipping = matchesShipping.slice(0, 10);

  matchesShipping.forEach((itemShipping) => {
    const fullStateShipping = stateNames[itemShipping.state] || "Other";
    const li = document.createElement("li");
    li.innerHTML = `
      <div class="main-line">City: ${itemShipping.city}</div>
      <div class="sub-line">State: ${fullStateShipping} (${itemShipping.state})</div>
      <div class="sub-line">ZIP Code: ${itemShipping.zip_code}</div>
    `;
    li.addEventListener("click", () => {
      cityInputShipping.value = itemShipping.city;
      stateSelectShipping.value = itemShipping.state;
      zipInputShipping.value = itemShipping.zip_code;
      citySuggestionBoxShipping.innerHTML = "";
      zipSuggestionBoxShipping.innerHTML = "";
    });
    citySuggestionBoxShipping.appendChild(li);
  });
}

function selectClosestCityShipping(valShipping) {
  if (!valShipping) return;

  let matchesShipping = zipDataShipping;
  if (stateSelectShipping.value) {
    matchesShipping = matchesShipping.filter(
      (x) => x.state === stateSelectShipping.value
    );
  }

  const closestShipping = matchesShipping.find((x) =>
    x.city.toLowerCase().startsWith(valShipping.toLowerCase())
  );

  if (closestShipping) {
    cityInputShipping.value = closestShipping.city;
    stateSelectShipping.value = closestShipping.state;
    zipInputShipping.value = closestShipping.zip_code;
    citySuggestionBoxShipping.innerHTML = "";
    zipSuggestionBoxShipping.innerHTML = "";
  }
}

/* -----------------------------------------
   5. Close suggestion boxes
------------------------------------------- */
document.addEventListener("click", (e) => {
  if (!zipInputShipping.contains(e.target))
    zipSuggestionBoxShipping.innerHTML = "";
  if (!cityInputShipping.contains(e.target))
    citySuggestionBoxShipping.innerHTML = "";
});
