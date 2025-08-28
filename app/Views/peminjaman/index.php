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
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        
        <table id="peminjamanTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Peminjam</th>
                    <th>Nama Barang</th>
                    <th>Kode Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Dipinjamkan Oleh</th>
                    <th>Dikembalikan kepada</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($peminjaman)): ?>
                    <?php foreach($peminjaman as $p): ?>
                        <tr>
                            <td></td> <!-- nomor akan diisi otomatis oleh datatables -->
                            <td><?= esc($p->nama_peminjam) ?></td>
                            <td><?= esc($p->nama_barang) ?></td>
                            <td><?= esc($p->kode_barang) ?></td>
                            <td><?= esc($p->jumlah) ?></td>
                            <td><?= esc($p->tanggal_pinjam) ?></td>
                            <td><?= $p->tanggal_kembali ?? '-' ?></td>
                            <td><?= esc($p->petugas_pinjam) ?></td>
                            <td><?= esc($p->petugas_kembalikan) ?></td>
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
                                    <button class="btn btn-success btn-sm" onclick="kembalikan(<?= $p->id ?>)">
                                        Kembalikan
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                        <tr>
                            <td colspan="11" class="text-center">Tidak ada data</td>
                        </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Modal Pengembalian -->
        <div class="modal fade" id="modalKembalikan" tabindex="-1">
            <div class="modal-dialog">
                <form id="formKembalikan" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Pengembalian Barang</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Jumlah yang dikembalikan</label>
                                <input type="number" name="jumlah_kembali" class="form-control" min="1" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Script DataTables + Modal -->
<script>
function kembalikan(id) {
    let url = "<?= site_url('peminjaman/prosesKembali') ?>/" + id;
    $('#formKembalikan').attr('action', url);
    $('#modalKembalikan').modal('show');
}

$(function () {
    var t = $('#peminjamanTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copy', text: 'Salin' },
            { extend: 'excel', text: 'Excel' },
            { extend: 'csv', text: 'CSV' },
            { extend: 'pdf', text: 'PDF' },
            { extend: 'print', text: 'Cetak' },
        ],
        columnDefs: [{
            searchable: false,
            orderable: false,
            targets: 0
        }],
        order: [[1, 'asc']],
        language: {
            url: "<?= base_url('adminlte/plugins/datatables/i18n/Indonesian.json') ?>"
        }
    });

    // isi nomor otomatis setiap kali table redraw
    t.on('order.dt search.dt draw.dt', function () {
        let i = 1;
        t.cells(null, 0, { search: 'applied', order: 'applied' }).every(function () {
            this.data(i++);
        });
    }).draw();
});
</script>

<?= $this->endSection() ?>
