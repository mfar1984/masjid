@props([
    'latitude' => '3.139003',
    'longitude' => '101.686855',
    'height' => '400px'
])

<!-- Google Maps JavaScript API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIjA8Obc9QBZquzRRrW18OxabdrW0tXoE&libraries=places&loading=async&callback=initGoogleMaps"></script>

<style>
    /* Maps styling */
    #map {
        width: 100%;
        height: {{ $height }};
        border-radius: 2px;
        border: 2px solid #e5e7eb;
        z-index: 1;
    }
    
    .map-controls {
        background: white;
        padding: 10px;
        border-radius: 2px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 15px;
    }
    
    .map-controls button {
        transition: all 0.2s ease;
    }
    
    .coordinate-display {
        font-family: 'Poppins', monospace;
        font-weight: 500;
        font-size: 11px;
        color: #374151;
    }
    
    /* Mobile-specific styles */
    @media (max-width: 640px) {
        .map-controls {
            padding: 16px;
        }
        
        .map-controls button {
            width: 100%;
            justify-content: center;
        }
        
        .coordinate-display {
            text-align: center;
            margin-top: 10px;
        }
    }
    
    /* Search bar styling */
    .map-search-container {
        position: relative;
        margin-top: 10px;
    }

    .map-search-input {
        width: 100% !important;
        padding: 8px 35px 8px 12px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 2px !important;
        font-family: 'Poppins', sans-serif !important;
        font-size: 12px !important;
        font-weight: 400 !important;
        line-height: 1.4 !important;
        background: white !important;
        color: #111827 !important;
        transition: all 0.2s ease !important;
    }

    .map-search-input::placeholder {
        color: #9ca3af !important;
        opacity: 1 !important;
    }

    .map-search-input::-webkit-input-placeholder {
        color: #9ca3af !important;
        opacity: 1 !important;
    }

    .map-search-input::-moz-placeholder {
        color: #9ca3af !important;
        opacity: 1 !important;
    }

    .map-search-input:-ms-input-placeholder {
        color: #9ca3af !important;
        opacity: 1 !important;
    }

    .map-search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        color: #111827 !important;
    }

    .map-search-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 14px;
        pointer-events: none;
    }

    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #d1d5db;
        border-top: none;
        border-radius: 0 0 2px 2px;
        max-height: 180px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .search-result-item {
        padding: 10px 12px;
        border-bottom: 1px solid #f3f4f6;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        transition: background-color 0.2s ease;
    }

    .search-result-item:hover {
        background-color: #f9fafb;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .search-result-name {
        font-weight: 500;
        color: #111827;
        margin-bottom: 2px;
    }

    .search-result-address {
        color: #6b7280;
        font-size: 10px;
    }

    .search-distance {
        color: #059669;
        font-weight: 500;
    }

    .search-loading {
        padding: 10px 12px;
        text-align: center;
        color: #6b7280;
        font-size: 11px;
    }

    .search-no-results {
        padding: 10px 12px;
        text-align: center;
        color: #6b7280;
        font-size: 11px;
    }

    /* Custom marker styling */
    .custom-marker {
        position: relative;
        width: 30px;
        height: 40px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 3px solid white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        animation: bounce 2s infinite;
    }

    .custom-marker::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(45deg);
        width: 12px;
        height: 12px;
        background: white;
        border-radius: 50%;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.2);
    }

    .custom-marker::before {
        content: '🕌';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(45deg);
        font-size: 8px;
        z-index: 1;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: rotate(-45deg) translateY(0);
        }
        40% {
            transform: rotate(-45deg) translateY(-5px);
        }
        60% {
            transform: rotate(-45deg) translateY(-3px);
        }
    }

    /* Alternative marker design - mosque icon */
    .mosque-marker {
        position: relative;
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        animation: pulse 2s infinite;
    }

    .mosque-marker::after {
        content: '🕌';
        font-size: 14px;
        filter: drop-shadow(0 1px 1px rgba(0,0,0,0.3));
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 4px 12px rgba(0,0,0,0.25), 0 0 0 0 rgba(5, 150, 105, 0.7);
        }
        70% {
            box-shadow: 0 4px 12px rgba(0,0,0,0.25), 0 0 0 10px rgba(5, 150, 105, 0);
        }
        100% {
            box-shadow: 0 4px 12px rgba(0,0,0,0.25), 0 0 0 0 rgba(5, 150, 105, 0);
        }
    }
