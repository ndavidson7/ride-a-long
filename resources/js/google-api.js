import { Loader } from "@googlemaps/js-api-loader";
import { getOrCreateModalButton, formatDateTime } from "./utils.js";

/*
|--------------------------------------------------------------------------
| Google API Loader Initialization
|--------------------------------------------------------------------------
*/

const loader = new Loader({
    apiKey: import.meta.env.VITE_MAPS_API_KEY,
    version: "weekly",
    libraries: ["core", "maps", "places", "routes"],
});

/*
|--------------------------------------------------------------------------
| Google Places Autocomplete Initialization
|--------------------------------------------------------------------------
*/

const acElements = document.querySelectorAll(".autocomplete");
const autocompletes = [];

if (acElements.length > 0) {
    loader.importLibrary("places").then(({ Autocomplete }) => {
        initAutocompletes(acElements, Autocomplete);
    });
}

/*
|--------------------------------------------------------------------------
| Modal Initialization
|--------------------------------------------------------------------------
*/

const modal = document.querySelector("#mapModal");
modal?.addEventListener("show.bs.modal", function (event) {
    initModal(event, modal);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

function initAutocompletes(elements, autocomplete) {
    // Google Maps UVA coordinates
    const center = { latitude: 38.03361737225505, lng: -78.50800895660305 };

    // Bias location autocomplete results to UVA grounds/Charlottesville
    const bounds = {
        north: center.latitude + 0.15,
        south: center.latitude - 0.15,
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

    elements.forEach((element) => {
        // Get all autocomplete-related elements
        const placeInput = element.querySelector(".place");
        const addressInput = element.querySelector(".address");
        const latitudeInput = element.querySelector(".latitude");
        const longitudeInput = element.querySelector(".longitude");

        // Construct new Autocomplete object and store all elements
        const ac = new autocomplete(placeInput, options);
        ac.placeInput = placeInput;
        ac.addressInput = addressInput;
        ac.latitudeInput = latitudeInput;
        ac.longitudeInput = longitudeInput;

        // Add event listener to the Autocomplete object...
        ac.addListener("place_changed", onPlaceChanged);
        // ...as well as the input itself (for when a user unfocuses it without selecting a location)
        placeInput.addEventListener("change", () => (placeInput.value = "")); // not invoked when a user selects a location from the autocomplete list

        // Save Autocomplete object
        autocompletes.push(ac);
    });
}

/**
 * Ensure the place details request was successful
 */
function onPlaceChanged() {
    const place = this.getPlace();

    if (!place.geometry || !place.geometry.location) {
        // User entered the name of a Place that was not suggested and
        // pressed the Enter key, or the Place Details request failed.
        placeInput.value = "";
        window.alert("Please select a location from the autocomplete list"); // TODO: Add is-invalid class and an error message instead
    } else {
        this.addressInput.value = place.formatted_address;
        const location = place.geometry.location;
        this.latitudeInput.value = location.lat();
        this.longitudeInput.value = location.lng();
    }
}

/**
 * Initialize the Google Maps map element
 *
 * @param {JSON} data Contains the route-related data: origin and destination coordinates and, optionally, waypoints
 * @param {Element} modal Div element containing the map
 */
async function initMap(data, modal) {
    let origin, destination, map;

    loader
        .importLibrary("core")
        .then(({ LatLng }) => {
            origin = new LatLng(data.origin.latitude, data.origin.longitude);
            destination = new LatLng(
                data.destination.latitude,
                data.destination.longitude
            );
        })
        .catch((e) =>
            console.error(
                `Google API loader failed when importing Core library.\n${e}`
            )
        );

    loader
        .importLibrary("maps")
        .then(({ Map }) => {
            const myOptions = {
                center: origin,
                zoom: 7,
                disableDefaultUI: true,
            };

            map = new Map(modal.querySelector(".map"), myOptions);
        })
        .catch((e) =>
            console.error(
                `Google API loader failed when importing Maps library.\n${e}`
            )
        );

    loader
        .importLibrary("routes")
        .then(({ DirectionsService, DirectionsRenderer }) => {
            const directionsService = new DirectionsService();
            const directionsRenderer = new DirectionsRenderer();
            directionsRenderer.setMap(map);

            directionsService.route(
                {
                    origin: origin,
                    destination: destination,
                    waypoints: data.waypoints,
                    optimizeWaypoints: true,
                    travelMode: google.maps.TravelMode.DRIVING,
                },
                function (result, status) {
                    if (status === "OK") {
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
                        const m = Math.floor((dur % 3600) / 60);
                        const s = Math.floor((dur % 3600) % 60);

                        const hDisplay =
                            h > 0 ? h + (h == 1 ? " hour, " : " hours, ") : "";
                        const mDisplay =
                            m > 0
                                ? m + (m == 1 ? " minute, " : " minutes, ")
                                : "";
                        const sDisplay =
                            s > 0 ? s + (s == 1 ? " second" : " seconds") : "";

                        const time = hDisplay + mDisplay + sDisplay;

                        modal.querySelector(".distance").textContent =
                            miles + " miles (" + time + ")";
                    } else {
                        window.alert(
                            "Directions request failed due to " + status
                        );
                    }
                }
            );
        })
        .catch((e) =>
            console.error(
                `Google API loader failed when importing Routes library.\n${e}`
            )
        );
}

/**
 * Initialize the map modal
 *
 * @param {Event} event The event that triggered the modal
 * @param {Element} modal The modal's div element (of class "modal")
 */
function initModal(event, modal) {
    /**
     * A string representing the modal's type. Potential values are found in App\Enums\MapType::class and include:
     * "info": for modals displayed on the home/ride listings page; includes the ride's details and a Request button,
     * "preview": for modals displayed when previewing a new ride post; includes the ride's details and a Post button,
     * "request": for modals displayed when requesting to join a ride; includes the ride's details with the user's additional waypoint(s) and a Confirm button,
     * "posted": for modals displaying a driver's posted ride; includes the ride's details and a Delete button,
     * "joined": for modals displaying a passenger's joined ride; includes the ride's details and a Leave button
     * @type {string}
     */
    const type = modal.dataset.type;
    if (!type) throw new Error("Modal type not specified");
    else if (!["info", "preview", "request", "posted", "joined"].includes(type))
        throw new Error("Invalid modal type");

    if (type !== "preview") {
        // Determine which ride was clicked and format URL for AJAX request
        var ride = event.relatedTarget.dataset.ride;
        var rideInfo = `${window.location.origin}/rides/${ride}`;
    }

    // Add and/or update modal button
    const modalButton = getOrCreateModalButton(modal, type);
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
        const data = {
            origin: {
                address: document.getElementById("origin-address").value,
                latitude: document.getElementById("origin-latitude").value,
                longitude: document.getElementById("origin-longitude").value,
            },
            destination: {
                address: document.getElementById("destination-address").value,
                latitude: document.getElementById("destination-latitude").value,
                longitude: document.getElementById("destination-longitude")
                    .value,
            },
            waypoints: [],
            description: document.getElementById("description").value,
            start_time: document.getElementById("start-time").value, // TODO: format this OR handle html datetime differently in utils.formatDateTime()
            // TODO: add driver info
            // driver: {
            //     first_name: document.getElementById("first-name").value,
            //     last_name: document.getElementById("last-name").value,
            //     email: document.getElementById("email").value,
            // }
        };
        populateModal(data, modal, type);
    } else {
        // AJAX request
        fetch(rideInfo)
            .then((response) => response.json())
            .then((data) => {
                populateModal(data, modal, type);
            });
    }
}

function populateModal(data, modal, type) {
    // Update the modal's content.
    modal.querySelector(".route").textContent =
        data.origin.address + " \u2192 " + data.destination.address;
    modal.querySelector(".description").textContent = data.description;
    if (type !== "preview")
        modal.querySelector(
            ".driver"
        ).textContent = `${data.driver.first_name} ${data.driver.last_name} (${data.driver.email})`;
    const { date, time } = formatDateTime(data.start_time);
    modal.querySelector(".date").textContent = date;
    modal.querySelector(".time").textContent = time;

    if (type === "request") {
        // Add the user's additional waypoints to data.waypoints
        const waypoints = document.querySelectorAll(".waypoint");
        waypoints.forEach((waypoint) => {
            if (waypoint.value) {
                data.waypoints.push({
                    location: waypoint.value,
                    stopover: true,
                });
            }
        });
    }

    // Initialize map
    initMap(data, modal);
}
