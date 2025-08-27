// Make functions available globally
window.initMap = initMap;
window.searchNearbyPlaces = searchNearbyPlaces;
window.getDirections = getDirections;
window.makeCall = makeCall;

// Initialize variables
let userLat;
let userLng;
let map;
let markers = [];
let infoWindow;
let radius = 5; // Default radius is 5km
let serviceType = '';

function initMap() {
    console.log('Initializing map...');
    // Default center (Mawanella)
    const defaultCenter = { lat: 7.2497, lng: 80.4533 };
    
    // Create the map instance
    const mapElement = document.getElementById('map');
    console.log('Map element:', mapElement);
    
    if (!mapElement) {
        console.error('Map element not found!');
        return;
    }

    map = new google.maps.Map(mapElement, {
        zoom: 14,
        center: defaultCenter,
        mapTypeControl: true,
        streetViewControl: true,
        fullscreenControl: true,
        zoomControl: true
    });

    infoWindow = new google.maps.InfoWindow();

    // Get user location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                console.log('Got user position:', position);
                userLat = position.coords.latitude;
                userLng = position.coords.longitude;
                
                const userLocation = { lat: userLat, lng: userLng };
                map.setCenter(userLocation);

                // Add user marker
                new google.maps.Marker({
                    position: userLocation,
                    map: map,
                    title: 'Your Location',
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 10,
                        fillColor: '#4285F4',
                        fillOpacity: 1,
                        strokeColor: '#ffffff',
                        strokeWeight: 2
                    }
                });

                // Add radius circle
                new google.maps.Circle({
                    strokeColor: '#4285F4',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#4285F4',
                    fillOpacity: 0.1,
                    map: map,
                    center: userLocation,
                    radius: radius * 1000 // Convert km to meters
                });

                // Update form fields
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                if (latInput && lngInput) {
                    latInput.value = userLat;
                    lngInput.value = userLng;
                }

                const locationInfo = document.getElementById('locationInfo');
                if (locationInfo) {
                    locationInfo.classList.remove('hidden');
                }

                // Search for nearby places
                searchNearbyPlaces();
            },
            (error) => {
                console.error('Geolocation error:', error);
                handleLocationError(true, infoWindow, map.getCenter());
                // Use Mawanella as default
                userLat = defaultCenter.lat;
                userLng = defaultCenter.lng;
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                if (latInput && lngInput) {
                    latInput.value = userLat;
                    lngInput.value = userLng;
                }
                searchNearbyPlaces();
            }
        );
    } else {
        console.error('Geolocation not supported');
        handleLocationError(false, infoWindow, map.getCenter());
    }
}

function getServiceLabel(type) {
    const serviceLabels = {
        'hospital': 'Hospital',
        'police': 'Police',
        'fire_station': 'Fire Station',
        'pharmacy': 'Pharmacy',
        'ambulance': 'Ambulance',
        'emergency': 'Emergency'
    };
    return serviceLabels[type] || type.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
}

function createServiceCard(place, type, distance) {
    const serviceLabel = getServiceLabel(type);
    return `
        <div class="service-card hover:shadow-lg transition-all duration-300 p-4 bg-white rounded-xl border border-gray-100">
            <div class="flex items-start justify-between mb-3 gap-3">
                <h3 class="text-lg font-semibold text-gray-900 flex-1 leading-tight">${place.name}</h3>
                <span class="service-badge ${type}-badge flex-shrink-0">
                    ${serviceLabel}
                </span>
            </div>
            <div class="space-y-2">
                ${place.vicinity ? `
                    <div class="flex items-start text-gray-600">
                        <i class="fas fa-location-dot mt-1 mr-3 text-gray-400"></i>
                        <span>${place.vicinity}</span>
                    </div>
                ` : ''}
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-road mr-3 text-gray-400"></i>
                    <span>${distance.toFixed(1)} km away</span>
                </div>
                ${place.rating ? `
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-star mr-3 text-yellow-400"></i>
                        <span>${place.rating} (${place.user_ratings_total} reviews)</span>
                    </div>
                ` : ''}
                ${place.opening_hours ? `
                    <div class="flex items-center ${place.opening_hours.open_now ? 'text-green-600' : 'text-red-600'}">
                        <i class="fas fa-clock mr-3"></i>
                        <span>${place.opening_hours.open_now ? 'Open now' : 'Closed'}</span>
                    </div>
                ` : ''}
            </div>
            <div class="mt-4 flex gap-2">
                <button onclick="getDirections(${place.geometry.location.lat()}, ${place.geometry.location.lng()})"
                        class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-directions mr-2"></i>
                    Directions
                </button>
                <button onclick="makeCall('${place.place_id}', '${place.name.replace(/'/g, "\\'")}')"
                        class="flex-1 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-phone mr-2"></i>
                    Call
                </button>
            </div>
        </div>
    `;
}

