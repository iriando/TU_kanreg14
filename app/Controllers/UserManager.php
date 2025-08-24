<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\GroupModel;
use Config\Database;

class UserManager extends BaseController
{
    protected $userModel;
    protected $groupModel;
    protected $db;

    public function __construct()
    {
        $this->userModel  = new UserModel();
        $this->groupModel = new GroupModel();
        $this->db         = Database::connect();
    }

    public function index()
    {
        $users = $this->userModel
            ->select('users.*, auth_groups.name as role')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left')
            ->findAll();

        return view('admin/users/index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $roles = $this->groupModel->findAll();

        return view('admin/users/create', [
            'roles' => $roles
        ]);
    }

    public function store()
    {
        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = new User([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'active'   => 1,
        ]);

        $this->userModel->save($user);

        $userId = $this->userModel->getInsertID();
        $roleId = $this->request->getPost('role');

        $this->db->table('auth_groups_users')->insert([
            'user_id'  => $userId,
            'group_id' => $roleId
        ]);

        return redirect()->to('/usermanager/users')->with('message', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = $this->userModel
            ->select('users.*, auth_groups_users.group_id')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
            ->find($id);

        if (! $user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("User dengan ID $id tidak ditemukan");
        }

        $roles = $this->groupModel->findAll();

        return view('admin/users/edit', [
            'user'  => $user,
            'roles' => $roles
        ]);
    }

    public function update($id)
    {
        $rules = [
            'username' => 'required|min_length[3]',
            'email'    => 'required|valid_email',
            'role'     => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil user lama
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('/usermanager/users')->with('error', 'User tidak ditemukan');
        }

        // Update data user hanya jika ada perubahan
        $dataUser = [];
        if ($user->username !== $this->request->getPost('username')) {
            $dataUser['username'] = $this->request->getPost('username');
        }
        if ($user->email !== $this->request->getPost('email')) {
            $dataUser['email'] = $this->request->getPost('email');
        }
        if ($this->request->getPost('password')) {
            $dataUser['password'] = $this->request->getPost('password'); // otomatis hash
        }

        if (!empty($dataUser)) {
            $this->userModel->update($id, $dataUser);
        }

        // Update role (selalu lakukan)
        $roleId = $this->request->getPost('role');
        $this->db->table('auth_groups_users')->where('user_id', $id)->delete();
        $this->db->table('auth_groups_users')->insert([
            'user_id'  => $id,
            'group_id' => $roleId
        ]);

        return redirect()->to('/usermanager/users')->with('message', 'User berhasil diupdate');
    }


    public function delete($id)
    {
        $this->db->table('auth_groups_users')->where('user_id', $id)->delete();
        $this->db->table('users')->where('id', $id)->delete();
        // $this->userModel->delete($id);

        return redirect()->to('/usermanager/users')->with('message', 'User berhasil dihapus');
    }
}
