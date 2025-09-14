<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Distribusi Obat</h3>
        <div class="card-tools">
            <a href="<?= site_url('obat') ?>" class="btn btn-secondary btn-sm">List Obat-obatan</a>
            <a href="<?= site_url('distribusiobat/create') ?>" class="btn btn-primary btn-sm">+ Distribusi</a>
        </div>
    </div>

    <div class="card-body table-responsive">
        <?php if(session()->getFlashdata('message')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
        <?php endif; ?>

    <table class="table table-bordered table-hover" id="distribusi-obat">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penerima</th>
                <th>Nama Obat</th>
                <th>Kode Barang</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Tanggal</th>
                <th>Petugas</th>
                <th>Keterangan</th>
                <?php if (in_groups('admin')): ?>
                <th>Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($distribusi as $d): ?>
                <tr>
                    <td></td>
                    <td><?= esc($d->nama_penerima ?? '-') ?></td>
                    <td><?= esc($d->nama_barang) ?></td>
                    <td><?= esc($d->kode_barang) ?></td>
                    <td><?= esc($d->jumlah) ?></td>
                    <td><?= esc($d->satuan ?? '-') ?></td>
                    <td><?= esc($d->tanggal) ?></td>
                    <td><?= esc($d->petugas ?? '-') ?></td>
                    <td><?= esc($d->keterangan ?? '-') ?></td>
                    <?php if (in_groups('admin') && $d->jenis === 'Distribusi'): ?>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="<?= site_url('distribusiobat/edit/'.$d->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?= site_url('distribusiobat/delete/'.$d->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </div>
                    </td>
                    <?php elseif (in_groups('admin')): ?>
                    <td>-</td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        let table = $('#distribusi-obat').DataTable({
        responsive: true,
        ordering: true,
        scrollX: true,
        lengthChange: false,
        autoWidth: false,
        buttons: [
            {
                extend: 'colvis',
                text: '<i class="fas fa-eye"></i> Colvis',
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            // Kolom No ada di index 0
                            if (column === 0) {
                                return row + 1; // isi manual nomor urut
                            }
                            return data;
                        }
                    }
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            if (column === 0) {
                                return row + 1;
                            }
                            return data;
                        }
                    }
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-info btn-sm',
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function (data, row, column, node) {
                            if (column === 0) {
                                return row + 1;
                            }
                            return data;
                        }
                    }
                }
            }
        ],
        columnDefs: [
            { orderable: false, targets: [0, -1] }
        ]
    });

    // tombol ke posisi adminlte default
    table.buttons().container().appendTo('#distribusi-obat_wrapper .col-md-6:eq(0)');

    // auto numbering di tabel (untuk tampilan web)
    table.on('order.dt search.dt draw.dt', function () {
        let i = 1;
        table.column(0, { search: 'applied', order: 'applied' })
            .nodes()
            .each(function (cell) {
                cell.innerHTML = i++;
            });
    }).draw();

    });

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


