const stateNames = {
  // States
  AL: "Alabama",
  AK: "Alaska",
  AZ: "Arizona",
  AR: "Arkansas",
  CA: "California",
  CO: "Colorado",
  CT: "Connecticut",
  DE: "Delaware",
  FL: "Florida",
  GA: "Georgia",
  HI: "Hawaii",
  ID: "Idaho",
  IL: "Illinois",
  IN: "Indiana",
  IA: "Iowa",
  KS: "Kansas",
  KY: "Kentucky",
  LA: "Louisiana",
  ME: "Maine",
  MD: "Maryland",
  MA: "Massachusetts",
  MI: "Michigan",
  MN: "Minnesota",
  MS: "Mississippi",
  MO: "Missouri",
  MT: "Montana",
  NE: "Nebraska",
  NV: "Nevada",
  NH: "New Hampshire",
  NJ: "New Jersey",
  NM: "New Mexico",
  NY: "New York",
  NC: "North Carolina",
  ND: "North Dakota",
  OH: "Ohio",
  OK: "Oklahoma",
  OR: "Oregon",
  PA: "Pennsylvania",
  RI: "Rhode Island",
  SC: "South Carolina",
  SD: "South Dakota",
  TN: "Tennessee",
  TX: "Texas",
  UT: "Utah",
  VT: "Vermont",
  VA: "Virginia",
  WA: "Washington",
  WV: "West Virginia",
  WI: "Wisconsin",
  WY: "Wyoming",

  // Federal district
  DC: "District of Columbia",

  // US territories
  AS: "American Samoa",
  GU: "Guam",
  MP: "Northern Mariana Islands",
  PR: "Puerto Rico",
  VI: "U.S. Virgin Islands",

  // Associated states (USPS)
  FM: "Federated States of Micronesia",
  MH: "Marshall Islands",
  PW: "Palau",

  // Military
  AA: "Armed Forces Americas",
  AE: "Armed Forces Europe",
  AP: "Armed Forces Pacific"
};


function applyWheelLock(el) {
  if (!el) return;

  el.addEventListener(
    "wheel",
    (e) => {
      const delta = e.deltaY;
      const canScrollUp = el.scrollTop > 0;
      const canScrollDown =
        el.scrollTop + el.clientHeight < el.scrollHeight;

      if ((delta < 0 && canScrollUp) || (delta > 0 && canScrollDown)) {
        e.stopPropagation(); // prevent page / Lenis scroll
      }
    },
    { passive: true }
  );
}


const zipInput = document.getElementById("zipCode");
const stateSelect = document.getElementById("state");
const cityInput = document.getElementById("city");

let zipData = [];
let zipLookup = {};



// ZIP suggestions box
const zipSuggestionBox = document.createElement("ul");
zipSuggestionBox.className = "zip-suggestions";
zipInput.parentElement.appendChild(zipSuggestionBox);
applyWheelLock(zipSuggestionBox);
// City suggestions box
const citySuggestionBox = document.createElement("ul");
citySuggestionBox.className = "city-suggestions";
cityInput.parentElement.appendChild(citySuggestionBox);
applyWheelLock(citySuggestionBox);

/* -----------------------------------------
    1. Fetch JSON once
------------------------------------------- */
fetch(
    "https://raw.githubusercontent.com/millbj92/US-Zip-Codes-JSON/refs/heads/master/USCities.json"
)
    .then((res) => res.json())
    .then((data) => {
        zipData = data;

        // ZIP lookup
        zipData.forEach((item) => {
            zipLookup[item.zip_code] = item;
        });

        populateStates();
        stateSelect.addEventListener("change", () => {
  const selectedState = stateSelect.value;

  // Nothing selected → clear dependent fields
  if (!selectedState) {
    zipInput.value = "";
    cityInput.value = "";
    return;
  }

  // Validate current ZIP
  if (zipInput.value) {
    const zipItem = zipLookup[zipInput.value];
    if (!zipItem || zipItem.state !== selectedState) {
      zipInput.value = "";
    }
  }

  // Validate current City
  if (cityInput.value) {
    const cityItem = zipData.find(
      x =>
        x.city.toLowerCase() === cityInput.value.toLowerCase() &&
        x.state === selectedState
    );

    if (!cityItem) {
      cityInput.value = "";
    }
  }

  // Clear suggestions (UX polish)
  zipSuggestionBox.innerHTML = "";
  citySuggestionBox.innerHTML = "";
});
    });

/* -----------------------------------------
    2. Populate States (unique list)
------------------------------------------- */
function populateStates() {
  const states = [...new Set(zipData.map(x => x.state))].sort();

  states.forEach(code => {
    const opt = document.createElement("option");
    opt.value = code;
    opt.textContent = `${stateNames[code]} (${code})`;
    stateSelect.appendChild(opt);
  });
}
/* -----------------------------------------
    3. ZIP Suggestions
------------------------------------------- */
zipInput.addEventListener("focus", () => {
    // Show suggestions even if empty
    showZipSuggestions(zipInput.value.trim() || "");
});

zipInput.addEventListener("input", () => {
    showZipSuggestions(zipInput.value.trim());
});

