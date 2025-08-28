<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Unit Barang</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url('barang/update-unit/' . $unit['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Kode Unit</label>
                <input type="text" name="kode_unit" class="form-control" value="<?= $unit['kode_unit'] ?>" required>
            </div>
            <div class="form-group">
                <label>Merk</label>
                <input type="text" name="merk" class="form-control" value="<?= $unit['merk'] ?>">
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
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
