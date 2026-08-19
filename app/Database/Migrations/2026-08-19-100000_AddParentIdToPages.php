<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddParentIdToPages extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pages', [
            'parent_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
            'order_num' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'parent_id',
            ],
        ]);
        
        // Add foreign key if supported by engine (optional, but good practice)
        // $this->forge->addForeignKey('parent_id', 'pages', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        $this->forge->dropColumn('pages', 'parent_id');
        $this->forge->dropColumn('pages', 'order_num');
    }
}
