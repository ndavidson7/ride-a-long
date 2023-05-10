<main class="d-flex flex-column align-items-center">
  <form class="mb-2 w-100" action="/signup" method="post">
    <h3 class="mb-3 fs-1 fw-normal text-white">Sign Up</h3>

    <?php if (isset($error_msg)) { ?>
      <p class="alert alert-danger"><?=$error_msg?></p>
    <?php } ?>
    <div class="form-floating mb-1">
      <input type="text" class="form-control form-control-lg" id="first_name" name="first_name" placeholder="First name" required>
      <label for="first_name">First name</label>
    </div>
    <div class="form-floating mb-1">
      <input type="text" class="form-control form-control-lg" id="last_name" name="last_name" placeholder="Last name" required>
      <label for="last_name">Last name</label>
    </div>
    <div class="form-floating mb-1">
      <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="computingID@virginia.edu" pattern="[A-Za-z0-9]+@virginia.edu" title="Valid UVA email (computingID@virginia.edu)" required>
      <label for="email">UVA email address</label>
    </div>
    <div class="form-floating mb-1">
      <input type="tel" class="form-control form-control-lg" id="phone" name="phone" placeholder="1234567890 (No spaces, no dashes)" pattern="[0-9]{10}" required>
      <label for="tel">Personal phone number</label>
    </div>
    <div class="form-floating mb-1">
      <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" autocomplete="new-password" required>
      <label for="password">Password</label>
    </div>
    <div class="form-floating mb-2">
      <input type="password" class="form-control form-control-lg" id="passwordConfirm" name="password2" placeholder="Password" autocomplete="new-password" required>
      <label for="passwordConfirm">Repeat Password</label>
    </div>
    <button class="w-100 btn btn-uva-ow" type="submit">Sign up</button>
  </form>
  <p class="text-white">Already have an account? <a class="orange orange-darken-hover" href="/signin">Sign in</a> here!</p>
</main>
