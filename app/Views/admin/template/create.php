<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Template</h3>
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
        <form action="<?= site_url('template/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label>Nama Template</label>
                    <input type="text" name="nama" value="<?= old('nama', 'BAST-peminjaman') ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label>Upload File (doc, docx)</label>
                    <input type="file" name="file_path" class="form-control">
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control"><?= old('keterangan') ?></textarea>
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
            </form>
    </div>
</div>

<?= $this->endSection() ?>
