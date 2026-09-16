<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMediaAndEventToNews extends Migration
{
    public function up()
    {
        $fields = [
            'images_gallery' => [
                'type' => 'LONGTEXT',
                'null' => true,
                'after' => 'thumbnail',
            ],
            'attachments' => [
                'type' => 'LONGTEXT',
                'null' => true,
                'after' => 'images_gallery',
            ],
            'cover_fit' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'cover',
                'after'      => 'attachments',
            ],
            'is_event' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'cover_fit',
            ],
            'event_start_date' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'is_event',
            ],
            'event_end_date' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'event_start_date',
            ],
            'event_location' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'event_end_date',
            ],
            'event_coordinates' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'event_location',
            ],
        ];

        $this->forge->addColumn('news', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('news', [
            'images_gallery',
            'attachments',
            'cover_fit',
            'is_event',
            'event_start_date',
            'event_end_date',
            'event_location',
            'event_coordinates',
        ]);
    }
}
