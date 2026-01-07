const map = L.map("map", {
    center: [30, -95], // roughly center of North America
    zoom: 3, // starting zoom
    minZoom: 3, // can't zoom out further than continent view
    maxZoom: 6, // can zoom in a little
    maxBounds: [
        [83, -180], // NorthWest corner
        [-60, -30], // SouthEast corner
    ],
    maxBoundsViscosity: 0.5,
});

// Base map
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; OpenStreetMap contributors",
}).addTo(map);

// Red icon for markers
const redIcon = L.icon({
    iconUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png",
    iconRetinaUrl:
        "https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png",
    shadowUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png",
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    shadowSize: [41, 41],
});

// Function to add pin with HTML popup
function addPin(lat, lng, companyData) {
    const popupContent = `
    <div>
      <strong>Company:</strong> ${companyData.name}<br>
      <strong>Phone:</strong> ${companyData.phone}<br>
      <strong>Website:</strong> <a href="${companyData.website}" target="_blank">${companyData.website}</a><br>
      <strong>Map Location:</strong> <a href="${companyData.mapLink}" target="_blank">View on Map</a>
    </div>
  `;

    L.marker([lat, lng], {
        icon: redIcon,
    })
        .addTo(map)
        .bindPopup(popupContent);
}

// Example locations
addPin(40.7128, -74.006, {
    name: "Company New York",
    phone: "UAN: 123-456-789",
    website: "https://example-ny.com",
    mapLink: "https://www.google.com/maps?q=New+York",
});

addPin(34.0522, -118.2437, {
    name: "Company Los Angeles",
    phone: "UAN: 987-654-321",
    website: "https://example-la.com",
    mapLink: "https://www.google.com/maps?q=Los+Angeles",
});
