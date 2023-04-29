<!DOCTYPE html>

<html lang="en" class="h-100">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Nicholas Davidson">
  <meta name="description" content="Ride-A-Long: Find peers with whom to carpool long-distance - Request to join ride">
  <meta name="keywords" content="Ride-a-long, ridealong, ride, uva, carpool, hoosdriving, hoosriding, ride, listings, rides">

  <title>Ride-A-Long - Request</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="/styles/main.css">

  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script src="/scripts/notifications.js" charset="utf-8" defer></script>
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
        <p class="info"><span id="info"></span> - <span id="driver"></span></p>
      </div>
    </div>
    <form class="" method="post">
      <label class="form-label" for="message">Message</label>
      <textarea class="form-control" id="message" name="message" rows=3 maxlength="150" aria-describedby="messageLimit"></textarea>
      <div id="messageLimit" class="form-text">Max 150 characters.</div>
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

      function initMap() {

      }

      function initPreview() {

      }
    </script>

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
