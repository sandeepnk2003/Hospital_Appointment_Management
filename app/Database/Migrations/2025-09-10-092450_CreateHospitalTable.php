<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHospitalTable extends Migration
{
    public function up()
    {
       $this->forge->addField([
        'id'=>['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
        'hospital_name'=>['type'=>'VARCHAR','constraint' => '100','null'=>false],
        'hospital_email'=>['type'=>'VARCHAR','constraint'=>'50','unique'=>true],
        'hospital_contact'=>['type'=>'VARCHAR','constraint'=>'20'],
        'created_at'    => ['type' => 'DATETIME', 'null' => true],
        'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        'deleted_at' => ['type' => 'DATETIME', 'null' => true],
       ]); 
       $this->forge->addKey('id',true);
       $this->forge->createTable('hospitals',true);
    }

    public function down()
    {
        $this->forge->droptable('hospitals',true);
    }
}
