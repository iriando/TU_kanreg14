<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container">
    <h3>Tambah User</h3>
    <form action="<?= site_url('usermanager/users/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" value="<?= old('username') ?>" class="form-control">
            <small class="text-danger"><?= session('errors.username') ?></small>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= old('email') ?>" class="form-control">
            <small class="text-danger"><?= session('errors.email') ?></small>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control" required>
                <option value="">-- Pilih Role --</option>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= $role->id ?>" <?= (isset($user->group_id) && $user->group_id == $role->id) ? 'selected' : '' ?>>
                        <?= ucfirst($role->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
            <small class="text-danger"><?= session('errors.password') ?></small>
        </div>

        <button class="btn btn-success">Simpan</button>
    </form>
</div>

<?= $this->endSection() ?>
