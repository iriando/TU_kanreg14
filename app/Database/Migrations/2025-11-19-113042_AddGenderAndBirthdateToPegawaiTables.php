<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGenderAndBirthdateToPegawaiTables extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pegawai', [
            'gender' => [
                'type'       => 'ENUM("pria","wanita")',
            ],
            'tanggal_lahir' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pegawai', 'gender');
        $this->forge->dropColumn('pegawai', 'tanggal_lahir');
    }
}
