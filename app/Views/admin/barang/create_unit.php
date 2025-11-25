<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Unit Barang</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url('barang/store-unit/' . $kode_barang) ?>" method="post" enctype="multipart/form-data" >
            <?= csrf_field() ?> <!-- penting kalau CSRF aktif -->

            <div class="form-group">
                <label>Kode Unit</label>
                <input type="text" name="kode_unit" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Merk</label>
                <input type="text" name="merk" class="form-control">
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="tersedia">Tersedia</option>
                    <option value="dipinjam">Dipinjam</option>
                    <option value="rusak">Rusak</option>
                </select>
            </div>

            <div class="form-group">
                <label>Kondisi</label>
                <select name="kondisi" class="form-control">
                    <option value="baik">Baik</option>
                    <option value="kurang baik">Kurang Baik</option>
                    <option value="rusak">Rusak</option>
                </select>
            </div>

            <div class="form-group">
                <label>Gambar</label>
                <input type="file" id="gambar" name="gambar" class="form-control">
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
