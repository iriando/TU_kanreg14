<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Distribusi ATK</h3>
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

        <form action="<?= site_url('distribusiatk/store') ?>" method="post">
            <div class="form-group">
                <label for="kode_barang">Barang ATK</label>
                <select name="kode_barang" class="form-control" required>
                    <option value="">-- Pilih Barang ATK --</option>
                    <?php foreach($atk as $a): ?>
                        <option value="<?= $a->kode_barang ?>" <?= old('kode_barang') == $a->kode_barang ? 'selected' : '' ?>>
                            <?= $a->nama_barang ?> (<?= $a->kode_barang ?>) - Sisa: <?= $a->sisa ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="jumlah">Jumlah Distribusi</label>
                <input type="number" name="jumlah" class="form-control" value="<?= old('jumlah', 1) ?>" required min="1">
            </div>

            <div class="form-group">
                <label for="nama_penerima">Nama Penerima</label>
                <input type="text" name="nama_penerima" class="form-control" value="<?= old('nama_penerima') ?>" required>
            </div>

            <div class="form-group">
                <label for="tanggal_distribusi">Tanggal Distribusi</label>
                <input type="date" name="tanggal_distribusi" class="form-control" value="<?= old('tanggal_distribusi', date('Y-m-d')) ?>" required>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan (opsional)</label>
                <input type="text" name="keterangan" class="form-control" value="<?= old('keterangan') ?>">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= site_url('distribusiatk') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
