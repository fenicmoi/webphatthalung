<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterSeeder extends Seeder
{
    public function run()
    {
        echo "=== Starting Master Database Seeding ===\n";

        // 1. Core Data (Banners, Executives, Nora AI, Procurements, ITA, Gallery)
        echo "\n[1/4] Seeding Site Core Data...\n";
        $this->call('JsonToDbSeeder');

        // 2. Static and CMS Pages
        echo "\n[2/4] Seeding Pages...\n";
        $this->call('PagesSeeder');

        // 3. Provincial Projects & e-MENSCR Settings
        echo "\n[3/4] Seeding Provincial Projects...\n";
        $this->call('ProvincialProjectsSeeder');

        // 4. Search Indexes
        echo "\n[4/4] Building Search Indexes...\n";
        $this->call('SearchIndexSeeder');

        echo "\n=== Master Database Seeding Completed Successfully! ===\n";
    }
}
