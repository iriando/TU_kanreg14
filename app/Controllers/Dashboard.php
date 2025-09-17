<?php

namespace App\Controllers;

use App\Models\BarangUnitModel;
use App\Models\BarangMasterModel;

class Dashboard extends BaseController
{
    protected $barangMaster;
    protected $barangUnit;
    
    public function __construct()
    {
        $this->barangMaster = new BarangMasterModel();
        $this->barangUnit   = new BarangUnitModel();
    }

    public function index()
    {
        $barang = $this->barangMaster->getWithUnits();

        // hitung total semua yg dipinjam
        $totalDipinjam = array_sum(array_column($barang, 'total_dipinjam'));

        $data = [
            'title'         => 'Dashboard',
            'totalDipinjam' => $totalDipinjam,
        ];

        return view('dashboard', $data);
    }
}