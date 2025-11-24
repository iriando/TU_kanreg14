<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnQrCodeToBarangUnitTables extends Migration
{
    public function up()
    {
        $this->forge->addColumn('barang_unit', [
            'qr_code' => [
                'type'     => 'VARCHAR',
                'constraint' => 255,
                'after' => 'slug',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('barang_unit', 'qr_code');
    }
}
