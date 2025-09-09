<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ObatModel;
use App\Models\DistribusiObatModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Obat extends BaseController
{
    protected $obatModel;
    protected $distribusiobatModel;

    public function __construct()
    {
        $this->obatModel = new ObatModel();
        $this->distribusiobatModel = new DistribusiObatModel();
    }

    public function index()
    {
        $data['obat'] = $this->obatModel->findAll();
        return view('admin/obat/index', $data);
    }

    public function create()
    {
        return view('admin/obat/create');
    }

    public function store()
    {
        $data = [
            'kode_barang'  => $this->request->getPost('kode_barang'),
            'nama_barang'  => $this->request->getPost('nama_barang'),
            'satuan'       => $this->request->getPost('satuan'),
            'jumlah'       => (int) $this->request->getPost('jumlah'),
            'kedaluwarsa'  => $this->request->getPost('kedaluwarsa'),
            'didistribusi' => 0,
            'sisa'         => (int) $this->request->getPost('jumlah'),
        ];

        // validasi sesuai rule insert
        $this->obatModel->setValidationRules($this->obatModel->getValidationRules());

        if (! $this->obatModel->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $this->obatModel->errors());
        }

        $petugas = user()->username ?? 'system';

        // log distribusi awal
        $this->distribusiobatModel->insert([
            'kode_barang'        => $data['kode_barang'],
            'nama_barang'        => $data['nama_barang'],
            'jumlah'             => $data['jumlah'],
            'nama_penerima'      => '-',
            'tanggal_distribusi' => date('Y-m-d'),
            'petugas'            => $petugas,
            'keterangan'         => 'Tambah stok',
            'created_at'         => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/obat')->with('message', 'Obat-obatan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $obat = $this->obatModel->find($id);
        if (! $obat) {
            throw new PageNotFoundException("Obat dengan ID $id tidak ditemukan");
        }
        return view('admin/obat/edit', ['obat' => $obat]);
    }

    public function update($id)
    {
        $obatLama = $this->obatModel->find($id);
        if (! $obatLama) {
            return redirect()->to('/obat')->with('error', 'Data obat tidak ditemukan');
        }

        $jumlahBaru = (int) $this->request->getPost('jumlah');
        $jumlahLama = (int) $obatLama->jumlah;
        $selisih    = $jumlahBaru - $jumlahLama;

        $data = [
            'id'          => $id,
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'satuan'      => $this->request->getPost('satuan'),
            'jumlah'      => $jumlahBaru,
            'kedaluwarsa' => $this->request->getPost('kedaluwarsa'),
            'didistribusi'=> (int) $obatLama->didistribusi,
            'sisa'        => max(0, $jumlahBaru - (int) $obatLama->didistribusi),
        ];

        try {
            $this->obatModel->save($data);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kode barang sudah digunakan!');
        }

        $petugas = user()->username ?? 'system';

        if ($selisih > 0) {
            $this->distribusiobatModel->insert([
                'kode_barang'        => $data['kode_barang'],
                'nama_barang'        => $data['nama_barang'],
                'jumlah'             => $selisih,
                'nama_penerima'      => '-',
                'tanggal_distribusi' => date('Y-m-d'),
                'petugas'            => $petugas,
                'keterangan'         => 'Tambah stok',
                'created_at'         => date('Y-m-d H:i:s'),
            ]);
        }

        if ($selisih < 0) {
            $this->distribusiobatModel->insert([
                'kode_barang'        => $data['kode_barang'],
                'nama_barang'        => $data['nama_barang'],
                'jumlah'             => abs($selisih),
                'nama_penerima'      => '-',
                'tanggal_distribusi' => date('Y-m-d'),
                'petugas'            => $petugas,
                'keterangan'         => 'Koreksi stok (pengurangan)',
                'created_at'         => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to('/obat')->with('message', 'Obat berhasil diupdate');
    }



    public function delete($id)
    {
        $obat = $this->obatModel->find($id);

        if ($obat) {
            $petugas = user()->username ?? 'system';
            $this->distribusiobatModel->insert([
                'kode_barang'        => $obat->kode_barang,
                'nama_barang'        => $obat->nama_barang,
                'jumlah'             => $obat->jumlah,
                'nama_penerima'      => '-',
                'tanggal_distribusi' => date('Y-m-d'),
                'petugas'            => $petugas,
                'keterangan'         => 'Obat dihapus',
                'created_at'         => date('Y-m-d H:i:s'),
            ]);
        }

        $this->obatModel->delete($id);

        return redirect()->to('/obat')->with('message', 'Obat berhasil dihapus');
    }
}
