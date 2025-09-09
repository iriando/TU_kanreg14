<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Peminjaman BMN</h3>
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

        <form action="<?= site_url('peminjaman/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <div class="form-group">
                    <label for="nama_peminjam">Peminjam</label>
                    <input type="text" name="nama_peminjam" class="form-control" value="<?= old('nama_peminjam') ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="kode_barang">Barang</label>
                <select name="kode_barang" id="kode_barang" class="form-control" required>
                    <option value="">-- Pilih Barang --</option>
                    <?php foreach($barang as $b): ?>
                        <option value="<?= $b['kode_barang'] ?>"><?= $b['nama_barang'] ?> (<?= $b['kode_barang'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="barangunit">Barang Unit</label>
                <select name="barangunit[]" id="barang_unit" style="width: 100%;" class="select2 form-control" multiple="multiple" data-placeholder="Pilih Unit" required>
                </select>
            </div>

            <div class="form-group">
                <label for="tanggal_pinjam">Tanggal & Jam Pinjam</label>
                <input type="datetime-local" 
                    name="tanggal_pinjam" 
                    class="form-control" 
                    value="<?= old('tanggal_pinjam', date('Y-m-d\TH:i')) ?>" 
                    required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= site_url('peminjaman') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
        $(function () {
        $('.select2').select2();
        // event saat barang dipilih
        $('#kode_barang').on('change', function () {
            let kode_barang = $(this).val();
            console.log("Barang dipilih:", kode_barang);

            if (kode_barang) {
                $.ajax({
                    url: '/peminjaman/getBarangUnit',
                    type: 'GET',
                    data: { kode_barang: kode_barang },
                    dataType: 'json',
                    success: function (data) {
                        let $barangUnit = $("#barang_unit");
                        $barangUnit.empty(); // hapus option lama
                        $.each(data, function (index, item) {
                            // sesuaikan nama field dengan JSON
                            $barangUnit.append(
                                `<option value="${item.id}">${item.merk} (${item.kode_unit})</option>`
                            );
                        });
                        // refresh select2 biar dropdown update
                        $barangUnit.trigger('change.select2');
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>


