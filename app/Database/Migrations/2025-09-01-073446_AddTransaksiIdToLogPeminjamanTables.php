<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTransaksiIdToLogPeminjamanTables extends Migration
{
    public function up()
    {
        $this->forge->addColumn('log_peminjaman', [
            'transaksi_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id', // sesuaikan posisi kolom
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('log_peminjaman', 'transaksi_id');
    }
}
