<!DOCTYPE html>
<!--
Sources used: https://fontawesome.com/, https://getbootstrap.com/docs/5.1/forms/
-->

<html lang="en" class="h-100">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Nicholas Davidson">
  <meta name="description" content="Ride-A-Long: Find peers with whom to carpool long-distance - Profile">
  <meta name="keywords" content="Ride-a-long, ridealong, ride, uva, carpool, hoosdriving, hoosriding, profile, account">

  <title>Ride-A-Long - My profile</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="/styles/main.css">

  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script src="/scripts/notifications.js" charset="utf-8" defer></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('form').on('input change', () => $('#save').attr('disabled', false));
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

  <main class="container-fluid d-flex flex-column flex-md-row align-items-center mt-5">
    <!-- <div class="container-fluid d-flex flex-column align-items-center col-md-6 mb-5 mb-md-0">
      <p style="font-size:2em;font-weight:500;"><?=$user_info[0]["first_name"]?> <?=$user_info[0]["last_name"]?></p>
      <i class="fa-solid fa-user mb-2" style="font-size:300px;"></i>
      <label for="pfp" class="form-label">Upload profile picture</label>
      <input class="form-control w-75" type="file" id="pfp" name="pfp" accept="image/*">
    </div> -->
    <div class="container-fluid d-flex flex-column align-items-center col-md-6">
      <form class="w-100" action="/profile" method="post">
        <div class="mb-3 col-md-8">
          <label class="form-label" for="year">Year</label>
          <select class="form-select" id="year" name="year">
            <option selected><?=$user_info[0]["year"]?></option>
            <option value="First">First</option>
            <option value="Second">Second</option>
            <option value="Third">Third</option>
            <option value="Fourth">Fourth</option>
            <option value="Graduate/Further Studies">Graduate/Further Studies</option>
          </select>
        </div>
        <div class="mb-3 col-md-8">
          <label class="form-label" for="studying">Studying</label>
          <input type="text" class="form-control" id="studying" name="studying" maxlength="50" aria-describedby="studyingLimit" value="<?=$user_info[0]["studying"]?>">
          <div id="studyingLimit" class="form-text">Max 50 characters.</div>
        </div>
        <div class="mb-3 col-md-8">
          <label class="form-label" for="bio">Bio</label>
          <textarea class="form-control" id="bio" name="bio" rows=3 maxlength="150" aria-describedby="bioLimit"><?=$user_info[0]["bio"]?></textarea>
          <div id="bioLimit" class="form-text">Max 150 characters.</div>
        </div>
        <button id="save" type="submit" class="btn btn-uva-ob" disabled>Save</button>
      </form>
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
</body>

</html>
