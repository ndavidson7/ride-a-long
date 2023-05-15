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