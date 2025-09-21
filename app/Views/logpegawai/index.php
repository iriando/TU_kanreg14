<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="content-header">
  <div class="container-fluid">
    <h1>Log Kehadiran Pegawai</h1>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Log Keluar/Masuk Pegawai</h3>
        <div class="card-tools">
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTanggal">
              <i class="fas fa-plus"></i> Buat Log Baru
            </button>
        </div>
      </div>
      <div class="card-body">
        <!-- Filter -->
        <div class="row mb-3">
          <div class="col-md-4">
            <label>Pegawai</label>
            <select id="filterPegawai" class="form-control">
              <option value="">-- Semua Pegawai --</option>
              <?php foreach ($pegawai as $p): ?>
                <option value="<?= $p->nama ?>"><?= $p->nama ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label>Tanggal</label>
            <input type="date" id="filterTanggal" class="form-control">
          </div>
        </div>

        <!-- Tabel -->
        <table id="logTable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Pegawai</th>
              <th>Tanggal</th>
              <th>Masuk</th>
              <th>Keluar</th>
              <th>Kembali</th>
              <th>Pulang</th>
              <th>Status</th>
              <th>Keterangan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($logs as $i => $log): ?>
              <tr>
                <td><?= $i+1 ?></td>
                <td><?= $log['nama'] ?></td>
                <td><?= $log['tanggal'] ?></td>
                <td><?= $log['waktu_masuk'] ? date('H:i', strtotime($log['waktu_masuk'])) : '-' ?></td>
                <td><?= $log['waktu_keluar'] ? date('H:i', strtotime($log['waktu_keluar'])) : '-' ?></td>
                <td><?= $log['waktu_kembali'] ? date('H:i', strtotime($log['waktu_kembali'])) : '-' ?></td>
                <td><?= $log['waktu_pulang'] ? date('H:i', strtotime($log['waktu_pulang'])) : '-' ?></td>
                <td><?= $log['status'] ?></td>
                <td><?= $log['keterangan'] ?></td>
                <td>
                  <a href="<?= base_url('log_pegawai/edit/'.$log['id']) ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                  <a href="<?= base_url('log_pegawai/delete/'.$log['id']) ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal-->
  <div class="modal fade" id="modalTanggal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="<?= base_url('logpegawai/logbaru') ?>" method="get">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Pilih Tanggal</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Tanggal</label>
              <input type="date" name="tanggal" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Lanjutkan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  $(document).ready(function() {
      var table = $('#logTable').DataTable({
          responsive: true,
          autoWidth: false
      });

      // Filter Pegawai
      $('#filterPegawai').on('change', function() {
          table.column(1).search(this.value).draw(); // kolom ke-1 = Pegawai
      });

      // Filter Tanggal
      $('#filterTanggal').on('change', function() {
          table.column(2).search(this.value).draw(); // kolom ke-2 = Tanggal
      });
  });
</script>
<?= $this->endSection() ?>
