<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\PeminjamanModel;

class Barang extends BaseController
{
    protected $barangModel;
    protected $peminjamanModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->peminjamanModel = new PeminjamanModel();
    }

    public function index()
    {
        $barang = $this->barangModel->findAll();

        return view('admin/barang/index', [
            'barang' => $barang,
        ]);
    }

    public function create()
    {
        return view('admin/barang/create');
    }

    public function store()
    {
        $rules = [
            'kode_barang' => 'required|is_unique[barang.kode_barang]',
            'nama_barang' => 'required',
            'kategori'    => 'required',
            'jumlah'      => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->barangModel->insert([
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $this->request->getPost('kategori'),
            'jumlah'      => $this->request->getPost('jumlah'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'dipinjam'    => 0,
            'sisa'        => $this->request->getPost('jumlah'),
        ]);

        return redirect()->to('/barang')->with('message', 'Barang berhasil ditambahkan');
    }

    public function edit($id)
    {
        $barang = $this->barangModel->find($id);
        if (!$barang) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Barang tidak ditemukan');
        }
        return view('admin/barang/edit', ['barang' => $barang]);
    }

    public function update($id)
    {
        $rules = [
            'nama_barang' => 'required',
            'kategori'    => 'required',
            'jumlah'      => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $barang = $this->barangModel->find($id);
        if (!$barang) {
            return redirect()->to('/barang')->with('error', 'Barang tidak ditemukan');
        }

        $this->barangModel->update($id, [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $this->request->getPost('kategori'),
            'jumlah'      => $this->request->getPost('jumlah'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'sisa'        => $this->request->getPost('jumlah') - $barang['dipinjam'],
        ]);

        return redirect()->to('/barang')->with('message', 'Barang berhasil diupdate');
    }

    public function delete($id)
    {
        $this->barangModel->delete($id);
        return redirect()->to('/barang')->with('message', 'Barang berhasil dihapus');
    }
}