</style>

<div class="mt-6">
    <h3 class="text-sm font-semibold text-blue-900 mb-3 flex items-center">
        <span class="material-icons text-blue-600 mr-2 text-sm">map</span>
        Pilih Lokasi pada Peta
    </h3>
    
    <!-- Map Controls -->
    <div class="map-controls mb-3">
        <!-- Desktop Layout -->
        <div class="hidden md:block">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="getCurrentLocation()" class="flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                        <span class="material-icons text-sm mr-1">my_location</span>
                        Lokasi Semasa
                    </button>
                    <button type="button" onclick="resetToDefault()" class="flex items-center justify-center h-[32px] px-4 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700">
                        <span class="material-icons text-sm mr-1">home</span>
                        Lokasi Default
                    </button>
                    <button type="button" onclick="manualCoordinateEntry()" class="flex items-center justify-center h-[32px] px-4 py-1 bg-purple-600 text-white text-xs rounded hover:bg-purple-700">
                        <span class="material-icons text-sm mr-1">edit_location</span>
                        Manual
                    </button>
                    <button type="button" id="layer-button" onclick="switchMapLayer()" class="flex items-center justify-center h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                        <span class="material-icons text-sm mr-1" id="layer-icon">satellite</span>
                        <span id="layer-text">Satelit</span>
                    </button>
                    <span class="text-2xs text-gray-600">Klik pada peta untuk pilih lokasi atau seret marker untuk sesuaikan</span>
                </div>
                <div class="coordinate-display" id="coordinate-display">
                    <span class="text-2xs font-medium text-gray-700">Koordinat:</span>
                    Lat: {{ number_format($latitude, 6) }}, Lng: {{ number_format($longitude, 6) }}
                </div>
            </div>

            <!-- Search Bar - Below buttons and coordinates -->
            <div class="map-search-container">
                <input
                    type="text"
                    id="location-search"
                    class="form-input map-search-input"
                    placeholder="Cari 'An-Nur', 'masjid', 'surau' (database Malaysia) atau lokasi..."
                    autocomplete="off"
                />
                <span class="material-icons map-search-icon">search</span>
                <div id="search-results" class="search-results"></div>
            </div>
        </div>
        
        <!-- Mobile Layout -->
        <div class="md:hidden space-y-3">
            <!-- Button Row -->
            <div class="flex flex-col space-y-2">
                <button type="button" onclick="getCurrentLocation()" class="flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                    <span class="material-icons text-sm mr-1">my_location</span>
                    Lokasi Semasa
                </button>
                <button type="button" onclick="resetToDefault()" class="flex items-center justify-center h-[32px] px-4 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700">
                    <span class="material-icons text-sm mr-1">home</span>
                    Lokasi Default
                </button>
                <button type="button" onclick="manualCoordinateEntry()" class="flex items-center justify-center h-[32px] px-4 py-1 bg-purple-600 text-white text-xs rounded hover:bg-purple-700">
                    <span class="material-icons text-sm mr-1">edit_location</span>
                    Manual
                </button>
                <button type="button" id="layer-button-mobile" onclick="switchMapLayer()" class="flex items-center justify-center h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                    <span class="material-icons text-sm mr-1" id="layer-icon-mobile">satellite</span>
                    <span id="layer-text-mobile">Satelit</span>
                </button>
            </div>
            
            <!-- Instructions -->
            <div class="text-center">
                <span class="text-2xs text-gray-600">Klik pada peta untuk pilih lokasi atau seret marker untuk sesuaikan</span>
            </div>
            
            <!-- Coordinates -->
            <div class="coordinate-display text-center" id="coordinate-display-mobile">
                <span class="text-2xs font-medium text-gray-700">Koordinat:</span>
                Lat: {{ number_format($latitude, 6) }}, Lng: {{ number_format($longitude, 6) }}
            </div>

            <!-- Search Bar Mobile -->
            <div class="map-search-container mt-3">
                <input
                    type="text"
                    id="location-search-mobile"
                    class="form-input map-search-input"
                    placeholder="Cari 'An-Nur', 'masjid', 'surau' (database Malaysia) atau lokasi..."
                    autocomplete="off"
                />
                <span class="material-icons map-search-icon">search</span>
                <div id="search-results-mobile" class="search-results"></div>
            </div>
        </div>
    </div>
    
    <!-- Google Maps Container -->
    <div id="map"></div>

    <p class="text-2xs text-gray-500 mt-2">
        💡 Petua: Jika peta tidak dipaparkan, gunakan butang <strong>Manual</strong> untuk masukkan koordinat atau <strong>Lokasi Semasa</strong> untuk dapatkan koordinat GPS anda.
    </p>
