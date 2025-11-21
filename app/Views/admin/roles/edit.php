<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Role</h3>
    </div>

    <form action="<?= site_url('roles/update/'.$role->id) ?>" method="post">
        <?= csrf_field() ?>

        <div class="card-body">
            <div class="form-group">
                <label>Nama Role</label>
                <input type="text" name="name" class="form-control" value="<?= $role->name ?>" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control"><?= $role->description ?></textarea>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">Update</button>
            <a href="<?= site_url('roles') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
