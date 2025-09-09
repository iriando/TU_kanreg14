<?php

namespace App\Controllers;

use Config\Database;
use App\Models\AtkModel;
use App\Models\DistribusiAtkModel;
use App\Controllers\BaseController;

class DistribusiAtk extends BaseController
{
    protected $distribusiModel;
    protected $atkModel;
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->distribusiModel = new DistribusiAtkModel();
        $this->atkModel = new AtkModel();
    }

    public function index()
    {
        $distribusi = $this->distribusiModel->findall();

        return view('distribusiatk/index', [
            'distribusi' => $distribusi
        ]);
    }

    public function create()
    {
        $atk = $this->atkModel->findAll();
        return view('distribusiatk/create', ['atk' => $atk]);
    }

    
    public function store()
    {
        $rules = [
            'nama_penerima'      => 'required',
            'tanggal_distribusi' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $kodeBarangArr = $this->request->getPost('kode_barang'); // array
        $jumlahArr     = $this->request->getPost('jumlah');      // array
        $namaPenerima  = $this->request->getPost('nama_penerima');
        $tanggal       = $this->request->getPost('tanggal_distribusi');
        $keterangan    = $this->request->getPost('keterangan');
        $petugas       = user()->username;

        $this->db->transStart();

        foreach ($kodeBarangArr as $i => $kode) {
            $jumlah = (int) ($jumlahArr[$i] ?? 0);
            if ($jumlah < 1) continue; // skip kalau kosong

            $atk = $this->atkModel->where('kode_barang', $kode)->get()->getRow();
            if (! $atk) {
                $this->db->transRollback();
                return redirect()->back()->withInput()->with('errors', ['kode_barang' => "ATK dengan kode $kode tidak ditemukan"]);
            }

            // simpan ke tabel distribusi_atk
            $this->distribusiModel->insert([
                'kode_barang'        => $kode,
                'nama_barang'        => $atk->nama_barang,
                'jumlah'             => $jumlah,
                'nama_penerima'      => $namaPenerima,
                'tanggal_distribusi' => $tanggal,
                'petugas'            => $petugas,
                'keterangan'         => $keterangan,
                'created_at'         => date('Y-m-d H:i:s'),
            ]);

            // update stok ATK
            $this->recalcAtk($kode);
        }

        $this->db->transComplete();

        return redirect()->to('/distribusiatk')->with('message', 'Distribusi ATK berhasil ditambahkan');
    }


    public function edit($id)
    {
        $distribusi = $this->distribusiModel->getById($id);

        if (!$distribusi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Distribusi dengan ID $id tidak ditemukan");
        }

        $atk = $this->atkModel->getAll();
        return view('distribusiatk/edit', [
            'distribusi' => $distribusi,
            'atk'     => $atk
        ]);
    }

    public function update($id)
    {
        $rules = [
            'nama_penerima' => 'required',
            'kode_barang'   => 'required',
            'jumlah'        => 'required|integer',
            'tanggal_distribusi'=> 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $this->distribusiModel->updateDistribusi($id, $this->request->getPost(), user()->username);
        return redirect()->to('/distribusiatk')->with('message', 'Distribusi ATK/ATRK berhasil diupdate');
    }

    public function delete($id)
    {
        $this->distribusiModel->deleteDistribusi($id);
        return redirect()->to('/distribusiatk')->with('success', 'Data distribusi berhasil dihapus');
    }

    private function recalcAtk(string $kodeBarang): void
    {
        $atk = $this->atkModel->where('kode_barang', $kodeBarang)->get()->getRow();
        if (! $atk) return;

        $didistribusi = (int) ($this->distribusiModel
            ->selectSum('jumlah')
            ->where('kode_barang', $kodeBarang)
            ->get()->getRow()->jumlah ?? 0);

        $sisa = (int) $atk->jumlah - $didistribusi;

        // update stok
        $this->atkModel->set([
            'didistribusi' => $didistribusi,
            'sisa'         => $sisa,
            'updated_at'   => date('Y-m-d H:i:s'),
        ])->where('kode_barang', $kodeBarang)->update();
    }
}
