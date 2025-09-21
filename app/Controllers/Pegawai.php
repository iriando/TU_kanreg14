<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PegawaiModel;
use CodeIgniter\HTTP\ResponseInterface;

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
            'unit' => $this->request->getPost('unit'),
        ];
        $success = $this->pegawaiModel->insert($data);
        if ($success) {
            return redirect()->to('/pegawai')->with('success', 'Pegawai berhasil ditambahkan');
        }
        return view('admin/pegawai/create');
    }
}
