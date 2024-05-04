export default () => ({
    // Map-related properties
    map: null,
    directionsClient: null,
    bounds: null,
    markers: [],

    // Ride locations
    _ride: {
        origin: null,
        destination: null,
        pickup: null,
        dropoff: null,
        waypoints: [],
    },

    // Route results
    route: [],
    totalDistance: null,
    totalDuration: null,

    init() {
        this.map = new mapboxgl.Map({
            container: this.$refs.map,
            style: "mapbox://styles/mapbox/streets-v12",
            minZoom: 3,
            cooperativeGestures: true,
            performanceMetricsCollection: false,
        })
            .addControl(new mapboxgl.FullscreenControl(), "top-right")
            .addControl(new mapboxgl.NavigationControl(), "top-right");

        this.directionsClient = createMapboxDirections({
            accessToken: mapboxgl.accessToken,
        });

        this.$watch("_ride", async () => await this.onRideUpdated());
    },

    /**
     * Route should follow this format (* indicates required):
     *  [
     *      {
     *          *address,
     *          *coordinates: [lng, lat],
     *          info,
     *          duration,
     *          distance,
     *      },
     *      ...
     *  ]
     */
    // set route(route) {
    //     if (!route || !Array.isArray(route) || route.length < 2)
    //         throw new TypeError(
    //             "Route must be an array of at least 2 waypoints",
    //         );

    //     if (JSON.stringify(this.route) === JSON.stringify(route)) return;

    //     this.origin = route[0];
    //     this.destination = route[route.length - 1];
    //     this.waypoints = route.slice(1, -1);
    // },

    get ride() {
        return this._ride;
    },

    set ride(ride) {
        this._ride.origin = ride.origin;
        this._ride.destination = ride.destination;
        this._ride.waypoints = ride.waypoints;
    },

    get origin() {
        return this._ride.origin;
    },

    set origin(origin) {
        this._ride.origin = origin;
    },

    get destination() {
        return this._ride.destination;
    },

    set destination(destination) {
        this._ride.destination = destination;
    },

    // should never need to set waypoints directly, i think...
    get waypoints() {
        return this._ride.waypoints;
    },

    get pickup() {
        return this._ride.pickup;
    },

    set pickup(pickup) {
        this._ride.pickup = pickup;
    },

    get dropoff() {
        return this._ride.dropoff;
    },

    set dropoff(dropoff) {
        this._ride.dropoff = dropoff;
    },

    async onRideUpdated() {
        if (!this.origin || !this.destination) return;

        const waypoints = await this.getOrderedWaypoints();
        console.log("Waypoints: ", waypoints);
        this.route = [
            this.formatAddressForRoute(this.origin),
            ...waypoints.map((waypoint) =>
                this.formatAddressForRoute(waypoint.address),
            ),
            this.formatAddressForRoute(this.destination),
        ];

        if (this.map.loaded()) this.update();
        else this.map.on("load", () => this.update());
    },

    async getOrderedWaypoints() {
        if (!this.pickup && !this.dropoff) return this.waypoints;

        let pickupWaypoint = null;
        if (this.pickup)
            pickupWaypoint = this.getOrCreateWaypoint(this.pickup, -2);

        let dropoffWaypoint = null;
        if (this.dropoff)
            dropoffWaypoint = this.getOrCreateWaypoint(this.dropoff, -1);

        // If we are adding to pre-existing waypoints, find the optimal order.
        // Otherwise, the only possible order is pickup -> dropoff.
        if (this.waypoints.length && (pickupWaypoint || dropoffWaypoint)) {
            return await this.optimizeWaypoints(
                pickupWaypoint,
                dropoffWaypoint,
            );
        } else {
            // concat pickup and/or dropoff to this.waypoints
            return this.waypoints.concat(
                pickupWaypoint ?? [],
                dropoffWaypoint ?? [],
            );
        }
    },

    getOrCreateWaypoint(address, id) {
        return (
            this.waypoints.find(
                (waypoint) =>
                    waypoint.address.longitude === address.longitude &&
                    waypoint.address.latitude === address.latitude,
            ) ?? {
                id: id,
                address: address,
            }
        );
    },

    async optimizeWaypoints(pickup, dropoff) {
        const response = await fetch(route("route.optimize"), {
            headers: {
                Accept: "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
            method: "POST",
            credentials: "same-origin",
            body: JSON.stringify({
                route: [this.origin, ...this.waypoints, this.destination],
                pickup: pickup,
                dropoff: dropoff,
            }),
        });

        const data = await response.json();

        if (!response.ok) throw new Error(data);

        console.log("Optimized waypoints: ", data);
        return data;
    },

    async update() {
        const directions = await this.getDirections();

        this.renderDirections(directions);

        this.updateInfo(directions);

        this.fitBounds();
    },

    async getDirections() {
        return this.directionsClient
            .getDirections({
                waypoints: this.route,
                geometries: "geojson",
            })
            .send()
            .then((response) => {
                return response.body;
            });
    },

    renderDirections(directions) {
        const geojsonData = {
            type: "Feature",
            properties: {},
            geometry: directions.routes[0].geometry,
        };

        const source = this.map.getSource("route");
        if (source) {
            source.setData(geojsonData);
        } else {
            this.map.addSource("route", {
                type: "geojson",
                data: geojsonData,
            });
        }

        if (!this.map.getLayer("route")) {
            this.map.addLayer({
                id: "route",
                type: "line",
                source: "route",
                layout: {
                    "line-join": "round",
                    "line-cap": "round",
                },
                paint: {
                    "line-color": "#2563eb",
                    "line-width": 5,
                },
            });
        }

        // Add markers for each waypoint
        this.markers.forEach((marker) => {
            marker.getPopup().remove();
            marker.remove();
        });
        this.markers = [];

        directions.waypoints.forEach((waypoint, index) => {
            const marker = document.createElement("div");
            marker.className =
                "rounded-full bg-blue-700 text-center text-white font-bold size-6 border-2 border-white grid place-items-center shadow-[0_0_8px_-1px_black]";
            marker.textContent = index + 1;

            this.markers.push(
                new mapboxgl.Marker(marker)
                    .setLngLat(waypoint.location)
                    .setPopup(
                        new mapboxgl.Popup({
                            className:
                                "[&_.mapboxgl-popup-close-button]:px-1.5",
                            offset: [0, -16],
                        }).setText(waypoint.name),
                    )
                    .addTo(this.map),
            );
        });
    },

    updateInfo(directions) {
        const route = directions.routes[0];

        const coordinates = route.geometry.coordinates;
        this.bounds = coordinates.reduce(
            (bounds, coord) => {
                return bounds.extend(coord);
            },
            new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]),
        );

        this.totalDistance = this.metersToMiles(route.distance);
        this.totalDuration = this.secondsToHoursMins(route.duration);

        route.legs.forEach((leg, index) => {
            this.route[index + 1].distance = this.metersToMiles(leg.distance);
            this.route[index + 1].duration = this.secondsToHoursMins(
                leg.duration,
            );
        });
    },

    metersToMiles(meters, verbose = false) {
        return (meters / 1609.34).toFixed(1) + (verbose ? " miles" : "mi");
    },

    secondsToHoursMins(seconds, verbose = false) {
        const hours = Math.floor(seconds / 3600);
        const mins = Math.floor((seconds % 3600) / 60);

        let formatted = "";

        if (hours > 0) {
            formatted +=
                hours + (verbose ? (" hour" + hours > 1 ? "s" : "") : "h");
        }

        if (mins > 0) {
            formatted +=
                mins + (verbose ? (" minute" + mins > 1 ? "s" : "") : "m");
        }

        return formatted;
    },

    onIntersect() {
        this.map.resize();
        if (this.bounds) this.fitBounds();
    },

    fitBounds() {
        const padding = 60;
        this.map.fitBounds(this.bounds, {
            animate: false,
            padding: {
                top: padding,
                bottom: padding,
                left:
                    getComputedStyle(this.$refs.route).getPropertyValue(
                        "position",
                    ) === "absolute"
                        ? this.$refs.route.getBoundingClientRect().width +
                          padding
                        : padding,
                right:
                    document
                        .querySelector(".mapboxgl-ctrl-top-right")
                        .getBoundingClientRect().width + padding,
            },
        });
    },

    flyToMarker(index) {
        const marker = this.markers[index];
        this.map.flyTo({
            center: marker.getLngLat(),
        });

        // if (!marker.getPopup().isOpen()) marker.togglePopup();
    },

    formatAddressForRoute(address) {
        return {
            address: address.formatted_address,
            coordinates: [address.longitude, address.latitude],
        };
    },
});
