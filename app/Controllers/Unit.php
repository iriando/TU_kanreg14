<?php

namespace App\Controllers;

use App\Models\BarangUnitModel;
use App\Models\BarangMasterModel;
use App\Controllers\BaseController;

class Unit extends BaseController
{
    protected $barangMaster;
    protected $barangUnit;

    public function __construct()
    {
        $this->barangMaster = new BarangMasterModel();
        $this->barangUnit   = new BarangUnitModel();
    }
    
    public function view($id)
    {
        // Ambil data unit berdasarkan ID
        $unit = $this->barangUnit->where('slug', $id)->first();
        if (!$unit) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Unit tidak ditemukan.');
        }

        // Ambil data barang dari kode_barang
        $barang = $this->barangMaster
            ->where('kode_barang', $unit['kode_barang'])
            ->first();

        $logPemeliharaanModel = new \App\Models\MaintenanceModel();
        $logPeminjamanDetailModel = new \App\Models\PeminjamanDetailModel();

        $log_pemeliharaan = $logPemeliharaanModel
            ->where('kode_unit', $unit['kode_unit'])
            ->orderBy('tanggal', 'DESC')
            ->findAll();

        $log_peminjaman = $logPeminjamanDetailModel
            ->select('
                log_peminjaman_detail.*,
                log_peminjaman.nama_peminjam,
                log_peminjaman.petugas_pinjam,
                log_peminjaman.petugas_kembalikan
            ')
            ->join('log_peminjaman', 'log_peminjaman.id = log_peminjaman_detail.peminjaman_id')
            ->where('log_peminjaman_detail.kode_unit', $unit['kode_unit'])
            ->orderBy('log_peminjaman_detail.tanggal_pinjam', 'DESC')
            ->findAll();

        // Kirim data ke view
        return view('admin/barang/view_unit', [
            'unit' => $unit,
            'barang' => $barang,
            'log_pemeliharaan' => $log_pemeliharaan,
            'log_peminjaman' => $log_peminjaman,
        ]);
    }
}
