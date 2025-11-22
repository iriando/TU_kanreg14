<?php

namespace App\Controllers;

use App\Models\BarangUnitModel;
use App\Models\PeminjamanModel;
use App\Models\PeminjamanDetailModel;
use App\Models\BarangMasterModel;
use App\Controllers\BaseController;
use PhpOffice\PhpWord\TemplateProcessor;

class Peminjaman extends BaseController
{
    protected $peminjamanModel;
    protected $peminjamanDetailModel;
    protected $barangModel;
    protected $unitModel;

    public function __construct()
    {
        $this->peminjamanModel       = new PeminjamanModel();
        $this->peminjamanDetailModel = new PeminjamanDetailModel();
        $this->barangModel           = new BarangMasterModel();
        $this->unitModel             = new BarangUnitModel();
    }

    public function index()
    {
        $peminjaman = $this->peminjamanModel->orderBy('id', 'DESC')->findAll();

        foreach ($peminjaman as &$p) {
            $p->sisa = $this->peminjamanModel->getSisaPinjam($p->transaksi_id);
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
        $unitDipilih    = $this->request->getPost('barangunit'); // array id unit
        $tanggalPinjam  = $this->request->getPost('tanggal_pinjam');
        $petugas        = user()->username;

        if (empty($unitDipilih)) {
            return redirect()->back()->with('error', 'Pilih minimal satu unit barang.');
        }

        // 1️⃣ Insert header log_peminjaman
        $peminjamanId = $this->peminjamanModel->insert([
            'nama_peminjam'   => $namaPeminjam,
            'kode_barang'     => $kodeBarang,
            'nama_barang'     => $barang['nama_barang'],
            'jumlah'          => count($unitDipilih),
            'tanggal_pinjam'  => $tanggalPinjam,
            'petugas_pinjam'  => $petugas,
            'status'          => 'pinjam',
        ], true);

        // 2️⃣ Update transaksi_id agar menunjuk dirinya sendiri
        $this->peminjamanModel->update($peminjamanId, ['transaksi_id' => $peminjamanId]);

        // 3️⃣ Insert detail untuk setiap unit
        foreach ($unitDipilih as $unitId) {
            $unit = $this->unitModel->find($unitId);
            if (!$unit) continue;

            $this->peminjamanDetailModel->insert([
                'peminjaman_id' => $peminjamanId,
                'kode_barang'   => $kodeBarang,
                'kode_unit'     => $unit['kode_unit'],
                'jumlah'        => 1,
                'status_unit'   => 'dipinjam',
                'tanggal_pinjam' => $tanggalPinjam,
            ]);

            // Update status unit → dipinjam
            $this->unitModel->update($unitId, ['status' => 'dipinjam']);
        }

        return redirect()->to('/peminjaman')->with('success', 'Peminjaman berhasil ditambahkan');
    }

    public function kembalikan()
    {
        $unitKembali      = $this->request->getPost('unit_kembali'); // array id_unit
        $peminjamanId     = $this->request->getPost('id');
        $tanggal_kembali  = $this->request->getPost('tanggal_kembali');
        $petugas          = user()->username;

        $peminjaman = $this->peminjamanModel->find($peminjamanId);
        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan.');
        }

        if (empty($unitKembali)) {
            return redirect()->back()->with('error', 'Tidak ada unit yang dikembalikan.');
        }

        if (strtotime($tanggal_kembali) < strtotime($peminjaman->tanggal_pinjam)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tanggal kembali tidak boleh lebih awal dari tanggal pinjam!');
        }
        // 1️⃣ Update status setiap unit → tersedia
        $this->unitModel->setTersedia($unitKembali);

        // 2️⃣ Tambah log detail pengembalian
        foreach ($unitKembali as $unitId) {
            $unit = $this->unitModel->find($unitId);

            // Update log detail
            $this->peminjamanDetailModel
                ->where('peminjaman_id', $peminjamanId)
                ->where('kode_unit', $unit['kode_unit'])
                ->set([
                    'status_unit'    => 'dikembalikan',
                    'tanggal_kembali'=> $tanggal_kembali,
                ])
                ->update();
        }

        // 3️⃣ Tambahkan catatan pengembalian di header
        $this->peminjamanModel->insert([
            'transaksi_id'       => $peminjaman->transaksi_id ?? $peminjaman->id,
            'nama_peminjam'      => $peminjaman->nama_peminjam,
            'kode_barang'        => $peminjaman->kode_barang,
            'nama_barang'        => $peminjaman->nama_barang,
            'jumlah'             => count($unitKembali),
            'tanggal_pinjam'     => $peminjaman->tanggal_pinjam,
            'tanggal_kembali'    => $tanggal_kembali,
            'petugas_pinjam'     => $peminjaman->petugas_pinjam,
            'petugas_kembalikan' => $petugas,
            'status'             => 'dikembalikan',
        ]);

        return redirect()->to('/peminjaman')->with('success', 'Barang berhasil dikembalikan');
    }

    public function getUnitDipinjam()
    {
        $kodeBarang = $this->request->getGet('kode_barang');
        $units = $this->unitModel
            ->where('kode_barang', $kodeBarang)
            ->where('status', 'dipinjam')
            ->findAll();

        return $this->response->setJSON($units);
    }

    public function delete($id)
    {
        $this->peminjamanModel->deletePeminjaman($id);
        $this->peminjamanDetailModel->where('peminjaman_id', $id)->delete();
        return redirect()->to('/peminjaman')->with('success', 'Peminjaman berhasil dihapus');
    }
}
