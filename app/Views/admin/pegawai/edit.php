<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Pegawai</h3>
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

        <form action="<?= site_url('pegawai/update/'.$pegawai->id) ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" class="form-control" value="<?= old('nama', $pegawai->nama) ?>" required>
            </div>
            <div class="form-group">
                <label>Jenis kelamin</label>
                <select name="gender" class="form-control">
                    <option value="pria">pria</option>
                    <option value="wanita">wanita</option>
                </select>
            </div>
            <div class="form-group col">
                <label for="tanggal_lahir">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="<?= old('tanggal_lahir', $pegawai->tanggal_lahir) ?>" required>
            </div>
            <div class="form-group">
                <label for="unit">Unit/Pokja</label>
                <input type="text" name="unit" class="form-control" value="<?= old('unit', $pegawai->unit) ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?= site_url('pegawai') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
