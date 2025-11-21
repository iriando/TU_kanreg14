<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div id="print-area">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="card-title">Rekam Medis Pasien</h4>
        </div>

        <div class="card-body">

            <!-- IDENTITAS PASIEN -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr>
                            <th>Nama</th>
                            <td><?= $pegawai->nama ?></td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td><?= $pegawai->gender ?></td>
                        </tr>
                        <tr>
                            <th>Usia</th>
                            <td><?= $pegawai->usia ?> Tahun</td>
                        </tr>
                        <tr>
                            <th>Unit Kerja</th>
                            <td><?= $pegawai->unit ?></td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6 text-right">
                    <a href="<?= site_url('rekammedis/create/' . $pegawai->id) ?>" 
                    class="btn btn-success">
                        <i class="fa fa-plus"></i> Tambah Rekam Medis
                    </a>
                    <button class="btn btn-primary" onclick="printRekamMedis()">
                        <i class="fa fa-print"></i> Cetak
                    </button>
                    <a href="<?= site_url('rekammedis') ?>" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <hr>

            <!-- RIWAYAT REKAM MEDIS -->
            <h5>Riwayat Rekam Medis</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="table-riwayat">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Petugas</th>
                            <th>Keluhan</th>
                            <th>Keterangan</th>
                            <th>Obat Diberikan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1 ?>
                        <?php foreach ($rekam as $r): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= date('d-m-Y', strtotime($r->tanggal)) ?></td>
                                <td><?= $r->petugas ?></td>
                                <td><?= $r->keluhan ?></td>
                                <td><?= $r->keterangan ?></td>
                                <td>
                                    <?php if (!empty($logDistribusi[$r->id])): ?>
                                        <ul class="mb-0">
                                            <?php foreach ($logDistribusi[$r->id] as $o): ?>
                                                <li>
                                                    <?= $o->nama_barang ?> 
                                                    <strong>(<?= $o->jumlah ?>)</strong>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <span class="text-muted">Tidak ada obat</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js"></script>

<script>
$(document).ready(function() {
    $('#table-riwayat').DataTable({
        responsive: true,
        ordering: true,
        autoWidth: false,
        lengthChange: false
    });
});

function printRekamMedis() {
    $("#print-area").printThis({
        importCSS: true,          // ikutkan semua CSS
        importStyle: true,        // ikutkan style <style> dari halaman
        loadCSS: "<?= base_url('assets/css/adminlte.min.css') ?>", // pastikan layout ikut
        printContainer: true,     // cetak elemen wrappernya juga
        pageTitle: "Rekam Medis Pasien", // judul di tab print
    });
}
</script>
<?= $this->endSection() ?>
