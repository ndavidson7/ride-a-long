import { Loader } from "@googlemaps/js-api-loader";

/*
|--------------------------------------------------------------------------
| Google API Loader Initialization
|--------------------------------------------------------------------------
*/

const loader = new Loader({
    apiKey: import.meta.env.VITE_MAPS_JS_API_KEY,
    version: "weekly",
    libraries: ["core", "maps", "places", "routes"],
});

// This will all add LatLng to the global google.maps namespace for later use
loader
    .importLibrary("core")
    .catch((e) =>
        console.error(
            `Google API loader failed when importing Core library.\n${e}`
        )
    );

export async function createMap(mapDiv) {
    try {
        const { Map } = await loader.importLibrary("maps");

        if (mapDiv == null) {
            throw new Error("Map div not found.");
        }

        return new Map(mapDiv, {
            disableDefaultUI: true,
        });
    } catch (e) {
        console.error(
            `Google API loader failed when importing Maps library.\n${e}`
        );
        // throw e;
    }
}

export async function createDirections(googleMap) {
    try {
        const { DirectionsService, DirectionsRenderer } =
            await loader.importLibrary("routes");

        const directionsService = new DirectionsService();
        const directionsRenderer = new DirectionsRenderer();
        directionsRenderer.setMap(googleMap);

        return { directionsService, directionsRenderer };
    } catch (e) {
        console.error(
            `Google API loader failed when importing Routes library.\n${e}`
        );
        // throw e;
    }
}

/*
|--------------------------------------------------------------------------
| Google Places Autocomplete Initialization
|
| Autocomplete inputs will always accompany a map component,
| and MapComponent is the only place this module is imported.
| Therefore, we can immediately check for and initialize any
| autocomplete inputs when this module is imported.
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
        fields: ["formatted_address", "geometry", "address_components"],
        strictBounds: false,
        types: [],
    };

    elements.forEach((element) => {
        // Get all autocomplete-related elements
        const placeInput = element.querySelector(".place");
        const addressInput = element.querySelector(".address");
        const cityInput = element.querySelector(".city");
        const stateInput = element.querySelector(".state");
        const countryInput = element.querySelector(".country");
        const latitudeInput = element.querySelector(".latitude");
        const longitudeInput = element.querySelector(".longitude");

        // Construct new Autocomplete object and store all elements
        const ac = new autocomplete(placeInput, options);
        ac.placeInput = placeInput;
        ac.addressInput = addressInput;
        ac.cityInput = cityInput;
        ac.stateInput = stateInput;
        ac.countryInput = countryInput;
        ac.latitudeInput = latitudeInput;
        ac.longitudeInput = longitudeInput;

        // Add event listener to the Autocomplete object...
        ac.addListener("place_changed", onPlaceChanged);
        // ...as well as the input itself (for when a user unfocuses it without selecting a location)
        // Note: change event not triggered when a user selects a location from the autocomplete list
        placeInput.addEventListener("change", () => {
            placeInput.value = "";
            addressInput.value = "";
            cityInput.value = "";
            stateInput.value = "";
            countryInput.value = "";
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
    const addressComponents = place.address_components;

    const componentMap = {
        locality: "cityInput",
        administrative_area_level_1: "stateInput",
        country: "countryInput",
    };

    if (!place.geometry || !place.geometry.location) {
        // User entered the name of a Place that was not suggested and
        // pressed the Enter key, or the Place Details request failed.
        this.placeInput.value = "";
        this.addressInput.value = "";
        this.cityInput.value = "";
        this.stateInput.value = "";
        this.countryInput.value = "";
        this.latitudeInput.value = "";
        this.longitudeInput.value = "";
        window.alert("Please select a location from the autocomplete list"); // TODO: Add is-invalid class and an error message instead
    } else {
        this.addressInput.value = place.formatted_address;
        const location = place.geometry.location;
        this.latitudeInput.value = location.lat();
        this.longitudeInput.value = location.lng();

        for (const component of addressComponents) {
            for (const type of component.types) {
                if (componentMap.hasOwnProperty(type)) {
                    this[componentMap[type]].value = component.long_name;
                }
            }
        }
    }

    this.addressInput.dispatchEvent(placeChanged);
}
