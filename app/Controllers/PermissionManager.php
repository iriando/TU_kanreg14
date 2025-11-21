<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Myth\Auth\Models\PermissionModel;

class PermissionManager extends BaseController
{
    protected $permissionModel;

    public function __construct()
    {
        $this->permissionModel = new PermissionModel();
    }

    public function index()
    {
        $permissions = $this->permissionModel->findAll();
        return view('admin/permissions/index', [
            'permissions' => $permissions,
        ]);
    }

    public function create()
    {
        return view('admin/permissions/create');
    }

    public function store()
    {
        $this->permissionModel->insert([
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/permissions')->with('success', 'Permission berhasil dibuat.');
    }

    public function edit($id)
    {
        $permission = $this->permissionModel->find($id);

        if (!$permission) {
            return redirect()->back()->with('error', 'Permission tidak ditemukan.');
        }

        return view('admin/permissions/edit', [
            'permission' => $permission,
        ]);
    }

    public function update($id)
    {
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');

        $this->permissionModel->update($id, [
            'name' => $name,
            'description' => $description
        ]);

        return redirect()->to('admin/permissions')->with('success', 'Permission berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->permissionModel->delete($id);
        return redirect()->to('/admin/permissions')->with('danger', 'Permission dihapus.');
    }
}
