<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnGambarToMaintenanceTables extends Migration
{
    public function up()
    {
        $this->forge->addColumn('log_maintenance', [
            'gambar' => [
                'type'     => 'VARCHAR',
                'constraint' => 255,
                'after' => 'status',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('log_maintenance', 'gambar');
    }
}
