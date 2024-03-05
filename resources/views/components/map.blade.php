<div {{ $attributes->class(['aspect-video']) }} x-data="{
    map: null,
    directionsClient: null,
    route: {
        origin: [],
        waypoints: [],
        destination: [],
    },

    init() {
        this.map = new mapboxgl.Map({
            container: $el,
            style: 'mapbox://styles/mapbox/streets-v12',
            minZoom: 3,
            performanceMetricsCollection: false,
        });

        this.directionsClient = createMapboxDirections({ accessToken: mapboxgl.accessToken });

        $watch('route', value => {
            console.log('Map received route: ', value);
            this.getDirections();
        });
    },

    update(directions) {
        console.log(directions);

        this.map.resize();

        if (this.map.getLayer('route')) {
            this.map.removeLayer('route');
        }

        if (this.map.getSource('route')) {
            this.map.removeSource('route');
        }

        this.map.addSource('route', {
            type: 'geojson',
            data: {
                type: 'FeatureCollection',
                features: [{
                        type: 'Feature',
                        properties: {},
                        geometry: directions.routes[0].geometry,
                    },
                    ...directions.waypoints.map(waypoint => {
                        return {
                            type: 'Feature',
                            properties: {},
                            geometry: {
                                type: 'Point',
                                coordinates: waypoint.location,
                            },
                        };
                    })
                ]
            },
        });

        this.map.addLayer({
            id: 'route',
            type: 'line',
            source: 'route',
            layout: {
                'line-join': 'round',
                'line-cap': 'round'
            },
            paint: {
                'line-color': '#BF93E4',
                'line-width': 5
            },
            filter: ['==', '$type', 'LineString']
        });

        this.map.addLayer({
            id: 'waypoints',
            type: 'circle',
            source: 'route',
            paint: {
                'circle-radius': 5,
                'circle-color': '#000000',
            },
            filter: ['==', '$type', 'Point']
        });

        const coordinates = directions.routes[0].geometry.coordinates;

        const bounds = coordinates.reduce((bounds, coord) => {
            return bounds.extend(coord);
        }, new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]));

        this.map.fitBounds(bounds, {
            padding: 40,
        });
    },

    getDirections() {
        this.directionsClient.getDirections({
                waypoints: [{
                        coordinates: this.route.origin,
                    },
                    ...this.route.waypoints.map(waypoint => {
                        return {
                            coordinates: waypoint,
                        };
                    }),
                    {
                        coordinates: this.route.destination,
                    }
                ],
                geometries: 'geojson',
            })
            .send()
            .then(response => {
                this.update(response.body);
            });
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
}">
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
