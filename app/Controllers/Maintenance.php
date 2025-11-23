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
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $nama_petugas = $this->request->getPost('nama_petugas');
        $kode_barang  = $this->request->getPost('kode_barang');
        $barangunits  = $this->request->getPost('barangunit'); // array id unit
        $statusUnit   = $this->request->getPost('status_unit');
        $tanggal      = $this->request->getPost('tanggal');
        $keterangan   = $this->request->getPost('keterangan');
        
        // update status unit barang
        $this->unitModel
            ->whereIn('id', $barangunits)
            ->set(['status' => $statusUnit])
            ->update();

        // pengingat: checkbox (1 atau 0)
        $pengingat    = $this->request->getPost('pengingat') ? 1 : 0;
        $hari         = $pengingat ? $this->request->getPost('hari') : null;

        $tanggal_pengingat = null;
        if ($pengingat && $hari) {
            $tanggal_pengingat = date('Y-m-d H:i:s', strtotime($tanggal . ' + ' . $hari . ' days'));
        }

        $barang = $this->barangModel
            ->where('kode_barang', $kode_barang)
            ->first();

        foreach ($barangunits as $unitId) {
            $unit = $this->unitModel->find($unitId);

            $this->maintenanceModel->insert([
                'nama_petugas'      => $nama_petugas,
                'nama_barang'       => $barang['nama_barang'],
                'kode_barang'       => $kode_barang,
                'kode_unit'         => $unit['kode_unit'],
                'unit'              => $unit['merk'],
                'tanggal'           => $tanggal,
                'pengingat'         => $pengingat,
                'hari'              => $hari,
                'tanggal_pengingat' => $tanggal_pengingat,
                'keterangan'        => $keterangan,
                'status'            => 'Belum',
            ]);
        }

        return redirect()->to(site_url('maintenance'))->with('success', 'Data maintenance berhasil disimpan.');
    }

    public function edit($id)
    {
        $maintenance = $this->maintenanceModel->find($id);
        if (!$maintenance) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Data maintenance dengan ID $id tidak ditemukan");
        }

        // ambil semua barang master untuk dropdown
        $barang = $this->barangModel->orderBy('id', 'DESC')->findAll();
        // ambil semua unit barang
        $units  = $this->unitModel->orderBy('id', 'DESC')->findAll();

        return view('maintenance/edit', [
            'maintenance' => $maintenance,
            'barang'      => $barang,
            'units'       => $units,
        ]);
    }

    public function update($id)
    {
        $maintenance = $this->maintenanceModel->find($id);

        if (!$maintenance) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pemeliharaan tidak ditemukan');
        }

        $kodeBarang   = $this->request->getPost('kode_barang');
        // $barang       = $this->barangModel->where('kode_barang', $kodeBarang)->first();

        $data = [
            'nama_petugas'   => $this->request->getPost('nama_petugas'),
            'tanggal'  => $this->request->getPost('tanggal'),
        ];

        $this->maintenanceModel->update($id, $data);

        return redirect()->to('/maintenance')->with('success', 'Data peminjaman berhasil diperbarui');
    }

    // public function update($id)
    // {
    //     $validation = \Config\Services::validation();

    //     $rules = [
    //         'nama_petugas' => 'required',
    //         'tanggal'      => 'required',
    //     ];

    //     if (!$this->validate($rules)) {
    //         return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    //     }

    //     $nama_petugas = $this->request->getPost('nama_petugas');
    //     $kode_barang  = $this->request->getPost('kode_barang');
    //     $kode_unit    = $this->request->getPost('kode_unit');
    //     $tanggal      = $this->request->getPost('tanggal');
    //     $keterangan   = $this->request->getPost('keterangan');
    //     $pengingat    = $this->request->getPost('pengingat');
    //     $hari         = $this->request->getPost('hari') ?? null;

    //     $barang = $this->barangModel->where('kode_barang', $kode_barang)->first();
    //     $unit   = $this->unitModel->where('kode_unit', $kode_unit)->first();

    //     $this->maintenanceModel->update($id, [
    //         'nama_petugas' => $nama_petugas,
    //         'nama_barang'  => $barang['nama_barang'] ?? null,
    //         'kode_barang'  => $kode_barang,
    //         'kode_unit'    => $kode_unit,
    //         'unit'         => $unit['merk'] ?? null,
    //         'tanggal'      => $tanggal,
    //         'pengingat'    => $pengingat,
    //         'hari'         => $pengingat === 'Aktif' ? $hari : null,
    //         'keterangan'   => $keterangan,
    //     ]);

    //     return redirect()->to(site_url('maintenance'))->with('success', 'Data maintenance berhasil diperbarui.');
    // }


    public function delete($id)
    {
        $this->maintenanceModel->delete($id);
        return redirect()->to('/maintenance')->with('message', 'Log berhasil dihapus');
    }

    public function selesai($id)
    {
        $this->maintenanceModel->update($id, ['pengingat' => 0]);
        return redirect()->back()->with('success', 'Pengingat berhasil ditandai selesai.');
    }
}
