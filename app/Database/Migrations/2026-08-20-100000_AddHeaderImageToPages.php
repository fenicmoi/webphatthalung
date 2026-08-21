<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHeaderImageToPages extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pages', [
            'header_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'slug',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pages', 'header_image');
    }
}
