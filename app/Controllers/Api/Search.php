<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

/**
 * Universal Smart Search Controller (Phatthalung Omni-Search Engine)
 * Handles fast real-time federated searching across e-Services, News, Documents, Videos, and Tourism.
 */
class Search extends ResourceController
{
    /**
     * Get the master search index (Loads from live database + defaults)
     */
    private function getMasterIndex(): array
    {
        helper('settings');
        $index = [];

        // --- 0. ดึงข้อมูลหน้าเว็บไซต์ (Static Pages) ---
        try {
            $pageModel = new \App\Models\PageModel();
            $allPages = $pageModel->findAll();
            foreach ($allPages as $p) {
                $cleanContent = strip_tags($p['content'] ?? '');
                $snippet = mb_substr($cleanContent, 0, 140) . (mb_strlen($cleanContent) > 140 ? '...' : '');

                $pUrl = base_url('page/' . $p['slug']);
                if (!empty($p['parent_id'])) {
                    $parentPage = $pageModel->find($p['parent_id']);
                    if ($parentPage) {
                        $pUrl = base_url('page/' . $parentPage['slug'] . '#tab-child-' . $p['id']);
                    }
                }

                $index[] = [
                    'id'          => 'page-' . $p['id'],
                    'type'        => 'page',
                    'title'       => $p['title'] ?? 'หน้าเว็บไซต์',
                    'description' => !empty($snippet) ? $snippet : 'หน้าเนื้อหาและข้อมูลทั่วไปของจังหวัดพัทลุง',
                    'url'         => $pUrl,
                    'icon'        => 'fa-solid fa-file-lines text-info',
                    'badge'       => 'หน้าเพจ / ข้อมูลทั่วไป',
                    'keywords'    => ($p['title'] ?? '') . ' ' . ($p['slug'] ?? '') . ' ' . $snippet . ' หน้าเว็บ ข้อมูลทั่วไป ประวัติ พัทลุง'
                ];
            }
        } catch (\Throwable $e) {
            // Ignore if DB not ready
        }

        // --- 1. ดึงข้อมูลคลังเอกสารและไฟล์ดาวน์โหลดจริง (Live Smart Documents) ---
        if (function_exists('get_site_documents')) {
            $docs = get_site_documents(null, null, true);
            foreach ($docs as $doc) {
                $fType = strtolower($doc['file_type'] ?? 'pdf');
                $fIcon = 'fa-solid fa-file-pdf';
                if (in_array($fType, ['doc', 'docx'])) $fIcon = 'fa-solid fa-file-word';
                elseif (in_array($fType, ['xls', 'xlsx'])) $fIcon = 'fa-solid fa-file-excel';
                elseif (in_array($fType, ['zip', 'rar'])) $fIcon = 'fa-solid fa-file-ziper';
                elseif ($fType === 'link') $fIcon = 'fa-solid fa-link';

                $docUrl = !empty($doc['file_url']) && $doc['file_url'] !== '#' ? (strpos($doc['file_url'], 'http') === 0 ? $doc['file_url'] : base_url($doc['file_url'])) : base_url('documents/category/' . urlencode((string)$doc['category']));

                $index[] = [
                    'id' => 'doc-' . ($doc['id'] ?? uniqid()),
                    'type' => 'document',
                    'title' => $doc['title'] ?? 'เอกสารดาวน์โหลด',
                    'description' => 'หมวดหมู่: ' . ($doc['category'] ?? '-') . ' (' . ($doc['sub_tag'] ?? 'ไฟล์ราชการ') . ') | ขนาด: ' . ($doc['file_size'] ?? '1.0 MB') . ' | ดึงข้อมูลอัตโนมัติจากคลัง 5 เสาหลัก',
                    'url' => $docUrl,
                    'icon' => $fIcon,
                    'badge' => $doc['sub_tag'] ?? 'คลังเอกสาร',
                    'keywords' => ($doc['title'] ?? '') . ' ' . ($doc['category'] ?? '') . ' ' . ($doc['sub_tag'] ?? '') . ' ดาวน์โหลด ไฟล์ เอกสาร กฎหมาย หนังสือราชการ ประกาศ ita oit'
                ];
            }
        }

        // --- 1.1 ดึงข้อมูลตัวชี้วัด ITA/OIT และ Open Data จริง ---
        if (function_exists('get_ita_items')) {
            $itaItems = get_ita_items(null, false);
            foreach ($itaItems as $i) {
                $fType = strtolower($i['file_type'] ?? 'pdf');
                $iIcon = 'fa-solid fa-file-pdf text-danger';
                if ($fType === 'csv') $iIcon = 'fa-solid fa-file-csv text-success';
                elseif ($fType === 'json') $iIcon = 'fa-solid fa-file-code text-warning';
                elseif ($fType === 'link') $iIcon = 'fa-solid fa-link text-primary';

                $iUrl = !empty($i['file_url']) && $i['file_url'] !== '#' ? (strpos($i['file_url'], 'http') === 0 ? $i['file_url'] : base_url($i['file_url'])) : base_url('ita');

                $index[] = [
                    'id' => 'ita-' . ($i['id'] ?? uniqid()),
                    'type' => 'ita',
                    'title' => '["' . ($i['code'] ?? 'OIT') . '"] ' . ($i['title'] ?? 'ตัวชี้วัด ITA'),
                    'description' => 'หมวดหมู่: ' . ($i['category'] ?? '-') . ' | ' . ($i['desc'] ?? '-') . ' | ดาวน์โหลด: ' . ($i['downloads'] ?? 0) . ' ครั้ง',
                    'url' => $iUrl,
                    'icon' => $iIcon,
                    'badge' => $i['code'] ?? 'OIT/ITA',
                    'keywords' => ($i['code'] ?? '') . ' ' . ($i['title'] ?? '') . ' ' . ($i['category'] ?? '') . ' ita oit ปปช ความโปร่งใส ต่อต้านการทุจริต ข้อมูลเปิด open data csv json'
                ];
            }
        }

        // --- 2. ดึงข้อมูลสื่อวิดีทัศน์ (Phatthalung Web TV) ---
        if (function_exists('get_site_videos')) {
            $videos = get_site_videos(null, null, true);
            foreach ($videos as $v) {
                $index[] = [
                    'id' => 'vid-' . ($v['id'] ?? uniqid()),
                    'type' => 'video',
                    'title' => $v['title'] ?? 'วิดีโอส่งเสริมจังหวัดพัทลุง',
                    'description' => 'หมวดหมู่: ' . ($v['category'] ?? '-') . ' | คลิปวิดีโอสตรีมมิ่งความคมชัดสูงจากสถานี Phatthalung Web TV',
                    'url' => base_url('videos'),
                    'icon' => 'fa-brands fa-youtube text-danger',
                    'badge' => 'Web TV Video',
                    'keywords' => ($v['title'] ?? '') . ' ' . ($v['category'] ?? '') . ' วิดีโอ ยูทูป สื่อนำเสนอ ท่องเที่ยว'
                ];
            }
        }

        // --- 3. ดึงข้อมูลข่าวประชาสัมพันธ์จริง (Live News) ---
        if (function_exists('get_site_news')) {
            $newsList = get_site_news(100); // Changed to limit 100, no category
            foreach ($newsList as $news) {
                if (($news['category'] ?? '') === 'event') continue; // Skip event in news list if desired
                $index[] = [
                    'id' => 'news-' . ($news['id'] ?? uniqid()),
                    'type' => 'news',
                    'title' => $news['title'] ?? 'ข่าวประชาสัมพันธ์',
                    'description' => $news['summary'] ?? ($news['title'] ?? ''),
                    'url' => '#news',
                    'icon' => 'fa-solid fa-bullhorn',
                    'badge' => 'ข่าว' . ($news['category'] ?? 'ทั่วไป'),
                    'keywords' => ($news['title'] ?? '') . ' ' . ($news['summary'] ?? '') . ' ข่าว ประกาศ โครงการ ก أن'
                ];
            }
        }

        // --- 3.1 ดึงข้อมูลคณะผู้บริหารปัจจุบัน (Current Executive Leadership) ---
        if (function_exists('get_site_executives')) {
            $execs = get_site_executives(null, null, false);
            foreach ($execs as $ex) {
                $index[] = [
                    'id' => 'exec-' . ($ex['id'] ?? uniqid()),
                    'type' => 'executive',
                    'title' => ($ex['name'] ?? 'ผู้บริหาร') . ' - ' . ($ex['position'] ?? 'ผู้บริหารจังหวัดพัทลุง'),
                    'description' => 'ตำแหน่ง: ' . ($ex['position'] ?? '-') . (!empty($ex['phone']) ? ' | โทร: ' . $ex['phone'] : '') . (!empty($ex['quote']) ? ' | วิสัยทัศน์: ' . $ex['quote'] : ''),
                    'url' => base_url('executives/detail/' . ($ex['id'] ?? '')),
                    'icon' => 'fa-solid fa-user-tie text-warning',
                    'badge' => 'คณะผู้บริหาร',
                    'keywords' => ($ex['name'] ?? '') . ' ' . ($ex['position'] ?? '') . ' ' . ($ex['education'] ?? '') . ' ' . ($ex['history'] ?? '') . ' ผู้บริหาร ผู้ว่า รองผู้ว่า หัวหน้าส่วนราชการ เบอร์โทร ติดต่อ'
                ];
            }
        }

        // --- 3.2 ดึงข้อมูลทำเนียบอดีตผู้ว่าราชการจังหวัด (Governors Archive) ---
        if (function_exists('get_site_governors')) {
            $govs = get_site_governors();
            foreach ($govs as $g) {
                $index[] = [
                    'id' => 'gov-' . ($g['id'] ?? uniqid()),
                    'type' => 'governor',
                    'title' => 'ผู้ว่าราชการจังหวัดคนที่ ' . ($g['sequence'] ?? '') . ': ' . ($g['name'] ?? ''),
                    'description' => 'ดำรงตำแหน่ง: ' . ($g['period'] ?? '-') . ' | ยุคสมัย: ' . ($g['era'] ?? '-') . ' | ' . ($g['achievement'] ?? ''),
                    'url' => base_url('governors'),
                    'icon' => 'fa-solid fa-crown text-warning',
                    'badge' => 'ทำเนียบอดีตผู้ว่าฯ',
                    'keywords' => ($g['name'] ?? '') . ' ' . ($g['period'] ?? '') . ' ' . ($g['era'] ?? '') . ' ' . ($g['achievement'] ?? '') . ' ผู้ว่าราชการจังหวัด เจ้าเมือง อดีตผู้ว่า ทำเนียบ ประวัติศาสตร์'
                ];
            }
        }

        // --- 4. บริการออนไลน์ (e-Services & Static Landmarks) ---
        $staticItems = [
            [
                'id' => 'srv-1',
                'type' => 'service',
                'title' => 'ระบบรับเรื่องร้องเรียน ร้องทุกข์ออนไลน์ 24 ชม.',
                'description' => 'แจ้งปัญหาความเดือดร้อน สาธารณูปโภคชำรุด หรือร้องเรียนการทำงานของเจ้าหน้าที่ พร้อมติดตามผลแบบ Real-Time',
                'url' => '#services',
                'icon' => 'fa-solid fa-headset',
                'badge' => '24 HR e-Service',
                'keywords' => 'แจ้งปัญหา, ถนนพัง, ไฟดับ, ขยะ, น้ำท่วม, ร้องทุกข์, สายด่วน, ศูนย์ช่วยเหลือ, เดือดร้อน'
            ],
            [
                'id' => 'srv-2',
                'type' => 'service',
                'title' => 'ยื่นคำร้องใช้สิทธิเกี่ยวกับข้อมูลส่วนบุคคล (PDPA Request)',
                'description' => 'ช่องทางให้ประชาชนผู้เป็นเจ้าของข้อมูล ยื่นขอเข้าถึง แก้ไข คัดค้าน หรือขอลบข้อมูลส่วนบุคคลตาม พ.ร.บ. PDPA',
                'url' => '#services',
                'icon' => 'fa-solid fa-shield-halved',
                'badge' => 'PDPA Protection',
                'keywords' => 'pdpa, ข้อมูลส่วนบุคคล, ลบข้อมูล, เพิกถอนสิทธิ, ความปลอดภัย, ความลับ, แบบฟอร์ม'
            ],
            [
                'id' => 'srv-3',
                'type' => 'service',
                'title' => 'ระบบตรวจสอบและชำระภาษีท้องถิ่นออนไลน์ (e-Tax)',
                'description' => 'ตรวจสอบรายการประเมินภาษีที่ดินและสิ่งปลูกสร้าง ภาษีป้าย หรือภาษีบำรุงท้องที่ พร้อมชำระเงินผ่าน QR PromptPay',
                'url' => '#services',
                'icon' => 'fa-solid fa-file-invoice-dollar',
                'badge' => 'Online Payment',
                'keywords' => 'ภาษีที่ดิน, สิ่งปลูกสร้าง, ภาษีป้าย, บำรุงท้องที่, จ่ายภาษี, ใบเสร็จ, กองคลัง, ชำระเงิน'
            ],
            [
                'id' => 'srv-4',
                'type' => 'service',
                'title' => 'ระบบจองคิวรับบริการงานทะเบียนและบัตรประชาชน',
                'description' => 'นัดหมายออนไลน์ล่วงหน้า เพื่อทำบัตรประชาชน แจ้งเกิด-ตาย ย้ายทะเบียนบ้าน ณ สำนักทะเบียนท้องถิ่น',
                'url' => '#services',
                'icon' => 'fa-solid fa-id-card',
                'badge' => 'Booking Online',
                'keywords' => 'บัตรประชาชน, บัตรหาย, ย้ายทะเบียนบ้าน, ทำบัตร, สำนักทะเบียน, นัดหมาย, จองคิว, อำเภอ'
            ],
            [
                'id' => 'srv-5',
                'type' => 'service',
                'title' => 'บริการยื่นขอกำหนดเลขที่บ้านและใบอนุญาตก่อสร้าง (e-Permission)',
                'description' => 'ระบบอำนวยความสะดวกประชาชนในการขอใบอนุญาตก่อสร้าง ปรุงแต่งอาคาร และขอเลขที่บ้านใหม่ ผ่านช่องทางดิจิทัล',
                'url' => '#services',
                'icon' => 'fa-solid fa-house-circle-check',
                'badge' => 'e-Permission',
                'keywords' => 'ก่อสร้าง, ใบอนุญาต, เลขที่บ้าน, ตึก, กองช่าง, อาคาร, ปรุงแต่ง'
            ],
            [
                'id' => 'tour-portal',
                'type' => 'tourism',
                'title' => 'เว็บไซต์ท่องเที่ยวทางการจังหวัดพัทลุง (มาเมืองลุง - ma-muanglung.go.th)',
                'description' => 'ศูนย์ข้อมูลการท่องเที่ยว แนะนำที่พัก ร้านอาหาร คาเฟ่ ชุมชนน่าเที่ยว เทศกาลประเพณี และเช็คอิน Landmark ทั่วทั้งภาคใต้มหัศจรรย์เมืองลุง',
                'url' => 'http://www.ma-muanglung.go.th',
                'icon' => 'fa-solid fa-compass text-info',
                'badge' => 'Official Tourism Web',
                'keywords' => 'มาเมืองลุง, ma-muanglung, ท่องเที่ยว, เที่ยวพัทลุง, ที่พัก, โรงแรม, คาเฟ่, ร้านอาหาร, งานประเพณี, เทศกาล, รีวิว, ล่องแก่ง'
            ],
            [
                'id' => 'tour-1',
                'type' => 'tourism',
                'title' => 'เขตห้ามล่าสัตว์ป่าทะเลน้อย & สะพานเฉลิมพระเกียรติ 80 พรรษา',
                'description' => 'ดินแดนชุ่มน้ำและทะเลสาบอันเลื่องชื่อ ชมจุดพักหย่อนใจ ดูกอหญ้า ควายน้ำเล่นน้ำ และพระอาทิตย์ขึ้นกลางสายหมอก (ดูข้อมูลเพิ่มเติมที่ ma-muanglung.go.th)',
                'url' => 'http://www.ma-muanglung.go.th',
                'icon' => 'fa-solid fa-dove',
                'badge' => 'Top Landmark',
                'keywords' => 'ทะเลน้อย, ชมฝูงนก, สะพานเฉลิมพระเกียรติ, ควายน้ำ, ทุ่งบัวแดง, ถ่ายรูป, เที่ยว'
            ],
            [
                'id' => 'tour-2',
                'type' => 'tourism',
                'title' => 'เขาอกทะลุ สัญลักษณ์ตราประจำจังหวัดพัทลุง',
                'description' => 'ภูเขาหินปูนที่โดดเด่นกลางเมือง พร้อมบันไดขึ้นสู่ช่องหน้าต่างเขาอกทะลุเพื่อชมวิวมุมสูงของเมืองพัทลุง 360 องศา (ดูข้อมูลเพิ่มเติมที่ ma-muanglung.go.th)',
                'url' => 'http://www.ma-muanglung.go.th',
                'icon' => 'fa-solid fa-mountain-sun',
                'badge' => 'Symbol of City',
                'keywords' => 'เขาอกทะลุ, เดินป่า, จุดชมวิว, Landmark, สัญลักษณ์, ขึ้นเขา'
            ],
            [
                'id' => 'tour-3',
                'type' => 'tourism',
                'title' => 'ศูนย์หัตถกรรมจักสานกระจูด บ้านทะเลน้อย',
                'description' => 'แหล่งเรียนรู้และยกระดับสินค้าชุมชน จากพืชท้องถิ่นสกัดสู่กระเป๋าและเครื่องประดับหรูหราพร้อมส่งออกสากล (ดูข้อมูลเพิ่มเติมที่ ma-muanglung.go.th)',
                'url' => 'http://www.ma-muanglung.go.th',
                'icon' => 'fa-solid fa-basket-shopping',
                'badge' => 'OTOP พรีเมียม',
                'keywords' => 'กระจูด, สินค้าชุมชน, OTOP, ของฝาก, หัตถกรรม, กระเป๋า, ตลาด'
            ]
        ];

        return array_merge($index, $staticItems);
    }

