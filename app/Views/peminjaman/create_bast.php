<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Buat Berita Acara Serah Terima</h3>
    </div>
    <div class="card-body">
    <form action="<?= site_url('peminjaman/prosesBast/' . $peminjaman->id) ?>" method="post">
            <?= csrf_field() ?>
        <div class="row">
            <div class="col form-group">
                <label for="">Nomor </label>
                <input type="text" name="nomor" class="form-control" required>
            </div>
            <div class="col form-group">
                <label for="">Tanggal</label>
                <input type="date" name="tanggal" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Data peminjam</h3>
                    </div>
                    <div class="card-body form-group">
                        <label for="">Nama</label>
                        <input type="text" name="peminjam_nama" class="form-control" value="<?= old('peminjam_nama', $peminjaman->nama_peminjam) ?>" required>
                        <label for="">NIP</label>
                        <input type="text" name="peminjam_nip" class="form-control" required>
                        <label for="">Pangkat</label>
                        <input type="text" name="peminjam_pangkat" class="form-control" required>
                        <label for="">Jabatan</label>
                        <input type="text" name="peminjam_jabatan" class="form-control" required>
                        <label for="">Unit kerja</label>
                        <input type="text" name="peminjam_unker" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Data petugas</h3>
                    </div>
                    <div class="card-body form-group">
                        <label for="">Nama</label>
                        <input type="text" name="petugas_nama" class="form-control" value="<?= old('petugas_nama', $peminjaman->petugas_pinjam) ?>" required>
                        <label for="">NIP</label>
                        <input type="text" name="petugas_nip" class="form-control" required>
                        <label for="">Pangkat</label>
                        <input type="text" name="petugas_pangkat" class="form-control" required>
                        <label for="">Jabatan</label>
                        <input type="text" name="petugas_jabatan" class="form-control" required>
                        <label for="">Unit kerja</label>
                        <input type="text" name="petugas_unker" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>
            <button type="submit" class="btn btn-primary">Download BAST</button>
            <a href="<?= site_url('peminjaman') ?>" class="btn btn-secondary">Batal</a>
    </form>
    </div>
</div>

<?= $this->endSection() ?>


