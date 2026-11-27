<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Ebook extends Controller
{
    private function getCustomEbooksPath(): string
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0775, true);
        }
        return $writableDir . DIRECTORY_SEPARATOR . 'site_ebooks.json';
    }

    private function loadCustomEbooks(): array
    {
        $path = $this->getCustomEbooksPath();
        if (!is_file($path)) {
            return [];
        }
        $data = json_decode((string) @file_get_contents($path), true);
        return is_array($data) ? $data : [];
    }

    private function saveCustomEbooks(array $list): bool
    {
        $path = $this->getCustomEbooksPath();
        return @file_put_contents($path, json_encode(array_values($list), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    /**
     * หน้าห้องสมุดชั้นหนังสือ E-Book จังหวัดพัทลุง (/ebooks)
     */
    public function index()
    {
        helper(['settings']);
        $strategy  = get_site_strategy();
        $planBooks = $strategy['plan_books'] ?? [];
        $docs      = $strategy['documents'] ?? [];
        $custom    = $this->loadCustomEbooks();

        $books = [];

        // 0. หนังสือ E-Book ที่เจ้าหน้าที่เพิ่มเองในชั้นหนังสือ (แสดงบนสุด + แก้ไข/ลบได้)
        usort($custom, function ($a, $b) {
            return strcmp((string)($b['created_at'] ?? ''), (string)($a['created_at'] ?? ''));
        });
        foreach ($custom as $cb) {
            $fileUrl = trim((string)($cb['file_url'] ?? ''));
            if ($fileUrl === '') continue;
            $pdfUrl = preg_match('#^https?://#i', $fileUrl) ? $fileUrl : base_url(ltrim($fileUrl, '/'));
            $coverRaw = trim((string)($cb['cover_image'] ?? ''));
            $coverUrl = $coverRaw !== ''
                ? (preg_match('#^https?://#i', $coverRaw) ? $coverRaw : base_url(ltrim($coverRaw, '/')))
                : '';

            $books[] = [
                'id'          => $cb['id'] ?? '',
                'title'       => $cb['title'] ?? 'หนังสือ E-Book',
                'category'    => $cb['category'] ?? 'หนังสืออิเล็กทรอนิกส์',
                'year'        => $cb['year'] ?? '',
                'pages'       => $cb['pages'] ?? '',
                'file_size'   => $cb['file_size'] ?? '',
                'file_url'    => $fileUrl,
                'theme'       => $cb['theme'] ?? 'emerald',
                'cover_image' => $coverUrl,
                'cover_raw'   => $coverRaw,
                'ebook_url'   => base_url('ebook/view') . '?' . http_build_query([
                    'file'  => $fileUrl,
                    'title' => $cb['title'] ?? 'หนังสือ E-Book',
                    'back'  => base_url('ebooks'),
                ]),
                'pdf_url'     => $pdfUrl,
                'is_featured' => !empty($cb['is_featured']),
                'is_custom'   => true,
            ];
        }

        // 1. สมุดแผน 3D หลักหน้าแรก (5 ปี & 1 ปี)
        if (!empty($planBooks['book_5yr']['url']) && preg_match('/\.pdf(\?.*)?$/i', $planBooks['book_5yr']['url'])) {
            $b5 = $planBooks['book_5yr'];
            $books[] = [
                'id'         => 'book_5yr',
                'title'      => ($b5['title'] ?? 'แผนพัฒนาจังหวัดพัทลุง') . (!empty($b5['period']) ? ' (' . $b5['period'] . ')' : ''),
                'category'   => 'แผนพัฒนาจังหวัด 5 ปี',
                'year'       => $b5['period'] ?? '2566-2570',
                'pages'      => '',
                'file_size'  => '',
                'theme'      => 'emerald',
                'ebook_url'  => base_url('ebook/view?book=5yr&back=' . urlencode(base_url('ebooks'))),
                'pdf_url'    => preg_match('#^https?://#i', $b5['url']) ? $b5['url'] : base_url($b5['url']),
                'is_featured'=> true,
            ];
        }
        if (!empty($planBooks['book_1yr']['url']) && preg_match('/\.pdf(\?.*)?$/i', $planBooks['book_1yr']['url'])) {
            $b1 = $planBooks['book_1yr'];
            $books[] = [
                'id'         => 'book_1yr',
                'title'      => ($b1['title'] ?? 'แผนปฏิบัติราชการประจำปี') . (!empty($b1['period']) ? ' (' . $b1['period'] . ')' : ''),
                'category'   => 'แผนปฏิบัติราชการประจำปี',
                'year'       => $b1['period'] ?? '2570',
                'pages'      => '',
                'file_size'  => '',
                'theme'      => 'royal',
                'ebook_url'  => base_url('ebook/view?book=1yr&back=' . urlencode(base_url('ebooks'))),
                'pdf_url'    => preg_match('#^https?://#i', $b1['url']) ? $b1['url'] : base_url($b1['url']),
                'is_featured'=> true,
            ];
        }

        // 2. เอกสารแผนพัฒนาฯ จาก /strategy (เรียงตามปีงบประมาณมากไปน้อย)
        usort($docs, function ($a, $b) {
            preg_match('/(\d{4})/', (string)($a['year'] ?? ''), $ma);
            preg_match('/(\d{4})/', (string)($b['year'] ?? ''), $mb);
            return ((int)($mb[1] ?? 0)) <=> ((int)($ma[1] ?? 0));
        });

        $themes = ['emerald', 'royal', 'amber', 'teal', 'indigo', 'rose'];
        foreach ($docs as $idx => $d) {
            $fileUrl = trim((string)($d['file_url'] ?? ''));
            if ($fileUrl === '') continue;
            $cat = $d['category'] ?? 'แผนพัฒนาจังหวัด';
            $theme = $themes[$idx % count($themes)];
            if (mb_stripos($cat, '5 ปี') !== false) $theme = 'emerald';
            elseif (mb_stripos($cat, 'ประจำปี') !== false) $theme = 'royal';
            elseif (mb_stripos($cat, 'รายงาน') !== false || mb_stripos($cat, 'M&E') !== false) $theme = 'amber';
            elseif (mb_stripos($cat, 'คู่มือ') !== false) $theme = 'teal';

            $books[] = [
                'id'         => $d['id'] ?? ('doc_' . $idx),
                'title'      => $d['title'] ?? 'เอกสารยุทธศาสตร์',
                'category'   => $cat,
                'year'       => $d['year'] ?? '',
                'pages'      => $d['pages'] ?? '',
                'file_size'  => $d['file_size'] ?? '',
                'theme'      => $theme,
                'ebook_url'  => base_url('ebook/view?doc=' . urlencode((string)($d['id'] ?? '')) . '&back=' . urlencode(base_url('ebooks'))),
                'pdf_url'    => preg_match('#^https?://#i', $fileUrl) ? $fileUrl : base_url($fileUrl),
                'is_featured'=> !empty($d['is_featured']),
            ];
        }

        // 3. รายงานงบประมาณจังหวัดพัทลุงจากสำนักงบประมาณ (พ.ศ. 2570 & 2569)
        $bbItems = [
            ['yr' => '2570', 'short' => '70', 'label' => 'สรุปงบประมาณจังหวัดพัทลุง 5 งบรายจ่าย พ.ศ. 2570', 'path' => 'PDF/5bud/bis60rchang1007_04930000.pdf', 'theme' => 'indigo'],
            ['yr' => '2570', 'short' => '70', 'label' => 'สรุปงบประมาณจังหวัดพัทลุง ตามแผนงาน พ.ศ. 2570', 'path' => 'PDF/plan/bis61rchang2001_04930000.pdf', 'theme' => 'amber'],
            ['yr' => '2569', 'short' => '69', 'label' => 'สรุปงบประมาณจังหวัดพัทลุง 5 งบรายจ่าย พ.ศ. 2569', 'path' => 'PDF/5bud/bis60rchang1007_04930000.pdf', 'theme' => 'teal'],
            ['yr' => '2569', 'short' => '69', 'label' => 'รายการจัดสรรงบประมาณรายรายการ พ.ศ. 2569', 'path' => 'PDF/item/04930000.pdf', 'theme' => 'rose'],
        ];
        foreach ($bbItems as $bi) {
            $pdfUrl = "https://www.bb.go.th/web/budget/province/province_bud{$bi['short']}/{$bi['path']}";
            $books[] = [
                'id'         => 'bb_' . $bi['short'] . '_' . md5($bi['path']),
                'title'      => $bi['label'],
                'category'   => 'งบประมาณจังหวัด',
                'year'       => $bi['yr'],
                'pages'      => '',
                'file_size'  => 'สำนักงบประมาณ',
                'theme'      => $bi['theme'],
                'ebook_url'  => base_url('ebook/view') . '?' . http_build_query([
                    'file'  => $pdfUrl,
                    'title' => $bi['label'],
                    'back'  => base_url('ebooks'),
                ]),
                'pdf_url'    => $pdfUrl,
                'is_featured'=> false,
            ];
        }

        // รวบรวมหมวดหมู่ไม่ซ้ำ
        $categories = array_values(array_unique(array_filter(array_column($books, 'category'))));

        return view('ebook/bookshelf', [
            'title'            => 'ห้องสมุด E-Book จังหวัดพัทลุง | ชั้นหนังสือดิจิทัลพลิกหน้า 3D',
            'meta_description' => 'ชั้นหนังสืออิเล็กทรอนิกส์ (E-Book) จังหวัดพัทลุง เปิดอ่านแผนพัฒนาจังหวัด แผนปฏิบัติราชการประจำปี และรายงานงบประมาณแบบพลิกหน้า 3D',
            'books'            => $books,
            'categories'       => $categories,
        ]);
    }

    /**
     * บันทึก (เพิ่ม/แก้ไข) หนังสือ E-Book พร้อมอัปโหลดไฟล์ PDF และภาพหน้าปก (ถ้าไม่มีปก ระบบจะสร้างปก 3D ให้อัตโนมัติ)
     */
    public function save()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบเจ้าหน้าที่ก่อนดำเนินการ']);
        }

        $id          = trim((string) $this->request->getPost('id'));
        $title       = trim((string) $this->request->getPost('title'));
        $category    = trim((string) $this->request->getPost('category')) ?: 'หนังสืออิเล็กทรอนิกส์';
        $year        = trim((string) $this->request->getPost('year'));
        $pages       = trim((string) $this->request->getPost('pages'));
        $fileUrl     = trim((string) $this->request->getPost('file_url'));
        $theme       = trim((string) $this->request->getPost('theme')) ?: 'emerald';
        $removeCover = !empty($this->request->getPost('remove_cover'));

        if ($title === '') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุชื่อหนังสือ E-Book']);
        }

        $allowedThemes = ['emerald', 'royal', 'amber', 'teal', 'indigo', 'rose'];
        if (!in_array($theme, $allowedThemes, true)) {
            $theme = 'emerald';
        }

        $list = $this->loadCustomEbooks();
        $existingIdx = -1;
        if ($id !== '') {
            foreach ($list as $idx => $item) {
                if (($item['id'] ?? '') === $id) {
                    $existingIdx = $idx;
                    break;
                }
            }
        }

        $fileSize   = ($existingIdx >= 0) ? ($list[$existingIdx]['file_size'] ?? '') : '';
        $coverImage = ($existingIdx >= 0 && !$removeCover) ? ($list[$existingIdx]['cover_image'] ?? '') : '';

        // 1. อัปโหลดไฟล์ PDF (ถ้ามี)
        $pdfFile = $this->request->getFile('pdf_file');
        if ($pdfFile && $pdfFile->isValid() && !$pdfFile->hasMoved()) {
            $ext = strtolower($pdfFile->getClientExtension());
            if ($ext !== 'pdf') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'รองรับเฉพาะไฟล์นามสกุล .pdf เท่านั้น']);
            }
            $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'ebooks';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0775, true);
            }
            $newName = 'ebook_' . time() . '_' . bin2hex(random_bytes(4)) . '.pdf';
            $bytes = $pdfFile->getSize();
            $pdfFile->move($uploadDir, $newName);
            $fileUrl = 'uploads/ebooks/' . $newName;
            $fileSize = ($bytes >= 1048576) ? round($bytes / 1048576, 1) . ' MB' : round($bytes / 1024) . ' KB';
        }

        if ($fileUrl === '' && $existingIdx >= 0) {
            $fileUrl = $list[$existingIdx]['file_url'] ?? '';
        }
        if ($fileUrl === '') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาอัปโหลดไฟล์ PDF หรือระบุ URL ไฟล์ PDF']);
        }

        // 2. อัปโหลดภาพหน้าปก (ถ้ามี — ถ้าไม่มีระบบจะสร้างปก 3D ให้อัตโนมัติ)
        $coverFile = $this->request->getFile('cover_file');
        if ($coverFile && $coverFile->isValid() && !$coverFile->hasMoved()) {
            $cExt = strtolower($coverFile->getClientExtension());
            if (!in_array($cExt, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'ภาพหน้าปกรองรับเฉพาะไฟล์ .jpg, .png, .webp เท่านั้น']);
            }
            $coverDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'ebooks' . DIRECTORY_SEPARATOR . 'covers';
            if (!is_dir($coverDir)) {
                @mkdir($coverDir, 0775, true);
            }
            $newCoverName = 'cover_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $cExt;
            $coverFile->move($coverDir, $newCoverName);
            $coverImage = 'uploads/ebooks/covers/' . $newCoverName;
        }

        $now = date('Y-m-d H:i:s');
        $entry = [
            'id'          => ($existingIdx >= 0) ? $id : ('eb_' . time() . '_' . mt_rand(100, 999)),
            'title'       => $title,
            'category'    => $category,
            'year'        => $year,
            'pages'       => $pages,
            'file_url'    => $fileUrl,
            'file_size'   => $fileSize,
            'theme'       => $theme,
            'cover_image' => $coverImage,
            'created_at'  => ($existingIdx >= 0) ? ($list[$existingIdx]['created_at'] ?? $now) : $now,
            'updated_at'  => $now,
        ];

        if ($existingIdx >= 0) {
            $list[$existingIdx] = $entry;
        } else {
            array_unshift($list, $entry);
        }

        if (!$this->saveCustomEbooks($list)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกไฟล์ข้อมูล E-Book ได้']);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => ($existingIdx >= 0 ? 'อัปเดตหนังสือ E-Book "' : 'เพิ่มหนังสือ E-Book "') . $title . '" เรียบร้อยแล้ว!',
            'data'    => $entry,
        ]);
    }

    /**
     * ลบหนังสือ E-Book ที่เพิ่มเอง
     */
    public function delete($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบเจ้าหน้าที่ก่อนดำเนินการ']);
        }
        $id = trim((string) $id);
        if ($id === '') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบรหัสหนังสือ']);
        }

        $list = $this->loadCustomEbooks();
        $filtered = [];
        $deletedTitle = '';
        foreach ($list as $item) {
            if (($item['id'] ?? '') === $id) {
                $deletedTitle = $item['title'] ?? '';
            } else {
                $filtered[] = $item;
            }
        }

        $this->saveCustomEbooks($filtered);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'ลบหนังสือ "' . $deletedTitle . '" ออกจากชั้นหนังสือเรียบร้อยแล้ว',
        ]);
    }

    /**
     * หน้าตัวอ่าน E-Book แบบพลิกหน้าได้ (Flipbook Viewer)
     * รองรับ:
     *   /ebook/view?book=5yr หรือ book=1yr (สมุดแผน 3D หน้าแรก)
     *   /ebook/view?doc=<doc_id> (เอกสารในหน้า /strategy)
     *   /ebook/view?file=<path_or_url>&title=<title> (ไฟล์ PDF ทั่วไปในระบบ)
     */
    public function view()
    {
        helper(['settings']);

        $bookKey = trim((string) $this->request->getGet('book'));
        $docId   = trim((string) $this->request->getGet('doc'));
        $file    = trim((string) $this->request->getGet('file'));
        $title   = trim((string) $this->request->getGet('title'));
        $backUrl = trim((string) $this->request->getGet('back'));

        $category = 'หนังสืออิเล็กทรอนิกส์ (E-Book)';
        $period   = '';
        $fileUrl  = '';

        // 1. เปิดจากสมุดแผน 3D หน้าแรก (5yr / 1yr)
        if ($bookKey !== '') {
            $strategy  = get_site_strategy();
            $planBooks = $strategy['plan_books'] ?? [];
            $key       = ($bookKey === '1yr' || $bookKey === 'book_1yr') ? 'book_1yr' : 'book_5yr';
            $book      = $planBooks[$key] ?? null;
            if ($book) {
                $title    = $book['title'] ?? 'แผนพัฒนาจังหวัดพัทลุง';
                $period   = $book['period'] ?? '';
                if ($period !== '') {
                    $title .= ' (' . $period . ')';
                }
                $category = $book['type_label'] ?? 'แผนพัฒนาจังหวัด';
                $fileUrl  = $book['url'] ?? '';
                if ($backUrl === '') {
                    $backUrl = base_url('/');
                }
            }
        }
        // 2. เปิดจากรายการเอกสารยุทธศาสตร์ (/strategy)
        elseif ($docId !== '') {
            $strategy = get_site_strategy();
            $docs     = $strategy['documents'] ?? [];
            foreach ($docs as $d) {
                if (($d['id'] ?? '') === $docId) {
                    $title    = $d['title'] ?? 'เอกสารยุทธศาสตร์';
                    $category = $d['category'] ?? 'เอกสารแผนพัฒนาจังหวัด';
                    $period   = !empty($d['year']) ? 'ปีงบประมาณ ' . $d['year'] : '';
                    $fileUrl  = $d['file_url'] ?? '';
                    break;
                }
            }
            if ($backUrl === '') {
                $backUrl = base_url('strategy');
            }
        }
        // 3. เปิดจากพารามิเตอร์ file โดยตรง
        elseif ($file !== '') {
            $fileUrl = $file;
            if ($title === '') {
                $title = pathinfo(parse_url($file, PHP_URL_PATH) ?: $file, PATHINFO_FILENAME) ?: 'เอกสาร E-Book';
            }
            if ($backUrl === '') {
                $backUrl = base_url('strategy');
            }
        }

        if ($backUrl === '') {
            $backUrl = base_url('/');
        }

        // แปลง URL ให้พร้อมใช้งานทั้ง stream (แก้ CORS) และดาวน์โหลด
        $resolvedDirectUrl = '';
        $streamUrl         = '';
        $isValidPdf        = false;

        if ($fileUrl !== '') {
            if (preg_match('#^https?://#i', $fileUrl)) {
                $resolvedDirectUrl = $fileUrl;
                $host = strtolower((string) parse_url($fileUrl, PHP_URL_HOST));
                $allowedProxyHosts = ['phatthalung.go.th', 'www.phatthalung.go.th', 'bb.go.th', 'www.bb.go.th'];
                if (in_array($host, $allowedProxyHosts, true)) {
                    $streamUrl = base_url('ebook/stream?src=' . urlencode($fileUrl));
                } else {
                    $streamUrl = $fileUrl;
                }
                $isValidPdf = true;
            } else {
                $cleanRel = ltrim(str_replace('\\', '/', $fileUrl), '/');
                if (strpos($cleanRel, '..') === false) {
                    $resolvedDirectUrl = base_url($cleanRel);
                    // ไฟล์อยู่ในโดเมนเดียวกัน โหลดตรงจาก static file จะเร็วกว่าและรองรับ Content-Length/Range เต็มรูปแบบ
                    $streamUrl         = $resolvedDirectUrl;
                    $isValidPdf        = true;
                }
            }
        }

        return view('ebook/viewer', [
            'title'       => $title ?: 'ระบบอ่านหนังสืออิเล็กทรอนิกส์ (E-Book) จังหวัดพัทลุง',
            'bookTitle'   => $title ?: 'ไม่พบข้อมูลเอกสาร',
            'category'    => $category,
            'period'      => $period,
            'streamUrl'   => $streamUrl,
            'downloadUrl' => $resolvedDirectUrl,
            'backUrl'     => $backUrl,
            'isValidPdf'  => $isValidPdf,
            'docId'       => $docId,
        ]);
    }

    /**
     * Stream ไฟล์ PDF สำหรับ PDF.js (ช่วยป้องกันปัญหา CORS และควบคุมความปลอดภัย)
     */
    public function stream()
    {
        $src = trim((string) $this->request->getGet('src'));
        if ($src === '') {
            return $this->response->setStatusCode(400)->setBody('Missing source');
        }

        // กรณีเป็น URL ของ phatthalung.go.th หรือ bb.go.th (สำนักงบประมาณ)
        if (preg_match('#^https?://#i', $src)) {
            $host = strtolower((string) parse_url($src, PHP_URL_HOST));
            $allowedProxyHosts = ['phatthalung.go.th', 'www.phatthalung.go.th', 'bb.go.th', 'www.bb.go.th'];
            if (!in_array($host, $allowedProxyHosts, true)) {
                return $this->response->setStatusCode(403)->setBody('Forbidden host');
            }

            $ch = curl_init($src);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS      => 3,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT      => 'PhatthalungEbookViewer/1.0',
            ]);
            $body     = curl_exec($ch);
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($body === false || $httpCode >= 400) {
                return $this->response->setStatusCode(404)->setBody('PDF not found');
            }

            return $this->response
                ->setStatusCode(200)
                ->setHeader('Content-Type', 'application/pdf')
                ->setHeader('Content-Disposition', 'inline; filename="ebook.pdf"')
                ->setHeader('Cache-Control', 'public, max-age=3600')
                ->setBody($body);
        }

        // กรณีเป็นไฟล์ Local ในโฟลเดอร์ public/uploads/ หรือ public/assets/
        $cleanRel = ltrim(str_replace('\\', '/', $src), '/');
        if (strpos($cleanRel, '..') !== false) {
            return $this->response->setStatusCode(403)->setBody('Invalid path');
        }

        if (!preg_match('#^(uploads|assets)/.+\.pdf$#i', $cleanRel)) {
            return $this->response->setStatusCode(403)->setBody('Only PDF files in uploads/assets are allowed');
        }

        $fullPath = FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $cleanRel);
        if (!is_file($fullPath)) {
            return $this->response->setStatusCode(404)->setBody('PDF file not found on server');
        }

        $fileName = basename($fullPath);
        return $this->response
            ->setStatusCode(200)
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . addslashes($fileName) . '"')
            ->setHeader('Content-Length', (string) filesize($fullPath))
            ->setHeader('Accept-Ranges', 'bytes')
            ->setHeader('Cache-Control', 'public, max-age=3600')
            ->setBody(file_get_contents($fullPath));
    }
}
