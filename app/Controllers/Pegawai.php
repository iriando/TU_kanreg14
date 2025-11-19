<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PegawaiModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Exceptions\PageNotFoundException;

class Pegawai extends BaseController
{
    protected $pegawaiModel;

    public function __construct()
    {
        $this->pegawaiModel = new PegawaiModel();
    }

    public function index()
    {
        $data['pegawai'] = $this->pegawaiModel->findAll();
        return view('admin/pegawai/index', $data);
    }

    public function create()
    {
        return view('admin/pegawai/create');
    }

    public function store()
    {
        $data = [
            'nama' => $this->request->getPost('nama'),
            'gender' => $this->request->getPost('gender'),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
            'unit' => $this->request->getPost('unit'),
        ];
        $success = $this->pegawaiModel->insert($data);
        if ($success) {
            return redirect()->to('/pegawai')->with('success', 'Pegawai berhasil ditambahkan');
        }
        return view('admin/pegawai/create');
    }

    public function edit($id)
    {
        $pegawai = $this->pegawaiModel->find($id);
        if (! $pegawai) {
            throw new PageNotFoundException("pegawai dengan ID $id tidak ditemukan");
        }
        return view('admin/pegawai/edit', ['pegawai' => $pegawai]);
    }

    public function update($id)
    {
        $pegawai = $this->pegawaiModel->find($id);
        if (!$pegawai) {
            return redirect()->to('/pegawai')->with('error', 'Pegawai tidak ditemukan');
        }
        $data = [
            'nama'             => $this->request->getPost('nama'),
            'gender'           => $this->request->getPost('gender'),
            'tanggal_lahir'    => $this->request->getPost('tanggal_lahir'),
            'unit'             => $this->request->getPost('unit'),
        ];
        if ($this->pegawaiModel->update($id, $data)) {
            return redirect()->to('/pegawai')->with('success', 'data berhasil diperbarui');
        }
        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data');
    }
}
