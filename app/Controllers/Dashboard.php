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
        $notifikasi = $this->maintenanceModel
            ->where('pengingat', 1) // hanya yg aktif
            ->where('tanggal_pengingat <=', date('Y-m-d H:i:s'))
            ->groupStart() // mulai group
                ->where('status', 'Hari ini')
                ->orWhere('status', 'Lewat')
            ->groupEnd()   // tutup group
            ->findAll();

        $data = [
            'title'         => 'Dashboard',
            'totalDipinjam' => $totalDipinjam,
            'notif' => $notifikasi,
        ];

        return view('dashboard', $data);
    }
}