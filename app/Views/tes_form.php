<form action="<?= base_url('tesform') ?>" method="post">
    <?= csrf_field() ?>
    <input type="text" name="nama" placeholder="Nama">
    <button type="submit">Kirim</button>
</form>