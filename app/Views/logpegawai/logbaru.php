<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lembar Kerja Log Pegawai - <?= date('d-m-Y', strtotime($tanggal)) ?></h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
        <thead>
            <tr>
            <th>#</th>
            <th>Nama Pegawai</th>
            <th>Jam Datang</th>
            <th>Jam Keluar</th>
            <th>Jam Kembali</th>
            <th>Jam Pulang</th>
            <th>Status</th>
            <th>Keterangan</th>
            <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $i => $log): ?>
            <tr>
                <form action="<?= base_url('logpegawai/update/'.$log['id']) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="tanggal" value="<?= $tanggal ?>">
                <td><?= $i+1 ?></td>
                <td><?= $log['nama'] ?></td>
                <td>
                    <input type="time" name="waktu_masuk" value="<?= $log['waktu_masuk'] ? date('H:i', strtotime($log['waktu_masuk'])) : '' ?>" class="form-control">
                </td>
                <td>
                    <input type="time" name="waktu_keluar" 
                        value="<?= $log['waktu_keluar'] ? date('H:i', strtotime($log['waktu_keluar'])) : '' ?>" 
                        class="form-control">
                </td>
                <td>
                <input type="time" name="waktu_kembali" 
                        value="<?= $log['waktu_kembali'] ? date('H:i', strtotime($log['waktu_kembali'])) : '' ?>" 
                        class="form-control">
                </td>
                <td>
                <input type="time" name="waktu_pulang" 
                        value="<?= $log['waktu_pulang'] ? date('H:i', strtotime($log['waktu_pulang'])) : '' ?>" 
                        class="form-control">
                </td>
                <td><input type="text" name="status" value="<?= $log['status'] ?>" class="form-control"></td>
                <td><input type="text" name="keterangan" value="<?= $log['keterangan'] ?>" class="form-control"></td>
                <td><button type="submit" class="btn btn-sm btn-success">Update</button></td>
                </form>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
    </div>
    <div class="card-footer">
        <?php if (in_groups('admin')): ?>
        <a href="<?= base_url('logpegawai/batal/'.$tanggal) ?>" class="btn btn-danger" onclick="return confirm('Yakin batal membuat log untuk tanggal ini?')">Batal</a>
        <?php endif ?>
        <!-- Tombol Selesai -->
        <a href="<?= base_url('logpegawai') ?>" class="btn btn-success">Selesai</a>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<?= $this->endSection() ?>



