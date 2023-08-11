<main class="container-fluid mt-3">
    <form class="" method="post">
        <label class="form-label" for="pickup-checkbox">Pickup</label>
        <input type="checkbox" name="pickup-checkbox">
        <div class="autocomplete" id="pickup-div">
            <label class="form-label" for="pickup">Your Pickup Address</label>
            <input type="text" class="place" name="pickup" id="pickup" value="">
            <input type="hidden" class="address" id="pickup-addr" name="pickup-addr" value="">
            <input type="hidden" class="latitude" id="pickup-lat" name="pickup-lat" value="">
            <input type="hidden" class="longitude" id="pickup-long" name="pickup-long" value="">
        </div>
        <label class="form-label" for="dropoff-checkbox">Dropoff</label>
        <input type="checkbox" name="dropoff-checkbox">
        <div class="autocomplete" id="dropoff-div">
            <label class="form-label" for="dropoff">Your Dropoff Address</label>
            <input type="text" class="place" name="dropoff" id="dropoff" value="">
            <input type="hidden" class="address" id="dropoff-addr" name="dropoff-addr" value="">
            <input type="hidden" class="latitude" id="dropoff-lat" name="dropoff-lat" value="">
            <input type="hidden" class="longitude" id="dropoff-long" name="dropoff-long" value="">
        </div>
        <button type="submit" class="btn btn-uva-ob">Request</button>
        <button type="button" class="btn btn-uva-ob" data-bs-toggle="modal" data-bs-target="#previewModal" onclick="initPreview()">Preview</button>
    </form>

    <script type="text/javascript">
        document.querySelectorAll('.autocomplete').forEach(autocomplete => {
            autocomplete.style.display = 'none';
        });

        document.querySelectorAll('input[type=checkbox]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.nextElementSibling.style.display = 'block';
                    this.nextElementSibling.firstElementChild.focus();
                    this.nextElementSibling.firstElementChild.required = true;
                } else {
                    this.nextElementSibling.style.display = 'none';
                    this.nextElementSibling.firstElementChild.required = false;
                }
            });
        });

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

            const modal = document.querySelector('#mapModal');
            initMap(data, modal);

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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</main>