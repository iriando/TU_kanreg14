<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Barang</h3>
        <div class="card-tools">
        <?php if (in_groups('admin')): ?>
            <a href="<?= site_url('barang/create') ?>" class="btn btn-primary btn-sm">Tambah Barang</a>
        <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <table id='tabelBarang' class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Total Unit</th>
                    <th>Total Dipinjam</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barang as $row): ?>
                <tr>
                    <td></td>
                    <td><?= esc($row['kode_barang']) ?></td>
                    <td><?= esc($row['nama_barang']) ?></td>
                    <td><?= esc($row['kategori']) ?></td>
                    <td><?= esc($row['total_unit']) ?></td>
                    <td><?= esc($row['total_dipinjam']) ?></td>
                    <?php if (in_groups('admin')): ?>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="<?= site_url('barang/edit/'.$row['id']) ?>" 
                            class="btn btn-warning btn-sm" 
                            title="Edit">
                                <i class="fa fa-edit"></i></i>
                            </a>
                            <a href="<?= site_url('barang/delete/'.$row['id']) ?>" 
                            class="btn btn-danger btn-sm" 
                            onclick="return confirm('Yakin hapus?')" 
                            title="Hapus">
                                <i class="fa fa-trash"></i>
                            </a>
                            <a href="<?= site_url('barang/detail/'.$row['kode_barang']) ?>" 
                            class="btn btn-info btn-sm" 
                            title="Detail">
                                <i class="fa fa-info-circle"></i>
                            </a>
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
    let table = $('#tabelBarang').DataTable({
        responsive: true,
        ordering: true,
        scrollX: true,
        lengthChange: false,
        autoWidth: false,
        dom: 'Bfrtip',
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