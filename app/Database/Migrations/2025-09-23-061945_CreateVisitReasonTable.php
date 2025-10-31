<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVisitReasonTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
        'id' => ['type'=> 'INT','constraint' => 11,'unsigned'=> true,'auto_increment' => true],
        'visit_id'=>['type'=> 'INT','constraint' => 11,'unsigned'=> true,],
        'reason'=>['type'=>'VARCHAR','constraint'=>50, 'null' => true,],
        'diagnosis'=>['type'=>'VARCHAR','constraint'=>100, 'null' => true,],
       'created_at'=> ['type' => 'DATETIME','null' => true,]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('visit_id','visits','id','CASCADE','CASCADE');
        $this->forge->createTable('visit_reasons',true);
    }

    public function down()
    {
        $this->forge->dropTable('visit_reasons');
    }
}
