<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Detail Barang: <?= esc($master['nama_barang']) ?> (<?= esc($master['kode_barang']) ?>)
        </h3>
    </div>
    <div class="card-body">
        <p><strong>Kategori:</strong> <?= esc($master['kategori']) ?></p>
        <p><strong>Keterangan:</strong> <?= esc($master['keterangan']) ?></p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nomor Unit</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($units as $u): ?>
                <tr>
                    <td><?= esc($u['nomor_urut']) ?></td>
                    <td>
                        <?php if ($u['status'] === 'dipinjam'): ?>
                            <span class="badge bg-danger">Dipinjam</span>
                        <?php else: ?>
                            <span class="badge bg-success">Tersedia</span>
                        <?php endif ?>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
