<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card card-primary">
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

        <form action="<?= site_url('maintenance/store') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col form-group">
                    <div class="form-group">
                        <label for="nama_peminjam">Petugas</label>
                        <input type="text" name="nama_petugas" class="form-control" value="" required>
                    </div>
                </div>
                <div class="col form-group">
                    <label for="kode_barang">Barang</label>
                    <select name="kode_barang" id="kode_barang" class="form-control" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach($barang as $b): ?>
                            <option value="<?= $b['kode_barang'] ?>"><?= $b['nama_barang'] ?> (<?= $b['kode_barang'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col form-group">
                    <label for="barangunit">Barang Unit</label>
                    <select name="barangunit[]" id="barang_unit" style="width: 100%;" class="select2 form-control" multiple="multiple" data-placeholder="Pilih Unit" required>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-3 form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="datetime-local" 
                        name="tanggal" class="form-control" value="" required>
                </div>
                <div class="col form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" class="form-control" value="" required></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-3 form-group">
                    <label for="pengingat">Pengingat</label>
                    <select name="pengingat" id="pengingat" class="form-control" required>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                        <option value="Aktif">Aktif</option>
                    </select>
                </div>
                <div class="col-3 form-group" id="pengingat_hari_group" style="display:none;">
                    <label for="hari">Diingatkan dalam (hari)</label>
                    <input type="number" name="hari" id="hari" class="form-control" min="1" placeholder="Isi jumlah hari">
                </div>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
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
            if (kode_barang) {
                $.ajax({
                    url: '/peminjaman/getBarangUnit',
                    type: 'GET',
                    data: { kode_barang: kode_barang },
                    dataType: 'json',
                    success: function (data) {
                        let $barangUnit = $("#barang_unit");
                        $barangUnit.empty();
                        $.each(data, function (index, item) {
                            $barangUnit.append(
                                `<option value="${item.id}">${item.merk} (${item.kode_unit})</option>`
                            );
                        });
                        $barangUnit.trigger('change.select2');
                    }
                });
            }
        });

        // toggle input hari sesuai pilihan pengingat
        $('#pengingat').on('change', function () {
            if ($(this).val() === 'Aktif') {
                $('#pengingat_hari_group').show();
            } else {
                $('#pengingat_hari_group').hide();
                $('#hari').val('');
            }
        });
    });
</script>
<?= $this->endSection() ?>



