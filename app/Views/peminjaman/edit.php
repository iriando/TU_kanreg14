<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container">
    <h3><?= $title ?></h3>

    <form action="<?= site_url('peminjaman/update/'.$peminjaman->id) ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label>Nama Peminjam</label>
            <input type="text" name="nama_peminjam" class="form-control" value="<?= esc($peminjaman->nama_peminjam) ?>" required>
        </div>
        <div class="mb-3">
            <label>Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" class="form-control" value="<?= esc($peminjaman->tanggal_pinjam) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= site_url('peminjaman') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?= $this->endSection() ?>
