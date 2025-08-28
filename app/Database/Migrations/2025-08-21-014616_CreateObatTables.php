<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateObatTables extends Migration
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
            'kode_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'satuan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'jumlah' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'didistribusi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'sisa' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
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
        $this->forge->createTable('obat', true);
    }

    public function down()
    {
        $this->forge->dropTable('obat', true);
    }
}
