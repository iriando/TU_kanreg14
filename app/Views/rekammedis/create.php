<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">

    <!-- CARD DATA DIRI -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Data Diri Pegawai</h4>
        </div>
        <div class="card-body">

            <div class="row mb-2">
                <div class="col-md-4"><strong>Nama :</strong></div>
                <div class="col-md-8"><?= esc($pegawai->nama) ?></div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4"><strong>Jenis Kelamin :</strong></div>
                <div class="col-md-8"><?= esc($pegawai->gender) ?></div>
            </div>

            <div class="row">
                <div class="col-md-4"><strong>Usia :</strong></div>
                <div class="col-md-8"><?= esc($usia) ?> Tahun</div>
            </div>

        </div>
    </div>

    <!-- FORM -->
    <form action="<?= base_url('/rekammedis/store') ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="id_pegawai" value="<?= $pegawai->id ?>">
        <input type="hidden" name="nama_pasien" value="<?= $pegawai->nama ?>">

        <div class="row">
            <!-- CARD REKAM MEDIS -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Rekam Medis</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label"><strong>Tanggal</strong></label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Keluhan</strong></label>
                            <textarea name="keluhan" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <!-- CARD OBAT -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Obat yang Diberikan</h4>
                    </div>
                    <div class="card-body">
                        <div id="obat-wrapper">
                            <div class="row obat-item mb-3">
                                <div class="col-md-7 mb-2">
                                    <label>Obat</label>
                                    <select name="kode_barang[]" class="form-control obat-select" required>
                                        <option value="">-- Pilih Obat --</option>
                                        <?php foreach ($obat as $o): ?>
                                        <option value="<?= $o->kode_barang ?>"
                                                data-nama="<?= $o->nama_barang ?>">
                                            <?= $o->nama_barang ?> (<?= $o->kode_barang ?>) - Stok: <?= $o->sisa ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <!-- Hidden untuk simpan nama barang -->
                                    <input type="hidden" name="nama_barang[]" class="nama-barang-hidden">
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label>Jumlah</label>
                                    <input type="number" name="jumlah[]" class="form-control" min="1" value="1" required>
                                </div>
                                <div class="col-md-2 mb-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-item" style="display:none;">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-obat" class="btn btn-success btn-sm mt-2">
                            + Tambah Obat
                        </button>
                        <div class="mb-3">
                            <label><strong>Keterangan</strong></label>
                            <textarea name="keterangan" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('/rekammedis') ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
// Auto-set nama obat ke hidden input
function syncNamaBarang(selectEl) {
    const selected = selectEl.options[selectEl.selectedIndex];
    const nama = selected.getAttribute("data-nama");

    const namaHidden = selectEl.closest(".obat-item").querySelector(".nama-barang-hidden");
    namaHidden.value = nama ?? "";
}

// Tambah item obat baru
document.getElementById("add-obat").addEventListener("click", function () {
    let wrapper = document.getElementById("obat-wrapper");
    let original = wrapper.querySelector(".obat-item");
    let clone = original.cloneNode(true);

    clone.querySelectorAll("select, input").forEach(el => {
        el.value = "";
    });

    clone.querySelector(".remove-item").style.display = "inline-block";

    wrapper.appendChild(clone);
});

// Hapus item
document.addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-item")) {
        e.target.closest(".obat-item").remove();
    }
});

// Ketika memilih obat, masukkan nama obat ke hidden input
document.addEventListener("change", function (e) {
    if (e.target.classList.contains("obat-select")) {
        syncNamaBarang(e.target);
    }
});
</script>

<?= $this->endSection() ?>
