<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthTables extends Migration
{
    public function up()
    {
        /*
         * Users
         */
        $this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'email'            => ['type' => 'varchar', 'constraint' => 191],
            'username'         => ['type' => 'varchar', 'constraint' => 191, 'null' => true],
            'password_hash'    => ['type' => 'varchar', 'constraint' => 255],
            'reset_hash'       => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'reset_at'         => ['type' => 'datetime', 'null' => true],
            'reset_expires'    => ['type' => 'datetime', 'null' => true],
            'activate_hash'    => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'status'           => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'status_message'   => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'active'           => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],
            'force_pass_reset' => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],
            'created_at'       => ['type' => 'datetime', 'null' => true],
            'updated_at'       => ['type' => 'datetime', 'null' => true],
            'deleted_at'       => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('email', false, true);
        $this->forge->addKey('username', false, true);
        $this->forge->createTable('users', true);

        /*
         * Auth Tokens
         */
        $this->forge->addField([
            'id'              => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'selector'        => ['type' => 'varchar', 'constraint' => 191],
            'hashedValidator' => ['type' => 'varchar', 'constraint' => 255],
            'user_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'expires'         => ['type' => 'datetime'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('selector', false, true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('auth_tokens', true);

        /*
         * Groups
         */
        $this->forge->addField([
            'id'          => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'varchar', 'constraint' => 191],
            'description' => ['type' => 'varchar', 'constraint' => 255],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('name', false, true);
        $this->forge->createTable('auth_groups', true);

        /*
         * Groups_users
         */
        $this->forge->addField([
            'group_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'user_id'  => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey(['group_id', 'user_id'], true);
        $this->forge->addForeignKey('group_id', 'auth_groups', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('auth_groups_users', true);

        /*
         * Permissions
         */
        $this->forge->addField([
            'id'          => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'varchar', 'constraint' => 191],
            'description' => ['type' => 'varchar', 'constraint' => 255],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('name', false, true);
        $this->forge->createTable('auth_permissions', true);

        /*
         * Permissions_users
         */
        $this->forge->addField([
            'permission_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'user_id'       => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey(['permission_id', 'user_id'], true);
        $this->forge->addForeignKey('permission_id', 'auth_permissions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('auth_permissions_users', true);

        /*
         * Permissions_groups
         */
        $this->forge->addField([
            'permission_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'group_id'      => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey(['permission_id', 'group_id'], true);
        $this->forge->addForeignKey('permission_id', 'auth_permissions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('group_id', 'auth_groups', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('auth_permissions_groups', true);

        /*
         * Logins
         */
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'ip_address' => ['type' => 'varchar', 'constraint' => 255],
            'email'      => ['type' => 'varchar', 'constraint' => 191],
            'user_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'date'       => ['type' => 'datetime'],
            'success'    => ['type' => 'tinyint', 'constraint' => 1],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('email');
        $this->forge->addKey('user_id');
        $this->forge->createTable('auth_logins', true);

        /*
         * Activation Attempts
         */
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'ip_address' => ['type' => 'varchar', 'constraint' => 255],
            'user_agent' => ['type' => 'varchar', 'constraint' => 255],
            'token'      => ['type' => 'varchar', 'constraint' => 191],
            'created_at' => ['type' => 'datetime'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('token');
        $this->forge->createTable('auth_activation_attempts', true);

        /*
         * Reset Attempts
         */
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'email'      => ['type' => 'varchar', 'constraint' => 191],
            'ip_address' => ['type' => 'varchar', 'constraint' => 255],
            'user_agent' => ['type' => 'varchar', 'constraint' => 255],
            'token'      => ['type' => 'varchar', 'constraint' => 191],
            'created_at' => ['type' => 'datetime'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('token');
        $this->forge->createTable('auth_reset_attempts', true);

        /*
         * Logouts
         */
        $this->forge->addField([
            'id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'ip_address' => ['type' => 'varchar', 'constraint' => 255],
            'user_agent' => ['type' => 'varchar', 'constraint' => 255],
            'date'       => ['type' => 'datetime'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->createTable('auth_logouts', true);
    }

    public function down()
    {
        $this->forge->dropTable('auth_logouts', true);
        $this->forge->dropTable('auth_reset_attempts', true);
        $this->forge->dropTable('auth_activation_attempts', true);
        $this->forge->dropTable('auth_logins', true);
        $this->forge->dropTable('auth_permissions_groups', true);
        $this->forge->dropTable('auth_permissions_users', true);
        $this->forge->dropTable('auth_permissions', true);
        $this->forge->dropTable('auth_groups_users', true);
        $this->forge->dropTable('auth_groups', true);
        $this->forge->dropTable('auth_tokens', true);
        $this->forge->dropTable('users', true);
    }
}
