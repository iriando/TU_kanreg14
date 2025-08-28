<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBarangMasterTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kode_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'keterangan' => [
                'type'       => 'TEXT',
                'null'       => true,
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
        
        $this->forge->addKey('id', true); // primary key
        $this->forge->addUniqueKey(['kode_barang']);
        $this->forge->createTable('barang_master', true, ['ENGINE' => 'InnoDB', 'CHARSET' => 'utf8mb4', 'COLLATE' => 'utf8mb4_0900_ai_ci']);
    }

    public function down()
    {
        $this->forge->dropTable('barang_master');
    }
}
