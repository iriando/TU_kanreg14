<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnIdPegawaiOnPegawaiTables extends Migration
{
    public function up()
    {
         $this->forge->addColumn('rekam_medis', [
            'id_pegawai' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'after'      => 'id',
            ],
        ]);

        $this->forge->addForeignKey('id_pegawai', 'pegawai', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('rekam_medis', 'rekam_medis_id_pegawai_foreign');
        $this->forge->dropColumn('rekam_medis', 'id_pegawai');
    }
}
