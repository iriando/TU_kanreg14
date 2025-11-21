<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Pastikan role admin ada
        $group = $db->table('auth_groups')
            ->where('name', 'admin')
            ->get()
            ->getRow();

        if (! $group) {
            // buat role admin dulu
            $db->table('auth_groups')->insert([
                'name'        => 'admin',
                'description' => 'Super Administrator'
            ]);
            $groupId = $db->insertID();
        } else {
            $groupId = $group->id;
        }

        // Buat user entity
        $user = new User([
            'email'    => 'admin@bkn.go.id',
            'username' => 'superadmin',
            'password' => 'admin123',
            'active'   => 1,
        ]);

        $userModel = new UserModel();

        // Simpan user
        $userModel->save($user);
        $userId = $userModel->getInsertID();

        // Assign ke group admin
        $db->table('auth_groups_users')->insert([
            'user_id'  => $userId,
            'group_id' => $groupId
        ]);

        echo "SuperAdmin berhasil dibuat!\n";
    }
}
