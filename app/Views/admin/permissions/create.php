<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Permission</h3>
    </div>

    <form action="<?= site_url('permissions/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="card-body">
            <div class="form-group">
                <label>Nama Permission</label>
                <input type="text" name="name" class="form-control" required>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">Simpan</button>
            <a href="<?= site_url('permissions') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
<?php if (session()->getFlashdata('errors')): ?>
    toastr.error(<?= json_encode(implode(', ', session()->getFlashdata('errors'))) ?>);
<?php endif; ?>
<?php if (session()->getFlashdata('success')): ?>
    toastr.success(<?= json_encode(session()->getFlashdata('success')) ?>);
<?php endif; ?>
</script>
<?= $this->endSection() ?>
