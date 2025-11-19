<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKeluhanToRekamMedisTables extends Migration
{
    public function up()
    {
        $this->forge->addColumn('rekam_medis', [
            'keluhan' => [
                'type' => 'TEXT',
                'null' => false,
                'after' => 'nama_pasien',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('rekam_medis', 'keluhan');
    }
}
