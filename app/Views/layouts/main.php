<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Aplikasi Asset BMN' ?></title>

  <!-- CSS AdminLTE -->
  <link rel="stylesheet" href="<?= base_url('adminlte/dist/css/adminlte.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
</head>
<body class="hold-transition sidebar-mini">

<div class="wrapper">
  
  <!-- Navbar -->
  <?= $this->include('layouts/navbar') ?>

  <!-- Sidebar -->
  <?= $this->include('layouts/sidebar') ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper p-3">
      <?= $this->renderSection('content') ?>
  </div>

  <!-- Footer -->
  <footer class="main-footer text-center">
      <strong>&copy; <?= date('Y') ?> - Aplikasi Asset BMN</strong>
  </footer>
</div>

<!-- JS -->
<script src="<?= base_url('adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('adminlte/dist/js/adminlte.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/jszip/jszip.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/pdfmake/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/pdfmake/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>

<script>
  $(document).ready(function () {
    var t = $('table.table').DataTable({
      responsive: true,
      autoWidth: false,
      pageLength: 10,
      lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
      language: {
        url: "<?= base_url('adminlte/plugins/datatables/i18n/Indonesian.json') ?>"
      },
      columnDefs: [
        {
          searchable: false,
          orderable: false,
          targets: 0   // kolom pertama untuk nomor
        }
      ],
      order: [[1, 'asc']],
      dom: 'Bfrtip', // <== ini penting untuk menampilkan tombol export
      buttons: [
        {
          extend: 'excelHtml5',
          text: '<i class="fas fa-file-excel"></i> Excel',
          className: 'btn btn-success btn-sm'
        },
        {
          extend: 'pdfHtml5',
          text: '<i class="fas fa-file-pdf"></i> PDF',
          className: 'btn btn-danger btn-sm'
        },
        {
          extend: 'print',
          text: '<i class="fas fa-print"></i> Print',
          className: 'btn btn-info btn-sm'
        },
        // {
        //   extend: 'colvis',
        //   text: '<i class="fas fa-eye"></i> Kolom',
        //   className: 'btn btn-secondary btn-sm'
        // }
      ]
    });

    // bikin nomor urut ulang tiap kali redraw (paging, sort, search)
    t.on('order.dt search.dt', function () {
      t.column(0, { search: 'applied', order: 'applied' })
        .nodes()
        .each(function (cell, i) {
          cell.innerHTML = i + 1;
        });
    }).draw();
  });
</script>
</body>
</html>
