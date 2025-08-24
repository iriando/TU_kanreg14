<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Myth\Auth\Entities\User;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $user = new User([
            'email'    => 'admin@bkn.go.id',
            'username' => 'superadmin',
            'password' => 'admin123',
            'active'   => 1,
        ]);

        $users = model('UserModel');
        $users->withGroup('admin');
        $users->save($user);
    }
}
