<main class="d-flex flex-column align-items-center">
  <form class="mb-2 w-100" action="/signin" method="post">
    <h3 class="mb-3 fs-1 fw-normal text-white">Sign In</h3>

    <?php if (isset($error_msg)) { ?>
      <p class="alert alert-danger"><?=$error_msg?></p>
    <?php } ?>
    <div class="form-floating mb-1">
      <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="computingID@virginia.edu" pattern="[A-Za-z0-9]+@virginia.edu" title="Valid UVA email (computingID@virginia.edu)" required>
      <label for="email">UVA email address</label>
    </div>
    <div class="form-floating mb-2">
      <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" autocomplete="current-password" required>
      <label for="password">Password</label>
    </div>
    <button class="w-100 btn btn-uva-ow" type="submit">Sign in</button>
  </form>
  <p class="text-white">New user? <a class="orange orange-darken-hover" href="/signup">Sign up</a> here!</p>
</main>
