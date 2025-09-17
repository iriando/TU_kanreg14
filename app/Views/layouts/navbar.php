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
    <!-- Reminder Notifications -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-danger navbar-badge">
              <?= isset($notifikasi) && is_array($notifikasi) ? count($notifikasi) : 0 ?>
          </span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <?php if (!empty($notifikasi)): ?>
              <?php foreach ($notifikasi as $item): ?>
                  <a href="maintenance" class="dropdown-item"><?= $item->keterangan ?> (<?= $item->unit ?>)  
                      <small class="text-danger"> <?= date('d-m-Y', strtotime($item->tanggal_pengingat)) ?></small>
                  </a>
              <?php endforeach; ?>
          <?php else: ?>
              <span class="dropdown-item">Tidak ada notifikasi</span>
          <?php endif; ?>
      </div>
  </li>


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
