<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">Ride Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row mb-3">
                        <div class="map" id="info-map" style="height:300px; width:100%;"></div>
                    </div>
                    <div class="row">
                        <h3 class="route" id="info-route"></h3>
                        <h4 class="distance" id="info-distance"></h4>
                        <h5 class="datetime"><span class="date" id="info-date"></span> @ <span class="time" id="info-time"></span></h6>
                    </div>
                    <?php if ($rider) { ?>
                        <div class="row">
                            <p class="info"><span class="description" id="info-description"></span> - <span class="driver" id="info-driver"></span></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <?php if ($request) { ?>
                    <a id="request" href="#" role="button" class="btn btn-primary">Request</a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.querySelector('#mapModal');
        modal.addEventListener('show.bs.modal', function(event) {
            initModal(event, modal, false);
        });
    });
</script>