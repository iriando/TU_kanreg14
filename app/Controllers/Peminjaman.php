<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\BarangModel;

class Peminjaman extends BaseController
{
    protected $peminjamanModel;
    protected $barangModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->barangModel     = new BarangModel();
    }

    public function index()
    {
        $peminjaman = $this->peminjamanModel->getAllWithBarang();
        return view('peminjaman/index', [
            'peminjaman' => $peminjaman
        ]);
    }

    public function create()
    {
        $barang = $this->barangModel->getAll();
        return view('peminjaman/create', ['barang' => $barang]);
    }

    public function store()
    {
        $rules = [
            'nama_peminjam' => 'required',
            'kode_barang'   => 'required',
            'jumlah'        => 'required|integer',
            'tanggal_pinjam'=> 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->peminjamanModel->createPeminjaman($this->request->getPost(), user()->username);
        return redirect()->to('/peminjaman')->with('message', 'Peminjaman berhasil ditambahkan');
    }

    public function edit($id)
    {
        $peminjaman = $this->peminjamanModel->getById($id);
        if (!$peminjaman) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Peminjaman tidak ditemukan');
        }

        $barang = $this->barangModel->getAll();
        return view('peminjaman/edit', [
            'peminjaman' => $peminjaman,
            'barang'     => $barang
        ]);
    }

    public function update($id)
    {
        $rules = [
            'nama_peminjam' => 'required',
            'kode_barang'   => 'required',
            'jumlah'        => 'required|integer',
            'tanggal_pinjam'=> 'required|valid_date',
            'status'        => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->peminjamanModel->updatePeminjaman($id, $this->request->getPost(), user()->username);
        return redirect()->to('/peminjaman')->with('message', 'Peminjaman berhasil diupdate');
    }

    public function delete($id)
    {
        $this->peminjamanModel->deletePeminjaman($id);
        return redirect()->to('/peminjaman')->with('message', 'Peminjaman berhasil dihapus');
    }

    public function dikembalikan($id)
    {
        $this->peminjamanModel->setKembali($id, user()->username);
        return redirect()->to('/peminjaman')->with('message', 'Status peminjaman diupdate menjadi Kembali');
    }

    public function formKembali($id)
    {
        $peminjaman = $this->peminjamanModel->getById($id);
        if (!$peminjaman) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Peminjaman tidak ditemukan');
        }

        return view('peminjaman/form_kembali', [
            'peminjaman' => $peminjaman
        ]);
    }

    public function prosesKembali($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);

        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan');
        }

        $barang = $this->barangModel
            ->where('kode_barang', $peminjaman->kode_barang)
            ->first();

        if (!$barang) {
            return redirect()->back()->with('error', 'Data barang tidak ditemukan');
        }

        $jumlahKembali = $this->request->getPost('jumlah_kembali');

        // validasi
        if ($jumlahKembali > $peminjaman->jumlah) {
            return redirect()->back()->with('error', 'Jumlah kembali melebihi jumlah yang dipinjam');
        }

        $sisaPinjam = $peminjaman->jumlah - $jumlahKembali;

        // update stok barang
        $this->barangModel->update($barang['id'], [
            'dipinjam' => $barang['dipinjam'] - $jumlahKembali,
            'sisa'     => $barang['sisa'] + $jumlahKembali,
        ]);

        // masukkan riwayat baru
        $this->peminjamanModel->insert([
            'kode_barang'    => $peminjaman->kode_barang,
            'nama_peminjam'       => $peminjaman->nama_peminjam,
            'jumlah'         => $jumlahKembali,
            'jumlah'         => $jumlahKembali,
            'status'         => 'Dikembalikan',
            'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
            'tanggal_kembali'=> date('Y-m-d H:i:s'),
            'petugas'         => user()->username,
        ]);

        if ($sisaPinjam > 0) {
            $this->peminjamanModel->update($id, [
                'jumlah' => $sisaPinjam,
                'status' => 'Dipinjam',
                'petugas'  => user()->username,
            ]);
        } else {
            $this->peminjamanModel->delete($id);
        }

        return redirect()->to('/peminjaman')->with('success', 'Pengembalian berhasil diproses');
    }

}
