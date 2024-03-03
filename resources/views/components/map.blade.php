@props(['type' => 'rideModal'])

<div {{ $attributes->class(['aspect-video']) }} x-data="{
    type: '{{ $type }}',
    map: null,
    directions: null,
    origin: { lng: null, lat: null },
    waypoints: null,
    destination: { lng: null, lat: null },

    init() {
        this.map = new mapboxgl.Map({
            container: $el,
            style: 'mapbox://styles/mapbox/streets-v12',
            minZoom: 3,
            performanceMetricsCollection: false,
        });

        this.directions = new MapboxDirections({
            accessToken: mapboxgl.accessToken,
            interactive: false,
            profile: 'mapbox/driving',
            controls: {
                inputs: false,
                instructions: false,
                profileSwitcher: false,
            },
            flyTo: false,
        });

        this.map.addControl(this.directions);

        $watch('origin', value => {
            if (!isValidPoint(value)) return;

            this.directions.setOrigin([value.lng, value.lat]);
        });
        $watch('waypoints', value => {
            value.forEach((waypoint, index) => {
                if (!isValidPoint(waypoint)) return;

                this.directions.addWaypoint(index, [waypoint.lng, waypoint.lat]);
            });
        });
        $watch('destination', value => {
            if (!isValidPoint(value)) return;

            this.directions.setDestination([value.lng, value.lat]);
        });
    },

    isValidPoint(point) {
        return point.lng && point.lat;
    },

    setOrigin(origin) {
        this.directions.setOrigin(origin);
    },

    setWaypoints(waypoints) {
        waypoints.forEach(waypoint => this.directions.addWaypoint(waypoint));
    },

    setDestination(destination) {
        this.directions.setDestination(destination);
    },

    async update(args) {
        switch (this.type) {
            case 'rideModal':
                this.directions.removeRoutes();
                $nextTick(() => this.map.resize());

                const data = await this.fetchData(args.rideId);
                console.log(data);
                this.directions.setOrigin([data.origin.longitude, data.origin.latitude]);
                data.waypoints.forEach((waypoint, index) => this.directions.addWaypoint(index, [waypoint.address.longitude, waypoint.address.latitude]));
                this.directions.setDestination([data.destination.longitude, data.destination.latitude]);

                break;
        }
    },

    async fetchData(rideId) {
        if (rideId === undefined) throw new TypeError(`Missing argument 'rideId'`);

        return fetch(route('rides.show', rideId), {
                headers: { Accept: 'application/json' },
            })
            .then((response) => response.json())
            .then((data) => {
                return data;
            });
    }
}" @mapupdate.window="update($event.detail)">
</div>

{{-- <div {{ $attributes->merge(['class' => 'd-flex flex-column row-gap-2 placeholder-glow']) }} id="map-component">
    <div class="map ratio ratio-21x9 w-100"></div>
    <div class="d-flex gap-2 align-items-center">
        <a class="btn btn-primary btn-bold" target="_blank" id="route-directions">View Directions</a>
        <h4 class="m-0 distance"></h4>
    </div>
    <ol class="route list-group list-group-numbered list-group-flush"></ol>
</div>

<template id="li-template">
    <li class="list-group-item d-flex align-items-start">
        <div class="ms-2 me-auto">
            <div class="fw-bold" id="detail"></div>
            <a target="_blank" id="address-directions"></a>
            (<span id="running-total"></span>)
        </div>
    </li>
</template> --}}
