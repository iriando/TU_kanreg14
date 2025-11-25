<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnGambarToBarangUnitTables extends Migration
{
    public function up()
    {
        $this->forge->addColumn('barang_unit', [
            'gambar' => [
                'type'     => 'VARCHAR',
                'constraint' => 255,
                'after' => 'qr_code',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('barang_unit', 'gambar');
    }
}
