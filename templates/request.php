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
    <div class="d-none" id="stop-div">
      <label class="form-label" for="stop">Your Stop</label>
      <input type="text" name="stop" id="stop" value="">
      <input type="hidden" id="stop-addr" name="stop-addr" value="">
      <input type="hidden" id="stop-lat" name="stop-lat" value="">
      <input type="hidden" id="stop-long" name="stop-long" value="">
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

    let acStop;

    function onStopChanged() {
      var place = acStop.getPlace();

      // User entered the name of a Place that was not suggested and
      // pressed the Enter key, or the Place Details request failed.
      if (!place.geometry || !place.geometry.location) {
        var input = document.getElementById('stop');
        input.value = '';
        window.alert("Please select a location from the autocomplete list");
      } else {
        document.getElementById('stop-addr').value = place.formatted_address;
        var location = place.geometry.location;
        document.getElementById('stop-lat').value = location.lat();
        document.getElementById('stop-long').value = location.lng();
      }
    }

    function initAutocomplete() {
      // Attach autocomplete widget to location inputs
      const stop = document.getElementById("stop");
      // Autocomplete configuration
      const options = {
        componentRestrictions: { country: "us" },
        fields: ["formatted_address", "geometry"],
        strictBounds: false
      };
      acStop = new google.maps.places.Autocomplete(stop, options);

      acStop.addListener('place_changed', onStopChanged);
    }
  </script>
</main>
