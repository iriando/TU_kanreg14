<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogPeminjamanDetail extends Migration
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
            'peminjaman_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'kode_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'kode_unit' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'tanggal_pinjam' => [
                'type' => 'DATETIME',
            ],
            'tanggal_kembali' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status_unit' => [
                'type'       => 'ENUM("dipinjam","dikembalikan")',
                'default'    => 'dipinjam',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('peminjaman_id', 'log_peminjaman', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('log_peminjaman_detail', true, ['ENGINE' => 'InnoDB', 'CHARSET' => 'utf8mb4', 'COLLATE' => 'utf8mb4_0900_ai_ci']);
    }

    public function down()
    {
        $this->forge->dropTable('log_peminjaman_detail');
    }
}
