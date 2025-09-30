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
        $peminjaman = $this->peminjamanModel->orderBy('id', 'DESC')->findAll();
        $sisaMap = [];
        foreach ($peminjaman as $p) {
            $transId = $p->transaksi_id ?? $p->id;
            if (! isset($sisaMap[$transId])) {
                $sisaMap[$transId] = $this->peminjamanModel->getSisaPinjam($transId);
            }
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
        $id           = $this->request->getPost('id');
        $data         = $this->peminjamanModel->find($id);
        $jumlahUnit   = $unitKembali ? count($unitKembali) : 0;
        $petugas      = user()->username;
        $tanggal_kembali = $this->request->getPost('tanggal_kembali');

        if (!empty($unitKembali) && $data) {

            if (strtotime($tanggal_kembali) < strtotime($data->tanggal_pinjam)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Tanggal kembali tidak boleh lebih awal dari tanggal pinjam!');
            }

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
            return redirect()->to('/peminjaman')->with('error', 'Data tidak ditemukan');
        }

        $data = [
            'nama_peminjam' => $this->request->getPost('nama_peminjam'),
            'tanggal_pinjam' => $this->request->getPost('tanggal_pinjam'),
        ];

        // Jika status = dikembalikan, update juga tanggal_kembali
        if ($peminjaman->status === 'dikembalikan') {
            $tanggal_kembali = $this->request->getPost('tanggal_kembali');

            // ✅ validasi: tanggal kembali >= tanggal pinjam
            if (strtotime($tanggal_kembali) < strtotime($data['tanggal_pinjam'])) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Tanggal kembali tidak boleh lebih awal dari tanggal pinjam!');
            }

            $data['tanggal_kembali'] = $tanggal_kembali;
        }

        $this->peminjamanModel->update($id, $data);

        return redirect()->to('/peminjaman')->with('success', 'Data berhasil diupdate');
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

        $unitModel   = new BarangUnitModel();
        $masterModel = new BarangMasterModel();

        // ambil data master barang
        $barangMaster = $masterModel->where('kode_barang', $peminjaman->kode_barang)->first();

        // ambil unit barang yg sedang dipinjam
        $barangDipinjam = $unitModel->where('kode_barang', $peminjaman->kode_barang)
            ->where('status', 'dipinjam')
            ->findAll();

        // ambil template dari database (default "BAST-peminjaman")
        $templateModel = new \App\Models\TemplateModel();
        $template      = $templateModel->where('nama', 'BAST-peminjaman')->first();

        if (!$template) {
            return redirect()->back()->with('error', 'Template BAST-peminjaman belum tersedia');
        }

        $templatePath = FCPATH . $template->file_path; // karena waktu upload kita simpan "templates/nama.docx"
        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'File template tidak ditemukan di server');
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // isi placeholder utama
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

        $filename = 'BAST_' . $peminjaman->id . '.docx';

        // output ke browser
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $templateProcessor->saveAs("php://output");
        exit;
    }
}
