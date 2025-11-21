<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        $permissions = [
            ['name' => 'user.view',   'description' => 'Melihat daftar user'],
            ['name' => 'user.create', 'description' => 'Menambahkan user'],
            ['name' => 'user.edit',   'description' => 'Mengedit user'],
            ['name' => 'user.delete', 'description' => 'Menghapus user'],

            ['name' => 'role.view',   'description' => 'Melihat daftar role'],
            ['name' => 'role.create', 'description' => 'Menambahkan role'],
            ['name' => 'role.edit',   'description' => 'Mengedit role'],
            ['name' => 'role.delete', 'description' => 'Menghapus role'],

            ['name' => 'permission.view',   'description' => 'Melihat daftar permission'],
            ['name' => 'permission.create', 'description' => 'Menambahkan permission'],
            ['name' => 'permission.edit',   'description' => 'Mengedit permission'],
            ['name' => 'permission.delete', 'description' => 'Menghapus permission'],
        ];

        foreach ($permissions as $p) {
            // hindari duplicate
            if (! $db->table('auth_permissions')->where('name', $p['name'])->countAllResults()) {
                $db->table('auth_permissions')->insert($p);
            }
        }

        echo "Permission dasar berhasil dibuat.\n";
    }
}
