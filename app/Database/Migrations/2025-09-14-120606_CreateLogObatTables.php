<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogObatTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'kode_barang'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'nama_barang'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'jumlah'           => ['type' => 'INT', 'default' => 0],
            'aksi'             => ['type' => 'VARCHAR', 'constraint' => 100],
            'keterangan'       => ['type' => 'TEXT', 'null' => true],
            'petugas'          => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('log_obat');
    }

    public function down()
    {
        $this->forge->dropTable('log_obat', true);
    }
}