function searchNearbyPlaces() {
    console.log('Searching nearby places...');
    if (!map || !userLat || !userLng) {
        console.error('Map or location not initialized');
        return;
    }

    // Clear existing markers and services list
    clearMarkers();
    const servicesContainer = document.getElementById('servicesContainer');
    if (servicesContainer) {
        servicesContainer.innerHTML = '';
    }
    
    const types = !serviceType ? ['hospital', 'police', 'fire_station', 'pharmacy'] : [serviceType];
    
    types.forEach(type => {
        const request = {
            location: { lat: userLat, lng: userLng },
            radius: radius * 1000, // Convert km to meters
            type: type
        };

        const service = new google.maps.places.PlacesService(map);
        service.nearbySearch(request, (results, status) => {
            console.log(`Places API response for ${type}:`, status, results);
            
            if (status === google.maps.places.PlacesServiceStatus.OK && results) {
                // Sort results by distance
                const placesWithDistance = results.map(place => {
                    const distance = google.maps.geometry.spherical.computeDistanceBetween(
                        new google.maps.LatLng(userLat, userLng),
                        place.geometry.location
                    ) / 1000;
                    return { place, distance };
                }).sort((a, b) => a.distance - b.distance);

                placesWithDistance.forEach(({ place, distance }) => {
                    // Add marker
                    const marker = new google.maps.Marker({
                        map: map,
                        position: place.geometry.location,
                        title: place.name,
                        icon: {
                            url: getMarkerIcon(type),
                            scaledSize: new google.maps.Size(32, 32)
                        }
                    });

                    markers.push(marker);

                    // Create info window content
                    const content = `
                        <div class="p-3 max-w-sm">
                            <h3 class="text-lg font-bold">${place.name}</h3>
                            <p class="text-gray-600">${place.vicinity}</p>
                            <p class="text-sm mt-2">${distance.toFixed(1)} km away</p>
                            ${place.rating ? `
                                <p class="text-sm text-yellow-500">
                                    Rating: ${place.rating} ⭐ (${place.user_ratings_total} reviews)
                                </p>
                            ` : ''}
                            ${place.opening_hours ? `
                                <p class="text-sm ${place.opening_hours.open_now ? 'text-green-600' : 'text-red-600'}">
                                    ${place.opening_hours.open_now ? '✓ Open now' : '× Closed'}
                                </p>
                            ` : ''}
                            <div class="mt-3 flex gap-2">
                                <button onclick="getDirections(${place.geometry.location.lat()}, ${place.geometry.location.lng()})"
                                        class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                    Get Directions
                                </button>
                                <button onclick="makeCall('${place.place_id}', '${place.name.replace(/'/g, "\\'")}')"
                                        class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">
                                    Call
                                </button>
                            </div>
                        </div>
                    `;

                    // Add service card to the list
                    const servicesContainer = document.getElementById('servicesContainer');
                    if (servicesContainer) {
                        servicesContainer.insertAdjacentHTML('beforeend', createServiceCard(place, type, distance));
                    }

                    marker.addListener('click', () => {
                        infoWindow.setContent(content);
                        infoWindow.open(map, marker);
                    });
                });
            } else {
                console.log(`No ${type} found or error:`, status);
            }
        });
    });
}

