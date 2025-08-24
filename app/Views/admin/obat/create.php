<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Obat</h3>
    </div>
    <div class="card-body">
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
            <div class="form-group">
                <label for="kode_barang">Kode Barang</label>
                <input type="text" name="kode_barang" class="form-control" value="<?= old('kode_barang') ?>" required>
            </div>
            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" value="<?= old('nama_barang') ?>" required>
            </div>
            <div class="form-group">
                <label for="satuan">Satuan</label>
                <input type="text" name="satuan" class="form-control" value="<?= old('satuan') ?>" required>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" class="form-control" value="<?= old('jumlah') ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= site_url('obat') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
