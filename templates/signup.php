<!DOCTYPE html>
<!--
Sources used: https://getbootstrap.com/docs/5.0/examples/sign-in/
-->

<html lang="en" class="h-100">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Nicholas Davidson">
  <meta name="description" content="Ride-A-Long: Find peers with whom to carpool long-distance - Sign up">
  <meta name="keywords" content="Ride-a-long, ridealong, ride, uva, carpool, hoosdriving, hoosriding, sign, up, signup, register">

  <title>Ride-A-Long - Sign up</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="/styles/largeheader.css">
  <link rel="stylesheet" type="text/css" href="/styles/signup.css">
</head>

<body class="d-flex flex-column align-items-center text-center h-100">
  <header class="container-fluid">
    <h1 class="fw-bold text-white">Ride-A-Long<sub class="orange">@UVA<sup><a class="disclaimer orange-darken-hover" href="#">*</a></sup></sub></h1>
  </header>

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
        <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Computing ID@virginia.edu" pattern="[A-Za-z0-9]+@virginia.edu" title="Valid UVA email (Computing ID@virginia.edu)" required>
        <label for="email">UVA email address</label>
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

  <footer class="container-fluid mt-auto text-muted">
    <p>© Nicholas Davidson | <a href="mailto:nid3dhu@virginia.edu">nid3dhu@virginia.edu</a> | *<a href="#">Disclaimer</a></p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
</body>

</html>
