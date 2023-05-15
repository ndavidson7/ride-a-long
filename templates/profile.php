<main class="container-fluid d-flex flex-column flex-md-row align-items-center mt-5">
  <!-- <div class="container-fluid d-flex flex-column align-items-center col-md-6 mb-5 mb-md-0">
    <p style="font-size:2em;font-weight:500;"><?=$user_info[0]["first_name"]?> <?=$user_info[0]["last_name"]?></p>
    <i class="fa-solid fa-user mb-2" style="font-size:300px;"></i>
    <label for="pfp" class="form-label">Upload profile picture</label>
    <input class="form-control w-75" type="file" id="pfp" name="pfp" accept="image/*">
  </div> -->
  <div class="container-fluid d-flex flex-column align-items-center col-md-6">
    <form class="w-100" action="/profile" method="post">
      <?php if (isset($error_msg)) { ?>
      <p class="alert alert-danger col-md-8"><?=$error_msg?></p>
      <?php } ?>
      <h3>Profile</h3>
      <div class="mb-3 col-md-8">
        <label class="form-label" for="year">Year</label>
        <!-- <select class="form-select" id="year" name="year">
          <option selected><?=$user_info[0]["year"]?></option>
          <option value="1">First</option>
          <option value="2">Second</option>
          <option value="3">Third</option>
          <option value="4">Fourth</option>
          <option value="5">Graduate/Further Studies</option>
        </select> -->
        <input type="number" class="form-control" id="year" name="year" value="<?=$user_info[0]["year"]?>" min="1">
      </div>
      <div class="mb-3 col-md-8">
        <label class="form-label" for="major">Major</label>
        <input type="text" class="form-control" id="major" name="major" maxlength="63" aria-describedby="majorLimit" value="<?=$user_info[0]["major"]?>">
        <div id="majorLimit" class="form-text">Max 63 characters.</div>
      </div>
      <div class="mb-3 col-md-8">
        <label class="form-label" for="bio">Bio</label>
        <textarea class="form-control" id="bio" name="bio" rows=3 maxlength="255" aria-describedby="bioLimit"><?=$user_info[0]["bio"]?></textarea>
        <div id="bioLimit" class="form-text">Max 255 characters.</div>
      </div>
      <h3>Emergency Contacts</h3>
      <div class="mb-3 col-md-8">
        <label class="form-label" for="contact-phone">Phone Number</label>
        <input type="tel" class="form-control" id="contact-phone" name="contact-phone" placeholder="1234567890 (No spaces, no dashes)" pattern="[0-9]{10}" value="<?=$contact_info[0]["phone"]?>">
      </div>
      <div class="mb-3 col-md-8">
        <label class="form-label" for="contact-first-name">First Name</label>
        <input type="text" class="form-control" id="contact-first-name" name="contact-first-name" maxlength="255" value="<?=$contact_info[0]["first_name"]?>">
      </div>
      <div class="mb-3 col-md-8">
        <label class="form-label" for="contact-last-name">Last Name</label>
        <input type="text" class="form-control" id="contact-last-name" name="contact-last-name" maxlength="255" value="<?=$contact_info[0]["last_name"]?>">
      </div>
      <div class="mb-3 col-md-8">
        <label class="form-label" for="contact-relationship">Relationship</label>
        <input type="text" class="form-control" id="contact-relationship" name="contact-relationship" maxlength="63" value="<?=$contact_info[0]["relationship"]?>">
      </div>
      <h3>Car Info</h3>
      <div class="mb-3 col-md-8">
        <label class="form-label" for="car-license-plate">License Plate</label>
        <input type="text" class="form-control" id="car-license-plate" name="car-license-plate" maxlength="7" aria-describedby="plateLimit" value="<?=$car_info[0]["license_plate"]?>">
        <div id="plateLimit" class="form-text">Max 7 characters.</div>
      </div>
      <div class="mb-3 col-md-8">
        <label class="form-label" for="car-make">Make</label>
        <input type="text" class="form-control" id="car-make" name="car-make" maxlength="63" aria-describedby="makeLimit" value="<?=$car_info[0]["make"]?>">
        <div id="makeLimit" class="form-text">Max 63 characters.</div>
      </div>
      <div class="mb-3 col-md-8">
        <label class="form-label" for="car-color">Color</label>
        <input type="text" class="form-control" id="car-color" name="car-color" maxlength="63" aria-describedby="colorLimit" value="<?=$car_info[0]["color"]?>">
        <div id="colorLimit" class="form-text">Max 63 characters.</div>
      </div>
      <h3>Rider Info</h3>
      <div class="mb-3 col-md-8">
        <label class="form-label" for="contributions">Contributions</label>
        <textarea class="form-control" id="contributions" name="contributions" rows=3 maxlength="255" aria-describedby="contributionsLimit" placeholder="Will pay for gas, good sense of humor, great music taste, humble, etc."><?=$rider_info[0]["contributions"]?></textarea>
        <div id="contributionsLimit" class="form-text">Max 255 characters.</div>
      </div>
      <button id="save" type="submit" class="btn btn-uva-ob" disabled>Save</button>
      <a href="/profile/delete" role="button" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account? This action is irreversible.');">Delete Account</a> 
    </form>
  </div>
</main>
