<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2>Tambah ATK</h2>

<?php if (session()->getFlashdata('errors')) : ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session('errors') as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<form action="<?= base_url('/atk/store') ?>" method="post">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="kode_barang" class="form-label">Kode Barang</label>
        <input type="text" name="kode_barang" id="kode_barang"value="<?= old('kode_barang') ?>" class="form-control <?= (session('errors.kode_barang') ? 'is-invalid' : '') ?>">
        <div class="invalid-feedback">
            <?= session('errors.kode_barang') ?>
        </div>
    </div>

    <div class="mb-3">
        <label for="nama_barang" class="form-label">Nama Barang</label>
        <input type="text" name="nama_barang" id="nama_barang"
               value="<?= old('nama_barang') ?>"
               class="form-control <?= (session('errors.nama_barang') ? 'is-invalid' : '') ?>">
        <div class="invalid-feedback">
            <?= session('errors.nama_barang') ?>
        </div>
    </div>

    <div class="mb-3">
        <label for="satuan" class="form-label">Satuan</label>
        <input type="text" name="satuan" id="satuan"
               value="<?= old('satuan') ?>"
               class="form-control <?= (session('errors.satuan') ? 'is-invalid' : '') ?>">
        <div class="invalid-feedback">
            <?= session('errors.satuan') ?>
        </div>
    </div>

    <div class="mb-3">
        <label for="jumlah" class="form-label">Jumlah</label>
        <input type="number" name="jumlah" id="jumlah"
               value="<?= old('jumlah') ?>"
               class="form-control <?= (session('errors.jumlah') ? 'is-invalid' : '') ?>">
        <div class="invalid-feedback">
            <?= session('errors.jumlah') ?>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="<?= base_url('/atk') ?>" class="btn btn-secondary">Batal</a>
</form>

<?= $this->endSection() ?>
