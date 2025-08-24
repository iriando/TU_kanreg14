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
            <div class="form-group">
                <div class="form-group">
                    <label for="nama_peminjam">Peminjam</label>
                    <input type="text" name="nama_peminjam" class="form-control" value="<?= old('nama_peminjam') ?>" required>
                </div>
                <!-- <label for="user_id">Peminjam</label> -->
                <!-- <select name="user_id" class="form-control" required> -->
                    <!-- <option value="">-- Pilih Peminjam --</option> -->
                    <!-- <?php //foreach($users as $u): ?> -->
                        <!-- <option value="<//?= $u->id ?>"><//?= $u->username ?></option> -->
                    <!-- <?php //endforeach; ?> -->
                <!-- </select> -->
            </div>
            <div class="form-group">
                <label for="kode_barang">Barang</label>
                <select name="kode_barang" class="form-control" required>
                    <option value="">-- Pilih Barang --</option>
                    <?php foreach($barang as $b): ?>
                        <option value="<?= $b['kode_barang'] ?>" <?= old('kode_barang') == $b['kode_barang'] ? 'selected' : '' ?>>
                            <?= $b['nama_barang'] ?> (<?= $b['kode_barang'] ?>) - Sisa: <?= $b['sisa'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" class="form-control" value="<?= old('jumlah', 1) ?>" required>
            </div>
            <div class="form-group">
                <label for="tanggal_pinjam">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" class="form-control" value="<?= old('tanggal_pinjam', date('Y-m-d')) ?>" required>
            </div>
            <div class="form-group">
                <label for="tanggal_kembali">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" class="form-control" value="<?= old('tanggal_kembali') ?>">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= site_url('peminjaman') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
