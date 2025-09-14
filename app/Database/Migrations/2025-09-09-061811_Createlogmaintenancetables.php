<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Createlogmaintenancetables extends Migration
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
            'nama_petugas' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'kode_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'kode_unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'tanggal' => [
                'type' => 'DATETIME',
            ],
            'pengingat' => [
                'type'      => 'BOOLEAN',
                'default'   => '0',
            ],
            'hari' => [
                'type'           => 'INT',
                'constraint'     => 255,
                'null'    => true,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('log_maintenance', true);
    }

    public function down()
    {
        $this->forge->dropTable('log_maintenance', true);
    }
}
