<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
    <title>EV charger locator</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script src="https://cdn.maptiler.com/maptiler-geocoding-control/v0.0.98/leaflet.umd.js"></script>
    <link href="https://cdn.maptiler.com/maptiler-geocoding-control/v0.0.98/style.css" rel="stylesheet">
    <style>
      /* Add styling for the dashboard */
      #dashboard {
        position: absolute;
        top: 0;
        left: 0;
        width: 400px; /* Increase width for a wider dashboard */
        height: 100%; /* Use 100% height for a larger dashboard */
        background-color: whitesmoke;
        padding-top: 20px; /* Increase padding for spacing */
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        overflow: hidden; /* Hide overflow */
        display: flex;
        flex-direction: column;
        align-items: center; /* Center items horizontally */
      }
      /* Add styling for the search input and button container */
      .search-container {
        width: 80%; /* Reduce the width of the search container */
        display: flex;
        align-items: center; /* Center items vertically */
        border: 1px solid black; /* Add a border */
        border-radius: 10px; /* Add border radius */
      }
      /* Add styling for the search input */
      #search-input {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px 0 0 5px; 
        /* Rounded corners on the left side */
      }
      /* Add styling for the search button */
      #search-button {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        cursor: pointer;
        border-radius: 0 5px 5px 0; /* Rounded corners on the right side */
      }
      #search-button:hover {
        background-color: #0056b3;
      }
      /* Adjust the map position to the right of the dashboard */
      #map {
        position: absolute;
        top: 0;
        left: 400px; /* Width of the dashboard */
        right: 0;
        bottom: 0;
      }
      /* Style for the search results */
      #search-results {
        overflow-y: scroll;
        scrollbar-width: thin;
        max-height: calc(100% - 140px);
        padding: 10px;
      }
      .search-result {
        margin-bottom: 20px;
        padding: 10px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 5px;
      }
      /* Style for the "Book Now" button in the dashboard */
      .book-now-button {
        background-color: #007bff;
        color: #fff;
        padding: 5px 10px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        display: inline-block;
        margin-top: 10px;
      }
      .book-now-button:hover {
        background-color: #0056b3;
      }

      .result-name {
        font-size: 24px; /* Large font size for name */
        font-weight: bold; /* Bold font weight for name */
        margin-bottom: 5px; /* Small margin below name */
      }
      .result-address {
        font-size: 18px; /* Medium font size for address */
        margin-bottom: 5px; /* Small margin below address */
      }

      /* Style for the city */
      .result-city {
        font-size: 14px; /* Smaller font size for city */
      }

      .name{
        font-size: 20px;
        color: black;
      }

      .address{
        font-size: 15px;
        color: grey;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }

      .label{
        font-size: 20px;
        padding: 10px;
      }
    </style>
  </head>
  <body>
    <!-- Create the dashboard on the left side -->
    <div id="dashboard">
      <!-- Add a label above the search bar -->
      <label for="search-input" ><div class="label">Enter the location:</div></label>
      <!-- Add a search container to the dashboard -->
      <div class="search-container">
        <!-- Add a search input to the container -->
        <input type="text" id="search-input" placeholder="Search...">
        <!-- Add a search button with an icon to the container -->
        <button id="search-button">&#128269;</button>
      </div>
      <!-- Display search results here -->
      <div id="search-results"></div>
      <!-- You can add additional dashboard elements here -->
    </div>
    <div id="map">
      <a href="https://www.maptiler.com" style="position:absolute;left:10px;bottom:10px;z-index:999;"><img src="https://api.maptiler.com/resources/logo.svg" alt="MapTiler logo"></a>
    </div>
    <p><a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a></p>
<script>
  const key = 'c36N8odexXbGeaXCCHE3';
  const map = L.map('map').setView([19.19845, 72.82572], 11);
  const markersLayer = L.layerGroup().addTo(map); // Create a layer group for markers

  // Define the URL of your form
  const formUrl = 'form.php';

  L.tileLayer(`https://api.maptiler.com/maps/streets-v2/{z}/{x}/{y}.png?key=${key}`, {
    tileSize: 512,
    zoomOffset: -1,
    minZoom: 1,
    attribution: "\u003ca href=\"https://www.maptiler.com/copyright/\" target=\"_blank\"\u003e\u0026copy; MapTiler\u003c/a\u003e \u003ca href=\"https://www.openstreetmap.org/copyright\" target=\"_blank\"\u003e\u0026copy; OpenStreetMap contributors\u003c/a\u003e",
    crossOrigin: true
  }).addTo(map);

  L.control.maptilerGeocoding({ apiKey: key }).addTo(map); // Add the MapTiler Geocoding control to the map

  let searchData = []; // Initialize an empty array to store the data

  function loadMarkerData() {
    fetch('markers.json')
      .then((response) => response.json())
      .then((data) => {
        // Loop through the data and create markers
        data.forEach((markerInfo) => {
          const marker = L.marker([markerInfo.lat, markerInfo.lng]);
          marker.bindPopup(`
            <div class="name">${markerInfo.name}</div><br>
            <strong>Address:</strong> ${markerInfo.address}<br>
            <strong>City:</strong> ${markerInfo.city}<br>
            
          `);

          // Add the marker to the layer group
          markersLayer.addLayer(marker);
        });

        // Store the data in the searchData array
        searchData = data;
      })
      .catch((error) => {
        console.error('Error loading markers.json data:', error);
      });
  }

  // Call the function to load marker data when your website loads
  loadMarkerData();

  // Function to display search results on the dashboard and open the form when the "Book Now" button is clicked
  function displaySearchResults(results) {
    const searchResultsContainer = document.getElementById('search-results');
    let resultHTML = '';

    if (results.length === 0) {
      resultHTML = '<p>No results found.</p>';
    } else {
      results.forEach((item) => {
        resultHTML += `
          <div class="search-result">
           <div class="name">${item.name}</div><br>
           <div class="address">${item.address}</div><br>
            <strong>City:</strong> ${item.city}<br>
            <button class="book-now-button" onclick="openForm('${formUrl}')">Book Now</button>
          </div>
        `;
      });

      // Find all the corresponding markers and open their popups
      markersLayer.eachLayer((marker) => {
        const markerInfo = marker.getPopup().getContent();
        if (results.some((item) => markerInfo.includes(item.name) && markerInfo.includes(item.address) && markerInfo.includes(item.city))) {
          marker.openPopup();
        }
      });
    }

    searchResultsContainer.innerHTML = resultHTML;
  }

  // Function to open the form in a new window or tab
  function openForm(url) {
    window.open(url, '_blank');
  }

  document.getElementById('search-button').addEventListener('click', function() {
    const searchInput = document.getElementById('search-input').value.toLowerCase(); // Convert search input to lowercase for case-insensitive search
    const filteredData = searchData.filter((item) => {
      // Customize this filtering logic based on your data structure
      return (
        item.name.toLowerCase().includes(searchInput) ||
        item.address.toLowerCase().includes(searchInput) ||
        item.city.toLowerCase().includes(searchInput)
      );
    });

    // Display the filtered results on the dashboard and open the form when the "Book Now" button is clicked
    displaySearchResults(filteredData);
  });
</script>

  </body>
</html>
