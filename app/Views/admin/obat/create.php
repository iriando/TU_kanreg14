<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach(session()->getFlashdata('errors') as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form action="<?= site_url('obat/store') ?>" method="post">
    <?= csrf_field() ?>

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Obat</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col">
                    <label for="kode_barang">Kode Barang</label>
                    <input type="text" name="kode_barang" class="form-control" value="<?= old('kode_barang') ?>" required>
                </div>
                <div class="form-group col">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" value="<?= old('nama_barang') ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group col">
                    <label for="satuan">Satuan</label>
                    <input type="text" name="satuan" class="form-control" value="<?= old('satuan') ?>" required>
                </div>
                <div class="form-group col-2">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="jumlah" class="form-control" value="<?= old('jumlah') ?>" required>
                </div>
                <div class="form-group col">
                    <label for="kedaluwarsa">Tanggal kedaluwarsa</label>
                    <input type="date" name="kedaluwarsa" class="form-control" value="<?= old('kedaluwarsa') ?>" required>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= site_url('obat') ?>" class="btn btn-secondary">Batal</a>
            
        </div>
    </div>
</form>

<?= $this->endSection() ?>
