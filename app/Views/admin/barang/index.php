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
        <table id='tabelBarang' class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 20px;"></th>  <!-- tombol expand -->
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
                <tr data-kode="<?= esc($row['kode_barang']) ?>">
                    <td class="details-control" style="cursor:pointer; text-align:center;">
                        <i class="fa fa-plus-circle text-primary"></i>
                    </td>
                    <td></td>
                    <td><?= esc($row['kode_barang']) ?></td>
                    <td><?= esc($row['nama_barang']) ?></td>
                    <td><?= esc($row['kategori']) ?></td>
                    <td><?= esc($row['total_unit']) ?></td>
                    <td><?= esc($row['total_dipinjam']) ?></td>

                    <?php if (in_groups('admin')): ?>
                    <td>
                        <div class="btn-group">
                            <a href="<?= site_url('barang/edit/'.$row['id']) ?>" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a href="<?= site_url('barang/delete/'.$row['id']) ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin hapus?')">
                                <i class="fa fa-trash"></i>
                            </a>
                            <a href="<?= site_url('barang/detail/'.$row['kode_barang']) ?>" class="btn btn-info btn-sm">
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
function formatUnit(units) {
    let html = `
        <table class="table table-bordered table-sm mb-0">
            <thead>
                <tr>
                    <th>Kode Unit</th>
                    <th>Merk</th>
                    <th>Status</th>
                    <th>Kondisi</th>
                </tr>
            </thead>
            <tbody>
    `;

    units.forEach(u => {
        html += `
            <tr>
                <td>${u.kode_unit}</td>
                <td>${u.merk ?? ''}</td>
                <td>${u.status}</td>
                <td>${u.kondisi}</td>
            </tr>
        `;
    });

    html += "</tbody></table>";

    return html;
}

$(document).ready(function () {

    let table = $('#tabelBarang').DataTable({
        responsive: true,
        ordering: true,
        scrollX: true,
        lengthChange: false,
        autoWidth: false,
        dom: 'Bfrtip',

        buttons: [
            { extend: 'colvis', text: '<i class="fas fa-eye"></i> Colvis' },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-info btn-sm',
                exportOptions: { columns: ':visible' }
            }
        ],

        columnDefs: [
            { orderable: false, targets: [0, 1, -1] }, // expand + nomor + aksi
        ]
    });

    // nomor otomatis
    table.on('order.dt search.dt draw.dt', function () {
        let i = 1;
        table.column(1, { search: 'applied', order: 'applied' })
            .nodes()
            .each(function (cell) {
                cell.innerHTML = i++;
            });
    }).draw();

    // CLICK EXPAND ROW
    $('#tabelBarang tbody').on('click', 'td.details-control', function () {
        let tr = $(this).closest('tr');
        let row = table.row(tr);
        let kode = tr.data('kode');

        let icon = $(this).find('i');

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
            icon.removeClass('fa-minus-circle').addClass('fa-plus-circle');
        } else {
            $.get("<?= site_url('barang/getUnits/') ?>" + kode, function (data) {
                row.child(formatUnit(data)).show();
                tr.addClass('shown');
                icon.removeClass('fa-plus-circle').addClass('fa-minus-circle');
            });
        }
    });

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