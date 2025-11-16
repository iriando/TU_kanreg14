<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - BMN Apps</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminlte/dist/css/adminlte.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('animation/style-login.css') ?>">
</head>
<body class="hold-transition login-page">

  <div class="login-box">
    <!-- Logo -->
    <div class="login-logo">
      <a href="#"><b>SiMAPAN</b></a>
    </div>
  </div>

    <form action="<?= url_to('login') ?>" method="post">
        <?= csrf_field() ?>

      <div class="box">
        <div class="login">
          <div class="loginBx">
            <h2>
              <i class="fa-solid fa-right-to-bracket"></i>
              Login
              <!-- <i class="fa-solid fa-heart"></i> -->
            </h2>
            <input type="text" name="login" placeholder="Email" value="<?= old('login') ?>">
            <input type="password" name="password" placeholder="Password">
            <input type="submit" value="Masuk" />
            <!-- <div class="group">
              <a href="#">Forgot Password</a>
              <a href="#">Sign up</a>
            </div> -->
          </div>
        </div>
      </div>
    </form>
  <!-- </div> -->
  
      <!-- Error Messages -->
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mt-2">
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

<!-- AdminLTE JS -->
<script src="<?= base_url('adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('adminlte/dist/js/adminlte.min.js') ?>"></script>
</body>
</html>
