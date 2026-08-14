<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExecutivesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'position' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'image_path' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'order_num' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('executives', true);
    }

    public function down()
    {
        $this->forge->dropTable('executives', true);
    }
}
