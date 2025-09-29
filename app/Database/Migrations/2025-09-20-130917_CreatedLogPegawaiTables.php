<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatedLogPegawaiTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'pegawai_id'    => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false
            ],
            'tanggal'       => ['type' => 'DATE', 'null' => false],
            'waktu_masuk'   => ['type' => 'DATETIME', 'null' => true],
            'waktu_keluar'  => ['type' => 'DATETIME', 'null' => true],
            'waktu_kembali' => ['type' => 'DATETIME', 'null' => true],
            'waktu_pulang'  => ['type' => 'DATETIME', 'null' => true],
            'status'        => [
                'type' => 'ENUM',
                'constraint' => ['tidak hadir', 'hadir', 'izin', 'cuti', 'dinas luar'],
                'default'    => 'tidak hadir',
            ],
            'keterangan'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pegawai_id', 'pegawai', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('log_pegawai', true, [
            'ENGINE' => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_0900_ai_ci'
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('log_pegawai', true);
    }
}
