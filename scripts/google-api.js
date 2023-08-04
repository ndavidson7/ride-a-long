var autocompletes = [];

function onPlaceChanged() {
    var place = this.getPlace();
    // console.log(place);
    // if (!place) return;

    if (!place.geometry || !place.geometry.location) {
        // User entered the name of a Place that was not suggested and
        // pressed the Enter key, or the Place Details request failed.
        this.inputDiv.children(".place").val('');
        window.alert("Please select a location from the autocomplete list");
    } else {
        this.inputDiv.children(".address").val(place.formatted_address);
        var location = place.geometry.location;
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
        var autocomplete = new google.maps.places.Autocomplete($(this).children(".place")[0], options);
        autocomplete.inputDiv = $(this);
        autocomplete.addListener('place_changed', onPlaceChanged);
        autocompletes.push(autocomplete);
    });
}

function onShowModal(event, modal, request) {
    // Determine which ride was clicked
    var ride = $(event.relatedTarget).attr("data-bs-ride");
    var url = $(location).attr('origin') + '/rides/' + ride;
    if (request) $('#request').attr('href', url + '/request');
    // AJAX request
    $.getJSON(url, function (data) {
        // Update the modal's content.
        $("#" + modal + "-route").html(data.origin.address + " &#8594; " + data.destination.address);
        $("#" + modal + "-description").text(data.description);
        $("#" + modal + "-driver").text(data.driver.first_name + " " + data.driver.last_name + " (" + data.driver.email + ")");

        // Source: https://itnext.io/create-date-from-mysql-datetime-format-in-javascript-912111d57599
        let dateTimeParts = data.start_time.split(/[- :]/); // regular expression split that creates array with: year, month, day, hour, minutes, seconds values
        dateTimeParts[1]--; // monthIndex begins with 0 for January and ends with 11 for December so we need to decrement by one
        var d = new Date(...dateTimeParts); // our Date object
        var month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        var date = month[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
        var time = d.toLocaleTimeString([], { hour: '2-digit', minute: "2-digit", hour12: true, timeZone: 'America/New_York' });
        $("#" + modal + "-date").text(date);
        $("#" + modal + "-time").text(time);

        // Initialize map
        initMap(data, modal);
    });
}

function initMap(data, modal) {
    var origLat = data.origin.latitude;
    var origLong = data.origin.longitude;
    var destLat = data.destination.latitude;
    var destLong = data.destination.longitude;
    var origin = new google.maps.LatLng(origLat, origLong);
    var destination = new google.maps.LatLng(destLat, destLong);
    // const waypoints = data.waypoints; TODO: pass this in data
    var myOptions = {
        zoom: 7,
        center: origin,
        disableDefaultUI: true
    }
    var map = new google.maps.Map(document.getElementById(modal + '-map'), myOptions);
    var directionsService = new google.maps.DirectionsService();
    var directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);

    directionsService.route({
        origin: origin,
        destination: destination,
        // waypoints: waypoints, TODO: uncomment when added
        travelMode: 'DRIVING',
    }, function (result, status) {
        if (status == 'OK') {
            directionsRenderer.setDirections(result);
            var leg = result.routes[0].legs[0];
            $('#' + modal + '-distance').html(leg.distance.text + ' (' + leg.duration.text + ')');
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });
}
