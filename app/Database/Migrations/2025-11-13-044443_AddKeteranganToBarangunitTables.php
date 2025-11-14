<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKeteranganToBarangunitTables extends Migration
{
    public function up()
    {
        $this->forge->addColumn('barang_unit', [
            'keterangan' => [
                'type'       => 'Text',
                'null'       => true,
                'after'      => 'kondisi',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('barang_unit', 'keterangan');
    }
}
