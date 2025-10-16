<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOtpsTable extends Migration
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
            //  'hospital_id'=>['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'patient_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'otp' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,   // 6-digit OTP
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        // $this->forge->addForeignKey('hospital_id', 'hospitals', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('otps');
    }

    public function down()
    {
        $this->forge->dropTable('otps');
    }
}
