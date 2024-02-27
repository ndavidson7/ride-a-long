<div {{ $attributes->class(['aspect-video']) }} x-data="{
    ride: null,

    init() {
        this.map = new mapboxgl.Map({
            container: $el,
            minZoom: 2,
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
            }
        });

        this.map.addControl(this.directions);
    },


}" @mapupdate.window="console.log($event.detail)">
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
