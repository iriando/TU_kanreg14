<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AtkModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Atk extends BaseController
{
    protected $atkModel;

    public function __construct()
    {
        $this->atkModel = new AtkModel();
    }

    public function index()
    {
        $data['atk'] = $this->atkModel->findAll();
        return view('admin/atk/index', $data);
    }

    public function create()
    {
        return view('admin/atk/create');
    }

    public function store()
    {
        $rules = [
            'kode_barang' => 'required|is_unique[atk.kode_barang]',
            'nama_barang' => 'required',
            'satuan'    => 'required',
            'jumlah'      => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->atkModel->insert([
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'satuan'    => $this->request->getPost('satuan'),
            'jumlah'      => $this->request->getPost('jumlah'),
            'didistribusi'    => 0,
            'sisa'        => $this->request->getPost('jumlah'),
        ]);

        return redirect()->to('/atk')->with('message', 'ATK/ATRK berhasil ditambahkan');
    }

    public function edit($id)
    {
        $atk = $this->atkModel->find($id);
        if (!$atk) {
            throw new PageNotFoundException("ATK dengan ID $id tidak ditemukan");
        }

        return view('admin/atk/edit', ['atk' => $atk]);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;
        $this->atkModel->setValidationRules([
            'kode_barang' => "required|is_unique[atk.kode_barang,id,{$id}]",
            'nama_barang' => 'required',
            'satuan'      => 'required',
            'jumlah'      => 'required|integer',
        ]);

        if (!$this->atkModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->atkModel->errors());
        }

        return redirect()->to('/atk')->with('message', 'ATK berhasil diupdate');
    }

    public function delete($id)
    {
        $this->atkModel->delete($id);
        return redirect()->to('/atk')->with('message', 'ATK berhasil dihapus');
    }
}
