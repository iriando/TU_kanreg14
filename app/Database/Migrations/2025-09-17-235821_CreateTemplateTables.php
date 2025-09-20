<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTemplateTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nama'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'file_path'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'keterangan'       => ['type' => 'TEXT', 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('template');
    }

    public function down()
    {
        $this->forge->dropTable('template', true);
    }
}
