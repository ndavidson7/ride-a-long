<main class="container-fluid mt-3">
    <form class="row col-sm-10 col-md-8 col-lg-6 mx-auto" action="/newride" method="post">
        <h2 class="text-center col-12">Ride Details</h2>
        <?php if (isset($error_msg)) { ?>
            <p class="alert alert-danger text-center"><?= $error_msg ?></p>
        <?php } ?>
        <div class="mb-3 col-md-4">
            <?php date_default_timezone_set('America/New_York'); ?>
            <label for="start-time" class="form-label">Date and Time</label>
            <input type="datetime-local" class="form-control" id="start-time" name="start-time" min="<?= date("Y-m-d\TH\:i"); ?>" required>
        </div>
        <div class="mb-3 col-md-4">
            <label for="seats" class="form-label">Seats</label>
            <input type="number" class="form-control" id="seats" name="seats" min="1" required>
        </div>
        <div class="mb-3 col-12 autocomplete">
            <label for="origin" class="form-label">Origin</label>
            <input type="text" class="form-control place" id="origin" name="origin" required>
            <input type="hidden" class="address" id="orig-addr" name="orig-addr" maxlength="255" value="">
            <input type="hidden" class="latitude" id="orig-lat" name="orig-lat" value="">
            <input type="hidden" class="longitude" id="orig-long" name="orig-long" value="">
        </div>
        <div class="mb-3 col-12 autocomplete">
            <label for="destination" class="form-label">Destination</label>
            <input type="text" class="form-control place" id="destination" name="destination" required>
            <input type="hidden" class="address" id="dest-addr" name="dest-addr" maxlength="255" value="">
            <input type="hidden" class="latitude" id="dest-lat" name="dest-lat" value="">
            <input type="hidden" class="longitude" id="dest-long" name="dest-long" value="">
        </div>
        <div class="mb-3 col-12">
            <label class="form-label" for="description">Description/Additional info</label>
            <textarea class="form-control" id="description" name="description" rows=3 maxlength="255"></textarea>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-uva-ob">Post</button>
            <button type="button" class="btn btn-uva-ob" data-bs-toggle="modal" data-bs-target="#previewModal" onclick="initPreview()">Preview</button>
        </div>
    </form>

    <script type="text/javascript">
        function initPreview() {
            data = {
                origin: {
                    latitude: document.getElementById('orig-lat').value,
                    longitude: document.getElementById('orig-long').value,
                },
                destination: {
                    latitude: document.getElementById('dest-lat').value,
                    longitude: document.getElementById('dest-long').value,
                }
            };

            initMap(data, 'preview');

            $("#preview-route").html($('#orig-addr').val() + " &#8594; " + $('#dest-addr').val());
            $("#preview-description").text($('#description').val());

            d = new Date($('#start-time').val());
            var month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
                'November', 'December'
            ];
            var date = month[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
            var time = d.toLocaleTimeString([], {
                hour: '2-digit',
                minute: "2-digit",
                hour12: true,
                timeZone: 'America/New_York'
            });
            $('#preview-date').text(date);
            $('#preview-time').text(time);
        }
    </script>

    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-fullscreen-md-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row mb-3">
                            <div id="preview-map" style="height:300px; width:100%;"></div>
                        </div>
                        <div class="row">
                            <h3 class="route" id="preview-route"></h3>
                            <h4 class="distance" id="preview-distance"></h4>
                            <h5 class="datetime"><span id="preview-date"></span> @ <span id="preview-time"></span></h6>
                        </div>
                        <div class="row">
                            <p class="info" id="preview-description"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</main>