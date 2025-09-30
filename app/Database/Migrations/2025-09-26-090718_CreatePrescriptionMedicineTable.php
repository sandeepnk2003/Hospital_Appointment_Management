<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrescriptionMedicineTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
        'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'prescription_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
             'medicine_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'=>false
            ],
             'frequency' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'=>false
            ],
            'duration' =>[
                'type' =>'VARCHAR',
                'constraint'=>255,
                'null'=>true
            ],
            'instruction' =>[
                'type'=>'TEXT',
                'null'=>true
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('prescription_id','prescription','id','CASCADE','CASCADE');
        $this->forge->createTable('prescription_Medicine',true);
    }

    public function down()
    {
    $this->forge->dropTable('prescription_Medicine',true);
    }
}
