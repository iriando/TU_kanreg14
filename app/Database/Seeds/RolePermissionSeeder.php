<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Ambil role admin
        $admin = $db->table('auth_groups')
            ->where('name', 'admin')
            ->get()
            ->getRow();

        if (! $admin) {
            echo "Role admin belum ada.\n";
            return;
        }

        // Ambil semua permission
        $permissions = $db->table('auth_permissions')->get()->getResult();

        foreach ($permissions as $perm) {
            // hindari duplicate
            $exists = $db->table('auth_permissions_groups')
                ->where('group_id', $admin->id)
                ->where('permission_id', $perm->id)
                ->countAllResults();

            if (!$exists) {
                $db->table('auth_permissions_groups')->insert([
                    'group_id'      => $admin->id,
                    'permission_id' => $perm->id
                ]);
            }
        }

        echo "Semua permission sudah diassign ke role admin.\n";
    }
}