// Handle Enter key for ZIP
zipInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        e.preventDefault();
        selectClosestZip(zipInput.value.trim());
    }
});

/* -----------------------------------------
    ZIP Suggestions
------------------------------------------- */
function showZipSuggestions(val) {
    // ✅ Only run if ZIP input and state select are not readonly/disabled
    if (zipInput.readOnly || zipInput.disabled || stateSelect.disabled) return;

    zipSuggestionBox.innerHTML = "";

    let matches = zipData;

    // Filter by state if selected
    if (stateSelect.value) {
        matches = matches.filter(x => x.state === stateSelect.value);
    }

    // Filter by typed ZIP
    if (val !== "") {
        matches = matches.filter(x => String(x.zip_code).startsWith(val));
    }

    matches = matches.slice(0, 10);

    if (matches.length === 0) {
        zipSuggestionBox.innerHTML = `<li class="no-results">No results <span class="red">(${val || "all"})</span></li>`;
        return;
    }

    matches.forEach(item => {
        const boldZip = String(item.zip_code).replace(
            val,
            `<strong>${val}</strong>`
        );

        // ✅ Show full state name + uppercase code
const fullState = stateNames[item.state] || "Other";
const stateDisplay = `${fullState} (${item.state})`;
        const li = document.createElement("li");
        li.innerHTML = `
            <div class="main-line">ZIP Code: ${boldZip}</div>
            <div class="sub-line">State: ${stateDisplay}</div>
            <div class="sub-line">City: ${item.city}</div>
        `;
        li.addEventListener("click", () => selectZip(item));
        zipSuggestionBox.appendChild(li);
    });
}




function selectZip(item) {
    zipInput.value = item.zip_code;
    stateSelect.value = item.state;
    cityInput.value = item.city;
    zipSuggestionBox.innerHTML = "";
    citySuggestionBox.innerHTML = "";
}

// Select closest ZIP on Enter
function selectClosestZip(val) {
    if (!val) return;
    const closest = zipData.find((x) => String(x.zip_code).startsWith(val));
    if (closest) selectZip(closest);
}

/* -----------------------------------------
    4. City Suggestions
------------------------------------------- */
cityInput.addEventListener("focus", () => {
    // Show suggestions even if empty
    showCitySuggestions(cityInput.value.trim() || "");
});

cityInput.addEventListener("input", () => {
    showCitySuggestions(cityInput.value.trim());
});

// Handle Enter key for city
cityInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        e.preventDefault();
        selectClosestCity(cityInput.value.trim());
    }
});

/* -----------------------------------------
    City Suggestions
------------------------------------------- */
function showCitySuggestions(val) {
    // ✅ Only run if city input and state select are not readonly/disabled
    if (cityInput.readOnly || cityInput.disabled || stateSelect.disabled) return;

    citySuggestionBox.innerHTML = "";

    let matches = zipData;

    if (stateSelect.value) {
        matches = matches.filter(x => x.state === stateSelect.value);
    }

    if (val !== "") {
        matches = matches.filter(x =>
            x.city.toLowerCase().startsWith(val.toLowerCase())
        );
    }

    matches = matches.slice(0, 10);

    if (matches.length === 0) {
        citySuggestionBox.innerHTML = `<li class="no-results">No results <span class="red">(${val})</span></li>`;
        return;
    }

    matches.forEach(item => {
        const cityName = item.city;
        const boldCity =
            cityName.substring(0, val.length).toUpperCase() === val.toUpperCase()
                ? `<strong>${cityName.substring(0, val.length)}</strong>${cityName.substring(val.length)}`
                : cityName;

const fullState = stateNames[item.state] || 'Other';
const stateDisplay = `${fullState} (${item.state})`;
        const li = document.createElement("li");
        li.innerHTML = `
            <div class="main-line">City: ${boldCity}</div>
            <div class="sub-line">State: ${stateDisplay}</div>
            <div class="sub-line">ZIP Code: ${item.zip_code}</div>
        `;

        li.addEventListener("click", () => {
            cityInput.value = item.city;
            stateSelect.value = item.state;
            zipInput.value = item.zip_code;
            citySuggestionBox.innerHTML = "";
            zipSuggestionBox.innerHTML = "";
        });

        citySuggestionBox.appendChild(li);
    });
}

// Select closest city on Enter
function selectClosestCity(val) {
    if (!val) return;

    let matches = zipData;
    if (stateSelect.value) {
        matches = matches.filter((x) => x.state === stateSelect.value);
    }

    const closest = matches.find((x) =>
        x.city.toLowerCase().startsWith(val.toLowerCase())
    );

    if (closest) {
        cityInput.value = closest.city;
        stateSelect.value = closest.state;
        zipInput.value = closest.zip_code;
        citySuggestionBox.innerHTML = "";
        zipSuggestionBox.innerHTML = "";
    }
}

/* -----------------------------------------
    5. Close suggestion boxes when clicking outside
------------------------------------------- */
document.addEventListener("click", (e) => {
    if (!zipInput.contains(e.target)) zipSuggestionBox.innerHTML = "";
    if (!cityInput.contains(e.target)) citySuggestionBox.innerHTML = "";
});
