<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true" data-ride="">
    <div class="modal-dialog modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">Ride Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row mb-3">
                        <div class="map" style="height:300px; width:100%;"></div>
                    </div>
                    <div class="row">
                        <h3 class="route"></h3>
                        <h4 class="distance"></h4>
                        <h5 class="datetime"><span class="date"></span> @ <span class="time"></span></h5>
                    </div>
                    <div class="row">
                        <p class="info"><span class="description"></span> - <span class="driver"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a id="modal-button" class="btn btn-primary" role="button" href="#"></a>
            </div>
        </div>
    </div>
</div>
