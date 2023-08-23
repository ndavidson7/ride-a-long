<main class="container-fluid d-flex flex-column flex-md-row align-items-center mt-5">
  <div class="container-fluid d-flex flex-column align-items-center col-md-6">
    <form class="w-100" action="/report" method="post">
      <?php if (isset($error_msg)) { ?>
      <p class="alert alert-danger col-md-8"><?=$error_msg?></p>
      <?php } ?>
      <h3>Report user</h3>
      <div class="mb-3 col-md-8">
        <label for="reportee-email">User email</label>
        <input type="email" class="form-control form-control-lg" id="reportee-email" name="reportee-email" placeholder="computingID@virginia.edu" pattern="[A-Za-z0-9]+@virginia.edu" title="Valid UVA email (computingID@virginia.edu)" required>
      </div>
      <div class="mb-3 col-md-8">
        <label for="reason">Reason</label>
        <input type="text" class="form-control form-control-lg" id="reason" name="reason" placeholder="Inappropriate, spamming, etc." maxlength="63" required>
      </div>
      <div class="mb-3 col-md-8">
        <label for="info">Additional info</label>
        <textarea class="form-control form-control-lg" id="info" name="info" rows="3" maxlength="255"></textarea>
      </div>
      <button id="report" type="submit" class="btn btn-uva-ob">Send report</button>
    </form>
  </div>
</main>