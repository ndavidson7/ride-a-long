import { createMap, createDirections } from "./google-api.js";
import { formatDateTime } from "./utils.js";

export class MapComponent {
    constructor(component) {
        if (!component) {
            throw new TypeError(
                "MapComponent constructor requires HTML element referring to map component."
            );
        }

        this.component = component;

        this.elements = {
            mapElement: component.querySelector(".map"),
            routeDirections: component.querySelector("#route-directions"),
            route: component.querySelector(".route"),
            distance: component.querySelector(".distance"),
            date: component.querySelector(".date"),
            time: component.querySelector(".time"),
            description: component.querySelector(".description"),
            // driver: component.querySelector(".driver"),
        };

        this.init();
    }

    async init() {
        this.map = await createMap(this.elements.mapElement);

        const { directionsService, directionsRenderer } =
            await createDirections(this.map);
        this.directionsService = directionsService;
        this.directionsRenderer = directionsRenderer;
    }

    async update() {
        // Show loading placeholders
        this.setLoading(true);

        // Get data
        const data = await this.getData();

        if (import.meta.env.VITE_APP_DEBUG)
            console.log("MapComponent data:", data);

        // Format data for directions request
        const routeData = this.formatData(data);

        if (import.meta.env.VITE_APP_DEBUG)
            console.log("MapComponent routeData:", routeData);

        // Get directions
        const routeResult = await this.getRoute(routeData);

        if (import.meta.env.VITE_APP_DEBUG)
            console.log("DirectionsResult:", routeResult);

        // Hide loading placeholders
        this.setLoading(false);

        // Render data
        this.renderData(data, routeResult);
    }

    setLoading(loading) {
        if (loading) {
            for (const element of Object.values(this.elements)) {
                element.classList.add("placeholder");
            }
        } else {
            for (const element of Object.values(this.elements)) {
                element.classList.remove("placeholder");
            }
        }
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
            waypoints:
                data.waypoints?.map((waypoint) => ({
                    location: new google.maps.LatLng(
                        waypoint.address.latitude,
                        waypoint.address.longitude
                    ),
                    stopover: true,
                })) ?? [],
            optimizeWaypoints: false,
            travelMode: google.maps.TravelMode.DRIVING,
        };
    }

    async getRoute(data) {
        return this.directionsService.route(data, (result, status) => {
            if (status !== "OK") {
                throw new Error("Directions request failed due to " + status);
            }

            this.directionsRenderer.setDirections(result);

            return result;
        });
    }

    renderData(data, routeResult) {
        const itinerary = [
            data.origin,
            ...(data.waypoints?.map((waypoint) => waypoint.address) ?? []),
            data.destination,
        ];

        this.elements.routeDirections.href =
            MapComponent.constructDirectionsUrl(itinerary);

        const liTemplate = document.getElementById("li-template");
        const routeChildren = [];
        itinerary.forEach((item, index) => {
            const li = liTemplate.content.cloneNode(true);

            const addressDirections = li.querySelector("#address-directions");
            addressDirections.textContent = item.address;
            addressDirections.href = MapComponent.constructDirectionsUrl(item);

            const detail = li.querySelector("#detail");
            if (index === 0) detail.textContent = "Origin";
            else if (index === itinerary.length - 1)
                detail.textContent = "Destination";
            else detail.textContent = `Waypoint ${index}`; //"Pickup/Dropoff person's name"

            routeChildren.push(li);
        });
        this.elements.route.replaceChildren(...routeChildren);

        const [miles, duration] =
            MapComponent.calculateTotalDistance(routeResult);
        this.elements.distance.textContent =
            miles + " miles (" + duration + ")";

        const [date, time] = formatDateTime(data.start_time);
        this.elements.date.textContent = date;
        this.elements.time.textContent = time;

        this.elements.description.textContent = data.description;

        // TODO: Remove this "if" when driver fetching implemented
        // if (data.driver)
        //     this.elements.driver.textContent = `${data.driver.first_name} ${data.driver.last_name} (${data.driver.email})`;
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

    static calculateTotalDistance(routeResult) {
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

        const duration = hDisplay + mDisplay + sDisplay;

        return [miles, duration];
    }

    static constructDirectionsUrl(addresses) {
        const apple = navigator.userAgent.includes("Mac OS");

        if (Array.isArray(addresses)) {
            return apple
                ? `maps://https://maps.apple.com/?dirflg=d&saddr=${
                      addresses[0].latitude
                  }%2C${addresses[0].longitude}&daddr=${addresses
                      .flatMap((address, index) =>
                          index > 0
                              ? `${address.latitude}%2C${address.longitude}`
                              : []
                      )
                      .join("&daddr=")}`
                : `https://www.google.com/maps/dir/?api=1&travelmode=driving&origin=${
                      addresses[0].latitude
                  }%2C${addresses[0].longitude}&destination=${
                      addresses[addresses.length - 1].latitude
                  }%2C${addresses[addresses.length - 1].longitude}&waypoints=${
                      addresses.length > 2
                          ? addresses
                                .flatMap((address, index) =>
                                    index > 0 && index < addresses.length - 1
                                        ? `${address.latitude}%2C${address.longitude}`
                                        : []
                                )
                                .join("%7C")
                          : ""
                  }`;
        } else if (typeof addresses === "object") {
            return apple
                ? `maps://https://maps.apple.com/?q=${addresses.latitude}%2C${addresses.longitude}`
                : `https://www.google.com/maps/search/?api=1&query=${addresses.latitude}%2C${addresses.longitude}`;
        } else {
            throw new TypeError(
                "MapComponent.constructDirectionsUrl() requires an object or array of objects."
            );
        }
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

    async getData() {
        return MapComponent.fetchData(this.rideId);
    }
}

export class RideCreateMapComponent extends MapComponent {
    async update() {
        this.component.classList.remove("d-none");

        super.update();
    }

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
            // driver: null, // TODO: Fetch driver data?
        };
    }
}

