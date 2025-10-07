<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDoctorsAvailabilityTable extends Migration
{
    public function up()
    {
       $this->forge->addField([ 
        'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true, ], 
        'doctor_id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, ], 
        'day_of_week' => [ 'type' => 'ENUM', 'constraint' => [ 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ], ], 
        'start_time' => [ 'type' => 'TIME', 'null' => false, ], 
        'end_time' => [ 'type' => 'TIME', 'null' => false, ], 
        'shift_name' => [ 'type' => 'VARCHAR', 'constraint' => 50, 'null' => true, ], 
        'is_available' => [ 'type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'null' => false, ],
        'created_at'    => ['type' => 'DATETIME', 'null' => true],
        'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        'deleted_at' => ['type' => 'DATETIME', 'null' => true], ]);
         $this->forge->addKey('id', true);
         $this->forge->addForeignKey('doctor_id', 'doctors', 'id', 'CASCADE', 'CASCADE'); 
         $this->forge->createTable('doctor_availability'); 
    }

    public function down()
    {
        $this->forge->dropTable('doctor_availability',true);
    }
}
