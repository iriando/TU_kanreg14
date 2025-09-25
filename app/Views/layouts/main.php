<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'SiMAPAN' ?></title>

  <!-- CSS AdminLTE -->
  <link rel="stylesheet" href="<?= base_url('adminlte/dist/css/adminlte.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/select2/css/select2.min.css') ?>">
  <!-- toastr -->
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/toastr/toastr.min.css') ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
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
      <strong>&copy; <?= date('Y') ?> - Bagian Tata Usaha Kanreg XIV BKN</strong>
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
<script src="<?= base_url('adminlte/plugins/select2/js/select2.full.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/toastr/toastr.min.js') ?>"></script>

<?= $this->renderSection('scripts') ?>

</body>
</html>
