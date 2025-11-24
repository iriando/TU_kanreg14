<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Edit Pemeliharaan BMN</h3>
    </div>

    <div class="card-body">
        <form action="<?= site_url('maintenance/update/' . $maintenance->id) ?>" method="post">
            <?= csrf_field() ?>

            <div class="row">
                <div class="col form-group">
                    <label for="nama_petugas">Petugas</label>
                    <input type="text" name="nama_petugas" class="form-control"
                        value="<?= $maintenance->nama_petugas ?>" required>
                </div>

                <div class="col form-group">
                    <label for="kode_barang">Barang</label>
                    <select name="kode_barang" id="kode_barang" class="form-control" required disabled>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach($barang as $b): ?>
                            <option value="<?= $b['kode_barang'] ?>"
                                <?= $b['kode_barang'] == $maintenance->kode_barang ? 'selected' : '' ?>>
                                <?= $b['nama_barang'] ?> (<?= $b['kode_barang'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col form-group">
                    <label for="barang_unit">Unit Barang</label>
                    <input type="text" class="form-control"
                        value="<?= $maintenance->unit ?> (<?= $maintenance->kode_unit ?>)"
                        disabled>
                </div>

                <div class="col form-group">
                    <label for="status_unit">Status Unit</label>
                    <select name="status_unit" id="status_unit" class="form-control" required>
                        <option value="Tersedia" <?= $maintenance->status == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                        <option value="Rusak" <?= $maintenance->status == 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-3 form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="datetime-local" 
                        name="tanggal" class="form-control"
                        value="<?= date('Y-m-d\TH:i', strtotime($maintenance->tanggal)) ?>" required>
                </div>

                <div class="col form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" class="form-control" required><?= $maintenance->keterangan ?></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-3 form-group">
                    <label for="pengingat">Pengingat</label>
                    <select name="pengingat" id="pengingat" class="form-control">
                        <option value="Tidak Aktif" <?= $maintenance->pengingat == 0 ? 'selected' : '' ?>>Tidak Aktif</option>
                        <option value="Aktif" <?= $maintenance->pengingat == 1 ? 'selected' : '' ?>>Aktif</option>
                    </select>
                </div>

                <div class="col-3 form-group" id="pengingat_hari_group"
                    style="<?= $maintenance->pengingat == 1 ? '' : 'display:none;' ?>">
                    <label for="hari">Diingatkan Dalam (hari)</label>
                    <input type="number" name="hari" id="hari" class="form-control"
                        value="<?= $maintenance->hari ?>" min="1">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?= site_url('maintenance') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    $('#pengingat').on('change', function() {
        if ($(this).val() === 'Aktif') {
            $('#pengingat_hari_group').show();
        } else {
            $('#pengingat_hari_group').hide();
            $("#hari").val('');
        }
    });
</script>
<?= $this->endSection() ?>
