<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\BarangMasterModel;
use App\Models\BarangUnitModel;

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

        if (!empty($unitKembali) && $data) {
            // Update status unit jadi tersedia
            $this->unitModel->setTersedia($unitKembali);

            // Insert log pengembalian
            $this->peminjamanModel->insert([
                'transaksi_id'        => $dat->transaksi_id ?? $data->id,
                'nama_peminjam'       => $data->nama_peminjam,
                'kode_barang'         => $data->kode_barang,
                'nama_barang'         => $data->nama_barang,
                'jumlah'              => $jumlahUnit,
                'tanggal_pinjam'      => $data->tanggal_pinjam,
                'tanggal_kembali'     => date('Y-m-d H:i:s'),
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
        // ambil data peminjaman berdasarkan id
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
        return redirect()->to('/peminjaman')->with('message', 'Peminjaman berhasil dihapus');
    }

}