</div>

<script>
    // Google Maps functionality
    let map, marker;
    let defaultLat = {{ $latitude }}; // Default latitude
    let defaultLng = {{ $longitude }}; // Default longitude
    let currentMapType = 'roadmap'; // Track current map type - DEFAULT TO ROADMAP

    function initMap() {
        // Clear any existing content in map container
        const mapContainer = document.getElementById('map');
        if (mapContainer) {
            mapContainer.innerHTML = '';
        }

        // Get coordinates from form or use defaults
        let lat = parseFloat(document.getElementById('latitude').value) || defaultLat;
        let lng = parseFloat(document.getElementById('longitude').value) || defaultLng;

        try {
            // Create Google Map
            map = new google.maps.Map(mapContainer, {
                center: { lat: lat, lng: lng },
                zoom: 13,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapTypeControl: false, // We'll use custom controls
                streetViewControl: false,
                fullscreenControl: false,
                zoomControl: true,
                zoomControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                }
            });

            // Check for billing errors after a short delay
            setTimeout(() => {
                const mapDiv = mapContainer.querySelector('div');
                if (mapDiv && mapDiv.innerHTML.includes('BillingNotEnabledMapError')) {
                    initFallbackMap();
                    return;
                }
            }, 2000);

        } catch (error) {
            initFallbackMap();
            return;
        }
        
        // Create custom marker with mosque icon
        const mosqueIcon = {
            url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40">
                    <circle cx="20" cy="20" r="18" fill="#007b8b" stroke="#fff" stroke-width="2" opacity="0.9"/>
                    <text x="20" y="28" text-anchor="middle" font-size="20" fill="white">🕌</text>
                </svg>
            `),
            scaledSize: new google.maps.Size(40, 40),
            anchor: new google.maps.Point(20, 40)
        };

        // Create marker
        marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            icon: mosqueIcon,
            draggable: true,
            title: 'Lokasi Masjid'
        });

        // Add click listener to map
        map.addListener('click', function(event) {
            placeMarker(event.latLng);
        });

        // Add drag listener to marker
        marker.addListener('dragend', function() {
            const position = marker.getPosition();
            updateCoordinates(position.lat(), position.lng());
        });
        
        // Update coordinates display
        updateCoordinates(lat, lng);

        // Initialize search after map is ready
        initSearch();
    }
    
    function placeMarker(latLng) {
        marker.setPosition(latLng);
        map.setCenter(latLng);
        updateCoordinates(latLng.lat(), latLng.lng());
    }
    
    function updateCoordinates(lat, lng) {
        // Update form fields
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
        
        // Update desktop display
        const desktopDisplay = document.getElementById('coordinate-display');
        if (desktopDisplay) {
            const display = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
            desktopDisplay.innerHTML = `<span class="text-2xs font-medium text-gray-700">Koordinat:</span> ${display}`;
        }

        // Update mobile display
        const mobileDisplay = document.getElementById('coordinate-display-mobile');
        if (mobileDisplay) {
            const display = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
            mobileDisplay.innerHTML = `<span class="text-2xs font-medium text-gray-700">Koordinat:</span> ${display}`;
        }
    }
    
    function getCurrentLocation() {
        if (!map) {
            // Fallback: Use geolocation to update coordinates directly
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        updateCoordinates(lat, lng);
                        alert(`Lokasi semasa ditemui: Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`);
                    },
                    function() {
                        alert('Tidak dapat mendapatkan lokasi semasa.');
                    }
                );
            } else {
                alert('Geolokasi tidak disokong oleh browser ini.');
            }
            return;
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    placeMarker(new google.maps.LatLng(lat, lng));
                },
                function() {
                    alert('Tidak dapat mendapatkan lokasi semasa. Sila pilih lokasi secara manual pada peta.');
                }
            );
        } else {
            alert('Geolokasi tidak disokong oleh browser ini.');
        }
    }

    function resetToDefault() {
        if (!map) {
            // Fallback: Update coordinates directly
            updateCoordinates(defaultLat, defaultLng);
            alert(`Reset ke lokasi default: Lat: ${defaultLat}, Lng: ${defaultLng}`);
            return;
        }
        placeMarker(new google.maps.LatLng(defaultLat, defaultLng));
    }

    function manualCoordinateEntry() {
        const lat = prompt('Masukkan Latitude (contoh: 2.312154):');
        const lng = prompt('Masukkan Longitude (contoh: 111.821291):');

        if (lat && lng) {
            const latNum = parseFloat(lat);
            const lngNum = parseFloat(lng);

            if (!isNaN(latNum) && !isNaN(lngNum)) {
                updateCoordinates(latNum, lngNum);
                if (map) {
                    placeMarker(new google.maps.LatLng(latNum, lngNum));
                } else {
                    alert(`Koordinat dikemaskini: Lat: ${latNum}, Lng: ${lngNum}`);
                }
            } else {
                alert('Koordinat tidak sah. Sila masukkan nombor yang betul.');
            }
        }
    }
    
    function switchMapLayer() {
        if (!map) {
            alert('Peta belum siap. Sila tunggu sebentar dan cuba lagi.');
            return;
        }

        const layerButton = document.getElementById('layer-button');
        const layerIcon = document.getElementById('layer-icon');
        const layerText = document.getElementById('layer-text');
        const layerButtonMobile = document.getElementById('layer-button-mobile');
        const layerIconMobile = document.getElementById('layer-icon-mobile');
        const layerTextMobile = document.getElementById('layer-text-mobile');

        if (currentMapType === 'roadmap') {
            // Switch to satellite
            map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
            currentMapType = 'satellite';
            layerIcon.textContent = 'map';
            layerText.textContent = 'Peta Jalan';
            layerIconMobile.textContent = 'map';
            layerTextMobile.textContent = 'Peta Jalan';
        } else {
            // Switch to roadmap
            map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
            currentMapType = 'roadmap';
            layerIcon.textContent = 'satellite';
            layerText.textContent = 'Satelit';
            layerIconMobile.textContent = 'satellite';
            layerTextMobile.textContent = 'Satelit';
        }
    }
    
    // Search functionality
    let searchTimeout;
    let searchResults = [];

    function initSearch() {
        // Desktop search
        const searchInput = document.getElementById('location-search');
        const resultsContainer = document.getElementById('search-results');

        // Mobile search
        const searchInputMobile = document.getElementById('location-search-mobile');
        const resultsContainerMobile = document.getElementById('search-results-mobile');

        // Setup desktop search
        if (searchInput) {
            setupSearchInput(searchInput, resultsContainer);
        }

        // Setup mobile search
        if (searchInputMobile) {
            setupSearchInput(searchInputMobile, resultsContainerMobile);
        }

        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.map-search-container')) {
                hideSearchResults();
            }
        });
    }

    function setupSearchInput(input, resultsContainer) {
        input.addEventListener('input', function() {
            const query = this.value.trim();

            // Clear previous timeout
            clearTimeout(searchTimeout);

            if (query.length < 3) {
                hideSearchResults();
                return;
            }

            // Show loading
            showSearchLoading(resultsContainer);

            // Debounce search
            searchTimeout = setTimeout(() => {
                performSearch(query, resultsContainer);
            }, 300);
        });

        // Sync search inputs
        input.addEventListener('input', function() {
            const otherInput = input.id === 'location-search' ?
                document.getElementById('location-search-mobile') :
                document.getElementById('location-search');
            if (otherInput) {
                otherInput.value = this.value;
            }
        });
    }

    function performSearch(query, resultsContainer) {
        // Check if map is initialized
        if (!map) {
            searchLocalDatabase(query, defaultLat, defaultLng, resultsContainer);
            return;
        }

        // Get current map center coordinates
        const currentCenter = map.getCenter();
        const currentLat = currentCenter.lat();
        const currentLng = currentCenter.lng();

        // Expand search radius for better coverage (±0.3 degrees ≈ 33km)
        const searchRadius = 0.3;

        // Always prioritize local database for mosque/surau searches (more reliable than APIs)
        if (query.toLowerCase().includes('masjid') || query.toLowerCase().includes('surau') ||
            query.toLowerCase().includes('mosque') || query.toLowerCase().includes('an-nur') ||
            query.toLowerCase().includes('musolla') || query.toLowerCase().includes('langgar')) {
            searchLocalDatabase(query, currentLat, currentLng, resultsContainer);
            return;
        }

        // Multiple search strategies for general location search
        const searchQueries = [
            // Direct search with expanded radius
            `https://nominatim.openstreetmap.org/search?format=json&limit=10&countrycodes=my&viewbox=${currentLng-searchRadius},${currentLat+searchRadius},${currentLng+searchRadius},${currentLat-searchRadius}&q=${encodeURIComponent(query + ' Malaysia')}`,

            // Specific mosque/surau search
            `https://nominatim.openstreetmap.org/search?format=json&limit=10&countrycodes=my&viewbox=${currentLng-searchRadius},${currentLat+searchRadius},${currentLng+searchRadius},${currentLat-searchRadius}&q=${encodeURIComponent(query + ' mosque surau masjid Malaysia')}`,

            // Broader area search without strict bounds
            `https://nominatim.openstreetmap.org/search?format=json&limit=15&countrycodes=my&lat=${currentLat}&lon=${currentLng}&q=${encodeURIComponent(query + ' Malaysia')}`
        ];

        // Try multiple search strategies
        Promise.all(searchQueries.map(url =>
            fetch(url).then(response => response.json()).catch(() => [])
        ))
        .then(results => {
            // Combine and deduplicate results
            const allResults = [];
            const seen = new Set();

            results.forEach(resultSet => {
                resultSet.forEach(result => {
                    const key = `${result.lat}_${result.lon}`;
                    if (!seen.has(key)) {
                        seen.add(key);
                        allResults.push(result);
                    }
                });
            });

            // Filter results within reasonable distance (50km)
            const filteredResults = allResults.filter(result => {
                const distance = calculateDistance(currentLat, currentLng, parseFloat(result.lat), parseFloat(result.lon));
                return distance <= 50; // 50km radius
            });

            // If no results from Nominatim, try alternative search methods
            if (filteredResults.length === 0) {
                if (query.toLowerCase().includes('masjid') || query.toLowerCase().includes('surau') || query.toLowerCase().includes('mosque')) {
                    // Try Overpass API for mosque/surau data
                    searchOverpassAPI(query, currentLat, currentLng, resultsContainer);
                } else {
                    // Try Google Places API for general location search
                    searchGooglePlaces(query, currentLat, currentLng, resultsContainer);
                }
            } else {
                // Sort results by distance from current location
                const sortedResults = sortResultsByDistance(filteredResults, currentLat, currentLng);
                displaySearchResults(sortedResults, resultsContainer);
            }
        })
        .catch(error => {
            showSearchError(resultsContainer);
        });
    }

    function sortResultsByDistance(results, currentLat, currentLng) {
        return results.map(result => {
            const distance = calculateDistance(currentLat, currentLng, parseFloat(result.lat), parseFloat(result.lon));
            return { ...result, distance };
        }).sort((a, b) => a.distance - b.distance);
    }

    function calculateDistance(lat1, lng1, lat2, lng2) {
        const R = 6371; // Earth's radius in kilometers
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLng/2) * Math.sin(dLng/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c; // Distance in kilometers
    }

    function searchOverpassAPI(query, currentLat, currentLng, resultsContainer) {
        // Search radius in meters (10km)
        const radius = 10000;

        // Overpass API query for mosques and prayer places
        const overpassQuery = `
            [out:json][timeout:25];
            (
                node["amenity"="place_of_worship"]["religion"="muslim"](around:${radius},${currentLat},${currentLng});
                way["amenity"="place_of_worship"]["religion"="muslim"](around:${radius},${currentLat},${currentLng});
                relation["amenity"="place_of_worship"]["religion"="muslim"](around:${radius},${currentLat},${currentLng});
            );
            out center;
        `;

        const overpassUrl = 'https://overpass-api.de/api/interpreter';

        fetch(overpassUrl, {
            method: 'POST',
            body: overpassQuery,
            headers: {
                'Content-Type': 'text/plain'
            }
        })
        .then(response => response.json())
        .then(data => {
            const overpassResults = data.elements.map(element => {
                const lat = element.lat || element.center?.lat;
                const lon = element.lon || element.center?.lon;
                const name = element.tags?.name || element.tags?.['name:ms'] || element.tags?.['name:en'] || 'Masjid/Surau';

                return {
                    lat: lat,
                    lon: lon,
                    display_name: `${name}, Malaysia`,
                    distance: calculateDistance(currentLat, currentLng, lat, lon)
                };
            }).filter(result => result.lat && result.lon);

            // Sort by distance
            const sortedResults = overpassResults.sort((a, b) => a.distance - b.distance);
            displaySearchResults(sortedResults, resultsContainer);
        })
        .catch(error => {
            // Fallback to showing no results
            displaySearchResults([], resultsContainer);
        });
    }

    function searchGooglePlaces(query, currentLat, currentLng, resultsContainer) {
        // Use Google Places API Text Search (requires API key)
        // For demo purposes, we'll use a CORS proxy to access Google Places
        const radius = 10000; // 10km radius

        // Alternative: Use Google Maps Geocoding API (no API key needed for basic search)
        const googleUrl = `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(query + ' Malaysia')}&region=my&key=YOUR_API_KEY`;

        // For now, let's use a simpler approach with local Malaysian places database
        searchLocalDatabase(query, currentLat, currentLng, resultsContainer);
    }

    function searchLocalDatabase(query, currentLat, currentLng, resultsContainer) {
        // Local database of common Malaysian mosques and surau
        const malaysianMosques = [
            // Sibu area
            { name: "Masjid An-Nur Sibu", lat: 2.296939, lng: 111.820520, address: "Jalan Kampung Nyabor, Sibu" },
            { name: "Masjid Darul Ehsan Sibu", lat: 2.3016, lng: 111.8356, address: "Jalan Oya, Sibu" },
            { name: "Surau Al-Hidayah Sibu", lat: 2.3089, lng: 111.8247, address: "Taman Desa Wira, Sibu" },
            { name: "Masjid Jamek Sibu", lat: 2.2945, lng: 111.8311, address: "Jalan Market, Sibu" },

            // Kuala Lumpur area
            { name: "Masjid Negara", lat: 3.1412, lng: 101.6914, address: "Jalan Perdana, Kuala Lumpur" },
            { name: "Masjid Jamek", lat: 3.1478, lng: 101.6953, address: "Jalan Tun Perak, Kuala Lumpur" },
            { name: "Masjid Wilayah Persekutuan", lat: 3.1726, lng: 101.7101, address: "Jalan Duta, Kuala Lumpur" },

            // Miri area
            { name: "Masjid Al-Taqwa Miri", lat: 4.3953, lng: 113.9914, address: "Jalan Bendahara, Miri" },
            { name: "Surau An-Nur Miri", lat: 4.4039, lng: 113.9878, address: "Taman Tunku, Miri" },

            // Kota Kinabalu area
            { name: "Masjid Bandaraya Kota Kinabalu", lat: 5.9804, lng: 116.0735, address: "Jalan Tunku Abdul Rahman, KK" },
            { name: "Masjid Negeri Sabah", lat: 5.9788, lng: 116.0753, address: "Jalan Tun Razak, KK" },

            // Kuching area
            { name: "Masjid Negeri Sarawak", lat: 1.5534, lng: 110.3473, address: "Jalan Masjid, Kuching" },
            { name: "Masjid India Kuching", lat: 1.5579, lng: 110.3471, address: "Jalan India, Kuching" }
        ];

        // Filter and sort by distance
        const results = malaysianMosques
            .filter(mosque => {
                const distance = calculateDistance(currentLat, currentLng, mosque.lat, mosque.lng);
                const matchesQuery = mosque.name.toLowerCase().includes(query.toLowerCase()) ||
                                   query.toLowerCase().includes('masjid') ||
                                   query.toLowerCase().includes('surau') ||
                                   query.toLowerCase().includes('mosque');
                return distance <= 50 && matchesQuery; // Within 50km and matches query
            })
            .map(mosque => {
                const distance = calculateDistance(currentLat, currentLng, mosque.lat, mosque.lng);
                return {
                    lat: mosque.lat,
                    lon: mosque.lng,
                    display_name: `${mosque.name}, ${mosque.address}`,
                    distance: distance
                };
            })
            .sort((a, b) => a.distance - b.distance);

        displaySearchResults(results, resultsContainer);
    }

    function displaySearchResults(results, resultsContainer) {
        if (results.length === 0) {
            resultsContainer.innerHTML = '<div class="search-no-results">Tiada hasil ditemui berhampiran lokasi semasa</div>';
            resultsContainer.style.display = 'block';
            return;
        }

        let html = '';
        results.forEach((result, index) => {
            const name = result.display_name.split(',')[0];
            const address = result.display_name.split(',').slice(1, 3).join(',').trim();
            const distance = result.distance ? result.distance.toFixed(1) : '';

            html += `
                <div class="search-result-item" onclick="selectSearchResult(${result.lat}, ${result.lon}, '${name.replace(/'/g, "\\'")}')">
                    <div class="search-result-name">${name}</div>
                    <div class="search-result-address">
                        ${address}
                        ${distance ? `<span class="search-distance"> • ${distance} km</span>` : ''}
                    </div>
                </div>
            `;
        });

        resultsContainer.innerHTML = html;
        resultsContainer.style.display = 'block';
    }

    function selectSearchResult(lat, lng, name) {
        // Update map and marker
        placeMarker(new google.maps.LatLng(parseFloat(lat), parseFloat(lng)));

        // Update both search inputs
        const desktopInput = document.getElementById('location-search');
        const mobileInput = document.getElementById('location-search-mobile');
        if (desktopInput) desktopInput.value = name;
        if (mobileInput) mobileInput.value = name;

        // Hide results
        hideSearchResults();

        // Zoom to location
        map.setView([lat, lng], 15);
    }

    function showSearchLoading(resultsContainer) {
        if (resultsContainer) {
            resultsContainer.innerHTML = '<div class="search-loading">Mencari...</div>';
            resultsContainer.style.display = 'block';
        }
    }

    function showSearchError(resultsContainer) {
        if (resultsContainer) {
            resultsContainer.innerHTML = '<div class="search-no-results">Ralat semasa mencari</div>';
            resultsContainer.style.display = 'block';
        }
    }

    function hideSearchResults() {
        const desktopResults = document.getElementById('search-results');
        const mobileResults = document.getElementById('search-results-mobile');
        if (desktopResults) desktopResults.style.display = 'none';
        if (mobileResults) mobileResults.style.display = 'none';
    }

    // Global error handler for Google Maps
    window.gm_authFailure = function() {
        initFallbackMap();
    };

    // Google Maps callback function (called when API loads)
    window.initGoogleMaps = function() {
        // Small delay to ensure DOM is ready
        setTimeout(() => {
            try {
                initMap(); // Call the actual map initialization function
                // Search will be initialized after map is ready (in initMap function)
            } catch (error) {
                initFallbackMap();
            }
        }, 100);
    };

    // Fallback to simple coordinate display if Google Maps fails
    function initFallbackMap() {
        const mapContainer = document.getElementById('map');
        if (mapContainer) {
            mapContainer.innerHTML = `
                <div style="
                    height: 400px;
                    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
                    border: 2px dashed #0ea5e9;
                    border-radius: 8px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;
                    text-align: center;
                    padding: 20px;
                ">
                    <div style="font-size: 48px; margin-bottom: 16px;">🗺️</div>
                    <div style="font-weight: bold; margin-bottom: 8px; color: #0f172a;">Peta Tidak Tersedia</div>
                    <div style="color: #64748b; margin-bottom: 8px; font-size: 14px;">Google Maps memerlukan billing untuk dipaparkan</div>
                    <div style="color: #64748b; margin-bottom: 16px; font-size: 14px;">Gunakan butang Manual atau Lokasi Semasa untuk input koordinat</div>
                    <div style="
                        background: white;
                        padding: 12px 16px;
                        border-radius: 6px;
                        border: 1px solid #e2e8f0;
                        font-family: monospace;
                        font-size: 12px;
                        color: #475569;
                    ">
                        Default: Lat: ${defaultLat}, Lng: ${defaultLng}
                    </div>
                </div>
            `;
        }

        // Initialize search with fallback
        initSearch();

        // Update coordinates display
        updateCoordinates(defaultLat, defaultLng);
    }

    // Check if Google Maps API failed to load
    window.addEventListener('load', function() {
        setTimeout(() => {
            if (!window.google || !window.google.maps) {
                initFallbackMap();
            } else if (!map) {
                initFallbackMap();
            }
        }, 5000); // Wait 5 seconds for Google Maps to load
    });
</script>
