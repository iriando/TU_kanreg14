<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Distribusi ATK/ARTK</h3>
        <div class="card-tools">
            <a href="<?= site_url('atk') ?>" class="btn btn-secondary btn-sm">List ATK/ARTK</a>
            <a href="<?= site_url('distribusiatk/create') ?>" class="btn btn-primary btn-sm">+ Distribusi</a>
        </div>
    </div>
    <div class="card-body table-responsive">
        <?php if(session()->getFlashdata('message')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
        <?php endif; ?>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Penerima</th>
                    <th>Nama Barang</th>
                    <th>Kode Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Distribusi</th>
                    <th>Petugas</th>
                    <?php if (in_groups('admin')): ?>
                    <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($distribusi as $d): ?>
                    <tr>
                        <td><?= $d->id ?></td>
                        <td><?= $d->nama_penerima ?></td>
                        <td><?= $d->nama_barang ?></td>
                        <td><?= $d->kode_barang ?></td>
                        <td><?= $d->jumlah ?></td>
                        <td><?= $d->tanggal_distribusi ?></td>
                        <td><?= $d->petugas ?></td>
                        <?php if (in_groups('admin')): ?>
                        <td>
                            <a href="<?= site_url('distribusiatk/edit/'.$d->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?= site_url('distribusiatk/delete/'.$d->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
