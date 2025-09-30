<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container">
    <h3><?= $title ?></h3>

    <form action="<?= site_url('peminjaman/update/'.$peminjaman->id) ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label>Nama Peminjam</label>
            <input type="text" name="nama_peminjam" class="form-control" value="<?= esc($peminjaman->nama_peminjam) ?>" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Pinjam</label>
            <input type="datetime-local" name="tanggal_pinjam" class="form-control" value="<?= esc($peminjaman->tanggal_pinjam) ?>" required>
        </div>

        <?php if ($peminjaman->status === 'dikembalikan'): ?>
            <div class="mb-3">
                <label>Tanggal Kembalikan</label>
                <input type="datetime-local" name="tanggal_kembali" class="form-control" value="<?= esc($peminjaman->tanggal_kembali) ?>" required>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= site_url('peminjaman') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    <?php if (session()->getFlashdata('success')): ?>
        toastr.success("<?= session()->getFlashdata('success') ?>");
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        toastr.error("<?= session()->getFlashdata('error') ?>");
    <?php endif; ?>

    <?php if (session()->getFlashdata('warning')): ?>
        toastr.warning("<?= session()->getFlashdata('warning') ?>");
    <?php endif; ?>

    <?php if (session()->getFlashdata('info')): ?>
        toastr.info("<?= session()->getFlashdata('info') ?>");
    <?php endif; ?>
</script>
<?= $this->endSection() ?>
