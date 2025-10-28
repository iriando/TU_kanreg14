<?php

namespace App\Controllers;

use App\Models\BarangUnitModel;
use App\Models\BarangMasterModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Unit extends BaseController
{
    protected $barangMaster;
    protected $barangUnit;

    public function __construct()
    {
        $this->barangMaster = new BarangMasterModel();
        $this->barangUnit   = new BarangUnitModel();
    }
    
    public function index($kode_barang)
    {
        $barangMaster = $this->barangMaster->where('kode_barang', $kode_barang)->first();
        if (!$barangMaster) {
            return redirect()->to('/barang')->with('error', 'Barang tidak ditemukan');
        }
        $units = $this->barangUnit->getByKodeBarang($kode_barang);
        $data = [
            'barang' => $barangMaster,
            'units'  => $units
        ];
        return view('admin/barang/view_unit', $data);
    }
}
