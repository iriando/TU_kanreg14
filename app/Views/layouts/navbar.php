<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="<?= base_url('/') ?>" class="nav-link">Home</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- User Profile Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-user-circle"></i>
        <span class="d-none d-md-inline"><?= user()->username ?? 'Guest' ?></span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header">
          <?= user()->username ?? 'Guest' ?><br>
          <small><?= user()->email ?? '' ?></small>
        </span>
        <div class="dropdown-divider"></div>
        <a href="<?= site_url('profile') ?>" class="dropdown-item">
          <i class="fas fa-id-card mr-2"></i> Profil Saya
        </a>
        <div class="dropdown-divider"></div>
        <a href="<?= site_url('logout') ?>" class="dropdown-item text-danger">
          <i class="fas fa-sign-out-alt mr-2"></i> Keluar
        </a>
      </div>
    </li>
  </ul>
</nav>
