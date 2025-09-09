<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Distribusi Obat</h3>
    </div>
    <div class="card-body">
        <form action="<?= site_url('distribusiobat/store') ?>" method="post">
            <?= csrf_field() ?>

            <div id="obat-wrapper">
                <div class="form-row align-items-end obat-item mb-2">
                    <div class="col-md-6">
                        <label>Obat</label>
                        <select name="kode_barang[]" class="form-control" required>
                            <option value="">-- Pilih Obat --</option>
                            <?php foreach($obat as $o): ?>
                            <option value="<?= $o->kode_barang ?>">
                                <?= $o->nama_barang ?> (<?= $o->kode_barang ?>) - Sisa: <?= $o->sisa ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah[]" class="form-control" value="1" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-item" style="display:none;">Hapus</button>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <button type="button" id="add-obat" class="btn btn-success btn-sm">+ Tambah</button>
            </div>

            <div class="row">
                <div class="col form-group">
                    <label for="nama_penerima">Nama Penerima</label>
                    <input type="text" name="nama_penerima" class="form-control" required>
                </div>

                <div class="col form-group">
                    <label for="tanggal_distribusi">Tanggal Distribusi</label>
                    <input type="date" name="tanggal_distribusi" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col form-group">
                    <label for="keterangan">Keterangan (opsional)</label>
                    <textarea class="form-control" name="keterangan" id="keterangan"></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= site_url('distribusiobat') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const wrapper = document.getElementById("obat-wrapper");
    const addBtn = document.getElementById("add-obat");

    addBtn.addEventListener("click", function() {
        let firstItem = wrapper.querySelector(".obat-item");
        let newItem = firstItem.cloneNode(true);

        // reset input
        newItem.querySelectorAll("select, input").forEach(el => {
            if(el.tagName === "SELECT") {
                el.selectedIndex = 0;
            } else {
                el.value = 1;
            }
        });

        // tampilkan tombol hapus
        newItem.querySelector(".remove-item").style.display = "inline-block";

        wrapper.appendChild(newItem);
    });

    wrapper.addEventListener("click", function(e) {
        if(e.target.classList.contains("remove-item")) {
            e.target.closest(".obat-item").remove();
        }
    });
});
</script>

<?= $this->endSection() ?>