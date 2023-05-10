<main class="container-fluid d-flex flex-column mt-3">
  <a href="/newride" class="btn btn-uva-ob fw-bold fs-5 mb-2" role="button" style="width:200px;">Post new ride</a>
  <?php if ($rides != null) { ?>
    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4 g-4">
    <?php foreach ($rides as $ride) { ?>
      <div class="col">
        <div class="card text-center h-100">
          <div class="card-body">
            <h5 class="card-title"><?=$ride["orig_addr"]?> &#8594; <?=$ride["dest_addr"]?></h5>
            <h6 class="card-subtitle mb-2"><?=date("n/j \@ g:i a", strtotime($ride["start_time"]))?></h6>
            <p class="card-text"><?=$ride["seats_open"]?> out of <?=$ride["seats_total"]?> seats left!</p>
            <button type="button" class="card-link btn btn-uva-ob stretched-link" data-bs-toggle="modal" data-bs-target="#infoModal" data-bs-ride="<?=$ride["id"]?>">More info</button>
          </div>
        </div>
      </div>
    <?php } ?>
    </div>

    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-fullscreen-md-down">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="infoModalLabel">Ride Info</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row mb-3">
                <div id="info-map" style="height:300px; width:100%;"></div>
              </div>
              <div class="row">
                <h3 class="route" id="info-route"></h3>
                <h4 id="info-distance"></h4>
                <h5 class="datetime"><span id="info-date"></span> @ <span id="info-time"></span></h6>
              </div>
              <div class="row">
                <p class="info"><span id="info-description"></span> - <span id="info-driver"></span></p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <a id="request" href="#" role="button" class="btn btn-primary">Request</a>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      $(document).ready(function() {
        $('#infoModal').on('show.bs.modal', function(event) {
          onShowModal(event, 'info', true);
        });
      });
    </script>

  <?php } else { ?>
    <div class="text-center">
      <h3>There are no upcoming rides :(</h3>
      <h4>Be the first to post one!</h4>
    </div>
  <?php } ?>

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
