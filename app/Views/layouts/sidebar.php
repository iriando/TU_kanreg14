<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url('/') ?>" class="brand-link">
    <span class="brand-text font-weight-light">SIMAPAN</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

        <!-- Layanan -->
         <?php if (in_groups(['admin', 'petugas BMN'])): ?>
        <li class="nav-header">Layanan</li>

        <li class="nav-item">
          <a href="<?= base_url('peminjaman') ?>" class="nav-link">
            <i class="nav-icon fas fa-handshake"></i>
            <p>Peminjaman</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('distribusiatk') ?>" class="nav-link">
            <i class="nav-icon fas fa-parachute-box"></i>
            <p>Distribusi ATK / ARTK</p>
          </a>
        </li>
        <?php endif; ?>

        <?php if (in_groups(['admin', 'petugas BMN', 'petugas klinik' ])): ?>
        <li class="nav-item">
          <a href="<?= base_url('distribusiobat') ?>" class="nav-link">
            <i class="nav-icon fas fa-prescription-bottle"></i>
            <p>Distribusi Obat-obatan</p>
          </a>
        </li>
        <?php endif; ?>

        <?php if (in_groups(['admin', 'petugas BMN'])): ?>
        <li class="nav-item">
          <a href="<?= base_url('maintenance') ?>" class="nav-link">
            <i class="nav-icon fas fa-tools"></i>
            <p>Pemeliharaan</p>
          </a>
        </li>
        
        
        <li class="nav-item">
          <a href="<?= base_url('logpegawai') ?>" class="nav-link">
            <i class="nav-icon fas fa-running"></i>
            <p>Log Pegawai</p>
            <span class="badge badge-danger navbar-badge">Beta</span>
          </a>
        </li>

        <li class="nav-header">Klinik</li>
          <li class="nav-item">
            <a href="<?= base_url('rekammedis') ?>" class="nav-link">
              <i class="nav-icon fas fa-book-medical"></i>
              <p>Rekam Medis</p>
            </a>
          </li>
        <?php endif ?>

        <!-- Asset -->
        <?php if (in_groups(['admin', 'petugas BMN'])): ?>
        <li class="nav-header">Aset</li>

        <li class="nav-item">
          <a href="<?= base_url('pegawai') ?>" class="nav-link">
            <i class="nav-icon fas fa-child"></i>
            <p>Pegawai</p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="<?= base_url('barang') ?>" class="nav-link">
            <i class="nav-icon fas fa-boxes"></i>
            <p>Barang Milik Negara</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('atk') ?>" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>ATK / ARTK</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('obat') ?>" class="nav-link">
            <i class="nav-icon fas fa-pills"></i>
            <p>Obat</p>
          </a>
        </li>
        <?php endif; ?>

        <!-- Pengaturan -->
        <?php if (in_groups('admin')): ?>
        <li class="nav-header">Pengaturan</li>

        <li class="nav-item">
          <a href="<?= site_url('usermanager/users') ?>" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>User Manager</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= site_url('template') ?>" class="nav-link">
            <i class="nav-icon fas fa-file"></i>
            <p>Template Manager</p>
          </a>
        </li>
        <?php endif; ?>

      </ul>
    </nav>
  </div>
</aside>
