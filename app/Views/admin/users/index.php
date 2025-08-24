<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container">
    <h3>User Manager</h3>
    <a href="<?= site_url('usermanager/users/create') ?>" class="btn btn-primary mb-3">Tambah User</a>

    <?php if(session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $u): ?>
                <tr>
                    <td><?= $u->id ?></td>
                    <td><?= $u->username ?></td>
                    <td><?= $u->email ?></td>
                    <td><?= $u->role ?></td>
                    <td>
                        <a href="<?= site_url('usermanager/users/edit/' . $u->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?= site_url('usermanager/users/delete/' . $u->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
