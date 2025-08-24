<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Peminjaman BMN</h3>
        <div class="card-tools">
            <a href="<?= site_url('barang') ?>" class="btn btn-secondary btn-sm">List Barang</a>
            <a href="<?= site_url('peminjaman/create') ?>" class="btn btn-primary btn-sm">+Peminjaman</a>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table id="peminjamanTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Peminjam</th>
                    <th>Nama Barang</th>
                    <th>Kode Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Petugas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($peminjaman)): ?>
                    <?php foreach($peminjaman as $p): ?>
                        <tr>
                            <td><?= $p->id ?></td>
                            <td><?= $p->nama_peminjam ?></td>
                            <td><?= $p->nama_barang ?></td>
                            <td><?= $p->kode_barang ?></td>
                            <td><?= $p->jumlah ?></td>
                            <td><?= $p->tanggal_pinjam ?></td>
                            <td><?= $p->tanggal_kembali ?? '-' ?></td>
                            <td><?= $p->petugas ?></td>
                            <td>
                                <?php if($p->status === 'Dipinjam'): ?>
                                    <span class="badge badge-danger">Dipinjam</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Kembali</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (in_groups('admin')): ?>
                                    <a href="<?= site_url('peminjaman/edit/'.$p->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="<?= site_url('peminjaman/delete/'.$p->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                                <?php endif; ?>
                                <?php if($p->status === 'Dipinjam'): ?>
                                    <a href="<?= site_url('peminjaman/dikembalikan/'.$p->id) ?>" class="btn btn-success btn-sm" onclick="return confirm('Ubah status menjadi Kembali?')">Dikembalikan</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
