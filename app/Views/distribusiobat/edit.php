<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Distribusi Obat</h3>
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

        <form action="<?= site_url('distribusiobat/update/'.$distribusi->id) ?>" method="post">
            <?= csrf_field() ?>
        <div class="form-group">
            <label for="kode_barang">Obat</label>
            <select name="kode_barang" class="form-control" required>
            <option value="">-- Pilih Obat --</option>
            <?php foreach($obat as $o): ?>
                <option value="<?= esc($o->kode_barang) ?>" <?= old('kode_barang', $distribusi->kode_barang)==$o->kode_barang?'selected':'' ?>>
                <?= esc($o->nama_barang) ?> (<?= esc($o->kode_barang) ?>)
                </option>
            <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah">Jumlah Distribusi</label>
            <input type="number" name="jumlah" class="form-control" value="<?= old('jumlah', $distribusi->jumlah) ?>" required>
        </div>

        <div class="form-group">
            <label for="nama_penerima">Nama Penerima</label>
            <input type="text" name="nama_penerima" class="form-control" value="<?= old('nama_penerima', $distribusi->nama_penerima) ?>" required>
        </div>

        <div class="form-group">
            <label for="tanggal_distribusi">Tanggal Distribusi</label>
            <input type="datetime-local" name="tanggal_distribusi" class="form-control" value="<?= old('tanggal_distribusi', $distribusi->tanggal_distribusi) ?>" required>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan (opsional)</label>
            <input type="text" name="keterangan" class="form-control" value="<?= old('keterangan', $distribusi->keterangan) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= site_url('distribusiobat') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
