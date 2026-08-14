<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\SiteBannerModel;
use App\Models\ProcurementModel;
use App\Models\ItaDocumentModel;
use App\Models\GalleryAlbumModel;
use App\Models\ExecutiveModel;
use App\Models\NoraKnowledgeModel;
use App\Models\SearchIndexModel;

class SearchIndexSeeder extends Seeder
{
    public function run()
    {
        $searchModel = new SearchIndexModel();
        // Clear existing index
        $this->db->table('search_indexes')->truncate();

        // 1. Procurements
        $procurementModel = new ProcurementModel();
        $procurements = $procurementModel->where('status', 'active')->findAll();
        foreach ($procurements as $item) {
            $searchModel->insert([
                'source_type' => 'procurement',
                'source_id' => $item['id'],
                'title' => $item['title'],
                'description' => "ประกาศจัดซื้อจัดจ้าง หมวดหมู่: " . $item['category'] . " งบประมาณ: " . number_format($item['budget'], 2) . " บาท",
                'url' => $item['doc_path'], // Or route to detail
                'image_url' => null
            ]);
        }

        // 2. ITA Documents
        $itaModel = new ItaDocumentModel();
        $itas = $itaModel->where('status', 'active')->findAll();
        foreach ($itas as $item) {
            $searchModel->insert([
                'source_type' => 'ita',
                'source_id' => $item['id'],
                'title' => $item['oit_code'] . ' - ' . $item['name'],
                'description' => "ข้อมูล ITA / OIT / Open Data ปี " . $item['year'],
                'url' => $item['url'],
                'image_url' => null
            ]);
        }

        // 3. Executives
        $execModel = new ExecutiveModel();
        $execs = $execModel->where('active', 1)->findAll();
        foreach ($execs as $item) {
            $searchModel->insert([
                'source_type' => 'executive',
                'source_id' => $item['id'],
                'title' => $item['name'],
                'description' => "ตำแหน่ง: " . $item['position'],
                'url' => '#', // Since it's shown on the page usually
                'image_url' => $item['image_path']
            ]);
        }

        // 4. Nora Knowledge
        $noraModel = new NoraKnowledgeModel();
        $noras = $noraModel->findAll();
        foreach ($noras as $item) {
            $searchModel->insert([
                'source_type' => 'nora',
                'source_id' => $item['id'],
                'title' => $item['intent'],
                'description' => $item['keywords'] . " " . $item['answer_text'],
                'url' => $item['action_link'],
                'image_url' => null
            ]);
        }

        // 5. Gallery Albums
        $galleryModel = new GalleryAlbumModel();
        $galleries = $galleryModel->findAll();
        foreach ($galleries as $item) {
            $searchModel->insert([
                'source_type' => 'gallery',
                'source_id' => $item['id'],
                'title' => $item['title'],
                'description' => "อัลบั้มภาพกิจกรรม",
                'url' => '#', 
                'image_url' => $item['cover_image']
            ]);
        }
        
        echo "Search Index seeded successfully.\n";
    }
}
