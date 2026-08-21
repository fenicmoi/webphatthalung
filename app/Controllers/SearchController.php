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
        
        // --- ADD STATIC PAGES TO RESULTS ---
        $pageModel = new \App\Models\PageModel();
        $pages = $pageModel->groupStart()
                           ->like('title', $q)
                           ->orLike('content', $q)
                           ->orLike('slug', $q)
                           ->groupEnd()
                           ->findAll(10);
                           
        foreach ($pages as $p) {
            $cleanContent = strip_tags($p['content'] ?? '');
            $cleanSnippet = mb_substr($cleanContent, 0, 140) . (mb_strlen($cleanContent) > 140 ? '...' : '');

            $targetUrl = base_url('page/' . $p['slug']);
            if (!empty($p['parent_id'])) {
                $parentPage = $pageModel->find($p['parent_id']);
                if ($parentPage) {
                    $targetUrl = base_url('page/' . $parentPage['slug'] . '#tab-child-' . $p['id']);
                }
            }

            $results[] = [
                'source_type' => 'page',
                'source_id'   => $p['id'],
                'title'       => $p['title'],
                'description' => !empty($cleanSnippet) ? $cleanSnippet : 'หน้าเนื้อหาและข้อมูลทั่วไปของจังหวัด',
                'url'         => $targetUrl,
                'image_url'   => null
            ];
        }

        // --- ADD DOCUMENTS (คลังเอกสารดิจิทัล / ประกาศคำสั่ง / รายงาน) TO RESULTS ---
        helper('settings');
        if (function_exists('get_site_documents')) {
            $docList = get_site_documents(null, null, false);
            foreach ($docList as $d) {
                $matchTitle = mb_stripos($d['title'] ?? '', $q) !== false;
                $matchCat   = mb_stripos($d['category'] ?? '', $q) !== false;
                $matchTag   = mb_stripos($d['sub_tag'] ?? '', $q) !== false;

                if ($matchTitle || $matchCat || $matchTag) {
                    $docUrl = !empty($d['file_url']) && $d['file_url'] !== '#' 
                        ? (strpos($d['file_url'], 'http') === 0 ? $d['file_url'] : base_url($d['file_url'])) 
                        : base_url('documents/category/' . urlencode((string)($d['category'] ?? '')));

                    $fType = strtolower($d['file_type'] ?? 'pdf');

                    $results[] = [
                        'source_type' => 'document',
                        'source_id'   => $d['id'] ?? uniqid(),
                        'title'       => $d['title'],
                        'description' => 'หมวดหมู่: ' . ($d['category'] ?? '-') . ' (' . ($d['sub_tag'] ?? 'เอกสารราชการ') . ') | ขนาด: ' . ($d['file_size'] ?? '1.0 MB') . ' | อัปเดต: ' . ($d['date'] ?? '-'),
                        'url'         => $docUrl,
                        'image_url'   => null,
                        'file_type'   => $fType
                    ];
                }
            }
        }

        // --- ADD NEWS TO RESULTS ---
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

        // --- ADD VIDEOS TO RESULTS ---
        if (function_exists('get_site_videos')) {
            $videoList = get_site_videos(null, null, false);
            foreach ($videoList as $v) {
                $matchTitle = mb_stripos($v['title'] ?? '', $q) !== false;
                $matchDesc  = mb_stripos($v['desc'] ?? '', $q) !== false;
                $matchCat   = mb_stripos($v['category'] ?? '', $q) !== false;
                if ($matchTitle || $matchDesc || $matchCat) {
                    $results[] = [
                        'source_type' => 'video',
                        'source_id'   => $v['id'],
                        'title'       => $v['title'],
                        'description' => 'หมวดหมู่วิดีโอ: ' . ($v['category'] ?? '-') . ' | ' . ($v['desc'] ?? ''),
                        'url'         => base_url('videos'),
                        'image_url'   => !empty($v['youtube_id']) ? "https://img.youtube.com/vi/{$v['youtube_id']}/hqdefault.jpg" : null
                    ];
                }
            }
        }
        
        // --- ADD GOVERNORS (ทำเนียบผู้ว่าราชการจังหวัด) TO RESULTS ---
        if (function_exists('get_site_governors')) {
            $govList = get_site_governors();
            foreach ($govList as $g) {
                $matchName   = mb_stripos($g['name'] ?? '', $q) !== false;
                $matchPeriod = mb_stripos($g['period'] ?? '', $q) !== false;
                $matchSeq    = mb_stripos('คนที่ ' . ($g['sequence'] ?? ''), $q) !== false;
                $matchEra    = mb_stripos($g['era'] ?? '', $q) !== false;
                $matchAch    = mb_stripos($g['achievement'] ?? '', $q) !== false;

                if ($matchName || $matchPeriod || $matchSeq || $matchEra || $matchAch || mb_stripos('ผู้ว่าราชการจังหวัด', $q) !== false) {
                    $results[] = [
                        'source_type' => 'governor',
                        'source_id'   => $g['id'],
                        'title'       => 'ผู้ว่าราชการจังหวัดคนที่ ' . ($g['sequence'] ?? '') . ': ' . ($g['name'] ?? ''),
                        'description' => 'ดำรงตำแหน่ง: ' . ($g['period'] ?? '-') . ' | ยุคสมัย: ' . ($g['era'] ?? '-') . ' | ' . ($g['achievement'] ?? ''),
                        'url'         => base_url('governors'),
                        'image_url'   => !empty($g['image']) ? (strpos($g['image'], 'http') === 0 ? $g['image'] : base_url($g['image'])) : null
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
                case 'governor':
                    $icon = 'fa-solid fa-crown text-warning';
                    $badgeColor = 'bg-warning text-dark';
                    $badgeText = 'ทำเนียบผู้ว่าฯ';
                    break;
                case 'document':
                    $fType = $item['file_type'] ?? 'pdf';
                    $icon = 'fa-solid fa-file-pdf text-danger';
                    if ($fType === 'doc' || $fType === 'docx') $icon = 'fa-solid fa-file-word text-primary';
                    elseif ($fType === 'xls' || $fType === 'xlsx') $icon = 'fa-solid fa-file-excel text-success';
                    elseif ($fType === 'zip' || $fType === 'rar') $icon = 'fa-solid fa-file-zipper text-warning';
                    elseif ($fType === 'link') $icon = 'fa-solid fa-link text-info';
                    $badgeColor = 'bg-success';
                    $badgeText = 'คลังเอกสารดาวน์โหลด';
                    break;
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
                case 'video':
                    $icon = 'fa-solid fa-film';
                    $badgeColor = 'bg-danger';
                    $badgeText = 'วิดีทัศน์ Web TV';
                    break;
                case 'page':
                    $icon = 'fa-solid fa-file-lines';
                    $badgeColor = 'bg-info text-dark';
                    $badgeText = 'หน้าเว็บไซต์ / ข้อมูลทั่วไป';
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
