<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AtkModel;
use App\Models\DistribusiAtkModel;

class DistribusiAtk extends BaseController
{
    protected $distribusiModel;
    protected $atkModel;

    public function __construct()
    {
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
            'nama_penerima' => 'required',
            'kode_barang'   => 'required',
            'jumlah'        => 'required|integer',
            'tanggal_distribusi'=> 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->distribusiModel->createDistribusi($this->request->getPost(), user()->username);
        return redirect()->to('/distribusiatk')->with('message', 'Log Distribusi berhasil ditambahkan');
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
}
