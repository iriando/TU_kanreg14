<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Barang BMN</h3>
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

        <form action="<?= site_url('barang/update/'.$barang['id']) ?>" method="post">
            <div class="form-group">
                <label for="kode_barang">Kode Barang</label>
                <input type="text" name="kode_barang" class="form-control" value="<?= old('kode_barang', $barang['kode_barang']) ?>" required>
            </div>
            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" value="<?= old('nama_barang', $barang['nama_barang']) ?>" required>
            </div>
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <input type="text" name="kategori" class="form-control" value="<?= old('kategori', $barang['kategori']) ?>" required>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" class="form-control" value="<?= old('jumlah', $barang['jumlah']) ?>" required>
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" class="form-control"><?= old('keterangan', $barang['keterangan']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?= site_url('barang') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
