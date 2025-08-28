<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Barang BMN</h3>
        <div class="card-tools">
            <a href="<?= site_url('peminjaman') ?>" class="btn btn-danger btn-sm">Kembali</a>
            <?php if (in_groups('admin')): ?>
            <a href="<?= site_url('barang/create') ?>" class="btn btn-primary btn-sm">Tambah Barang</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <?php if (in_groups('admin')): ?>
                    <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($barang as $b): ?>
                <tr>
                    <td><?= $b['id'] ?></td>
                    <td><?= $b['kode_barang'] ?></td>
                    <td><?= $b['nama_barang'] ?></td>
                    <td><?= $b['kategori'] ?></td>
                    <td><?= $b['jumlah'] ?></td>
                    <td><?= $b['keterangan'] ?></td>
                    <?php if (in_groups('admin')): ?>
                        <td>
                            <a href="<?= site_url('barang/edit/'.$b['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?= site_url('barang/delete/'.$b['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                        <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
