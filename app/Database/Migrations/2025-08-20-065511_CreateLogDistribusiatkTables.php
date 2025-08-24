<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LogDistribusiAtkTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_penerima' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'kode_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'jumlah' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'tanggal_distribusi' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'petugas' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('log_distribusiatk');
    }

    public function down()
    {
        $this->forge->dropTable('log_distribusiatk');
    }
}
