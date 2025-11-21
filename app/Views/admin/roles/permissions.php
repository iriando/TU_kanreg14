<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h4>Kelola Permission untuk Role: <?= $role->name ?></h4>
    </div>

    <div class="card-body">
        <form action="<?= site_url('roles/' . $role->id . '/permissions/update') ?>" method="post">

            <div class="row">
                <?php foreach ($permissions as $p): ?>
                    <div class="col-md-4 mb-2">
                        <label>
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="<?= $p->id ?>"
                                   <?= in_array($p->id, $permissions) ? 'checked' : '' ?>>
                            <?= $p->name ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

            <hr>

            <button class="btn btn-success">Simpan</button>
            <a href="<?= site_url('roles') ?>" class="btn btn-secondary">Kembali</a>

        </form>
    </div>
</div>

<?= $this->endSection() ?>
