<!DOCTYPE html>
<!--
Sources used: https://getbootstrap.com/docs/5.1/components/navbar/, https://fontawesome.com/,
https://getbootstrap.com/docs/5.0/content/tables/, https://getbootstrap.com/docs/5.0/components/pagination/
-->

<html lang="en" class="h-100">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Nicholas Davidson">
  <meta name="description" content="Ride-A-Long: Find peers with whom to carpool long-distance - New ride">
  <meta name="keywords" content="Ride-a-long, ridealong, ride, uva, carpool, hoosdriving, hoosriding, ride, listings, rides">

  <title>Ride-A-Long - New ride</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="/styles/main.css">

  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script src="/scripts/notifications.js" charset="utf-8" defer></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#date').change(function() {
        var date = new Date();
        if ($('#date').val() == date.toLocaleDateString('sv', { timeZone: 'America/New_York' })) {
          $('#time').attr('min', date.toLocaleTimeString([], { hour: '2-digit', minute: "2-digit", hour12: false, timeZone: 'America/New_York' }));
        } else {
          $('#time').removeAttr('min');
        }
      });
    });
  </script>
</head>

<body class="d-flex flex-column h-100">
  <header class="container-fluid ps-4">
    <nav class="navbar navbar-expand navbar-light">
      <a class="navbar-brand fw-bold fs-1 text-white" href="/rides">Ride-A-Long<sub class="orange">@UVA</sub></a>
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-2">
          <div class="dropdown nav-link fs-1">
            <a class="nav-link fs-2" href="#" role="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications Dropdown">
              <i class="fa-solid fa-car"></i>
              <span id="notifsBadge" class="notification badge rounded-pill bg-danger">
                <span id="numNotifs"></span>
                <span class="visually-hidden">unread notifications</span>
              </span>
            </a>
            <ul id="notifs" class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown"></ul>
          </div>
        </li>
        <li class="nav-item">
          <div class="dropdown nav-link fs-1">
            <a class="nav-link" href="#" role="button" id="accountDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account Dropdown">
              <i class="fa-solid fa-user"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
              <li><a class="dropdown-item" href="/profile">Profile</a></li>
              <li><a class="dropdown-item" href="/myrides">My Rides</a></li>
              <li><a class="dropdown-item" href="/signout" id="signOut">Sign Out</a></li>
            </ul>
          </div>
        </li>
      </ul>
    </nav>
  </header>

  <main class="container-fluid mt-3">
    <form class="row col-sm-10 col-md-8 col-lg-6 mx-auto" action="/newride" method="post">
      <h2 class="text-center col-12">Ride Details</h2>
      <?php if (isset($error_msg)) { ?>
        <p class="alert alert-danger text-center"><?=$error_msg?></p>
      <?php } ?>
      <div class="mb-3 col-md-4">
        <?php date_default_timezone_set('America/New_York'); ?>
        <label for="date" class="form-label">Date</label>
        <input type="date" class="form-control" id="date" name="date" min="<?=date("Y-m-d");?>" required>
      </div>
      <div class="mb-3 col-md-4">
        <label for="time" class="form-label">Time</label>
        <input type="time" class="form-control" id="time" name="time" required>
      </div>
      <div class="mb-3 col-md-4">
        <label for="seats" class="form-label">Seats</label>
        <input type="number" class="form-control" id="seats" name="seats" required>
      </div>
      <div class="mb-3 col-12">
        <label for="origin" class="form-label">Origin</label>
        <input type="text" class="form-control" id="origin" name="origin" required>
        <input type="hidden" id="orig-addr" name="orig-addr" value="">
        <input type="hidden" id="orig-lat" name="orig-lat" value="">
        <input type="hidden" id="orig-long" name="orig-long" value="">
      </div>
      <div class="mb-3 col-12">
        <label for="destination" class="form-label">Destination</label>
        <input type="text" class="form-control" id="destination" name="destination" required>
        <input type="hidden" id="dest-addr" name="dest-addr" value="">
        <input type="hidden" id="dest-lat" name="dest-lat" value="">
        <input type="hidden" id="dest-long" name="dest-long" value="">
      </div>
      <div class="mb-3 col-12">
        <label class="form-label" for="info">More info</label>
        <textarea class="form-control" id="info" name="info" rows=3 maxlength="255"></textarea>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-uva-ob">Post</button>
        <button type="button" class="btn btn-uva-ob" data-bs-toggle="modal" data-bs-target="#previewModal" onclick="initPreview()">Preview</button>
      </div>
    </form>

    <script type="text/javascript">
      let acOrigin;
      let acDestination;

      function onOriginChanged() {
        var place = acOrigin.getPlace();

        // User entered the name of a Place that was not suggested and
        // pressed the Enter key, or the Place Details request failed.
        if (!place.geometry || !place.geometry.location) {
          var input = document.getElementById('origin');
          input.value = '';
          window.alert("Please select a location from the autocomplete list");
        } else {
          document.getElementById('orig-addr').value = place.formatted_address;
          var location = place.geometry.location;
          document.getElementById('orig-lat').value = location.lat();
          document.getElementById('orig-long').value = location.lng();
        }
      }

      function onDestinationChanged() {
        var place = acDestination.getPlace();

        // User entered the name of a Place that was not suggested and
        // pressed the Enter key, or the Place Details request failed.
        if (!place.geometry || !place.geometry.location) {
          var input = document.getElementById('destination');
          input.value = '';
          window.alert("Please select a location from the autocomplete list");
        } else {
          document.getElementById('dest-addr').value = place.formatted_address;
          var location = place.geometry.location;
          document.getElementById('dest-lat').value = location.lat();
          document.getElementById('dest-long').value = location.lng();
        }
      }

      function initAutocomplete() {
        // Google Maps UVA coordinates
        const center = { lat: 38.03361737225505, lng: -78.50800895660305 };
        // Bias location autocomplete results to UVA grounds/Charlottesville
        const bounds = {
          north: center.lat + 0.15,
          south: center.lat - 0.15,
          east: center.lng + 0.15,
          west: center.lng - 0.15,
        };
        // Attach autocomplete widget to location inputs
        const origin = document.getElementById("origin");
        const destination = document.getElementById("destination");
        // Autocomplete configuration
        const options = {
          bounds: bounds,
          componentRestrictions: { country: "us" },
          fields: ["formatted_address", "geometry"],
          strictBounds: false,
          types: [],
        };
        acOrigin = new google.maps.places.Autocomplete(origin, options);
        acDestination = new google.maps.places.Autocomplete(destination, options);

        acOrigin.addListener('place_changed', onOriginChanged);
        acDestination.addListener('place_changed', onDestinationChanged);
      }

      function initMap() {
        var origLat = document.getElementById('orig-lat').value;
        var origLong = document.getElementById('orig-long').value;
        var destLat = document.getElementById('dest-lat').value;
        var destLong = document.getElementById('dest-long').value;
        var origin = new google.maps.LatLng(origLat, origLong);
        var destination = new google.maps.LatLng(destLat, destLong);
        var myOptions = {
              zoom: 7,
              center: origin,
              disableDefaultUI: true
            }
        var map = new google.maps.Map(document.getElementById('map'), myOptions);
        var directionsService = new google.maps.DirectionsService();
        var directionsRenderer = new google.maps.DirectionsRenderer();
        directionsRenderer.setMap(map);

        directionsService.route({
          origin: origin,
          destination: destination,
          travelMode: 'DRIVING',
        }, function (result, status) {
          if (status == 'OK') {
            directionsRenderer.setDirections(result);
            var leg = result.routes[0].legs[0];
            $('#preview-distance').html(leg.distance.text + ' (' + leg.duration.text + ')');
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }

      function initPreview() {
        initMap();

        $("#preview-route").html($('#orig-addr').val() + " &#8594; " + $('#dest-addr').val());
        $("#preview-info").text($('#info').val());

        d = new Date($('#date').val()+'T'+$('#time').val());
        var month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        var date = month[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
        var time = d.toLocaleTimeString([], { hour: '2-digit', minute: "2-digit", hour12: true, timeZone: 'America/New_York' });
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
                <div id="map" style="height:300px; width:100%;"></div>
              </div>
              <div class="row">
                <h3 class="route" id="preview-route"></h3>
                <h4 class="distance" id="preview-distance"></h4>
                <h5 class="datetime"><span id="preview-date"></span> @ <span id="preview-time"></span></h6>
              </div>
              <div class="row">
                <p class="info" id="preview-info"></p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="requestModalLabel">Request to Join Ride</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col-12"><b>Ride:</b> <span id="requestRide"></span></div>
              </div>
              <div class="row">
                <div class="col-12"><b>User:</b> <span id="requestUser"></span></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <a id="deny" href="#" role="button" class="btn btn-danger">Deny</a>
            <a id="accept" href="#" role="button" class="btn btn-success">Accept</a>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="responseModalLabel">Response to Request</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col-12"><b>Ride:</b> <span id="responseRide"></span></div>
              </div>
              <div class="row">
                <div class="col-12"><b>Response:</b> <span id="response"></span></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <a id="read" href="#" role="button" class="btn btn-primary">Mark as Read</a>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="container-fluid mt-auto">
    <p class="text-muted text-center m-auto py-3">© Nicholas Davidson | <a href="mailto:nid3dhu@virginia.edu">nid3dhu@virginia.edu</a> | <a href="#">Disclaimer</a></p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/d21f3fd807.js" crossorigin="anonymous"></script>
  <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIlNof-TwR5KfntgTBOWjxDcBV-mqNAnc&callback=initAutocomplete&libraries=places"></script>
</body>

</html>
