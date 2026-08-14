<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSiteBannersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'subtitle' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'badge_title' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'badge_icon' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'bg_type' => ['type' => 'VARCHAR', 'constraint' => '50', 'default' => 'image'],
            'image_path' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'floating_img_path' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'floating_pos' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'floating_anim' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'card_placement' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true],
            'desc' => ['type' => 'TEXT', 'null' => true],
            'button_text' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'button_url' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'button_icon' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'style_class' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('site_banners', true);
    }

    public function down()
    {
        $this->forge->dropTable('site_banners', true);
    }
}
