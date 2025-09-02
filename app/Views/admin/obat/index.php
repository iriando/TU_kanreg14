<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Obat</h3>
        <div class="card-tools">
            <a href="<?= site_url('distribusiobat') ?>" class="btn btn-danger btn-sm">Kembali</a>
            <?php if (in_groups('admin')): ?>
            <a href="<?= site_url('obat/create') ?>" class="btn btn-primary btn-sm">+Tambah Obat</a>
            <?php endif; ?>
        </div>
    </div>
    <?php if(session()->getFlashdata('message')): ?>
    <div class="card-body table-responsive">
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?>
    </div>
    <?php endif; ?>

    <div class="card-body table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                    <th>Didistribusi</th>
                    <th>Sisa</th>
                    <th>Tanggal kedaluwarsa</th>
                    <?php if (in_groups('admin')): ?>
                    <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($obat as $o): ?>
                <tr>
                    <td><?= $o->id ?></td>
                    <td><?= $o->kode_barang ?></td>
                    <td><?= $o->nama_barang ?></td>
                    <td><?= $o->satuan ?></td>
                    <td><?= $o->jumlah ?></td>
                    <td><?= $o->didistribusi ?></td>
                    <td><?= $o->sisa ?></td>
                    <td><?= $o->kedaluwarsa ?></td>
                    <?php if (in_groups('admin')): ?>
                    <td>
                        <a href="<?= site_url('obat/edit/'.$o->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?= site_url('obat/delete/'.$o->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
