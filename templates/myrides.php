<!DOCTYPE html>
<!--
Sources used: https://getbootstrap.com/docs/5.1/components/navbar/, https://fontawesome.com/,
https://getbootstrap.com/docs/5.1/components/card/
-->

<html lang="en" class="h-100">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Nicholas Davidson">
  <meta name="description" content="Ride-A-Long: Find peers with whom to carpool long-distance - My rides">
  <meta name="keywords" content="Ride-a-long, ridealong, ride, uva, carpool, hoosdriving, hoosriding, ride, listings, rides">

  <title>Ride-A-Long - My rides</title>

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

  <main class="container-fluid d-flex flex-column mt-3">
    <a href="/newride" class="btn btn-uva-ob fw-bold fs-5 mb-2" style="width:200px;">Post new ride</a>
    <?php if (isset($_SESSION['error_msg'])) { ?>
      <p class="alert alert-danger"><?=$_SESSION['error_msg']?></p>
    <?php
    unset($_SESSION['error_msg']);
    } else if (isset($_SESSION['success_msg'])) { ?>
      <p class="alert alert-success"><?=$_SESSION['success_msg']?></p>
    <?php
    unset($_SESSION['success_msg']);
    } ?>
    <h2>Posted Rides:</h2>
    <?php if ($posted != null) { ?>
      <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-4 mb-3">
        <?php foreach ($posted as $ride) { ?>
          <div class="col">
            <div class="card text-center h-100">
              <div class="card-body">
                <h5 class="card-title"><?=$ride["orig_addr"]?> &#8594; <?=$ride["dest_addr"]?></h5>
                <h6 class="card-subtitle mb-2"><?=date("m/d", strtotime($ride["date"]))?> @ <?=date("g:i a", strtotime($ride["time"]))?></h6>
                <p class="card-text"><?=$ride["seats_open"]?> out of <?=$ride["seats_total"]?> seats left!</p>
                <button type="button" class="card-link btn btn-uva-ob" data-bs-toggle="modal" data-bs-target="#infoPostedModal" data-bs-ride="<?=$ride["id"]?>">More info</button>
                <a href="/myrides/delete/<?=$ride['id']?>" class="card-link btn btn-uva-ob">Delete</a>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>

    <?php } else { ?>
      <div class="text-center mb-3">
        <h3>You have not posted any rides!</h3>
      </div>
    <?php } ?>

    <h2>Joined Rides:</h2>
    <?php if ($joined != null) { ?>
      <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-4">
        <?php foreach ($joined as $ride) { ?>
          <div class="col">
            <div class="card text-center h-100">
              <div class="card-body">
                <h5 class="card-title"><?=$ride["orig_addr"]?> &#8594; <?=$ride["dest_addr"]?></h5>
                <h6 class="card-subtitle mb-2"><?=date("m/d", strtotime($ride["date"]))?> @ <?=date("g:i a", strtotime($ride["time"]))?></h6>
                <p class="card-text"><?=$ride["seats_open"]?> out of <?=$ride["seats_total"]?> seats left!</p>
                <button type="button" class="card-link btn btn-uva-ob" data-bs-toggle="modal" data-bs-target="#infoJoinedModal" data-bs-ride="<?=$ride["id"]?>">More info</button>
                <a href="/myrides/leave/<?=$ride['id']?>" class="card-link btn btn-uva-ob">Leave</a>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>

    <?php } else { ?>
      <div class="text-center">
        <h3>You have not joined any rides!</h3>
      </div>
    <?php } ?>

    <div class="modal fade" id="infoJoinedModal" tabindex="-1" aria-labelledby="infoJoinedModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="infoJoinedModalLabel">Ride Info</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row mb-3">
                <div id="joined-map" style="height:300px; width:100%;"></div>
              </div>
              <div class="row">
                <h3 class="route" id="joined-route"></h3>
                <h4 id="joined-distance"></h4>
                <h5 class="datetime"><span id="joined-date"></span> @ <span id="joined-time"></span></h6>
              </div>
              <div class="row">
                <p class="info"><span id="joined-info"></span> - <span id="joined-driver"></span></p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <!--
            CHANGE THIS TO A LEAVE RIDE BUTTON
            <a id="request" href="#" role="button" class="btn btn-primary">Request</a>
            -->
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="infoPostedModal" tabindex="-1" aria-labelledby="infoPostedModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="infoPostedModalLabel">Preview</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row mb-3">
                <div id="posted-map" style="height:300px; width:100%;"></div>
              </div>
              <div class="row">
                <h3 class="route" id="posted-route"></h3>
                <h4 class="distance" id="posted-distance"></h4>
                <h5 class="datetime"><span id="posted-date"></span> @ <span id="posted-time"></span></h6>
              </div>
              <div class="row">
                <p class="info" id="posted-info"></p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      function initMap(modal, data) {
        var origLat = data.orig_lat;
        var origLong = data.orig_long;
        var destLat = data.dest_lat;
        var destLong = data.dest_long;
        var origin = new google.maps.LatLng(origLat, origLong);
        var destination = new google.maps.LatLng(destLat, destLong);
        var myOptions = {
          zoom: 7,
          center: origin,
          disableDefaultUI: true
        }
        var map = new google.maps.Map(document.getElementById(modal+'-map'), myOptions);
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
            $('#'+modal+'-distance').html(leg.distance.text + ' (' + leg.duration.text + ')');
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }

      $(document).ready(function() {
        $('#infoJoinedModal').on('show.bs.modal', function(event) {
          // Determine which ride was clicked
          var ride = $(event.relatedTarget).attr("data-bs-ride");
          var url = $(location).attr('origin') == 'http://localhost' ? 'http://localhost/rides/'+ride : 'https://cs4640.cs.virginia.edu/nid3dhu/project/rides/'+ride;
          // AJAX request
          $.getJSON(url, function(data) {
            // Update the modal's content.
            $("#joined-route").html(data.orig_addr + " &#8594; " + data.dest_addr);
            $("#joined-info").text(data.info);
            $("#joined-driver").text(data.user.first_name+" "+data.user.last_name+" ("+data.user.email+")");

            d = new Date(data.date+'T'+data.time);
            var month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            var date = month[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
            var time = d.toLocaleTimeString([], { hour: '2-digit', minute: "2-digit", hour12: true, timeZone: 'America/New_York' });
            $('#joined-date').text(date);
            $('#joined-time').text(time);

            initMap('joined', data);
          });
        });

        $('#infoPostedModal').on('show.bs.modal', function(event) {
          // Determine which ride was clicked
          var ride = $(event.relatedTarget).attr("data-bs-ride");
          var url = $(location).attr('origin') == 'http://localhost' ? 'http://localhost/rides/'+ride : 'https://cs4640.cs.virginia.edu/nid3dhu/project/rides/'+ride;
          // AJAX request
          $.getJSON(url, function(data) {
            // Update the modal's content.
            $("#posted-route").html(data.orig_addr + " &#8594; " + data.dest_addr);
            $("#posted-info").text(data.info);

            d = new Date(data.date+'T'+data.time);
            var month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            var date = month[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
            var time = d.toLocaleTimeString([], { hour: '2-digit', minute: "2-digit", hour12: true, timeZone: 'America/New_York' });
            $('#posted-date').text(date);
            $('#posted-time').text(time);

            initMap('posted', data);
          });
        });
      });
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
  <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIlNof-TwR5KfntgTBOWjxDcBV-mqNAnc&libraries=places"></script>
</body>

</html>
