import { createMap, createDirections } from "./google-api.js";

class MapComponent {
    static route;
    static distance;
    static date;
    static time;
    static description;
    static driver;
    static {
        this.route = document.getElementById("route");
        this.distance = document.getElementById("distance");
        this.date = document.getElementById("date");
        this.time = document.getElementById("time");
        this.description = document.getElementById("description");
        this.driver = document.getElementById("driver");
    }

    constructor() {
        this.map = createMap();
        const { directionsService, directionsRenderer } = createDirections(
            this.map
        );
        this.directionsService = directionsService;
        this.directionsRenderer = directionsRenderer;
    }

    update(rideId) {
        fetch(route("rides.show", rideId))
            .then((response) => response.json())
            .then((data) => {
                if (import.meta.env.VITE_APP_DEBUG)
                    console.log("Map data:", data);

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

                            // Calculate distance and duration from start to end
                            var dist = 0;
                            var dur = 0;
                            for (
                                let i = 0;
                                i < result.routes[0].legs.length;
                                i++
                            ) {
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
                                h > 0
                                    ? h + (h == 1 ? " hour, " : " hours, ")
                                    : "";
                            const mDisplay =
                                m > 0
                                    ? m + (m == 1 ? " minute, " : " minutes, ")
                                    : "";
                            const sDisplay =
                                s > 0
                                    ? s + (s == 1 ? " second" : " seconds")
                                    : "";

                            const time = hDisplay + mDisplay + sDisplay;

                            this.distance.textContent =
                                miles + " miles (" + time + ")";
                        } else {
                            window.alert(
                                "Directions request failed due to " + status
                            ); // TODO: Handle this
                        }
                    }
                );
            });
    }
}
