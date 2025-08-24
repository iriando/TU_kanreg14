<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Distribusi Obat</h3>
        <div class="card-tools">
            <a href="<?= site_url('obat') ?>" class="btn btn-secondary btn-sm">List Obat-obatan</a>
            <a href="<?= site_url('distribusiobat/create') ?>" class="btn btn-primary btn-sm">+ Distribusi</a>
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
                <th>Nama Obat</th>
                <th>Kode Barang</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Tanggal Distribusi</th>
                <th>Petugas</th>
                <th>Keterangan</th>
                <?php if (in_groups('admin')): ?>
                <th>Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($distribusi as $d): ?>
                <tr>
                    <td><?= $d->id ?></td>
                    <td><?= esc($d->nama_penerima) ?></td>
                    <td><?= esc($d->obat_nama ?? $d->nama_barang) ?></td>
                    <td><?= esc($d->kode_barang) ?></td>
                    <td><?= esc($d->jumlah) ?></td>
                    <td><?= esc($d->satuan ?? '-') ?></td>
                    <td><?= esc($d->tanggal_distribusi) ?></td>
                    <td><?= $d->petugas ?></td>
                    <td><?= esc($d->keterangan ?? '-') ?></td>
                    <?php if (in_groups('admin')): ?>
                    <td>
                    <a href="<?= site_url('distribusiobat/edit/'.$d->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="<?= site_url('distribusiobat/delete/'.$d->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<?= $this->endSection() ?>
