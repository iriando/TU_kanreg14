<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<section class="content">
    <div class="card card-solid">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div class="col-12">
                        <div class="col-12 text-center">
                            <?php if (!empty($unit['gambar'])): ?>
                                <img src="<?= base_url('uploads/unit-images/' . $unit['gambar']) ?>"
                                    class="img-fluid img-thumbnail"
                                    style="max-height: 250px; object-fit: cover;"
                                    alt="Gambar Unit">
                            <?php else: ?>
                                <div class="text-muted small">Tidak ada gambar</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6">
                    <h4>Detail Barang</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th>Kode Barang</th>
                            <td><?= esc($barang['kode_barang']) ?></td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td><?= esc($barang['nama_barang']) ?></td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td><?= esc($barang['kategori']) ?></td>
                        </tr>
                        <tr>
                            <th>Kode unit</th>
                            <td><?= esc($unit['kode_unit']) ?></td>
                        </tr>
                        <tr>
                            <th>Merk</th>
                            <td><?= esc($unit['merk']) ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><?= esc($unit['status']) ?></td>
                        </tr>
                        <tr>
                            <th>Kondisi</th>
                            <td><?= esc($unit['kondisi']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <nav class="w-100">
                    <div class="nav nav-tabs" id="product-tab" role="tablist">
                        <a class="nav-item nav-link active" id="tab-pemeliharaan" data-toggle="tab" href="#log-pemeliharaan" role="tab" aria-controls="log-pemeliharaan" aria-selected="true">Log Pemeliharaan</a>
                        <a class="nav-item nav-link" id="tab-peminjaman" data-toggle="tab" href="#log-peminjaman" role="tab" aria-controls="log-peminjaman" aria-selected="false">Log Peminjaman</a>
                    </div>
                </nav>

                <div class="tab-content p-3 w-100" id="nav-tabContent">
                    <!-- TAB PEMELIHARAAN -->
                    <div class="tab-pane fade show active" id="log-pemeliharaan" role="tabpanel" aria-labelledby="tab-pemeliharaan">
                        <?php if (!empty($log_pemeliharaan)): ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Petugas</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($log_pemeliharaan as $i => $log): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= esc($log->tanggal) ?></td>
                                            <td><?= esc($log->nama_petugas) ?></td>
                                            <td><?= esc($log->keterangan) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted">Belum ada log pemeliharaan untuk unit ini.</p>
                        <?php endif; ?>
                    </div>

                    <!-- TAB PEMINJAMAN -->
                    <div class="tab-pane fade" id="log-peminjaman" role="tabpanel"
                         aria-labelledby="tab-peminjaman">
                        <?php if (!empty($log_peminjaman)): ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Peminjam</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($log_peminjaman as $i => $log): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= esc($log->nama_peminjam) ?></td>
                                            <td><?= esc($log->tanggal_pinjam) ?></td>
                                            <td><?= esc($log->tanggal_kembali) ?></td>
                                            <td><?= esc($log->status_unit) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted">Belum ada log peminjaman untuk unit ini.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
</section>

<?= $this->endSection() ?>
