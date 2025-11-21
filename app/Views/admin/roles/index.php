<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Role Management</h3>
        <a href="<?= site_url('roles/create') ?>" class="btn btn-primary float-right">
            <i class="fa fa-plus"></i> Tambah Role
        </a>
    </div>

    <div class="card-body">
        <table id="roleTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Role</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1 ?>
                <?php foreach ($roles as $r): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $r->name ?></td>
                        <td><?= $r->description ?></td>
                        <td>
                            <a href="<?= site_url('roles/edit/'.$r->id) ?>" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="<?= site_url('roles/permissions/'.$r->id) ?>" class="btn btn-info btn-sm">
                                <i class="fa fa-key"></i> Permission
                            </a>
                            <a href="<?= site_url('roles/delete/'.$r->id) ?>" 
                               onclick="return confirm('Yakin ingin dihapus?')"
                               class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(function() {
    $('#roleTable').DataTable();
});
</script>
<?= $this->endSection() ?>
