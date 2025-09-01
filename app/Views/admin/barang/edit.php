<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container">
    <h3>Edit Barang</h3>

    <form action="<?= site_url('barang/update/'.$barang['id']) ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="kode_barang">Kode Barang</label>
            <input type="text" name="kode_barang" id="kode_barang" class="form-control"
                   value="<?= esc($barang['kode_barang']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control"
                   value="<?= esc($barang['nama_barang']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="kategori">Kategori</label>
            <input type="text" name="kategori" id="kategori" class="form-control"
                   value="<?= esc($barang['kategori']) ?>">
        </div>

        <div class="mb-3">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control"><?= esc($barang['keterangan']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="<?= site_url('barang') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?= $this->endSection() ?>
