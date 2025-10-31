<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserHospitalJunctionTable extends Migration
{
    public function up()
    {
         
         $this->forge->addField([
        'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
        'hospital_id'=>['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        'userid'    =>['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        'created_at'    => ['type' => 'DATETIME', 'null' => true],
        'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        'deleted_at' => ['type' => 'DATETIME', 'null' => true],
         ]);
         $this->forge->addKey('id',true);
            $this->forge->addForeignKey('hospital_id', 'hospitals', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('userid', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('UserHospital_Junction');
    }

    public function down()
    {
        $this->forge->dropTable('UserHospital_Junction');
    }
}
