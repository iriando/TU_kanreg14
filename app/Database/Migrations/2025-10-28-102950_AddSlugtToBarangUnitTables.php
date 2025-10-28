<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSlugtToBarangUnitTables extends Migration
{
    public function up()
    {
        $this->forge->addColumn('barang_unit', [
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'kondisi',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('barang_unit', 'slug');
    }
}
