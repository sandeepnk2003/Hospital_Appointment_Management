<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTablePaymentsItem extends Migration
{
    public function up()
    {
         $this->forge->addField([
            'id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'payment_id' => ['type'=>'INT','constraint'=>11,'unsigned'=>true],
            'item_name' => ['type'=>'VARCHAR','constraint'=>100],
            'quantity' => ['type'=>'INT','default'=>1],
            'price' => ['type'=>'DECIMAL','constraint'=>'10,2','default'=>0.00],
            'total' => ['type'=>'DECIMAL','constraint'=>'10,2','default'=>0.00],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('payment_id', 'payments', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('payment_items');
    }

    public function down()
    {
      $this->forge->dropTable('payment_items',true);
    }
}
