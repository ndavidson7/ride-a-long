<main class="container-fluid mt-3">
    <div class="border-bottom">
        <div class="row mb-3">
            <div id="map" style="height:300px; width:100%;"></div>
        </div>
        <div class="row">
            <h3 class="route" id="route"></h3>
            <h4 id="distance"></h4>
            <h5 class="datetime"><span id="date"></span> @ <span id="time"></span></h6>
        </div>
        <div class="row">
            <p class="info"><span id="description"></span> - <span id="driver"></span></p>
        </div>
    </div>
    <form class="" method="post">
        <label class="form-label" for="checkbox">Add Stop?</label>
        <input type="checkbox" name="checkbox">
        <div class="d-none autocomplete" id="stop-div">
            <label class="form-label" for="stop">Your Stop</label>
            <input type="text" class="place" name="stop" id="stop" value="">
            <input type="hidden" class="address" id="stop-addr" name="stop-addr" value="">
            <input type="hidden" class="latitude" id="stop-lat" name="stop-lat" value="">
            <input type="hidden" class="longitude" id="stop-long" name="stop-long" value="">
            <button type="button" class="btn btn-uva-ob" onclick="initPreview()">Preview</button>
        </div>
    </form>

    <script type="text/javascript">
    $('input[name=checkbox]').change(function() {
        if ($(this).is(':checked')) {
            $('#stop-div').removeClass('d-none');
            $('#stop').prop('required', true);
        } else {
            $('#stop-div').addClass('d-none');
            $('#stop').prop('required', false);
        }
    });
    </script>
</main>