<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateItaDocumentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'oit_code' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'name' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'url' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'year' => ['type' => 'VARCHAR', 'constraint' => '4', 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => '50', 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('ita_documents', true);
    }

    public function down()
    {
        $this->forge->dropTable('ita_documents', true);
    }
}
