<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar ATK</h3>
        <div class="card-tools">
            <?php if (in_groups('admin')): ?>
            <a href="<?= site_url('atk/create') ?>" class="btn btn-primary btn-sm">Tambah ATK</a>
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
                    <th>Satuan</th>
                    <th>Jumlah</th>
                    <th>Didistribusi</th>
                    <th>Sisa</th>
                    <?php if (in_groups('admin')): ?>
                    <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($atk as $a): ?>
                    <tr>
                        <td><?= $a->id ?></td>
                        <td><?= $a->kode_barang ?></td>
                        <td><?= $a->nama_barang ?></td>
                        <td><?= $a->satuan ?></td>
                        <td><?= $a->jumlah ?></td>
                        <td><?= $a->didistribusi ?></td>
                        <td><?= $a->sisa ?></td>
                        <?php if (in_groups('admin')): ?>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?= site_url('atk/edit/'.$a->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= site_url('atk/delete/'.$a->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
