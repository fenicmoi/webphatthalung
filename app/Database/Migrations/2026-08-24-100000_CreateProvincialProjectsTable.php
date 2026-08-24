<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProvincialProjectsTable extends Migration
{
    public function up()
    {
        // 1. Create provincial_projects table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'emenscr_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'fiscal_year' => [
                'type'       => 'INT',
                'constraint' => 4,
                'default'    => 2568,
            ],
            'project_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'agency' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'pillar_number' => [
                'type'       => 'INT',
                'constraint' => 2,
                'default'    => 1,
            ],
            'pillar_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'budget' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'disbursed_budget' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'objectives' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kpis' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'progress_pct' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'in_progress', 'completed', 'delayed'],
                'default'    => 'in_progress',
            ],
            'status_desc' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'district' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'เมืองพัทลุง',
            ],
            'subdistrict' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'location_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'latitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
            ],
            'longitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '11,8',
                'null'       => true,
            ],
            'photos' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'documents' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'is_featured' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'last_sync_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addKey('fiscal_year');
        $this->forge->addKey('district');
        $this->forge->addKey('status');
        $this->forge->createTable('provincial_projects', true);

        // 2. Create emenscr_settings table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'api_endpoint' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'https://emenscr.nesdc.go.th/api/v1/provincial/projects',
            ],
            'api_token' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'province_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => '93',
            ],
            'province_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'พัทลุง',
            ],
            'auto_sync' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'sync_frequency' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'daily',
            ],
            'last_sync_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'ready',
            ],
            'last_sync_time' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'last_sync_message' => [
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
        $this->forge->createTable('emenscr_settings', true);
    }

    public function down()
    {
        $this->forge->dropTable('provincial_projects', true);
        $this->forge->dropTable('emenscr_settings', true);
    }
}
