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
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <table id="peminjamanTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Peminjam</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Unit</th>
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
                            <td><?= esc($p->kode_barang) ?></td>
                            <td><?= esc($p->nama_barang) ?></td>
                            <td><?= esc($p->jumlah) ?></td>
                            <td><?= esc($p->tanggal_pinjam) ?></td>
                            <td><?= $p->tanggal_kembali ?? '-' ?></td>
                            <td><?= esc($p->petugas_pinjam) ?></td>
                            <td><?= esc($p->petugas_kembalikan) ?></td>
                            <td>
                                <?php if($p->status === 'pinjam'): ?>
                                    <span class="badge badge-danger">Pinjam</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Dikembalikan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <?php if (in_groups('admin')): ?>
                                        <a href="<?= site_url('peminjaman/edit/'.$p->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="<?= site_url('peminjaman/delete/'.$p->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                                    <?php endif; ?>
                                    <?php if (!empty($p->sisa) && $p->status === 'pinjam'): ?>
                                        <a href="#" 
                                        class="btn btn-sm btn-success btn-kembalikan"
                                        data-toggle="modal"
                                        data-target="#modalKembalikan"
                                        data-id="<?= $p->id ?>"
                                        data-kode="<?= esc($p->kode_barang) ?>">
                                        Kembalikan (<?= $p->sisa ?>)
                                        </a>
                                    <?php endif; ?>
                                </div>
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
    </div>
</div>

<div class="modal fade" id="modalKembalikan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <form action="<?= site_url('peminjaman/kembalikan') ?>" method="post">
            <?= csrf_field() ?>
            <div class="modal-header">
            <h5 class="modal-title">Kembalikan Barang</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="kode_barang" id="kode_barang">

            <div class="form-group">
                <label for="unitKembali">Pilih Unit yang Dikembalikan</label>
                <select name="unit_kembali[]" id="unitKembali" style="width: 100%;" class="select2 form-control" multiple="multiple" required>
                <!-- opsi diisi via AJAX -->
                </select>
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Pengembalian</button>
            </div>
        </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function () {
    let table = $('#peminjamanTable').DataTable({
        responsive: true,
        ordering: true,
        scrollX: true,
        columnDefs: [
            { orderable: false, targets: [0, -1] } // kolom nomor & aksi tidak bisa diurutkan
        ]
    });

    table.on('order.dt search.dt draw.dt', function () {
        let i = 1;
        table.column(0, { search: 'applied', order: 'applied' })
                .nodes()
                .each(function (cell) {
                    cell.innerHTML = i++;
        });
    }).draw();
});

$(document).on('click', '.btn-kembalikan', function () {
    let id   = $(this).data('id');
    let kode = $(this).data('kode');
    $('.select2').select2();
    $('#id').val(id);
    $('#kode_barang').val(kode);

    // Kosongkan select
    $('#unitKembali').empty();

    // Ambil unit yang sedang dipinjam
    $.get('<?= site_url("peminjaman/getUnitDipinjam") ?>', { id: id, kode_barang: kode }, function (data) {
        if (data.length > 0) {
            data.forEach(function (item) {
                $('#unitKembali').append(
                    `<option value="${item.id}">${item.merk} (${item.kode_unit})</option>`
                );
            });
        } else {
            $('#unitKembali').append(`<option disabled>Tidak ada unit yang sedang dipinjam</option>`);
        }
    }, 'json');
});
</script>
<?= $this->endSection() ?>

