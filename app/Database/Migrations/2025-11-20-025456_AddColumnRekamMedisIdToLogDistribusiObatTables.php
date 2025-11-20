<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColumnRekamMedisIdToLogDistribusiObatTables extends Migration
{
    public function up()
    {
        $this->forge->addColumn('log_distribusiobat', [
            'rekam_medis_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'after' => 'id',
            ],
        ]);
        $this->forge->addForeignKey('rekam_medis_id', 'rekam_medis', 'id', 'CASCADE', 'CASCADE', 'fk_logdistribusi_rekammedis');

        $this->forge->processIndexes('log_distribusiobat');
    }

    public function down()
    {
        $this->forge->dropForeignKey('log_distribusiobat', 'fk_logdistribusi_rekammedis');
        $this->forge->dropColumn('log_distribusiobat', 'rekam_medis_id');
    }
}
