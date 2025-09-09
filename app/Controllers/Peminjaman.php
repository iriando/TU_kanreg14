<?php

namespace App\Controllers;

use App\Models\BarangUnitModel;
use App\Models\PeminjamanModel;
use App\Models\BarangMasterModel;
use App\Controllers\BaseController;
use PhpOffice\PhpWord\TemplateProcessor;

class Peminjaman extends BaseController
{
    protected $peminjamanModel;
    protected $barangModel;
    protected $unitModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->barangModel     = new BarangMasterModel();
        $this->unitModel       = new BarangUnitModel();
    }

    public function index()
    {
        // ambil semua log peminjaman (urut terbaru dulu)
        $peminjaman = $this->peminjamanModel->orderBy('id', 'DESC')->findAll();
        // cache sisa per transaksi supaya tidak query berkali-kali
        $sisaMap = [];
        foreach ($peminjaman as $p) {
            // pastikan kita pakai transaksi_id sebagai key; kalau belum ada pakai id sendiri
            $transId = $p->transaksi_id ?? $p->id;
            if (! isset($sisaMap[$transId])) {
                // getSisaPinjam harus mengembalikan integer sisa (pinjam - kembali)
                $sisaMap[$transId] = $this->peminjamanModel->getSisaPinjam($transId);
            }
            // pasang properti sisa ke tiap record supaya view bisa pakai $p->sisa
            $p->sisa = $sisaMap[$transId];
        }
        return view('peminjaman/index', ['peminjaman' => $peminjaman]);
    }

    public function create()
    {
        $data['barang'] = $this->barangModel->orderBy('id', 'DESC')->findAll();
        return view('peminjaman/create', $data);
    }

    public function getBarangUnit()
    {
        $kode_barang = $this->request->getGet('kode_barang');
        $units = $this->unitModel->getAvailableByKodeBarang($kode_barang);
        return $this->response->setJSON($units);
    }

    public function store()
    {
        $namaPeminjam   = $this->request->getPost('nama_peminjam');
        $kodeBarang     = $this->request->getPost('kode_barang');
        $barang         = $this->barangModel->where('kode_barang', $kodeBarang)->first();
        $unitDipilih    = $this->request->getPost('barangunit');
        $tanggalPinjam  = $this->request->getPost('tanggal_pinjam');
        $jumlahUnit     = count($unitDipilih);
        $petugas        = user()->username;

        // Insert peminjaman awal
        $id = $this->peminjamanModel->insert([
            'nama_peminjam'   => $namaPeminjam,
            'kode_barang'     => $kodeBarang,
            'nama_barang'     => $barang['nama_barang'],
            'jumlah'          => $jumlahUnit,
            'tanggal_pinjam'  => $tanggalPinjam,
            'petugas_pinjam'  => $petugas,
            'status'          => 'pinjam',
        ], true); // true supaya return insertID

        // Update transaksi_id dengan id sendiri (jadi induk log)
        $this->peminjamanModel->update($id, ['transaksi_id' => $id]);

        // Update unit → jadi dipinjam
        if (!empty($unitDipilih)) {
            $this->unitModel->whereIn('id', $unitDipilih)
                            ->set(['status' => 'dipinjam'])
                            ->update();
        }

        return redirect()->to('/peminjaman')->with('success', 'Peminjaman berhasil ditambahkan');
    }

    public function kembalikan()
    {
        $unitKembali  = $this->request->getPost('unit_kembali');
        $id           = $this->request->getPost('id'); // id peminjaman induk
        $data         = $this->peminjamanModel->find($id);
        $jumlahUnit   = count($unitKembali);
        $petugas      = user()->username;
        $tanggal_kembali = $this->request->getPost('tanggal_kembali');

        if (!empty($unitKembali) && $data) {
            // Update status unit jadi tersedia
            $this->unitModel->setTersedia($unitKembali);

            // Insert log pengembalian
            $this->peminjamanModel->insert([
                'transaksi_id'        => $data->transaksi_id ?? $data->id,
                'nama_peminjam'       => $data->nama_peminjam,
                'kode_barang'         => $data->kode_barang,
                'nama_barang'         => $data->nama_barang,
                'jumlah'              => $jumlahUnit,
                'tanggal_pinjam'      => $data->tanggal_pinjam,
                'tanggal_kembali'     => $tanggal_kembali,
                'petugas_pinjam'      => $data->petugas_pinjam,
                'petugas_kembalikan'  => $petugas,
                'status'              => 'dikembalikan',
            ]);
        }

        return redirect()->to('/peminjaman')->with('success', 'Barang berhasil dikembalikan');
    }


    public function getUnitDipinjam()
    {
        $peminjamanId = $this->request->getGet('peminjaman_id');
        $kodeBarang   = $this->request->getGet('kode_barang');
        $units = $this->unitModel
                    ->where('kode_barang', $kodeBarang)
                    ->where('status', 'dipinjam')
                    ->findAll();

        return $this->response->setJSON($units);
    }

    public function edit($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);

        if (!$peminjaman) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Peminjaman tidak ditemukan');
        }

        return view('peminjaman/edit', [
            'title'      => 'Edit Peminjaman',
            'peminjaman' => $peminjaman,
            'barang'     => $this->barangModel->findAll(), // dropdown kode barang
        ]);
    }

    public function update($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);

        if (!$peminjaman) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Peminjaman tidak ditemukan');
        }

        $kodeBarang   = $this->request->getPost('kode_barang');
        $barang       = $this->barangModel->where('kode_barang', $kodeBarang)->first();

        $data = [
            'nama_peminjam'   => $this->request->getPost('nama_peminjam'),
            'tanggal_pinjam'  => $this->request->getPost('tanggal_pinjam'),
        ];

        $this->peminjamanModel->update($id, $data);

        return redirect()->to('/peminjaman')->with('success', 'Data peminjaman berhasil diperbarui');
    }


    public function delete($id)
    {
        $this->peminjamanModel->deletePeminjaman($id);
        return redirect()->to('/peminjaman')->with('success', 'Peminjaman berhasil dihapus');
    }

    public function bast($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);

        if (!$peminjaman) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Peminjaman tidak ditemukan');
        }

        return view('peminjaman/create_bast', [
            'title'      => 'Buat Berita Acara Serah Terima',
            'peminjaman' => $peminjaman,
        ]);
    }

    public function prosesBast($id = null)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        if (!$peminjaman) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $unitModel = new BarangUnitModel();
        $masterModel = new BarangMasterModel();

        // ambil data master barang
        $barangMaster = $masterModel->where('kode_barang', $peminjaman->kode_barang)->first();

        // ambil unit barang yg sedang dipinjam
        $barangDipinjam = $unitModel->where('kode_barang', $peminjaman->kode_barang)
            ->where('status', 'dipinjam')
            ->findAll();

        $templateProcessor = new TemplateProcessor(WRITEPATH . '../app/Templates/BAST-template.docx');

        // isi placeholder utama (contoh, silakan sesuaikan)
        $templateProcessor->setValue('nomor', $this->request->getPost('nomor'));
        $templateProcessor->setValue('tanggal', date('d-m-Y', strtotime($this->request->getPost('tanggal'))));
        $templateProcessor->setValue('peminjam_nama', $this->request->getPost('peminjam_nama'));
        $templateProcessor->setValue('peminjam_nip', $this->request->getPost('peminjam_nip'));
        $templateProcessor->setValue('peminjam_pangkat', $this->request->getPost('peminjam_pangkat'));
        $templateProcessor->setValue('peminjam_jabatan', $this->request->getPost('peminjam_jabatan'));
        $templateProcessor->setValue('peminjam_unker', $this->request->getPost('peminjam_unker'));
        $templateProcessor->setValue('petugas_nama', $this->request->getPost('petugas_nama'));
        $templateProcessor->setValue('petugas_nip', $this->request->getPost('petugas_nip'));
        $templateProcessor->setValue('petugas_pangkat', $this->request->getPost('petugas_pangkat'));
        $templateProcessor->setValue('petugas_jabatan', $this->request->getPost('petugas_jabatan'));
        $templateProcessor->setValue('petugas_unker', $this->request->getPost('petugas_unker'));

        // isi tabel barang dipinjam
        if (!empty($barangDipinjam)) {
            $templateProcessor->cloneRow('no', count($barangDipinjam));

            foreach ($barangDipinjam as $i => $unit) {
                $row = $i + 1;
                $templateProcessor->setValue("no#{$row}", $row);
                $templateProcessor->setValue("nama_barang#{$row}", $barangMaster['nama_barang']);
                $templateProcessor->setValue("kode_unit#{$row}", $unit['kode_unit']);
                $templateProcessor->setValue("merk_unit#{$row}", $unit['merk'] ?? '-');
                $templateProcessor->setValue("kondisi_unit#{$row}", $unit['kondisi'] ?? '-');
            }
        }

        $filename = 'BAST_' . $peminjaman->id. '.docx';

        // output ke browser
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $templateProcessor->saveAs("php://output");
        exit;
    }


}
