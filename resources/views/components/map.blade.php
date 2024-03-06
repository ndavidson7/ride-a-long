<div {{ $attributes->class(['aspect-video']) }} x-data="{
    map: null,
    directionsClient: null,
    bounds: null,
    markers: [],

    init() {
        this.map = new mapboxgl.Map({
            container: $el,
            style: 'mapbox://styles/mapbox/streets-v12',
            minZoom: 3,
            performanceMetricsCollection: false,
        });

        this.directionsClient = createMapboxDirections({ accessToken: mapboxgl.accessToken });
    },

    async update(route) {
        console.log('Map received route: ', route);
        const directions = await this.getDirections(route);
        console.log('Directions: ', directions);

        this.renderDirections(directions);

        const coordinates = directions.routes[0].geometry.coordinates;
        this.bounds = coordinates.reduce((bounds, coord) => {
            return bounds.extend(coord);
        }, new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]));
    },

    async getDirections(route) {
        return this.directionsClient.getDirections({
                waypoints: [{
                        coordinates: route.origin,
                    },
                    ...route.waypoints.map(waypoint => {
                        return {
                            coordinates: waypoint,
                        };
                    }),
                    {
                        coordinates: route.destination,
                    }
                ],
                geometries: 'geojson',
            })
            .send()
            .then(response => {
                return response.body;
            });
    },

    renderDirections(directions) {
        const geojsonData = {
            type: 'Feature',
            properties: {},
            geometry: directions.routes[0].geometry,
        };

        const source = this.map.getSource('route');
        if (source) {
            source.setData(geojsonData);
        } else {
            this.map.addSource('route', {
                type: 'geojson',
                data: geojsonData,
            });
        }

        if (!this.map.getLayer('route')) {
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
                }
            });
        }

        {{-- Add markers for each waypoint --}}
        this.markers.forEach(marker => {
            marker.getPopup().remove();
            marker.remove()
        });
        this.markers = [];

        directions.waypoints.forEach(waypoint => {
            this.markers.push(new mapboxgl.Marker({ color: 'black' })
                .setLngLat(waypoint.location)
                .setPopup(new mapboxgl.Popup({ className: '[&_.mapboxgl-popup-close-button]:px-1.5' }).setText(waypoint.name))
                .addTo(this.map));
        });
    },
}"
    x-intersect="map.resize(); map.fitBounds(bounds, { animate: false, padding: 40, });">
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
