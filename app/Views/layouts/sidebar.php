<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url('/') ?>" class="brand-link">
    <span class="brand-text font-weight-light">Asset BMN</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

        <!-- Barang Milik Negara -->
        <li class="nav-header">Barang Milik Negara</li>

        <li class="nav-item">
          <a href="<?= base_url('peminjaman') ?>" class="nav-link">
            <i class="nav-icon fas fa-handshake"></i>
            <p>Peminjaman</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('distribusiatk') ?>" class="nav-link">
            <i class="nav-icon fas fa-pencil-alt"></i>
            <p>ATK / ARTK</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('distribusiobat') ?>" class="nav-link">
            <i class="nav-icon fas fa-pills"></i>
            <p>Obat-obatan</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('maintenance') ?>" class="nav-link">
            <i class="nav-icon fas fa-tools"></i>
            <p>Maintenance BMN</p>
          </a>
        </li>

        <!-- Pengaturan -->
        <li class="nav-header">Pengaturan</li>

        <?php if (in_groups('admin')): ?>
        <li class="nav-item">
          <a href="<?= site_url('usermanager/users') ?>" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>User Manager</p>
          </a>
        </li>
        <?php endif; ?>

      </ul>
    </nav>
  </div>
</aside>
