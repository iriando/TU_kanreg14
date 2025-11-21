<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Permission Management</h3>
        <a href="<?= site_url('permissions/create') ?>" class="btn btn-primary float-right">
            <i class="fa fa-plus"></i> Tambah Permission
        </a>
    </div>

    <div class="card-body">
        <table id="permissionTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Permission</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1 ?>
                <?php foreach ($permissions as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $p->name ?></td>
                        <td>
                            <a href="<?= site_url('permissions/edit/'.$p->id) ?>" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="<?= site_url('permissions/delete/'.$p->id) ?>"
                               onclick="return confirm('Yakin ingin menghapus?')"
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
    $('#permissionTable').DataTable();
});
</script>
<?= $this->endSection() ?>
