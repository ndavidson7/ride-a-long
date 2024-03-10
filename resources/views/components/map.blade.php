<div {{ $attributes->class(['relative', 'space-y-4', 'lg:space-y-0']) }} x-data="{
    map: null,
    directionsClient: null,
    bounds: null,
    totalDistance: null,
    totalDuration: null,
    legs: [],
    markers: [],

    init() {
        this.map = new mapboxgl.Map({
                container: $refs.map,
                style: 'mapbox://styles/mapbox/streets-v12',
                minZoom: 3,
                cooperativeGestures: true,
                performanceMetricsCollection: false,
            })
            .addControl(new mapboxgl.FullscreenControl(), 'top-right')
            .addControl(new mapboxgl.NavigationControl(), 'top-right');

        this.directionsClient = createMapboxDirections({ accessToken: mapboxgl.accessToken });
    },

    async update(route) {
        console.log('Map received route: ', route);
        const directions = await this.getDirections(route);
        console.log('Directions: ', directions);

        this.renderDirections(directions);

        this.updateInfo(directions.routes[0]);
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
                    'line-color': '#2563eb',
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

        directions.waypoints.forEach((waypoint, index) => {
            const marker = document.createElement('div');
            marker.className = 'rounded-full bg-blue-700 text-center text-white font-bold size-6 border-2 border-white grid place-items-center shadow-[0_0_8px_-1px_black]';
            marker.textContent = index + 1;

            this.markers.push(new mapboxgl.Marker(marker)
                .setLngLat(waypoint.location)
                .setPopup(new mapboxgl.Popup({
                        className: '[&_.mapboxgl-popup-close-button]:px-1.5',
                        offset: [0, -16]
                    })
                    .setText(waypoint.name))
                .addTo(this.map));
        });
    },

    updateInfo(route) {
        const coordinates = route.geometry.coordinates;
        this.bounds = coordinates.reduce((bounds, coord) => {
            return bounds.extend(coord);
        }, new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]));

        this.totalDistance = this.metersToMiles(route.distance);
        this.totalDuration = this.secondsToHoursMins(route.duration);

        this.legs = route.legs.map(leg => {
            return {
                distance: this.metersToMiles(leg.distance),
                duration: this.secondsToHoursMins(leg.duration),
            };
        });
    },

    metersToMiles(meters) {
        return (meters / 1609.34).toFixed(1) + ' miles';
    },

    secondsToHoursMins(seconds) {
        const hours = Math.floor(seconds / 3600);
        const mins = Math.floor((seconds % 3600) / 60);
        const hDisplay = hours > 0 ? `${hours} hour${hours !== 1 ? 's' : ''}` : '';
        const mDisplay = mins > 0 ? `${mins} minute${mins !== 1 ? 's' : ''}` : '';
        return `${hDisplay} ${mDisplay}`;
    },

    onIntersect() {
        this.map.resize();
        this.fitBounds();
    },

    fitBounds() {
        const padding = 60;
        this.map.fitBounds(this.bounds, {
            animate: false,
            padding: {
                top: padding,
                bottom: padding,
                left: getComputedStyle($refs.route).getPropertyValue('position') === 'absolute' ? $refs.route.getBoundingClientRect().width + padding : padding,
                right: document.querySelector('.mapboxgl-ctrl-top-right').getBoundingClientRect().width + padding
            },
        });
    },

    flyToMarker(index) {
        const marker = this.markers[index];
        this.map.flyTo({
            center: marker.getLngLat(),
        });

        {{-- if (!marker.getPopup().isOpen()) marker.togglePopup(); --}}
    },
}"
    x-intersect="onIntersect">

    {{-- Map container element --}}
    <div class="aspect-video rounded-lg" x-ref="map"></div>

    {{-- Route info --}}
    <div class="left-2.5 top-2.5 divide-y divide-[#ddd] overflow-hidden rounded-lg bg-white shadow-[0_0_0_2px_rgba(0,0,0,.1)] lg:absolute"
        x-ref="route">
        <div class="flex flex-wrap items-center gap-3 p-3">
            <div class="space-x-1">
                <x-fas-route class="size-5 inline-block text-neutral-600" />
                <span class="align-middle text-base font-medium text-neutral-600" x-text="totalDistance"></span>
            </div>
            <div class="space-x-1">
                <x-fas-clock class="size-5 inline-block text-neutral-600" />
                <span class="align-middle text-base font-medium text-neutral-600" x-text="totalDuration"></span>
            </div>
        </div>
        <ol class="">
            <template
                x-for="(stop, index) in [ride?.origin, ...(ride?.waypoints?.map(waypoint => waypoint.address) ?? []), ride?.destination]">
                <li class="relative grid cursor-pointer grid-cols-[auto_0.8fr_0.2fr] items-center gap-3 p-3 text-sm font-medium before:absolute before:bottom-0 before:left-[23px] before:top-0 before:z-0 before:border-x before:border-dashed before:border-blue-400 hover:bg-blue-100"
                    :class="index === 0 ? 'before:!top-1/2' : stop === ride?.destination ?
                        'before:!bottom-1/2' : ''"
                    @click="flyToMarker(index)">
                    <div class="size-6 relative z-10 grid place-items-center rounded-full bg-blue-700 text-white"
                        x-text="index + 1"></div>
                    <div class="" x-text="stop?.address"></div>
                    <div class="text-xs font-normal" x-text="index > 0 ? legs[index-1]?.duration : ''">
                    </div>
                </li>
            </template>
        </ol>
    </div>

</div>
