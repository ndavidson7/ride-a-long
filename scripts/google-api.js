const autocompletes = [];

function onPlaceChanged() {
    const place = this.getPlace();
    // console.log(place);
    // if (!place) return;

    if (!place.geometry || !place.geometry.location) {
        // User entered the name of a Place that was not suggested and
        // pressed the Enter key, or the Place Details request failed.
        this.inputDiv.children(".place").val('');
        window.alert("Please select a location from the autocomplete list");
    } else {
        this.inputDiv.children(".address").val(place.formatted_address);
        const location = place.geometry.location;
        this.inputDiv.children(".latitude").val(location.lat());
        this.inputDiv.children(".longitude").val(location.lng());
    }
}

function initAutocomplete() {
    // Google Maps UVA coordinates
    const center = { lat: 38.03361737225505, lng: -78.50800895660305 };

    // Bias location autocomplete results to UVA grounds/Charlottesville
    const bounds = {
        north: center.lat + 0.15,
        south: center.lat - 0.15,
        east: center.lng + 0.15,
        west: center.lng - 0.15,
    };

    // Autocomplete configuration
    const options = {
        bounds: bounds,
        componentRestrictions: { country: "us" },
        fields: ["formatted_address", "geometry"],
        strictBounds: false,
        types: [],
    };

    $('.autocomplete').each(function () {
        const autocomplete = new google.maps.places.Autocomplete($(this).children(".place")[0], options);
        autocomplete.inputDiv = $(this);
        autocomplete.addListener('place_changed', onPlaceChanged);
        autocompletes.push(autocomplete);
    });
}

/**
 * 
 * @param {Event} event The event that triggered the modal
 * @param {Element} modal The modal's div element (of class "modal")
 */
function initModal(event, modal) {
    /**
     * @type {string} A string representing the modal's type. Valid values include:
     * "info": for modals displayed on the home/ride listings page; includes the ride's details and a "Request" button,
     * "preview": for modals displayed when previewing a new ride post; includes the ride's details and a "Post" button,
     * "request": for modals displayed when requesting to join a ride; includes the ride's details with the user's additional waypoint(s) and a "Confirm" button,
     * "posted": for modals displaying a driver's posted ride; includes the ride's details and a "Delete" button,
     * "joined": for modals displaying a passenger's joined ride; includes the ride's details and a "Leave" button
     */
    const type = event.relatedTarget.dataset.modalType;

    if (type !== "preview") {
        // Determine which ride was clicked and format URL for AJAX request
        var ride = event.relatedTarget.dataset.ride;
        var rideInfo = `${window.location.origin}/rides/${ride}`;
    }

    // Add and/or update modal button
    const modalButton = getOrCreateModalButton(modal, type); // from utils.js (must be loaded)
    switch (type) {
        case "info":
            modalButton.href = `${rideInfo}/request`;
            break;
        case "posted":
            modalButton.href = `${window.location.origin}/myrides/delete/${ride}`;
            break;
        case "joined":
            modalButton.href = `${window.location.origin}/myrides/leave/${ride}`;
            break;
    }

    if (type === "preview") {
        let data = {
            origin: {
                address: document.getElementById("orig-addr").value,
                latitude: document.getElementById("orig-lat").value,
                longitude: document.getElementById("orig-long").value,
            },
            destination: {
                address: document.getElementById("dest-addr").value,
                latitude: document.getElementById("dest-lat").value,
                longitude: document.getElementById("dest-long").value,
            },
            waypoints: [],
            description: document.getElementById("description").value,
            start_time: document.getElementById("start-time").value, // TODO: format this
            // TODO: add driver info
            // driver: {
            //     first_name: document.getElementById("first-name").value,
            //     last_name: document.getElementById("last-name").value,
            //     email: document.getElementById("email").value,
            // }
        };
        console.log(data);
        populateModal(data, modal, type);
    } else {
        // AJAX request
        fetch(rideInfo)
            .then(response => response.json())
            .then(data => {
                populateModal(data, modal, type);
            });
    }
}

function populateModal(data, modal, type) {
    // Update the modal's content.
    modal.querySelector(".route").textContent = data.origin.address + " \u2192 " + data.destination.address;
    modal.querySelector(".description").textContent = data.description;
    if (type !== "preview") modal.querySelector(".driver").textContent = `${data.driver.first_name} ${data.driver.last_name} (${data.driver.email})`;
    const { date, time } = formatDateTime(data.start_time); // from utils.js (must be loaded)
    modal.querySelector(".date").textContent = date;
    modal.querySelector(".time").textContent = time;

    if (type === "request") {
        // Add the user's additional waypoints to data.waypoints
        const waypoints = document.querySelectorAll(".waypoint");
        waypoints.forEach(waypoint => {
            if (waypoint.value) {
                data.waypoints.push({
                    location: waypoint.value,
                    stopover: true
                });
            }
        });
    }

    // Initialize map
    initMap(data, modal);
}

function initMap(data, modal) {
    const origin = new google.maps.LatLng(data.origin.latitude, data.origin.longitude);
    const destination = new google.maps.LatLng(data.destination.latitude, data.destination.longitude);
    const waypoints = data.waypoints;
    const myOptions = {
        zoom: 7,
        center: origin,
        disableDefaultUI: true
    }
    const map = new google.maps.Map(modal.querySelector('.map'), myOptions);
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);

    directionsService.route({
        origin: origin,
        destination: destination,
        waypoints: waypoints,
        optimizeWaypoints: true,
        travelMode: google.maps.TravelMode.DRIVING,
    }, function (result, status) {
        if (status === 'OK') {
            directionsRenderer.setDirections(result);

            // Calculate distance and duration from start to end
            var dist = 0;
            var dur = 0;
            for (let i = 0; i < result.routes[0].legs.length; i++) {
                dist += result.routes[0].legs[i].distance.value;
                dur += result.routes[0].legs[i].duration.value;
            }
            const miles = (dist / 1609.344).toFixed(1);

            // Format duration to hours and minutes
            // Author: Wilson Lee, https://stackoverflow.com/a/37096512
            const h = Math.floor(dur / 3600);
            const m = Math.floor(dur % 3600 / 60);
            const s = Math.floor(dur % 3600 % 60);

            const hDisplay = h > 0 ? h + (h == 1 ? " hour, " : " hours, ") : "";
            const mDisplay = m > 0 ? m + (m == 1 ? " minute, " : " minutes, ") : "";
            const sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";

            const time = hDisplay + mDisplay + sDisplay;

            modal.querySelector('.distance').textContent = miles + ' miles (' + time + ')';
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });
}
