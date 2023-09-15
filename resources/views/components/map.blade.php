<div class="container-fluid" id="map-component">
    <div class="row mb-3">
        <div class="map" style="height:300px; width:100%;"></div>
    </div>
    <div class="row">
        <h3 class="route"></h3>
        <h4 class="distance"></h4>
        <h5><span class="date"></span> @ <span class="time"></span></h5>
    </div>
    <div class="row">
        <p><span class="description"></span> - <span class="driver"></span></p>
    </div>
    @vite('resources/js/map.js')
</div>
