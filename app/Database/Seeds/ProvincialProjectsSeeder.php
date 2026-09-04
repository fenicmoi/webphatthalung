<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProvincialProjectsSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed emenscr_settings
        $settingsFile = WRITEPATH . 'dump_emenscr_settings.json';
        if (file_exists($settingsFile)) {
            $settings = json_decode(file_get_contents($settingsFile), true);
            if (!empty($settings)) {
                foreach ($settings as $row) {
                    $this->db->table('emenscr_settings')->ignore(true)->insert($row);
                }
            }
        } else {
            $this->db->table('emenscr_settings')->ignore(true)->insert([
                'id'                => 1,
                'api_endpoint'      => 'https://emenscr.nesdc.go.th/api/v1/provincial/projects',
                'api_token'         => '',
                'province_code'     => '93',
                'province_name'     => 'พัทลุง',
                'auto_sync'         => 1,
                'sync_frequency'    => 'daily',
                'last_sync_status'  => 'success',
                'last_sync_time'    => date('Y-m-d H:i:s'),
                'last_sync_message' => 'ข้อมูลชุดโครงการยุทธศาสตร์จังหวัดพัทลุงพร้อมใช้งาน',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ]);
        }

        // 2. Seed provincial_projects
        $projectsFile = WRITEPATH . 'dump_provincial_projects.json';
        if (file_exists($projectsFile)) {
            $projects = json_decode(file_get_contents($projectsFile), true);
            if (!empty($projects)) {
                foreach ($projects as $proj) {
                    $this->db->table('provincial_projects')->ignore(true)->insert($proj);
                }
                echo "Seeded provincial_projects from {$projectsFile}\n";
                return;
            }
        }
        
        echo "Seeded emenscr settings and projects\n";
    }
}
