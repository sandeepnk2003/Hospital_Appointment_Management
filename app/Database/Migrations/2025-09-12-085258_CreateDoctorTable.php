<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDoctorTable extends Migration
{
   public function up()
{
    $this->forge->addField([
        'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
        'userid'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        'specialization' => ['type' => 'VARCHAR', 'constraint' => '150'],
        'qualification'  => ['type' => 'VARCHAR', 'constraint' => '150', 'null' => true],
        'experience'     => ['type' => 'INT', 'constraint' => 3, 'null' => true],
        'consultation_fee' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
        'created_at'     => ['type' => 'DATETIME', 'null' => true],
        'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        'deleted_at' => ['type' => 'DATETIME', 'null' => true],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->addForeignKey('userid', 'users', 'id', 'CASCADE', 'CASCADE');
    $this->forge->createTable('doctors');
}


    public function down()
    {
        $this->forge->dropTable('doctors');
    }
}
