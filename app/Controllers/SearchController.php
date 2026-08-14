<?php

namespace App\Controllers;

use App\Models\SearchIndexModel;

class SearchController extends BaseController
{
    public function query()
    {
        $q = $this->request->getGet('q');
        
        if (empty(trim($q))) {
            return $this->response->setJSON([
                'success' => true,
                'data' => []
            ]);
        }

        $model = new SearchIndexModel();
        
        // Search across title and description
        $model->groupStart()
              ->like('title', $q)
              ->orLike('description', $q)
              ->groupEnd();
              
        // Limit results to keep UI clean
        $results = $model->findAll(15);
        
        // --- ADD NEWS TO RESULTS ---
        helper('settings');
        if (function_exists('get_site_news')) {
            $newsList = get_site_news(100);
            foreach ($newsList as $n) {
                $matchTitle = mb_stripos($n['title'] ?? '', $q) !== false;
                $matchDesc  = mb_stripos($n['summary'] ?? '', $q) !== false;
                if ($matchTitle || $matchDesc) {
                    $results[] = [
                        'source_type' => 'news',
                        'source_id'   => $n['id'],
                        'title'       => $n['title'],
                        'description' => $n['summary'] ?? '',
                        'url'         => base_url('news/detail/' . $n['id']),
                        'image_url'   => $n['cover_image'] ?? null
                    ];
                }
            }
        }
        
        // Add specific icons based on source_type
        $mapped = array_map(function($item) {
            $icon = 'fa-solid fa-file-lines';
            $badgeColor = 'bg-secondary';
            $badgeText = 'เอกสาร';
            
            switch($item['source_type']) {
                case 'procurement':
                    $icon = 'fa-solid fa-file-invoice-dollar';
                    $badgeColor = 'bg-primary';
                    $badgeText = 'จัดซื้อจัดจ้าง';
                    break;
                case 'ita':
                    $icon = 'fa-solid fa-building-shield';
                    $badgeColor = 'bg-success';
                    $badgeText = 'ITA / เปิดเผยข้อมูล';
                    break;
                case 'gallery':
                    $icon = 'fa-solid fa-images';
                    $badgeColor = 'bg-info';
                    $badgeText = 'คลังภาพกิจกรรม';
                    break;
                case 'executive':
                    $icon = 'fa-solid fa-user-tie';
                    $badgeColor = 'bg-warning text-dark';
                    $badgeText = 'คณะผู้บริหาร';
                    break;
                case 'nora':
                    $icon = 'fa-solid fa-robot';
                    $badgeColor = 'bg-danger';
                    $badgeText = 'น้องโนรา AI';
                    break;
                case 'news':
                    $icon = 'fa-solid fa-bullhorn';
                    $badgeColor = 'bg-primary text-light';
                    $badgeText = 'ข่าวประชาสัมพันธ์';
                    break;
            }
            
            $item['ui_icon'] = $icon;
            $item['ui_badge_color'] = $badgeColor;
            $item['ui_badge_text'] = $badgeText;
            
            return $item;
        }, $results);

        return $this->response->setJSON([
            'success' => true,
            'data' => $mapped
        ]);
    }
}
