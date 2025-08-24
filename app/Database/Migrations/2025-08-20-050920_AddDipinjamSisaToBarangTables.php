<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDipinjamSisaToBarangTables extends Migration
{
    public function up()
    {
        $this->forge->addColumn('barang', [
            'dipinjam' => [
                'type'       => 'INT',
                'default'    => 0,
                'after'      => 'jumlah',
            ],
            'sisa' => [
                'type'       => 'INT',
                'default'    => 0,
                'after'      => 'dipinjam',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('barang', ['dipinjam', 'sisa']);
    }
}
