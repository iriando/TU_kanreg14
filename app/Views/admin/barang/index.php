<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Barang</h3>
        <a href="<?= site_url('barang/create') ?>" class="btn btn-primary btn-sm float-right">Tambah Barang</a>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Total Unit</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barang as $row): ?>
                <tr>
                    <td><?= esc($row['id']) ?></td>
                    <td><?= esc($row['kode_barang']) ?></td>
                    <td><?= esc($row['nama_barang']) ?></td>
                    <td><?= esc($row['kategori']) ?></td>
                    <td><?= esc($row['total_unit']) ?></td>
                    <td><?= esc($row['keterangan']) ?></td>
                    <?php if (in_groups('admin')): ?>
                        <td>
                            <a href="<?= site_url('barang/edit/'.$row['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?= site_url('barang/delete/'.$row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            <a href="<?= site_url('barang/detail/'.$row['kode_barang']) ?>" class="btn btn-info btn-sm">detail</a>
                        </td>
                    <?php endif; ?>
                    
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
