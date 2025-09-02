<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKedaluwarsaToObatTables extends Migration
{
    public function up()
    {
        $this->forge->addColumn('obat', [
            'kedaluwarsa' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('obat', 'kedaluwarsa');
    }
}
