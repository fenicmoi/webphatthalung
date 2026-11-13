<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDamrongthamDailyRecordsTable extends Migration
{
    public function up()
    {
        // 1. Table: damrongtham_daily_records
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'record_date' => [
                'type' => 'DATE',
            ],
            'date_text' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'received_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'resolved_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'pending_count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'general_inquiries' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            // 4 Channels
            'channel_walkin' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'channel_phone' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'channel_website' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'channel_letter' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            // 6 Categories
            'cat_general' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'cat_welfare' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'cat_distress' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'cat_officer' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'cat_whistleblowing' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'cat_land' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'officer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('record_date');
        $this->forge->createTable('damrongtham_daily_records', true);

        // 2. Table: damrongtham_baseline
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'baseline_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'default'    => 'default',
            ],
            'announcement_title' => [
                'type' => 'TEXT',
            ],
            'period_text' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'base_received' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 12051,
            ],
            'base_resolved' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 12022,
            ],
            'base_pending' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 29,
            ],
            // 4 Channels baseline
            'base_ch_walkin' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 6123,
            ],
            'base_ch_phone' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 3497,
            ],
            'base_ch_website' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1521,
            ],
            'base_ch_letter' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 873,
            ],
            // 6 Categories baseline
            'base_cat_general' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 5343,
            ],
            'base_cat_welfare' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 3596,
            ],
            'base_cat_distress' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1334,
            ],
            'base_cat_officer' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 841,
            ],
            'base_cat_whistleblowing' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 592,
            ],
            'base_cat_land' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 308,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('damrongtham_baseline', true);
    }

    public function down()
    {
        $this->forge->dropTable('damrongtham_daily_records', true);
        $this->forge->dropTable('damrongtham_baseline', true);
    }
}
