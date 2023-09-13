import { Loader } from "@googlemaps/js-api-loader";
import { formatDateTime } from "./utils.js";

/*
|--------------------------------------------------------------------------
| Google API Loader Initialization
|--------------------------------------------------------------------------
*/

let map, directionsService, directionsRenderer;

const loader = new Loader({
    apiKey: import.meta.env.VITE_MAPS_API_KEY,
    version: "weekly",
    libraries: ["core", "maps", "places", "routes"],
});

// These will all add their respective library to the global google.maps namespace for later use
loader
    .importLibrary("core")
    .catch((e) =>
        console.error(
            `Google API loader failed when importing Core library.\n${e}`
        )
    );

loader
    .importLibrary("maps")
    .then(({ Map }) => {
        const mapDiv = document.querySelector(".map");

        if (!mapDiv) throw new Error("Map div not found.");

        map = new Map(mapDiv, {
            disableDefaultUI: true,
        });
    })
    .catch((e) =>
        console.error(
            `Google API loader failed when importing Maps library.\n${e}`
        )
    );

loader
    .importLibrary("routes")
    .then(({ DirectionsService, DirectionsRenderer }) => {
        directionsService = new DirectionsService();
        directionsRenderer = new DirectionsRenderer();
        directionsRenderer.setMap(map);
    })
    .catch((e) =>
        console.error(
            `Google API loader failed when importing Routes library.\n${e}`
        )
    );

/*
|--------------------------------------------------------------------------
| Google Places Autocomplete Initialization
|--------------------------------------------------------------------------
*/

const acElements = document.querySelectorAll(".autocomplete");
const autocompletes = [];
const placeChanged = new Event("change");

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
modal?.addEventListener("show.bs.modal", (event) => {
    initModal(modal, event);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

function initAutocompletes(elements, autocomplete) {
    // Google Maps UVA coordinates
    const center = {
        latitude: 38.03361737225505,
        longitude: -78.50800895660305,
    };

    // Bias location autocomplete results to UVA grounds/Charlottesville
    const radius = 0.15; // degrees
    const bounds = {
        north: center.latitude + radius,
        south: center.latitude - radius,
        east: center.longitude + radius,
        west: center.longitude - radius,
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
        // Note: change event not triggered when a user selects a location from the autocomplete list
        placeInput.addEventListener("change", () => {
            placeInput.value = "";
            addressInput.value = "";
            latitudeInput.value = "";
            longitudeInput.value = "";

            addressInput.dispatchEvent(placeChanged);
        });

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
        this.placeInput.value = "";
        this.addressInput.value = "";
        this.latitudeInput.value = "";
        this.longitudeInput.value = "";
        window.alert("Please select a location from the autocomplete list"); // TODO: Add is-invalid class and an error message instead
    } else {
        this.addressInput.value = place.formatted_address;
        const location = place.geometry.location;
        this.latitudeInput.value = location.lat();
        this.longitudeInput.value = location.lng();
    }

    this.addressInput.dispatchEvent(placeChanged);
}

/**
 * Initialize the map modal
 *
 * @param {Element} modal The modal element to be initialized
 * @param {Event} event The event that triggered the modal
 */
function initModal(modal, event) {
    // Determine which ride was clicked and format URL for AJAX request
    const ride = event.relatedTarget.dataset.ride; // relatedTarget is the clicked ride card
    const relatedModelId = event.relatedTarget.dataset.relatedModelId;

    if (modal.dataset.ride === ride) return; // the modal still contains info for this ride
    modal.dataset.ride = ride;

    // AJAX request
    fetch(route("rides.show", ride))
        .then((response) => response.json())
        .then((data) => {
            // Initialize map
            initMap(data, modal);

            // Update the modal's content.
            const userRelation = event.relatedTarget.dataset.userRelation;
            const deleteFormTemplate =
                document.getElementById("delete-form").content;

            let modalButton;
            switch (userRelation) {
                case "driver":
                    modalButton = document.createElement("a");
                    modalButton.href = route("rides.edit", ride);
                    modalButton.classList.add("btn", "btn-primary");
                    modalButton.textContent = "Edit Ride";
                    break;
                case "passenger":
                    if ("content" in document.createElement("template")) {
                        modalButton = deleteFormTemplate.cloneNode(true);
                        modalButton.querySelector("form").action = route(
                            "rideUser.destroy",
                            relatedModelId
                        );
                        modalButton.querySelector("button").textContent =
                            "Leave Ride";
                    } else {
                        console.error("HTML template element not supported.");
                    }
                    break;
                case "requester":
                    if ("content" in document.createElement("template")) {
                        modalButton = deleteFormTemplate.cloneNode(true);
                        modalButton.querySelector("form").action = route(
                            "requests.destroy",
                            relatedModelId
                        );
                        modalButton.querySelector("button").textContent =
                            "Cancel Request";
                    } else {
                        console.error("HTML template element not supported.");
                    }
                    break;
                case "none":
                default:
                    modalButton = document.createElement("a");
                    modalButton.href = route("requests.create", ride);
                    modalButton.classList.add("btn", "btn-primary");
                    modalButton.textContent = "Request to Join";
            }
            document
                .getElementById("modal-button-div")
                .replaceChildren(modalButton);

            modal.querySelector(".route").textContent =
                data.origin.address + " \u2192 " + data.destination.address;
            modal.querySelector(".description").textContent = data.description;
            modal.querySelector(
                ".driver"
            ).textContent = `${data.driver.first_name} ${data.driver.last_name} (${data.driver.email})`;

            const { date, time } = formatDateTime(data.start_time);
            modal.querySelector(".date").textContent = date;
            modal.querySelector(".time").textContent = time;
        });
}

/**
 * Initialize the Google Maps map element
 *
 * @param {JSON} data Contains the route-related data: origin and destination coordinates and, optionally, waypoints
 * @param {Element} modal Div element containing the map
 */
function initMap(data, modal) {
    if (import.meta.env.VITE_APP_DEBUG) console.log("Map data:", data);

    const origin = new google.maps.LatLng(
        data.origin.latitude,
        data.origin.longitude
    );
    const destination = new google.maps.LatLng(
        data.destination.latitude,
        data.destination.longitude
    );

    let waypoints = [];
    if (data.waypoints) {
        for (const waypoint of data.waypoints) {
            waypoints.push({
                location: new google.maps.LatLng(
                    waypoint.address.latitude,
                    waypoint.address.longitude
                ),
                stopover: true,
            });
        }
    }

    directionsService.route(
        {
            origin: origin,
            destination: destination,
            waypoints: waypoints,
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
                    m > 0 ? m + (m == 1 ? " minute, " : " minutes, ") : "";
                const sDisplay =
                    s > 0 ? s + (s == 1 ? " second" : " seconds") : "";

                const time = hDisplay + mDisplay + sDisplay;

                modal.querySelector(".distance").textContent =
                    miles + " miles (" + time + ")";
            } else {
                window.alert("Directions request failed due to " + status); // TODO: Handle this
            }
        }
    );
}
