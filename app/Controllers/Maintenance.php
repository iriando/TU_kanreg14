<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BarangModel;

class Maintenance extends BaseController
{
    protected $barangModel;
    protected $peminjamanModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
    }
    public function index()
    {
        $barang = $this->barangModel->findAll();

        return view('admin/barang/index', [
            'barang' => $barang,
        ]);
    }
}
