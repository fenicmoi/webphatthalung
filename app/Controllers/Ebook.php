<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Ebook extends Controller
{
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
                if (in_array($host, ['phatthalung.go.th', 'www.phatthalung.go.th'], true)) {
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

        // กรณีเป็น URL ของ phatthalung.go.th (เช่น ไฟล์จาก /2022/files/...)
        if (preg_match('#^https?://#i', $src)) {
            $host = strtolower((string) parse_url($src, PHP_URL_HOST));
            if (!in_array($host, ['phatthalung.go.th', 'www.phatthalung.go.th'], true)) {
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
