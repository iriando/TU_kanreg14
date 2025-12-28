<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBarangUnitTable extends Migration
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
            'kode_unit'   => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'merk'        => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'status'      => [
                'type'       => 'varchar',
                'constraint' => 20,
                'default'    => 'tersedia',
            ],
            'kondisi'      => [
                'type'       => 'varchar',
                'constraint' => 20,
                'default'    => 'baik',
            ],
            'created_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at'  => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kode_barang', 'barang_master', 'kode_barang', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['kode_barang', 'kode_unit']);
        $this->forge->createTable('barang_unit', true, ['ENGINE' => 'InnoDB', 'CHARSET' => 'utf8mb4', 'COLLATE' => 'utf8mb4_unicode_ci']);

    }

    public function down()
    {
        $this->forge->dropTable('barang_unit');
    }
}
