<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablePayment extends Migration
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
            'hospital_id'=>['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'appointment_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'patient_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'doctor_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'payment_mode' => [
                'type'       => 'ENUM',
                'constraint' => ['cash', 'upi', 'card'
            ],
                'default'    => 'cash',
            ],
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            // 'transaction_id' => [
            //     'type'       => 'VARCHAR',
            //     'constraint' => 100,
            //     'null'       => true,
            // ],
            'payment_status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'completed'],
                'default'    => 'pending',
            ],
            // 'payment_date' => [
            //     'type' => 'DATETIME',
            //     'null' => true,
            // ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Primary Key
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('hospital_id', 'hospitals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('appointment_id', 'appointments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('doctor_id', 'doctors', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('payments');
    }

    public function down()
    {
        $this->forge->dropTable('payments',true);
    }
}
