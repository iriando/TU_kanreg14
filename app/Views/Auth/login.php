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
</head>
<body class="hold-transition login-page">

<div class="login-box">
  <!-- Logo -->
  <div class="login-logo">
    <a href="#"><b>S</b>istem <b>I</b>nformasi <b>MA</b>najemen <b>P</b>ersedi<b>AN</b></a>
  </div>

  <!-- Card -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Login</p>

      <!-- Form Login -->
      <form action="<?= url_to('login') ?>" method="post">
        <?= csrf_field() ?>

        <!-- Email / Username -->
        <div class="input-group mb-3">
          <input type="text" name="login" class="form-control" placeholder="Email atau Username" value="<?= old('login') ?>" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-user"></span></div>
          </div>
        </div>

        <!-- Password -->
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <!-- Remember Me -->
        <!-- <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">Ingat Saya</label>
            </div>
          </div> -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Login</button>
          </div>
        </div>
      </form>

      <!-- Error Messages -->
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger mt-2">
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

      <!-- <p class="mb-1 mt-3">
        <a href="<//?= url_to('forgot') ?>">Lupa Password?</a>
      </p>
      <p class="mb-0">
        <a href="<//?= url_to('register') ?>" class="text-center">Daftar akun baru</a>
      </p> -->
    </div>
  </div>
</div>

<!-- AdminLTE JS -->
<script src="<?= base_url('adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('adminlte/dist/js/adminlte.min.js') ?>"></script>
</body>
</html>
