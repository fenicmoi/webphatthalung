<?php

namespace App\Libraries;

use App\Models\PageModel;

class SmartSearchService
{
    /**
     * ค้นหาข้อมูลแบบองค์รวม (Omni-Search) จากทุกระบบของจังหวัดพัทลุง
     */
    public static function search(string $query, int $limit = 10): array
    {
        $q = trim($query);
        if (empty($q)) {
            return [];
        }

        helper('settings');
        $results = [];

        // 1. ค้นหาจากคลังเอกสารราชการและประกาศคำสั่ง (Documents)
        if (function_exists('get_site_documents')) {
            $docList = get_site_documents(null, null, false);
            foreach ($docList as $d) {
                $mTitle = mb_stripos($d['title'] ?? '', $q) !== false;
                $mCat   = mb_stripos($d['category'] ?? '', $q) !== false;
                $mTag   = mb_stripos($d['sub_tag'] ?? '', $q) !== false;

                if ($mTitle || $mCat || $mTag) {
                    $docUrl = !empty($d['file_url']) && $d['file_url'] !== '#' 
                        ? (str_starts_with($d['file_url'], 'http') ? $d['file_url'] : base_url($d['file_url'])) 
                        : base_url('documents');

                    $results[] = [
                        'source_type' => 'document',
                        'title'       => $d['title'] ?? 'เอกสารราชการ',
                        'description' => 'หมวดหมู่: ' . ($d['category'] ?? '-') . ' | วันที่: ' . ($d['date'] ?? '-'),
                        'url'         => $docUrl,
                        'icon'        => 'fa-solid fa-file-pdf text-danger',
                        'badge'       => 'เอกสารราชการ (PDF)'
                    ];
                }
            }
        }

        // 2. ค้นหาจากข่าวสารและประชาสัมพันธ์ (News & PR)
        if (function_exists('get_site_news')) {
            $newsList = get_site_news(50);
            foreach ($newsList as $n) {
                $mTitle = mb_stripos($n['title'] ?? '', $q) !== false;
                $mDesc  = mb_stripos($n['summary'] ?? '', $q) !== false;
                if ($mTitle || $mDesc) {
                    $results[] = [
                        'source_type' => 'news',
                        'title'       => $n['title'] ?? 'ข่าวสารจังหวัด',
                        'description' => mb_substr(strip_tags($n['summary'] ?? ''), 0, 120) . '...',
                        'url'         => base_url('news/detail/' . ($n['id'] ?? '')),
                        'icon'        => 'fa-solid fa-newspaper text-primary',
                        'badge'       => 'ข่าวสารจังหวัด'
                    ];
                }
            }
        }

        // 3. ค้นหาจากประกาศจัดซื้อจัดจ้าง e-GP (Procurement)
        if (function_exists('get_site_procurements')) {
            $procList = get_site_procurements(null, null, false);
            foreach ($procList as $p) {
                $mTitle = mb_stripos($p['title'] ?? '', $q) !== false;
                $mDept  = mb_stripos($p['department'] ?? '', $q) !== false;
                if ($mTitle || $mDept) {
                    $results[] = [
                        'source_type' => 'procurement',
                        'title'       => $p['title'] ?? 'ประกาศจัดซื้อจัดจ้าง',
                        'description' => 'หน่วยงาน: ' . ($p['department'] ?? '-') . ' | งบประมาณ: ' . ($p['budget'] ?? '-'),
                        'url'         => base_url('procurement'),
                        'icon'        => 'fa-solid fa-gavel text-warning',
                        'badge'       => 'จัดซื้อจัดจ้าง e-GP'
                    ];
                }
            }
        }

        // 4. ค้นหาจากหน้าเนื้อหาทั่วไป (Pages)
        try {
            $pageModel = new PageModel();
            $pages = $pageModel->groupStart()
                               ->like('title', $q)
                               ->orLike('content', $q)
                               ->groupEnd()
                               ->findAll(5);
            foreach ($pages as $p) {
                $clean = strip_tags($p['content'] ?? '');
                $results[] = [
                    'source_type' => 'page',
                    'title'       => $p['title'] ?? 'หน้าข้อมูล',
                    'description' => mb_substr($clean, 0, 110) . '...',
                    'url'         => base_url('page/' . ($p['slug'] ?? '')),
                    'icon'        => 'fa-solid fa-file-lines text-info',
                    'badge'       => 'ข้อมูลทั่วไป'
                ];
            }
        } catch (\Throwable $e) {}

        // 5. ค้นหาจากคณะผู้บริหาร (Executives)
        if (function_exists('get_site_executives')) {
            $execList = get_site_executives();
            foreach ($execList as $ex) {
                $mName = mb_stripos($ex['name'] ?? '', $q) !== false;
                $mPos  = mb_stripos($ex['position'] ?? '', $q) !== false;
                if ($mName || $mPos) {
                    $results[] = [
                        'source_type' => 'executive',
                        'title'       => ($ex['name'] ?? '') . ' - ' . ($ex['position'] ?? ''),
                        'description' => 'ตำแหน่ง: ' . ($ex['position'] ?? '-') . (!empty($ex['phone']) ? ' | โทร: ' . $ex['phone'] : ''),
                        'url'         => base_url('executives/detail/' . ($ex['id'] ?? '')),
                        'icon'        => 'fa-solid fa-user-tie text-success',
                        'badge'       => 'คณะผู้บริหาร'
                    ];
                }
            }
        }

        // 6. ค้นหาจากตัวชี้วัดความโปร่งใส ITA / OIT
        if (function_exists('get_ita_items')) {
            $itaItems = get_ita_items();
            foreach ($itaItems as $ita) {
                $mCode  = mb_stripos($ita['code'] ?? '', $q) !== false;
                $mTitle = mb_stripos($ita['title'] ?? '', $q) !== false;
                if ($mCode || $mTitle) {
                    $results[] = [
                        'source_type' => 'ita',
                        'title'       => ($ita['code'] ?? '') . ': ' . ($ita['title'] ?? ''),
                        'description' => 'ศูนย์ข้อมูลความโปร่งใสและการเปิดเผยข้อมูลสาธารณะ (OIT)',
                        'url'         => !empty($ita['file_url']) ? $ita['file_url'] : base_url('ita'),
                        'icon'        => 'fa-solid fa-award text-warning',
                        'badge'       => 'ความโปร่งใส ITA'
                    ];
                }
            }
        }

        // 7. ค้นหาจากวิดีทัศน์และสารคดี (Videos)
        if (function_exists('get_site_videos')) {
            $videoList = get_site_videos();
            foreach ($videoList as $v) {
                if (mb_stripos($v['title'] ?? '', $q) !== false) {
                    $results[] = [
                        'source_type' => 'video',
                        'title'       => $v['title'] ?? 'วิดีโอประชาสัมพันธ์',
                        'description' => 'หมวดหมู่: ' . ($v['category'] ?? 'ทั่วไป'),
                        'url'         => base_url('videos'),
                        'icon'        => 'fa-solid fa-video text-danger',
                        'badge'       => 'วิดีโอประชาสัมพันธ์'
                    ];
                }
            }
        }

        // Slice to max limit
        return array_slice($results, 0, $limit);
    }

