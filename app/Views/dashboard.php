<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
  <h1>Dashboard</h1>
</div>

<div class="callout callout-info">
  <h5>Selamat Datang!</h5>
  <p>di aplikasi Sistem Informasi Manajemen Perkantoran.</p>
</div>

        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?= $totalDipinjam ?></h3>

                <p>Unit Dipinjam</p>
              </div>
              <div class="icon">
                <i class="ion ion-arrow-swap"></i>
              </div>
              <a href="<?= site_url('peminjaman') ?>" class="small-box-footer">Info lebih lanjut <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?= count($notifikasi) ?></h3>

                <p>Notif Maintenance</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-bell"></i>
              </div>
              <a href="<?= site_url('maintenance') ?>" class="small-box-footer">Info lebih lanjut <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
        </div>
        <!-- /.row -->

<?= $this->endSection() ?>
