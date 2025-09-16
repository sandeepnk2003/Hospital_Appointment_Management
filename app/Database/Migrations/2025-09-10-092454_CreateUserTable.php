<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserTable extends Migration
{
    public function up()
    {
          $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username'      => ['type' => 'VARCHAR', 'constraint' => '100'],
            'email'         => ['type' => 'VARCHAR', 'constraint' => '150', 'unique' => true],
            'password' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'role'          => ['type' => 'ENUM("superadmin","admin","doctor","patient")', 'default' => 'patient'],
            'phoneno' =>[ 'type' => 'VARCHAR', 'constraint' => '10'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
