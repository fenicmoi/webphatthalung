<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PagesSeeder extends Seeder
{
    public function run()
    {
        $jsonFile = WRITEPATH . 'dump_pages.json';
        if (file_exists($jsonFile)) {
            $pages = json_decode(file_get_contents($jsonFile), true);
            if (!empty($pages)) {
                foreach ($pages as $page) {
                    $this->db->table('pages')->ignore(true)->insert($page);
                }
                echo "Seeded pages from {$jsonFile}\n";
                return;
            }
        }

        // Fallback default pages
        $defaultPages = [
            [
                'id'           => 1,
                'parent_id'    => null,
                'order_num'    => 0,
                'title'        => 'ข้อมูลทั่วไปจังหวัด',
                'slug'         => 'general',
                'header_image' => 'uploads/pages/header_1787197577_7804.png',
                'content'      => '<p><img src="../uploads/pages/page_1787280116_8602.png" alt="" width="2752" height="1536"></p>',
                'views'        => 40,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'id'           => 2,
                'parent_id'    => 1,
                'order_num'    => 1,
                'title'        => 'ข้อมูลทางประวัติศาสตร์',
                'slug'         => 'history',
                'header_image' => null,
                'content'      => '<p><strong><span class="font-green">จังหวัดพัทลุง</span> เป็นจังหวัดหนึ่งในภาคใต้ของประเทศไทย ที่มีประวัติความเป็นมาอันยาวนาน</strong></p>',
                'views'        => 5,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'id'           => 3,
                'parent_id'    => 1,
                'order_num'    => 2,
                'title'        => 'สัญลักษณ์ประจำจังหวัด',
                'slug'         => 'mascot',
                'header_image' => null,
                'content'      => '<p>โลโก้จังหวัด คำขวัญ ธงประจำจังหวัด ต้นไม้/ดอกไม้ประจำจังหวัด</p>',
                'views'        => 0,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'id'           => 5,
                'parent_id'    => null,
                'order_num'    => 0,
                'title'        => 'แผนที่จังหวัดพัทลุง',
                'slug'         => 'gismap',
                'header_image' => null,
                'content'      => '',
                'views'        => 0,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'id'           => 6,
                'parent_id'    => null,
                'order_num'    => 0,
                'title'        => 'ยุทธศาสตร์การพัฒนาจังหวัด',
                'slug'         => 'development',
                'header_image' => null,
                'content'      => '',
                'views'        => 2,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($defaultPages as $p) {
            $this->db->table('pages')->ignore(true)->insert($p);
        }
        echo "Seeded default pages\n";
    }
}
