<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\PermissionModel;

class RoleManager extends BaseController
{
    protected $groupModel;
    protected $permissionModel;
    protected $db;
     protected $authorize;

    public function __construct()
    {
        $this->groupModel       = new GroupModel();
        $this->permissionModel  = new PermissionModel();
        $this->db               = \Config\Database::connect();
        $this->authorize = service('authorization');
    }

    public function index()
    {
        $roles = $this->groupModel->findAll();

        return view('admin/roles/index', [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        return view('admin/roles/create');
    }

    public function store()
    {
        $this->groupModel->insert([
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/roles')->with('success', 'Role berhasil dibuat.');
    }

    public function edit($id)
    {
        $role = $this->groupModel->find($id);
        $permissions = $this->permissionModel->findAll();

        // Ambil permission role ini
        $rolePermission = $this->db
            ->table('auth_permissions_groups')
            ->where('group_id', $id)
            ->get()->getResultArray();

        $rolePermission = array_column($rolePermission, 'permission_id');

        return view('admin/roles/edit', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermission' => $rolePermission,
        ]);
    }

    public function update($id)
    {
        // Update nama & deskripsi role
        $this->groupModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        // Update permission
        $selectedPermissions = $this->request->getPost('permissions') ?? [];

        $builder = $this->db->table('auth_permissions_groups');
        $builder->where('group_id', $id)->delete();

        foreach ($selectedPermissions as $pid) {
            $builder->insert([
                'group_id' => $id,
                'permission_id' => $pid,
            ]);
        }

        return redirect()->to('/admin/roles')->with('success', 'Role berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->groupModel->delete($id);
        return redirect()->to('/admin/roles')->with('danger', 'Role berhasil dihapus.');
    }

    public function permissions($groupId)
    {
        $role = $this->groupModel->find($groupId);
        $permissions = $this->permissionModel->findAll();

        // AMBIL DATA PERMISSION YANG DIMILIKI ROLE (QUERY MANUAL)
        $assigned = $this->db->table('auth_permissions_groups')
            ->where('group_id', $groupId)
            ->get()
            ->getResultArray();

        $assignedIds = array_column($assigned, 'permission_id');

        return view('admin/roles/permissions', [
            'role'        => $role,
            'permissions' => $permissions,
            'assignedIds' => $assignedIds,
        ]);
    }

    // UPDATE PERMISSION UNTUK ROLE
    public function updatePermissions($groupId)
    {
        $selected = $this->request->getPost('permissions') ?? [];

        // DELETE ALL PERMISSION UNTUK ROLE
        $this->db->table('auth_permissions_groups')
            ->where('group_id', $groupId)
            ->delete();

        // INSERT PERMISSION YANG DIPILIH
        foreach ($selected as $permId) {
            $this->db->table('auth_permissions_groups')->insert([
                'group_id'      => $groupId,
                'permission_id' => $permId,
            ]);
        }

        return redirect()->to('/roles')->with('success', 'Permission role berhasil diperbarui.');
    }

}
