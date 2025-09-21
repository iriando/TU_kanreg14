<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Pegawai</h3>
    </div>
    <div class="card-body">
        <form action="<?= site_url('pegawai/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" class="form-control" value="<?= old('nama') ?>" required>
            </div>
            <div class="form-group">
                <label for="unit">Unit/Pokja</label>
                <input type="text" name="unit" class="form-control" value="<?= old('unit') ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= site_url('pegawai') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