    /**
     * Real-time query search endpoint
     * GET /api/search?q={keyword}
     */
    public function query()
    {
        $keyword = trim($this->request->getGet('q') ?? '');
        $index = $this->getMasterIndex();
        $results = [];

        if ($keyword === '') {
            // Return top trending items across categories
            $results = array_slice($index, 0, 8);
        } else {
            $kwLower = mb_strtolower($keyword, 'UTF-8');

            foreach ($index as $item) {
                $matchTitle = mb_stripos($item['title'], $keyword) !== false;
                $matchDesc  = mb_stripos($item['description'], $keyword) !== false;
                $matchKw    = mb_stripos($item['keywords'], $keyword) !== false;

                if ($matchTitle || $matchDesc || $matchKw) {
                    $results[] = $item;
                }
            }
        }

        // Categorize results for structured front-end display
        $categorized = [
            'executive' => ['label' => '👔 คณะผู้บริหารจังหวัดพัทลุง (Executive Leadership)', 'items' => []],
            'governor'  => ['label' => '👑 ทำเนียบอดีตผู้ว่าราชการจังหวัดพัทลุง', 'items' => []],
            'ita'       => ['label' => '🏆 ความโปร่งใส ITA/OIT & ชุดข้อมูลเปิด (Open Data)', 'items' => []],
            'service'   => ['label' => '⚙️ บริการออนไลน์และระบบราชการ (e-Services)', 'items' => []],
            'document'  => ['label' => '📂 คลังเอกสาร & ไฟล์ดาวน์โหลดดิจิทัล', 'items' => []],
            'video'     => ['label' => '🎬 สื่อวิดีทัศน์ Phatthalung Web TV', 'items' => []],
            'news'      => ['label' => '📰 ข่าวประชาสัมพันธ์ & ประกาศจังหวัด', 'items' => []],
            'page'      => ['label' => '📄 หน้าเพจและข้อมูลทั่วไป', 'items' => []],
            'tourism'   => ['label' => '🌿 ท่องเที่ยว & แลนด์มาร์กสำคัญ', 'items' => []],
        ];

        foreach ($results as $item) {
            $type = $item['type'] ?? 'news';
            if (isset($categorized[$type])) {
                $categorized[$type]['items'][] = $item;
            }
        }

        // Remove empty categories from final output
        $finalCat = [];
        foreach ($categorized as $key => $data) {
            if (!empty($data['items'])) {
                $finalCat[$key] = $data;
            }
        }

        return $this->respond([
            'status' => 'success',
            'query'  => $keyword,
            'total'  => count($results),
            'categories' => $finalCat
        ]);
    }

    /**
     * Get trending search keywords
     * GET /api/search/trending
     */
    public function trending()
    {
        return $this->respond([
            'status' => 'success',
            'keywords' => [
                'ประกาศต่อต้านการทุจริต',
                'หนังสือเวียน 2569',
                'ยื่นคำร้อง PDPA',
                'ภาษีที่ดิน',
                'ทะเลน้อย 4K',
                'ร้องทุกข์ 24 ชม.',
                'คู่มือ PDPA'
            ]
        ]);
    }
}
