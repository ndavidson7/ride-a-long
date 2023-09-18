import { createMap, createDirections } from "./google-api.js";
import { formatDateTime } from "./utils.js";

export class MapComponent {
    constructor(component) {
        if (!component) {
            throw new TypeError(
                "MapComponent constructor requires HTML element referring to map component."
            );
        }

        this.route = component.querySelector(".route");
        this.distance = component.querySelector(".distance");
        this.date = component.querySelector(".date");
        this.time = component.querySelector(".time");
        this.description = component.querySelector(".description");
        this.driver = component.querySelector(".driver");

        this.init(component);
    }

    async init(component) {
        this.map = await createMap(component.querySelector(".map"));

        const { directionsService, directionsRenderer } =
            await createDirections(this.map);
        this.directionsService = directionsService;
        this.directionsRenderer = directionsRenderer;
    }

    async update() {
        const data = await this.getData();

        if (import.meta.env.VITE_APP_DEBUG)
            console.log("MapComponent data:", data);

        const routeData = this.formatData(data);

        if (import.meta.env.VITE_APP_DEBUG)
            console.log("MapComponent routeData:", routeData);

        this.getRoute(routeData);

        this.renderData(data);
    }

    getData() {
        /**
         * Data should be an object with the following properties:
         * - origin: { address: string, latitude: number, longitude: number }
         * - destination: { address: string, latitude: number, longitude: number }
         * - waypoints: (optional) [{ address: string, latitude: number, longitude: number }]
         * - description: string
         * - start_time: string
         * - driver: { first_name: string, last_name: string, email: string }
         */
        throw new Error("MapComponent.getData() should be overriden!");
    }

    formatData(data) {
        return {
            origin: new google.maps.LatLng(
                data.origin.latitude,
                data.origin.longitude
            ),
            destination: new google.maps.LatLng(
                data.destination.latitude,
                data.destination.longitude
            ),
            waypoints: data.waypoints
                ? data.waypoints.map((waypoint) => ({
                      location: new google.maps.LatLng(
                          waypoint.address.latitude,
                          waypoint.address.longitude
                      ),
                      stopover: true,
                  }))
                : [],
            optimizeWaypoints: false,
            travelMode: google.maps.TravelMode.DRIVING,
        };
    }

    getRoute(data) {
        this.directionsService.route(data, (result, status) => {
            if (status === "OK") {
                this.directionsRenderer.setDirections(result);

                const { miles, time } = MapComponent.calculateDistance(result);

                this.distance.textContent = miles + " miles (" + time + ")";
            } else {
                window.alert("Directions request failed due to " + status); // TODO: Handle this
            }
        });
    }

    renderData(data) {
        this.route.textContent =
            data.origin.address + " \u2192 " + data.destination.address;

        const { date, time } = formatDateTime(data.start_time);
        this.date.textContent = date;
        this.time.textContent = time;

        this.description.textContent = data.description;

        // TODO: Remove this "if" when driver fetching implemented
        if (data.driver)
            this.driver.textContent = `${data.driver.first_name} ${data.driver.last_name} (${data.driver.email})`;
    }

    static async fetchData(rideId) {
        if (!rideId) {
            throw new TypeError();
        }

        return fetch(route("rides.show", rideId), {
            headers: { Accept: "application/json" },
        })
            .then((response) => response.json())
            .then((data) => {
                return data;
            });
    }

    static calculateDistance(routeResult) {
        // Calculate distance and duration from start to end
        let dist = 0;
        let dur = 0;
        for (let i = 0; i < routeResult.routes[0].legs.length; i++) {
            dist += routeResult.routes[0].legs[i].distance.value;
            dur += routeResult.routes[0].legs[i].duration.value;
        }
        const miles = (dist / 1609.344).toFixed(1);

        // Format duration to hours and minutes
        // Author: Wilson Lee, https://stackoverflow.com/a/37096512
        const h = Math.floor(dur / 3600);
        const m = Math.floor((dur % 3600) / 60);
        const s = Math.floor((dur % 3600) % 60);

        const hDisplay = h > 0 ? h + (h == 1 ? " hour, " : " hours, ") : "";
        const mDisplay = m > 0 ? m + (m == 1 ? " minute, " : " minutes, ") : "";
        const sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";

        const time = hDisplay + mDisplay + sDisplay;

        return { miles, time };
    }
}

export class RideModalMapComponent extends MapComponent {
    async update(newRideId) {
        // if this already contains the relevant ride info, don't update
        if (newRideId) {
            if (this.rideId === newRideId) return;

            this.rideId = newRideId;
        }

        super.update();
    }

    getData() {
        return MapComponent.fetchData(this.rideId);
    }
}

export class RideCreateMapComponent extends MapComponent {
    getData() {
        return {
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
            description: document.getElementById("description").value,
            start_time: document.getElementById("start-time").value,
            driver: null, // TODO: Fetch driver data?
        };
    }
}

export class RideEditMapComponent extends RideCreateMapComponent {
    async init(component) {
        // Update as soon as map is initialized
        await super.init(component);

        super.update();
    }

    getData() {
        const data = super.getData();

        data.waypoints = ride.waypoints; // Ride will be provided in a script tag in the Blade view

        return data;
    }
}

export class RequestCreateMapComponent extends MapComponent {
    async init(component) {
        // Update as soon as map is initialized
        await super.init(component);

        super.update();
    }

    getData() {
        // Ride will be defined in a script tag in the Blade view
        // Deep copy to avoid saving old pickup/dropoff in event that user removes or changes one or both
        const data = JSON.parse(JSON.stringify(ride)); // TODO: Probably not the most efficient way to do this. Maybe destructure?

        const pickupLatitude = document.getElementById("pickup-latitude");
        const pickupLongitude = document.getElementById("pickup-longitude");
        if (
            pickupLatitude &&
            pickupLatitude.value &&
            pickupLongitude &&
            pickupLongitude.value
        ) {
            data.pickup = {
                latitude: pickupLatitude.value,
                longitude: pickupLongitude.value,
            };
        }

        const dropoffLatitude = document.getElementById("dropoff-latitude");
        const dropoffLongitude = document.getElementById("dropoff-longitude");
        if (
            dropoffLatitude &&
            dropoffLatitude.value &&
            dropoffLongitude &&
            dropoffLongitude.value
        ) {
            data.dropoff = {
                latitude: dropoffLatitude.value,
                longitude: dropoffLongitude.value,
            };
        }

        return data;
    }

    formatData(data) {
        const routeData = super.formatData(data);

        // Add pickup and dropoff waypoints to data and optimize route if they exist
        routeData.optimizeWaypoints =
            data.pickup != null || data.dropoff != null;

        if (data.pickup) {
            routeData.waypoints.push({
                location: new google.maps.LatLng(
                    data.pickup.latitude,
                    data.pickup.longitude
                ),
                stopover: true,
            });
        }

        if (data.dropoff) {
            routeData.waypoints.push({
                location: new google.maps.LatLng(
                    data.dropoff.latitude,
                    data.dropoff.longitude
                ),
                stopover: true,
            });
        }

        return routeData;
    }
}

export class RequestShowMapComponent extends RequestCreateMapComponent {
    getData() {
        // Request will be defined in a script tag in the Blade view
        const data = JSON.parse(JSON.stringify(request.ride)); // TODO: Probably not the most efficient way to do this. Maybe destructure?

        data.pickup = request.pickup;
        data.dropoff = request.dropoff;

        return data;
    }
}
