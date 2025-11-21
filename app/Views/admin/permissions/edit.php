<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Permission</h3>
    </div>

    <form action="<?= site_url('permissions/update/'.$permission->id) ?>" method="post">
        <?= csrf_field() ?>

        <div class="card-body">
            <div class="form-group">
                <label>Nama Permission</label>
                <input type="text" name="name" value="<?= $permission->name ?>" class="form-control" required>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">Update</button>
            <a href="<?= site_url('permissions') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
