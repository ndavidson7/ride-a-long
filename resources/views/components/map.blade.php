<div {{ $attributes->merge(['class' => 'd-flex flex-column row-gap-2 placeholder-glow']) }} id="map-component">
    <div class="map" style="height:300px; width:100%;"></div>
    <div>
        <a class="btn btn-uva-ob" target="_blank" id="route-directions">View Directions</a>
    </div>
    <ol class="route list-group list-group-numbered list-group-flush"></ol>
    <h4 class="distance"></h4>
    <h5><span class="date"></span> @ <span class="time"></span></h5>
    {{-- <p><span class="description"></span> - <span class="driver"></span></p> --}}
    <p class="description"></p>
</div>

<template id="li-template">
    <li class="list-group-item d-flex align-items-start">
        <div class="ms-2 me-auto">
            <div class="fw-bold" id="detail"></div>
            <a target="_blank" id="address-directions"></a>
            (<span id="running-total"></span>)
        </div>
    </li>
</template>
