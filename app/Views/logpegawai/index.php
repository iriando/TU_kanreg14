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
              <i class="fas fa-plus"></i> Buat Log
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
              <th>No. </th>
              <th>Pegawai</th>
              <th>Tanggal</th>
              <th>Masuk</th>
              <th>Keluar</th>
              <th>Kembali</th>
              <th>Pulang</th>
              <th>Status</th>
              <th>Keterangan</th>
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
          autoWidth: false,
          dom: 'Bfrtip', // butuh ini untuk tombol export
          buttons: [
              {
                  extend: 'excel',
                  text: '<i class="fas fa-file-excel"></i> Excel',
                  className: 'btn btn-success btn-sm',
                  exportOptions: {
                      columns: ':visible',
                      format: {
                          body: function (data, row, column, node) {
                              // Kolom No ada di index 0
                              if (column === 0) {
                                  return row + 1; // isi manual nomor urut
                              }
                              return data;
                          }
                      }
                  }
              },
              {
                  extend: 'pdf',
                  text: '<i class="fas fa-file-pdf"></i> PDF',
                  className: 'btn btn-danger btn-sm',
                  exportOptions: {
                      columns: ':visible',
                      format: {
                          body: function (data, row, column, node) {
                              if (column === 0) {
                                  return row + 1;
                              }
                              return data;
                          }
                      }
                  }
              },
              {
                  extend: 'print',
                  text: '<i class="fas fa-print"></i> Print',
                  className: 'btn btn-info btn-sm',
                  exportOptions: {
                      columns: ':visible',
                      format: {
                          body: function (data, row, column, node) {
                              if (column === 0) {
                                  return row + 1;
                              }
                              return data;
                          }
                      }
                  }
              }
          ],
          columnDefs: [
              { orderable: false, targets: 0 } // kolom No tidak ikut sorting
          ],
          order: [[2, 'asc']], // default urut berdasarkan Tanggal
          drawCallback: function(settings) {
              var api = this.api();
              api.column(0, {search:'applied', order:'applied'}).nodes().each(function(cell, i) {
                  cell.innerHTML = i + 1; // autonumber tiap draw
              });
          }
      });

      // Filter Pegawai
      $('#filterPegawai').on('change', function() {
          table.column(1).search(this.value).draw();
      });

      // Filter Tanggal
      $('#filterTanggal').on('change', function() {
          table.column(2).search(this.value).draw();
      });
  });

</script>
<?= $this->endSection() ?>