function getMarkerIcon(type) {
    const icons = {
        'hospital': '/images/hospital-marker.svg',
        'police': '/images/police-marker.svg',
        'fire_station': '/images/fire-marker.svg',
        'pharmacy': '/images/pharmacy-marker.svg'
    };
    return icons[type] || icons['hospital'];
}

function getDirections(destLat, destLng) {
    if (!userLat || !userLng) {
        alert('Your location is not available. Please enable location services.');
        return;
    }
    const url = `https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLng}&destination=${destLat},${destLng}`;
    window.open(url, '_blank');
}

function makeCall(placeId, placeName) {
    if (!placeId) {
        alert('Phone number not available for this place.');
        return;
    }

    // Show loading state
    const loadingHTML = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    <span>Getting phone number...</span>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', loadingHTML);

    // Use Google Places API to get detailed information including phone number
    const service = new google.maps.places.PlacesService(map);
    const request = {
        placeId: placeId,
        fields: ['formatted_phone_number', 'international_phone_number', 'name']
    };

    service.getDetails(request, (place, status) => {
        // Remove loading overlay
        const loadingOverlay = document.querySelector('.fixed.inset-0');
        if (loadingOverlay) {
            loadingOverlay.remove();
        }

        if (status === google.maps.places.PlacesServiceStatus.OK) {
            const phoneNumber = place.formatted_phone_number || place.international_phone_number;
            
            if (phoneNumber) {
                // Create a modal for phone number confirmation
                const modalHTML = `
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" id="callModal">
                        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm mx-4">
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                                    <i class="fas fa-phone text-green-600 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Call ${placeName}?</h3>
                                <p class="text-sm text-gray-500 mb-4">${phoneNumber}</p>
                                <div class="flex space-x-3">
                                    <button onclick="document.getElementById('callModal').remove()" 
                                            class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                                        Cancel
                                    </button>
                                    <a href="tel:${phoneNumber}" 
                                       onclick="document.getElementById('callModal').remove()"
                                       class="flex-1 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors text-center">
                                        Call Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', modalHTML);
            } else {
                // Show error modal if no phone number
                const errorModalHTML = `
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" id="errorModal">
                        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm mx-4">
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Phone Not Available</h3>
                                <p class="text-sm text-gray-500 mb-4">Phone number is not available for ${placeName}.</p>
                                <button onclick="document.getElementById('errorModal').remove()" 
                                        class="w-full bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                                    OK
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', errorModalHTML);
            }
        } else {
            console.error('Error fetching place details:', status);
            alert('Unable to get phone number. Please try again.');
        }
    });
}

function clearMarkers() {
    markers.forEach(marker => marker.setMap(null));
    markers = [];
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(
        browserHasGeolocation
            ? 'Error: The Geolocation service failed. Please enable location services.'
            : "Error: Your browser doesn't support geolocation."
    );
    infoWindow.open(map);
}

// Set up event listeners when the document is ready
document.addEventListener('DOMContentLoaded', () => {
    console.log('Setting up event listeners...');
    
    // Update radius from input
    const radiusInput = document.getElementById('radius');
    if (radiusInput) {
        radius = parseInt(radiusInput.value) || 5;
        radiusInput.addEventListener('change', () => {
            console.log('Radius changed to:', radiusInput.value);
            radius = parseInt(radiusInput.value);
            searchNearbyPlaces();
        });
    }

    // Update service type from select
    const typeSelect = document.getElementById('type');
    if (typeSelect) {
        serviceType = typeSelect.value;
        typeSelect.addEventListener('change', () => {
            console.log('Service type changed to:', typeSelect.value);
            serviceType = typeSelect.value;
            searchNearbyPlaces();
        });
    }
});
