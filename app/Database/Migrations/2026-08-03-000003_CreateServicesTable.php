<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServicesTable extends Migration
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
            'service_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '300',
                'null'       => true,
            ],
            'icon_class' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'fa-solid fa-laptop-file',
            ],
            'external_url' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 0,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('services', true);
    }

    public function down()
    {
        $this->forge->dropTable('services', true);
    }
}
