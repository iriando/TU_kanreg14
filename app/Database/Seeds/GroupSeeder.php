<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'        => 'admin',
                'description' => 'Administrator, memiliki semua akses',
            ],
            [
                'name'        => 'petugas',
                'description' => 'Petugas/operator dengan akses terbatas',
            ],
        ];

        $this->db->table('auth_groups')->insertBatch($data);
    }
}
