import { createMap, createDirections } from "./google-api.js";

class MapComponent {
    constructor(component) {
        this.map = createMap(component.querySelector(".map"));
        const { directionsService, directionsRenderer } = createDirections(
            this.map
        );
        this.directionsService = directionsService;
        this.directionsRenderer = directionsRenderer;

        this.route = component.querySelector(".route");
        this.distance = component.querySelector(".distance");
        this.date = component.querySelector(".date");
        this.time = component.querySelector(".time");
        this.description = component.querySelector(".description");
        this.driver = component.querySelector(".driver");
    }

    // Ride modal, show ride: all info in DB
    //     update(rideId)
    // Create/edit ride: all info in form
    //     update(null, data)
    // Create request: ride origin, destination, and current waypoints in DB + request waypoints in form
    //     update(rideId, data)
    // Show request: ride origin, destination, and current waypoints in DB + request waypoints in blade view
    //     update(rideId, data)
    update(rideId = null) {
        // if this already contains the relevant ride info, don't update
        if (this.rideId && this.rideId == id) return;
        this.rideId = rideId;

        this.getData();

        if (import.meta.env.VITE_APP_DEBUG)
            console.log("Ride data:", this.data);

        this.getRoute();
    }

    getData() {
        // If rideId is null, all data is in the form (we're creating a new ride)
        if (this.rideId == null) {
            this.extractData();
        } else {
            this.fetchData();
        }
    }

    getRoute() {
        const origin = new google.maps.LatLng(
            this.data.origin.latitude,
            this.data.origin.longitude
        );
        const destination = new google.maps.LatLng(
            this.data.destination.latitude,
            this.data.destination.longitude
        );

        let waypoints = [];
        if (this.data.waypoints) {
            for (const waypoint of this.data.waypoints) {
                waypoints.push({
                    location: new google.maps.LatLng(
                        waypoint.address.latitude,
                        waypoint.address.longitude
                    ),
                    stopover: true,
                });
            }
        }

        this.directionsService.route(
            {
                origin: origin,
                destination: destination,
                waypoints: waypoints,
                optimizeWaypoints: true,
                travelMode: google.maps.TravelMode.DRIVING,
            },
            function (result, status) {
                if (status === "OK") {
                    this.directionsRenderer.setDirections(result);

                    const { miles, time } = this.calculateDistance(result);

                    this.distance.textContent = miles + " miles (" + time + ")";
                } else {
                    window.alert("Directions request failed due to " + status); // TODO: Handle this
                }
            }
        );
    }

    renderData() {
        this.route.textContent =
            this.data.origin.address +
            " \u2192 " +
            this.data.destination.address;

        const { date, time } = formatDateTime(this.data.start_time);
        this.date.textContent = date;
        this.time.textContent = time;

        this.description.textContent = this.data.description;

        if (this.data.driver)
            // TODO: Remove this when driver fetching implemented
            this.driver.textContent = `${this.data.driver.first_name} ${this.data.driver.last_name} (${this.data.driver.email})`;
    }

    extractData() {
        this.data = {
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

    fetchData() {
        fetch(route("rides.show", this.rideId), {
            headers: { Accept: "application/json" },
        })
            .then((response) => response.json())
            .then((data) => {
                this.data = data;
            });
    }

    calculateDistance(routeResult) {
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
