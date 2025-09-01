<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Distribusi Obat</h3>
    </div>
    <div class="card-body">
        <?php if(session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <ul>
            <?php foreach(session()->getFlashdata('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form action="<?= site_url('distribusiobat/store') ?>" method="post">
            <?= csrf_field() ?>
        <div class="form-group">
            <label for="kode_barang">Obat</label>
            <select name="kode_barang" class="form-control" required>
            <option value="">-- Pilih Obat --</option>
            <?php foreach($obat as $o): ?>
            <option value="<?= $o->kode_barang ?>" <?= old('kode_barang') == $o->kode_barang ? 'selected' : '' ?>>
                <?= $o->nama_barang ?> (<?= $o->kode_barang ?>) - Sisa: <?= $o->sisa ?>
            </option>
            <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah">Jumlah Distribusi</label>
            <input type="number" name="jumlah" class="form-control" value="<?= old('jumlah', 1) ?>" required>
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
        <a href="<?= site_url('distribusiobat') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
