<?php

namespace App\Controllers;

use App\Models\SearchIndexModel;

class SearchController extends BaseController
{
    public function query()
    {
        $q = (string)$this->request->getGet('q');
        
        if (empty(trim($q))) {
            return $this->response->setJSON([
                'success' => true,
                'data' => []
            ]);
        }

        if (function_exists('record_search_query')) {
            record_search_query($q);
        }

        $model = new SearchIndexModel();
        
        // Exclude legacy database rows for items that are pulled live with full details
        $model->whereNotIn('source_type', ['executive', 'governor']);

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
        
        // --- ADD CURRENT EXECUTIVES (คณะผู้บริหารปัจจุบัน) TO RESULTS ---
        if (function_exists('get_site_executives')) {
            $execList = get_site_executives(null, null, false);
            foreach ($execList as $ex) {
                $matchName     = mb_stripos($ex['name'] ?? '', $q) !== false;
                $matchPos      = mb_stripos($ex['position'] ?? '', $q) !== false;
                $matchQuote    = mb_stripos($ex['quote'] ?? '', $q) !== false;
                $matchEdu      = mb_stripos($ex['education'] ?? '', $q) !== false;
                $matchHist     = mb_stripos($ex['history'] ?? '', $q) !== false;
                $matchCat      = mb_stripos($ex['category'] ?? '', $q) !== false;
                $matchPhone    = mb_stripos($ex['phone'] ?? '', $q) !== false;
                $matchEmail    = mb_stripos($ex['email'] ?? '', $q) !== false;
                $matchExecKw   = in_array(mb_strtolower($q), ['ผู้บริหาร', 'คณะผู้บริหาร', 'ผู้ว่า', 'ผู้ว่าฯ', 'รองผู้ว่า', 'รองผู้ว่าฯ', 'หัวหน้าส่วน']);

                if ($matchName || $matchPos || $matchQuote || $matchEdu || $matchHist || $matchCat || $matchPhone || $matchEmail || $matchExecKw) {
                    $results[] = [
                        'source_type' => 'executive',
                        'source_id'   => $ex['id'] ?? uniqid(),
                        'title'       => ($ex['name'] ?? '') . ' - ' . ($ex['position'] ?? 'คณะผู้บริหาร'),
                        'description' => 'ตำแหน่ง: ' . ($ex['position'] ?? '-') . (!empty($ex['phone']) ? ' | โทร: ' . $ex['phone'] : '') . (!empty($ex['quote']) ? ' | วิสัยทัศน์: ' . $ex['quote'] : ''),
                        'url'         => base_url('executives/detail/' . ($ex['id'] ?? '')),
                        'image_url'   => !empty($ex['photo']) ? (strpos($ex['photo'], 'http') === 0 ? $ex['photo'] : base_url($ex['photo'])) : null
                    ];
                }
            }
        }

        // --- ADD NAVIGATION MENUS & PORTAL LINKS TO RESULTS ---
        if (function_exists('get_site_menus')) {
            $menus = get_site_menus();
            $flatMenus = [];
            foreach ($menus as $m) {
                $flatMenus[] = $m;
                if (!empty($m['children']) && is_array($m['children'])) {
                    foreach ($m['children'] as $cm) {
                        $flatMenus[] = $cm;
                    }
                }
            }
            foreach ($flatMenus as $menu) {
                $mTitle = $menu['title'] ?? '';
                if (empty($mTitle) || empty($menu['url']) || $menu['url'] === '#') continue;
                if (mb_stripos($mTitle, $q) !== false || mb_stripos($q, $mTitle) !== false) {
                    $targetUrl = function_exists('format_menu_url') ? format_menu_url($menu['url']) : base_url($menu['url']);
                    $results[] = [
                        'source_type' => 'menu',
                        'source_id'   => 'menu_' . md5($mTitle),
                        'title'       => 'เมนู: ' . $mTitle,
                        'description' => 'ลิงก์เข้าถึงหน้า ' . $mTitle . ' ของเว็บไซต์จังหวัดพัทลุง',
                        'url'         => $targetUrl,
                        'image_url'   => null
                    ];
                }
            }
        }

        // --- ADD GOVERNORS (ทำเนียบผู้ว่าราชการจังหวัด) TO RESULTS ---
        if (function_exists('get_site_governors')) {
            $isGovernorKw = (
                mb_stripos('ทำเนียบผู้ว่าราชการจังหวัด', $q) !== false ||
                mb_stripos($q, 'ทำเนียบ') !== false ||
                mb_stripos($q, 'ผู้ว่า') !== false ||
                mb_stripos($q, 'หอเกียรติยศ') !== false ||
                mb_stripos($q, 'รายนามผู้ว่า') !== false ||
                mb_stripos($q, 'อดีตผู้ว่า') !== false
            );

            // Add top-level Hall of Fame entry if matching
            if ($isGovernorKw) {
                $results[] = [
                    'source_type' => 'governor_portal',
                    'source_id'   => 'gov_portal_main',
                    'title'       => 'ทำเนียบผู้ว่าราชการจังหวัดพัทลุง (Governor Hall of Fame)',
                    'description' => 'รวบรวมรายนาม ประวัติ ยุคสมัย และผลงานของผู้ว่าราชการจังหวัดพัทลุงตั้งแต่อดีตจนถึงปัจจุบัน',
                    'url'         => base_url('governors'),
                    'image_url'   => base_url('uploads/logo/logo_1787048018.png')
                ];
            }

            $govList = get_site_governors();
            foreach ($govList as $g) {
                $matchName   = mb_stripos($g['name'] ?? '', $q) !== false;
                $matchPeriod = mb_stripos($g['period'] ?? '', $q) !== false;
                $matchSeq    = mb_stripos('คนที่ ' . ($g['sequence'] ?? ''), $q) !== false;
                $matchEra    = mb_stripos($g['era'] ?? '', $q) !== false;
                $matchAch    = mb_stripos($g['achievement'] ?? '', $q) !== false;

                if ($matchName || $matchPeriod || $matchSeq || $matchEra || $matchAch || $isGovernorKw) {
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
        
        // --- ADD STRATEGY & ACTION PLANS TO SMART SEARCH ---
        if (function_exists('get_site_strategy')) {
            $strat = get_site_strategy();
            
            // 1. Match Vision & Motto
            $visionKeywords = ['ยุทธศาสตร์', 'วิสัยทัศน์', 'พันธกิจ', 'เป้าหมาย', 'แผนพัฒนา', 'ค่านิยม', 'คำขวัญ'];
            $isVisionKeyword = false;
            foreach ($visionKeywords as $vk) {
                if (mb_stripos($vk, $q) !== false || mb_stripos($q, $vk) !== false) {
                    $isVisionKeyword = true;
                    break;
                }
            }

            if ($isVisionKeyword || mb_stripos($strat['vision']['statement'] ?? '', $q) !== false || mb_stripos($strat['vision']['motto'] ?? '', $q) !== false || mb_stripos($strat['vision']['title'] ?? '', $q) !== false) {
                $results[] = [
                    'source_type' => 'strategy',
                    'source_id'   => 'strat_vision',
                    'title'       => 'วิสัยทัศน์จังหวัดพัทลุง: ' . ($strat['vision']['title'] ?? 'วิสัยทัศน์การพัฒนา 2566-2570'),
                    'description' => ($strat['vision']['statement'] ?? '') . ' | คำขวัญ: ' . ($strat['vision']['motto'] ?? ''),
                    'url'         => base_url('strategy'),
                    'image_url'   => base_url('uploads/logo/logo_1787048018.png')
                ];
            }

            // 2. Match Missions
            foreach ($strat['missions'] ?? [] as $mIdx => $missionText) {
                if (mb_stripos($missionText, $q) !== false) {
                    $results[] = [
                        'source_type' => 'strategy',
                        'source_id'   => 'strat_mission_' . $mIdx,
                        'title'       => 'พันธกิจการพัฒนาจังหวัดพัทลุง (ข้อที่ ' . ($mIdx + 1) . ')',
                        'description' => $missionText,
                        'url'         => base_url('strategy'),
                        'image_url'   => null
                    ];
                }
            }

            // 3. Match Development Themes (ประเด็นการพัฒนาจังหวัด)
            foreach ($strat['pillars'] ?? [] as $pl) {
                $stratMatched = false;
                $matchingStratText = '';
                foreach ($pl['strategies'] ?? [] as $st) {
                    if (mb_stripos($st, $q) !== false) {
                        $stratMatched = true;
                        $matchingStratText = $st;
                        break;
                    }
                }

                if (
                    mb_stripos($pl['title'] ?? '', $q) !== false || 
                    mb_stripos($pl['short_title'] ?? '', $q) !== false || 
                    mb_stripos($pl['summary'] ?? '', $q) !== false || 
                    mb_stripos($pl['flagship'] ?? '', $q) !== false ||
                    $stratMatched ||
                    mb_stripos('ประเด็นการพัฒนา', $q) !== false ||
                    mb_stripos('ประเด็นที่ ' . $pl['number'], $q) !== false
                ) {
                    $results[] = [
                        'source_type' => 'strategy_pillar',
                        'source_id'   => $pl['id'],
                        'title'       => 'ประเด็นการพัฒนาที่ ' . $pl['number'] . ': ' . ($pl['short_title'] ?: $pl['title']),
                        'description' => $pl['summary'] . (!empty($pl['flagship']) ? ' | โครงการสำคัญ: ' . $pl['flagship'] : '') . ($matchingStratText ? ' | กลยุทธ์: ' . $matchingStratText : ''),
                        'url'         => base_url('strategy#pillarsSection'),
                        'image_url'   => null
                    ];
                }
            }

            // 4. Match Key Target Indicators / KPIs
            foreach ($strat['kpis'] ?? [] as $kpi) {
                if (
                    mb_stripos($kpi['title'] ?? '', $q) !== false || 
                    mb_stripos($kpi['desc'] ?? '', $q) !== false || 
                    mb_stripos($kpi['target'] ?? '', $q) !== false ||
                    mb_stripos('ตัวชี้วัด', $q) !== false ||
                    mb_stripos('kpi', $q) !== false
                ) {
                    $results[] = [
                        'source_type' => 'strategy_kpi',
                        'source_id'   => $kpi['id'],
                        'title'       => 'ตัวชี้วัดเป้าหมาย: ' . $kpi['title'] . ' (' . $kpi['target'] . ' ' . $kpi['unit'] . ')',
                        'description' => 'เป้าหมาย: ' . $kpi['target'] . ' ' . $kpi['unit'] . (!empty($kpi['current']) ? ' | สถานะปัจจุบัน: ' . $kpi['current'] : '') . ' | ' . ($kpi['desc'] ?? ''),
                        'url'         => base_url('strategy#kpiSection'),
                        'image_url'   => null
                    ];
                }
            }

            // 5. Match Strategy Documents & Annual Action Plans
            foreach ($strat['documents'] ?? [] as $sDoc) {
                if (
                    mb_stripos($sDoc['title'] ?? '', $q) !== false || 
                    mb_stripos($sDoc['category'] ?? '', $q) !== false || 
                    mb_stripos($sDoc['year'] ?? '', $q) !== false ||
                    mb_stripos('แผนปฏิบัติราชการ', $q) !== false ||
                    mb_stripos('action plan', $q) !== false
                ) {
                    $results[] = [
                        'source_type' => 'strategy_doc',
                        'source_id'   => $sDoc['id'],
                        'title'       => $sDoc['title'],
                        'description' => 'หมวดหมู่: ' . $sDoc['category'] . ' (ปี ' . $sDoc['year'] . ') | ขนาด ' . $sDoc['file_size'] . ' (' . ($sDoc['pages'] ?? '-') . ' หน้า)',
                        'url'         => base_url('strategy#documentsSection'),
                        'image_url'   => null
                    ];
                }
            }
        }

        // --- ADD PROVINCIAL PROJECTS (GIS & eMENSCR) TO SMART SEARCH ---
        try {
            $projectModel = new \App\Models\ProjectModel();
            $matchedProjects = $projectModel->getFilteredProjects(['q' => $q]);
            foreach (array_slice($matchedProjects, 0, 8) as $pj) {
                $results[] = [
                    'source_type' => 'project',
                    'source_id'   => $pj['id'],
                    'title'       => 'โครงการ: ' . $pj['project_name'] . ' (ปี ' . $pj['fiscal_year'] . ')',
                    'description' => 'หน่วยงาน: ' . $pj['agency'] . ' | อ.' . $pj['district'] . ' | งบประมาณ ฿' . number_format($pj['budget']) . ' | ความก้าวหน้า ' . $pj['progress_pct'] . '%',
                    'url'         => base_url('projects/gis?year=' . $pj['fiscal_year'] . '&district=' . urlencode($pj['district'])),
                    'image_url'   => !empty($pj['photos_array'][0]) ? $pj['photos_array'][0] : null
                ];
            }
        } catch (\Exception $e) {
            // If table not ready
        }
        
        // Add specific icons based on source_type
        $mapped = array_map(function($item) {
            $icon = 'fa-solid fa-file-lines';
            $badgeColor = 'bg-secondary';
            $badgeText = 'เอกสาร';
            
            switch($item['source_type']) {
                case 'strategy':
                    $icon = 'fa-solid fa-bullseye text-warning';
                    $badgeColor = 'bg-warning text-dark';
                    $badgeText = 'วิสัยทัศน์/ยุทธศาสตร์';
                    break;
                case 'strategy_pillar':
                    $icon = 'fa-solid fa-layer-group text-primary';
                    $badgeColor = 'bg-primary';
                    $badgeText = 'ประเด็นการพัฒนา';
                    break;
                case 'strategy_kpi':
                    $icon = 'fa-solid fa-chart-pie text-success';
                    $badgeColor = 'bg-success';
                    $badgeText = 'ตัวชี้วัดเป้าหมาย';
                    break;
                case 'strategy_doc':
                    $icon = 'fa-solid fa-file-pdf text-danger';
                    $badgeColor = 'bg-info text-dark';
                    $badgeText = 'แผนพัฒนา/แผนปฏิบัติ';
                    break;
                case 'project':
                    $icon = 'fa-solid fa-map-location-dot text-info';
                    $badgeColor = 'bg-primary';
                    $badgeText = 'โครงการ GIS';
                    break;
                case 'governor_portal':
                    $icon = 'fa-solid fa-landmark-dome text-warning';
                    $badgeColor = 'bg-warning text-dark';
                    $badgeText = 'ทำเนียบผู้ว่าฯ';
                    break;
                case 'menu':
                    $icon = 'fa-solid fa-compass text-success';
                    $badgeColor = 'bg-success';
                    $badgeText = 'เมนูหลักเว็บไซต์';
                    break;
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
                case 'news_feed':
                    $icon = 'fa-solid fa-satellite-dish text-danger';
                    $badgeColor = 'bg-danger text-light';
                    $badgeText = 'ฟีดข่าว สปชส./โซเชียล';
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
