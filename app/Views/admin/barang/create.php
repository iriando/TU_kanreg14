<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Barang</h3>
    </div>
    <div class="card-body">
        <form action="<?= site_url('barang/store') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Kode Barang</label>
                <input type="text" name="kode_barang" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" class="form-control">
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control"></textarea>
            </div>

            <hr>
            <h5>Unit Barang</h5>
            <div id="units-wrapper">
                <div class="unit-row mb-2">
                    <input type="text" name="units[0][kode_unit]" placeholder="Kode Unit" class="form-control d-inline-block w-25">
                    <input type="text" name="units[0][merk]" placeholder="Merk" class="form-control d-inline-block w-25">
                    <select name="units[0][status]" class="form-control d-inline-block w-25">
                        <option value="tersedia">tersedia</option>
                    </select>
                    <button type="button" class="btn btn-danger btn-sm remove-unit">X</button>
                </div>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" id="add-unit">+ Tambah Unit</button>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="<?= site_url('barang') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
    let unitIndex = 1;
    document.getElementById('add-unit').addEventListener('click', function () {
        const wrapper = document.getElementById('units-wrapper');
        const newRow = document.createElement('div');
        newRow.classList.add('unit-row', 'mb-2');
        newRow.innerHTML = `
            <input type="text" name="units[${unitIndex}][kode_unit]" placeholder="Kode Unit" class="form-control d-inline-block w-25">
            <input type="text" name="units[${unitIndex}][merk]" placeholder="Merk" class="form-control d-inline-block w-25">
            <select name="units[${unitIndex}][status]" class="form-control d-inline-block w-25">
                <option value="tersedia">tersedia</option>
            </select>
            <button type="button" class="btn btn-danger btn-sm remove-unit">X</button>
        `;
        wrapper.appendChild(newRow);

        newRow.querySelector('.remove-unit').addEventListener('click', function () {
            newRow.remove();
        });

        unitIndex++;
    });

    document.querySelectorAll('.remove-unit').forEach(btn => {
        btn.addEventListener('click', function () {
            btn.parentElement.remove();
        });
    });
</script>

<?= $this->endSection() ?>
