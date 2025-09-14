<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\BarangUnitModel;
use App\Models\MaintenanceModel;
use App\Models\BarangMasterModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Maintenance extends BaseController
{
    protected $maintenanceModel;
    protected $barangModel;
    protected $unitModel;

    public function __construct()
    {
        $this->maintenanceModel = new MaintenanceModel();
        $this->barangModel      = new BarangMasterModel();
        $this->unitModel        = new BarangUnitModel();
    }
    public function index()
    {
        $maintenance = $this->maintenanceModel->orderBy('id', 'DESC')->findAll();
        return view('maintenance/index', ['maintenance' => $maintenance]);
    }

    public function create()
    {
        $data['barang'] = $this->barangModel->orderBy('id', 'DESC')->findAll();
        return view('maintenance/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'nama_petugas' => 'required',
            'kode_barang'  => 'required',
            'barangunit'   => 'required',
            'tanggal'      => 'required',
            'keterangan'   => 'required',
            'pengingat'    => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        $nama_petugas = $this->request->getPost('nama_petugas');
        $kode_barang  = $this->request->getPost('kode_barang');
        $barangunits  = $this->request->getPost('barangunit'); // array id unit
        $tanggal      = $this->request->getPost('tanggal');
        $keterangan   = $this->request->getPost('keterangan');
        $pengingat    = $this->request->getPost('pengingat');
        $hari         = $this->request->getPost('hari') ?? null;

        // ambil data barang utama
        $barang = $this->barangModel
            ->where('kode_barang', $kode_barang)
            ->first();

        // simpan log untuk setiap unit yang dipilih
        foreach ($barangunits as $unitId) {
            $unit = $this->unitModel->find($unitId);
            $this->maintenanceModel->insert([
                'nama_petugas' => $nama_petugas,
                'nama_barang'  => $barang['nama_barang'],
                'kode_barang'  => $kode_barang,
                'kode_unit'    => $unit['kode_unit'],
                'unit'         => $unit['merk'],
                'tanggal'      => $tanggal,
                'pengingat'    => $pengingat,
                'hari'         => $pengingat === 'Aktif' ? $hari : null,
                'keterangan'   => $keterangan,
            ]);
        }

        return redirect()->to(site_url('maintenance'))->with('success', 'Data maintenance berhasil disimpan.');
    }

    public function delete($id)
    {
        $this->maintenanceModel->delete($id);
        return redirect()->to('/maintenance')->with('message', 'Log berhasil dihapus');
    }
}