    /**
     * ประมวลผลและสร้างคำตอบแชตบอตอัจฉริยะจาก Smart Search
     */
    public static function generateChatReply(string $message): ?array
    {
        $q = trim($message);
        if (mb_strlen($q) < 2) {
            return null;
        }

        // Clean query terms (remove common conversational filler words in Thai)
        $cleanQ = preg_replace('/(มี|คืออะไร|อะไรบ้าง|อย่างไร|ที่ไหน|ขอดู|หา|อยากทราบ|สอบถาม|น้องโนรา|ครับ|ค่ะ|ขอ|เช็ค)/u', '', $q);
        $cleanQ = trim($cleanQ);
        if (empty($cleanQ)) {
            $cleanQ = $q;
        }

        $results = self::search($cleanQ, 4);

        if (empty($results)) {
            // Fallback search with original message
            $results = self::search($q, 4);
        }

        if (empty($results)) {
            return null;
        }

        // Construct synthesized smart response
        $count = count($results);
        $reply = "น้องโนราได้สืบค้นข้อมูลผ่านระบบ **Smart Omni-Search** ของจังหวัดพัทลุง พบข้อมูลที่ตรงกับคำถาม **\"" . esc($q) . "\"** จำนวน {$count} รายการ ดังนี้ค่ะ:\n";

        $cards = [];
        foreach ($results as $idx => $r) {
            $num = $idx + 1;
            $reply .= "\n{$num}. **{$r['title']}**\n   _{$r['description']}_";

            $cards[] = [
                'title' => "👉 [{$r['badge']}] " . mb_substr($r['title'], 0, 45) . (mb_strlen($r['title']) > 45 ? '...' : ''),
                'url'   => $r['url'],
                'icon'  => $r['icon'] ?? 'fa-solid fa-arrow-up-right-from-square text-primary'
            ];
        }

        $reply .= "\n\n💡 คุณสามารถคลิกที่ปุ่มการ์ดด้านล่างเพื่อเปิดอ่านรายละเอียดหรือดาวน์โหลดเอกสารได้ทันทีเลยค่ะ 😊";

        return [
            'status' => 'success',
            'reply'  => $reply,
            'cards'  => $cards
        ];
    }
}
