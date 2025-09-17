<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
  <h1>Dashboard SIMAPAN</h1>
  <h4>Selamat datang di aplikasi Sistem Informasi Manajemen Asset & Persediaan.</h4>
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
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>0</h3>

                <p>Pemeliharaan</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-bell"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
        </div>
        <!-- /.row -->

<?= $this->endSection() ?>
