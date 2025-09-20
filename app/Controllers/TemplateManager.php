<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TemplateModel;

class TemplateManager extends BaseController
{
    protected $templateModel;

    public function __construct()
    {
        $this->templateModel  = new TemplateModel();
    }

    public function index()
    {
        $data['templates'] = $this->templateModel->findAll();
        return view('admin/template/index', $data);
    }

    public function create()
    {
        return view('admin/template/create');
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'nama'       => 'required|min_length[3]|max_length[50]',
            'file_path'  => 'uploaded[file_path]|max_size[file_path,2048]|ext_in[file_path,doc,docx,pdf]',
            'keterangan' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $namaTemplate = $this->request->getPost('nama') ?? 'BAST-peminjaman';

        $file = $this->request->getFile('file_path');
        $ext  = $file->getClientExtension();
        $newName = url_title($namaTemplate, '-', true) . '.' . $ext;

        // pastikan folder ada
        $uploadPath = FCPATH . 'templates';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $file->move($uploadPath, $newName, true);

        $this->templateModel->insert([
            'nama'       => $namaTemplate,
            'file_path'  => 'templates/' . $newName,
            'keterangan' => $this->request->getPost('keterangan'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/template')->with('success', 'Template berhasil disimpan!');
    }

    public function delete($id)
    {
        $this->templateModel->delete($id);
        return redirect()->to('/template')->with('success', 'Template berhasil dihapus');
    }
}
