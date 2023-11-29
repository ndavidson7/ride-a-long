<div {{ $attributes->merge(['class' => 'd-flex flex-column row-gap-2 placeholder-glow']) }} id="map-component">
    <div class="map" style="height:300px; width:100%;"></div>
    <div class="d-flex gap-2 align-items-center">
        <a class="btn btn-uva-ob btn-bold" target="_blank" id="route-directions">View Directions</a>
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
</template>
