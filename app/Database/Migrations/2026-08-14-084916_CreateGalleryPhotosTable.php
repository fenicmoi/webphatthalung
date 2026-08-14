<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGalleryPhotosTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'album_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'image_path' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'caption' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('album_id', 'gallery_albums', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('gallery_photos', true);
    }

    public function down()
    {
        $this->forge->dropTable('gallery_photos', true);
    }
}