export class RideEditMapComponent extends RideCreateMapComponent {
    async init() {
        // Update as soon as map is initialized
        await super.init();

        super.update();
    }

    getData() {
        const data = super.getData();

        data.waypoints = ride.waypoints; // Ride will be provided in a script tag in the Blade view

        return data;
    }
}

export class RideShowMapComponent extends MapComponent {
    async init() {
        // Update as soon as map is initialized
        await super.init();

        super.update();
    }

    getData() {
        return ride; // Ride will be provided in a script tag in the Blade view
    }
}

class RequestMapComponent extends MapComponent {
    async init() {
        // Update as soon as map is initialized
        await super.init();

        super.update();
    }

    static getOrCreateWaypoint(waypoints, address, id) {
        return (
            waypoints.find(
                (waypoint) => waypoint.address.address === address.address
            ) ?? {
                id: id,
                address: address,
            }
        );
    }

    static async optimizeWaypoints(data, pickup, dropoff) {
        let response;
        try {
            response = await fetch(route("route.optimize"), {
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                method: "POST",
                credentials: "same-origin",
                body: JSON.stringify({
                    route: [
                        data.origin,
                        ...(data.waypoints ?? []),
                        data.destination,
                    ],
                    pickup: pickup,
                    dropoff: dropoff,
                }),
            });
        } catch (error) {
            alert(
                "Error optimizing route. Try different pickup and/or dropoff locations, or try again later."
            );
            throw error;
        }

        response = await response.json();

        if (response["error"]) {
            alert(`Error optimizing route. ${response["content"]}}`);
            throw new Error(response["content"]);
        }

        return response["content"];
    }

    static async handleWaypoints(data, pickup, dropoff) {
        if (!pickup && !dropoff) return data; // neither pickup nor dropoff

        if (data.waypoints.length || (pickup && dropoff)) {
            data.waypoints = await RequestMapComponent.optimizeWaypoints(
                data,
                pickup,
                dropoff
            );
        } else {
            data.waypoints.push(pickup ?? dropoff);
        }

        return data;
    }
}

export class RequestCreateMapComponent extends RequestMapComponent {
    async getData() {
        // Ride will be defined in a script tag in the Blade view
        // Deep copy to avoid saving old pickup/dropoff in event that user removes or changes one or both
        const data = JSON.parse(JSON.stringify(ride)); // TODO: Probably not the most efficient way to do this. Maybe destructure?

        let pickup;
        const pickupAddress = document.getElementById("pickup-address");
        const pickupLatitude = document.getElementById("pickup-latitude");
        const pickupLongitude = document.getElementById("pickup-longitude");
        if (
            pickupAddress?.value &&
            pickupLatitude?.value &&
            pickupLongitude?.value
        ) {
            pickup = RequestMapComponent.getOrCreateWaypoint(
                data.waypoints,
                {
                    address: pickupAddress.value,
                    latitude: pickupLatitude.value,
                    longitude: pickupLongitude.value,
                },
                -2
            );
        }

        let dropoff;
        const dropoffAddress = document.getElementById("dropoff-address");
        const dropoffLatitude = document.getElementById("dropoff-latitude");
        const dropoffLongitude = document.getElementById("dropoff-longitude");
        if (
            dropoffAddress?.value &&
            dropoffLatitude?.value &&
            dropoffLongitude?.value
        ) {
            dropoff = RequestMapComponent.getOrCreateWaypoint(
                data.waypoints,
                {
                    address: dropoffAddress.value,
                    latitude: dropoffLatitude.value,
                    longitude: dropoffLongitude.value,
                },
                -1
            );
        }

        return await RequestMapComponent.handleWaypoints(data, pickup, dropoff);
    }
}

export class RequestShowMapComponent extends RequestMapComponent {
    async getData() {
        // Request will be defined in a script tag in the Blade view
        const data = JSON.parse(JSON.stringify(request.ride)); // TODO: Probably not the most efficient way to do this. Maybe destructure?

        const pickup = request.pickup
            ? RequestMapComponent.getOrCreateWaypoint(
                  data.waypoints,
                  request.pickup,
                  -2
              )
            : null;
        const dropoff = request.dropoff
            ? RequestMapComponent.getOrCreateWaypoint(
                  data.waypoints,
                  request.dropoff,
                  -1
              )
            : null;

        return await RequestMapComponent.handleWaypoints(data, pickup, dropoff);
    }
}
