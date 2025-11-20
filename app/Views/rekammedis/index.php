<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Rekam Medis</h3>
        <div class="card-tools">
            <!-- // -->
        </div>
    </div>
    

    <div class="card-body table-responsive">
        <table class="table table-bordered" id="t-rekammedis">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Usia</th>
                    <th>Unit</th>
                    <?php if (in_groups('admin')): ?>
                    <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pegawai as $p): ?>
                <tr>
                    <td></td>
                    <td><?= $p->nama ?></td>
                    <td><?= $p->gender ?></td>
                    <td><?= $p->usia ?> Tahun</td>
                    <td><?= $p->unit ?></td>
                    <?php if (in_groups('admin')): ?>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="<?= site_url('rekammedis/create/'.$p->id) ?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>
                            <a href="<?= site_url('rekammedis/view/'.$p->id) ?>" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                        </div>
                    </td>
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
        let table = $('#t-rekammedis').DataTable({
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
                            if (column === 0) {
                                return row + 1;
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

    table.buttons().container().appendTo('#t-pegawai_wrapper .col-md-6:eq(0)');

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
