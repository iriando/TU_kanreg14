<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Barang: <?= esc($barang['nama_barang']) ?> (<?= esc($barang['kode_barang']) ?>)</h3>
        <div class="card-tools">
            <a href="<?= site_url('barang') ?>" class="btn btn-secondary btn-sm">Kembali</a>
            <a href="<?= site_url('barang/create-unit/'.$barang['kode_barang']) ?>" class="btn btn-sm btn-primary">Tambah Unit</a>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Kode Barang</th>
                <td><?= esc($barang['kode_barang']) ?></td>
            </tr>
            <tr>
                <th>Nama Barang</th>
                <td><?= esc($barang['nama_barang']) ?></td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td><?= esc($barang['kategori']) ?></td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td><?= esc($barang['keterangan']) ?></td>
            </tr>
        </table>

        <h5 class="mt-4">Daftar Unit</h5>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Unit</th>
                    <th>Merk</th>
                    <th>Status</th>
                    <th>Kondisi</th>
                    <th>Tanggal Dibuat</th>
                    <th>Tanggal Update</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($units)): ?>
                    <?php foreach ($units as $i => $unit): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= esc($unit['kode_unit']) ?></td>
                            <td><?= esc($unit['merk']) ?></td>
                            <td><?= esc($unit['status']) ?></td>
                            <td><?= esc($unit['kondisi']) ?></td>
                            <td><?= esc($unit['created_at']) ?></td>
                            <td><?= esc($unit['updated_at']) ?></td>
                            <td>
                                <a href="<?= site_url('barang/view-unit/'.$unit['slug']) ?>" 
                                class="btn btn-info btn-sm">Lihat</a>
                                <a href="<?= site_url('barang/edit-unit/'.$unit['id']) ?>" 
                                class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= site_url('barang/delete-unit/'.$unit['id']) ?>" 
                                class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Belum ada unit</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
