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

// function onShowModal(event, modal, request) {
//     // Determine which ride was clicked
//     const ride = $(event.relatedTarget).attr("data-bs-ride");
//     const url = $(location).attr('origin') + '/rides/' + ride;
//     if (request) $('#request').attr('href', url + '/request');

//     // AJAX request
//     $.getJSON(url, function (data) {
//         // Update the modal's content.
//         $("#" + modal + "-route").html(data.origin.address + " &#8594; " + data.destination.address);
//         $("#" + modal + "-description").text(data.description);
//         $("#" + modal + "-driver").text(data.driver.first_name + " " + data.driver.last_name + " (" + data.driver.email + ")");

//         // Source: https://itnext.io/create-date-from-mysql-datetime-format-in-javascript-912111d57599
//         let dateTimeParts = data.start_time.split(/[- :]/); // regular expression split that creates array with: year, month, day, hour, minutes, seconds values
//         dateTimeParts[1]--; // monthIndex begins with 0 for January and ends with 11 for December so we need to decrement by one
//         const d = new Date(...dateTimeParts); // our Date object
//         const month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
//         const date = month[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
//         const time = d.toLocaleTimeString([], { hour: '2-digit', minute: "2-digit", hour12: true, timeZone: 'America/New_York' });
//         $("#" + modal + "-date").text(date);
//         $("#" + modal + "-time").text(time);

//         // Initialize map
//         initMap(data, modal);
//     });
// }

/**
 * 
 * @param {Event} event The event that triggered the modal
 * @param {Element} modal The modal's div element (of class "modal")
 * @param {string} type A string representing the modal's type. Valid values include:
 *     "info": for modals displayed on the home/ride listings page; includes the ride's details and a "Request" button,
 *     "preview": for modals displayed when previewing a new ride post; includes the ride's details and a "Post" button,
 *     "request": for modals displayed when requesting to join a ride; includes the ride's details with the user's additional waypoint(s) and a "Confirm" button,
 *     "posted": for modals displaying a driver's posted ride; includes the ride's details and a "Delete" button,
 *     "joined": for modals displaying a passenger's joined ride; includes the ride's details and a "Leave" button
 */
function initModal(event, modal, type) {
    // Determine which ride was clicked and format URL for AJAX request
    const url = window.location.origin + '/rides/' + event.relatedTarget.dataset.bsRide;

    // Set request button URL if necessary
    if (request) document.getElementById("request").href = url + '/request';

    // AJAX request
    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Update the modal's content.
            modal.querySelector(".route").textContent = data.origin.address + " \u2192 " + data.destination.address;
            modal.querySelector(".description").textContent = data.description;
            modal.querySelector(".driver").textContent = data.driver.first_name + " " + data.driver.last_name + " (" + data.driver.email + ")";

            // Source: https://itnext.io/create-date-from-mysql-datetime-format-in-javascript-912111d57599
            let dateTimeParts = data.start_time.split(/[- :]/); // regular expression split that creates array with: year, month, day, hour, minutes, seconds values
            dateTimeParts[1]--; // monthIndex begins with 0 for January and ends with 11 for December so we need to decrement by one
            const d = new Date(...dateTimeParts); // our Date object
            const month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            const date = month[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
            const time = d.toLocaleTimeString([], { hour: '2-digit', minute: "2-digit", hour12: true, timeZone: 'America/New_York' });
            modal.querySelector(".date").textContent = date;
            modal.querySelector(".time").textContent = time;

            // Initialize map
            initMap(data, modal);
        });
}

// function initMap(data, modal) {
//     const origin = new google.maps.LatLng(data.origin.latitude, data.origin.longitude);
//     const destination = new google.maps.LatLng(data.destination.latitude, data.destination.longitude);
//     const waypoints = data.waypoints;
//     const myOptions = {
//         zoom: 7,
//         center: origin,
//         disableDefaultUI: true
//     }
//     const map = new google.maps.Map(document.getElementById(modal + '-map'), myOptions);
//     const directionsService = new google.maps.DirectionsService();
//     const directionsRenderer = new google.maps.DirectionsRenderer();
//     directionsRenderer.setMap(map);

//     directionsService.route({
//         origin: origin,
//         destination: destination,
//         waypoints: waypoints,
//         optimizeWaypoints: true,
//         travelMode: google.maps.TravelMode.DRIVING,
//     }, function (result, status) {
//         if (status == 'OK') {
//             directionsRenderer.setDirections(result);

//             // Calculate distance and duration from start to end
//             var dist = 0;
//             var dur = 0;
//             for (let i = 0; i < result.routes[0].legs.length; i++) {
//                 dist += result.routes[0].legs[i].distance.value;
//                 dur += result.routes[0].legs[i].duration.value;
//             }
//             const miles = (dist / 1609.344).toFixed(1);

//             // Format duration to hours and minutes
//             // Author: Wilson Lee, https://stackoverflow.com/a/37096512
//             const h = Math.floor(dur / 3600);
//             const m = Math.floor(dur % 3600 / 60);
//             const s = Math.floor(dur % 3600 % 60);

//             const hDisplay = h > 0 ? h + (h == 1 ? " hour, " : " hours, ") : "";
//             const mDisplay = m > 0 ? m + (m == 1 ? " minute, " : " minutes, ") : "";
//             const sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";

//             const time = hDisplay + mDisplay + sDisplay;

//             $('#' + modal + '-distance').html(miles + ' miles (' + time + ')');
//         } else {
//             window.alert('Directions request failed due to ' + status);
//         }
//     });
// }

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
        if (status == 'OK') {
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
