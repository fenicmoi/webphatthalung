<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProcurementsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'budget' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'null' => true],
            'method' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'category' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => '50', 'default' => 'active'],
            'doc_path' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'published_date' => ['type' => 'DATE', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('procurements', true);
    }

    public function down()
    {
        $this->forge->dropTable('procurements', true);
    }
}
