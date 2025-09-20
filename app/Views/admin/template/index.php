<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Template</h3>
        <div class="card-tools">
            <?php if (in_groups('admin')): ?>
            <a href="<?= site_url('template/create') ?>" class="btn btn-primary btn-sm">+Tambah Template</a>
            <?php endif; ?>
        </div>
    </div>
    

    <div class="card-body table-responsive">
        <table class="table table-bordered" id="t-template">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Template</th>
                    <th>Path File</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($templates as $t): ?>
                <tr>
                    <td></td>
                    <td><?= $t->nama ?></td>
                    <td><?= $t->file_path ?></td>
                    <td><?= $t->keterangan ?></td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="<?= site_url('template/edit/'.$t->id) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?= site_url('template/delete/'.$t->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </div>
                    </td>
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
        let table = $('#t-template').DataTable({
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
    table.buttons().container().appendTo('#t-obat_wrapper .col-md-6:eq(0)');

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
