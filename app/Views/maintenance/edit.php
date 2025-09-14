<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container">
    <h3>Edit Pemeliharaan BMN</h3>

    <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach(session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

    <form action="<?= site_url('maintenance/update/'.$maintenance->id) ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label>Nama Petugas</label>
            <input type="text" name="nama_petugas" class="form-control" value="<?= esc($maintenance->nama_petugas) ?>" required>
        </div>
        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="<?= esc($maintenance->tanggal) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= site_url('maintenance') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?= $this->endSection() ?>
