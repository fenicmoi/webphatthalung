<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNoraKnowledgeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'intent' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'keywords' => ['type' => 'TEXT', 'null' => true],
            'answer_text' => ['type' => 'TEXT'],
            'action_link' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('nora_knowledge', true);
    }

    public function down()
    {
        $this->forge->dropTable('nora_knowledge', true);
    }
}
