<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ObatModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Obat extends BaseController
{
    protected $obatModel;

    public function __construct()
    {
        $this->obatModel = new ObatModel();
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
        $rules = [
            'kode_barang' => 'required|is_unique[obat.kode_barang]',
            'nama_barang' => 'required',
            'satuan'    => 'required',
            'jumlah'      => 'required|integer',
            'kedaluwarsa'      => 'required|date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->obatModel->insert([
            'kode_barang' => $this->request->getPost('kode_barang'),
            'nama_barang' => $this->request->getPost('nama_barang'),
            'satuan'    => $this->request->getPost('satuan'),
            'jumlah'      => $this->request->getPost('jumlah'),
            'kedaluwarsa'      => $this->request->getPost('kedaluwarsa'),
            'didistribusi'    => 0,
            'sisa'        => $this->request->getPost('jumlah'),
        ]);

        return redirect()->to('/obat')->with('message', 'Obat-obatan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $obat = $this->obatModel->find($id);
        if (!$obat) {
            throw new PageNotFoundException("Obat dengan ID $id tidak ditemukan");
        }

        return view('admin/obat/edit', ['obat' => $obat]);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $data['id'] = $id;
        $this->obatModel->setValidationRules([
            'kode_barang' => "required|is_unique[obat.kode_barang,id,{$id}]",
            'nama_barang' => 'required',
            'satuan'      => 'required',
            'jumlah'      => 'required|integer',
            'kedaluwarsa' => 'required|date',
        ]);

        if (!$this->obatModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->obatModel->errors());
        }

        return redirect()->to('/obat')->with('message', 'Obat berhasil diupdate');
    }

    public function delete($id)
    {
        $this->obatModel->delete($id);
        return redirect()->to('/obat')->with('message', 'obat berhasil dihapus');
    }
}
