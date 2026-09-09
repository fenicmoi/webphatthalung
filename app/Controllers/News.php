<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class News extends BaseController
{
    use ResponseTrait;

    private function getNewsPath(): string
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        return $writableDir . DIRECTORY_SEPARATOR . 'site_news.json';
    }

    private function getCategoriesPath(): string
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../writable');
        if (!is_dir($writableDir)) {
            @mkdir($writableDir, 0777, true);
        }
        return $writableDir . DIRECTORY_SEPARATOR . 'news_categories.json';
    }

    /**
     * หน้าแสดงรายการข่าวประชาสัมพันธ์ทั้งหมด (Public Grid & Filtering)
     */
    public function index()
    {
        helper('settings');
        $cat = $this->request->getGet('category');
        $categories = get_news_categories();

        $prdCategoryName = 'ข่าวประชาสัมพันธ์ (สปชส.พัทลุง)';
        if (!in_array($prdCategoryName, $categories, true)) {
            $categories[] = $prdCategoryName;
        }

        $egpService = new \App\Libraries\EGpService();
        $isProcurementCat = !empty($cat) && (mb_stripos($cat, 'จัดซื้อจัดจ้าง') !== false || mb_stripos($cat, 'e-gp') !== false);
        $egpProjects = $isProcurementCat ? $egpService->getPhatthalungProjects() : [];

        $isPrdCat = !empty($cat) && (mb_stripos($cat, 'สปชส') !== false || mb_stripos($cat, 'สำนักงานประชาสัมพันธ์') !== false || mb_stripos($cat, 'กรมประชาสัมพันธ์') !== false || mb_stripos($cat, 'nnt') !== false || mb_stripos($cat, 'prd') !== false);
        $prdNewsList = \App\Libraries\PrdNewsService::getPhatthalungNews(24);

        if ($isPrdCat) {
            $newsList = $prdNewsList;
        } elseif ($isProcurementCat) {
            $newsList = get_site_news(null, $cat, true);
        } else {
            $newsList = get_site_news(null, $cat, true);
        }

        return view('news/index', [
            'newsList'          => $newsList,
            'categories'        => $categories,
            'currentCat'        => $cat,
            'isProcurementCat'  => $isProcurementCat,
            'isPrdCat'          => $isPrdCat,
            'egpProjects'       => $egpProjects,
            'pageTitle'         => !empty($cat) ? esc($cat) . ' | ข่าวสารและประกาศ' : 'ข่าวสารและประกาศจากสำนักงาน'
        ]);
    }

    /**
     * หน้าอ่านบทความฉบับเต็ม (Article Detail & Reading Room)
     */
    public function detail($id = null)
    {
        helper('settings');
        if (empty($id)) {
            return redirect()->to(base_url('news'));
        }

        // Check if this is a PRD News item (from NNT or สปชส.พัทลุง)
        if (strpos((string)$id, 'prd-') === 0 || strpos((string)$id, 'ptl-prd-') === 0 || strpos((string)$id, 'ptl-') === 0) {
            $prdNewsList = \App\Libraries\PrdNewsService::getPhatthalungNews(50);
            $foundPrd = null;
            foreach ($prdNewsList as $item) {
                if (
                    $item['id'] === $id 
                    || strval($item['prd_news_id'] ?? '') === str_replace(['ptl-prd-', 'prd-', 'ptl-'], '', $id)
                    || strval($item['id'] ?? '') === 'ptl-prd-' . $id
                    || strval($item['id'] ?? '') === 'prd-' . $id
                ) {
                    $foundPrd = $item;
                    break;
                }
            }
            if ($foundPrd) {
                $recentNews = array_slice($prdNewsList, 0, 4);
                return view('news/detail', [
                    'news'       => $foundPrd,
                    'recentNews' => $recentNews,
                    'pageTitle'  => $foundPrd['title']
                ]);
            }
        }

        $news = get_news_by_id($id);
        if (!$news) {
            return redirect()->to(base_url('news'))->with('error', 'ไม่พบข่าวสารที่ท่านค้นหา หรือถูกยกเลิกการเผยแพร่ออกไปแล้ว');
        }

        // Increment view count
        $this->incrementViews($id);

        $recentNews = get_site_news(4, null, true);

        return view('news/detail', [
            'news'       => $news,
            'recentNews' => $recentNews,
            'pageTitle'  => $news['title']
        ]);
    }

    private function incrementViews($id)
    {
        $path = $this->getNewsPath();
        if (is_file($path)) {
            $all = json_decode(file_get_contents($path), true);
            if (is_array($all)) {
                $changed = false;
                foreach ($all as &$item) {
                    if (strval($item['id']) === strval($id)) {
                        $item['views'] = isset($item['views']) ? ((int)$item['views'] + 1) : 1;
                        $changed = true;
                        break;
                    }
                }
                if ($changed) {
                    @file_put_contents($path, json_encode($all, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                }
            }
        }
    }

    /**
     * ดึงข้อมูลข่าวสาร 1 รายการเป็น JSON เพื่อใช้เปิดหน้าต่างแก้ข่าวบนหน้าบ้าน
     */
    public function getJson($id = null)
    {
        helper('settings');
        $news = get_news_by_id($id);
        if ($news) {
            return $this->respond(['status' => 'success', 'data' => $news]);
        }
        return $this->respond(['status' => 'error', 'message' => 'ไม่พบข้อมูลบทความ']);
    }

    /**
     * บันทึกหรือสร้างข่าวสารใหม่จาก On-Page News Studio (Officer/Admin only)
     */
    public function save()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->respond(['status' => 'error', 'message' => 'กรุณาสู่ระบบเจ้าหน้าที่ก่อนจัดการข้อมูล'], 401);
        }

        $id = $this->request->getPost('id');
        if (empty($id)) {
            $id = 'news-' . time() . '-' . mt_rand(100, 999);
        }

        $title = trim((string)$this->request->getPost('title'));
        $category = trim((string)$this->request->getPost('category'));
        $summary = trim((string)$this->request->getPost('summary'));
        $content = trim((string)$this->request->getPost('content'));
        $coverImage = trim((string)$this->request->getPost('cover_image'));
        
        $isEvent = !empty($this->request->getPost('is_event')) && $this->request->getPost('is_event') !== '0' && $this->request->getPost('is_event') !== 'false';
        $eventStartDate = trim((string)$this->request->getPost('event_start_date'));
        $eventEndDate = trim((string)$this->request->getPost('event_end_date'));
        $eventLocation = trim((string)$this->request->getPost('event_location'));
        $eventCoordinates = trim((string)$this->request->getPost('event_coordinates'));
        
        $imagesGalleryRaw = $this->request->getPost('images_gallery');
        $imagesGallery = [];
        if (!empty($imagesGalleryRaw)) {
            $imagesGallery = is_array($imagesGalleryRaw) ? $imagesGalleryRaw : json_decode($imagesGalleryRaw, true);
            if (!is_array($imagesGallery)) {
                $imagesGallery = [$imagesGalleryRaw];
            }
        }

        $attachmentsRaw = $this->request->getPost('attachments');
        $attachments = [];
        if (!empty($attachmentsRaw)) {
            $attachments = is_array($attachmentsRaw) ? $attachmentsRaw : json_decode($attachmentsRaw, true);
            if (!is_array($attachments)) {
                $attachments = [];
            }
        }

        if (empty($title) || empty($content)) {
            return $this->respond(['status' => 'error', 'message' => 'กรุณากรอกหัวข้อข่าวและเนื้อหาให้ครบถ้วน']);
        }

        helper('settings');
        $allNews = get_site_news(null, null, false);
        
        $foundIndex = -1;
        foreach ($allNews as $idx => $item) {
            if (strval($item['id']) === strval($id)) {
                $foundIndex = $idx;
                break;
            }
        }

        $now = date('Y-m-d H:i:s');
        $newEntry = [
            'id' => $id,
            'title' => $title,
            'category' => !empty($category) ? $category : 'ประกาศราชการ / แจ้งเตือน',
            'summary' => !empty($summary) ? $summary : mb_substr(strip_tags($content), 0, 160, 'UTF-8') . '...',
            'content' => $content,
            'cover_image' => !empty($coverImage) ? $coverImage : (!empty($imagesGallery[0]) ? $imagesGallery[0] : 'assets/images/slider/sane_muanglung.png'),
            'cover_fit' => trim((string)$this->request->getPost('cover_fit')) ?: 'cover',
            'is_event' => $isEvent,
            'event_start_date' => $isEvent ? ($eventStartDate ?: date('Y-m-d')) : '',
            'event_end_date' => $isEvent ? ($eventEndDate ?: ($eventStartDate ?: date('Y-m-d'))) : '',
            'event_location' => $isEvent ? $eventLocation : '',
            'event_coordinates' => $isEvent ? $eventCoordinates : '',
            'images_gallery' => $imagesGallery,
            'attachments' => $attachments,
            'views' => ($foundIndex >= 0 && isset($allNews[$foundIndex]['views'])) ? (int)$allNews[$foundIndex]['views'] : 1,
            'created_at' => ($foundIndex >= 0 && isset($allNews[$foundIndex]['created_at'])) ? $allNews[$foundIndex]['created_at'] : $now,
            'updated_at' => $now,
            'active' => true
        ];

        if ($foundIndex >= 0) {
            $allNews[$foundIndex] = $newEntry;
        } else {
            array_unshift($allNews, $newEntry);
        }

        $path = $this->getNewsPath();
        $writableDir = dirname($path);

        // 1. สำรองข้อมูล backup เสมอก่อนบันทึก เพื่อป้องกันไฟล์สูญหาย
        if (file_exists($path)) {
            $backupPath = $writableDir . DIRECTORY_SEPARATOR . 'site_news.backup.json';
            @copy($path, $backupPath);
        }

        // 2. บันทึกลง MySQL Database ตาราง news เป็นหลัก (Primary Source of Truth)
        $dbError = null;
        $insertedDbId = null;
        try {
            $db = \Config\Database::connect();
            
            // ตรวจสอบและสร้างตาราง news อัตโนมัติหากบนโฮสต์ยังไม่ได้สร้าง
            if (!$db->tableExists('news')) {
                $db->query("CREATE TABLE IF NOT EXISTS `news` (
                  `id` int unsigned NOT NULL AUTO_INCREMENT,
                  `title` varchar(255) NOT NULL,
                  `slug` varchar(255) NOT NULL,
                  `category` varchar(100) NOT NULL DEFAULT 'ข่าวประชาสัมพันธ์',
                  `content` longtext NOT NULL,
                  `thumbnail` varchar(255) DEFAULT NULL,
                  `status` enum('draft','published','archived') NOT NULL DEFAULT 'published',
                  `views_count` int NOT NULL DEFAULT '0',
                  `author_id` int unsigned DEFAULT NULL,
                  `created_at` datetime DEFAULT NULL,
                  `updated_at` datetime DEFAULT NULL,
                  PRIMARY KEY (`id`),
                  KEY `idx_slug` (`slug`),
                  KEY `idx_category` (`category`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
            }

            if ($db->tableExists('news')) {
                // ตรวจสอบรายการเดิมด้วย ID (ถ้าเป็นตัวเลข) หรือค้นหาจากชื่อหัวข้อ
                $existingDb = null;
                if (is_numeric($id)) {
                    $existingDb = $db->table('news')->where('id', (int)$id)->get()->getRowArray();
                }
                if (!$existingDb) {
                    $existingDb = $db->table('news')->where('title', $title)->get()->getRowArray();
                }

                // สร้าง slug ที่ปลอดภัยและไม่ซ้ำ
                $baseSlug = function_exists('url_title') ? url_title($title, '-', true) : '';
                if (empty($baseSlug) || mb_strlen($baseSlug) < 2) {
                    $baseSlug = 'news-' . time();
                }
                $baseSlug = mb_substr($baseSlug, 0, 240);
                $slug = $baseSlug;

                $slugQuery = $db->table('news')->where('slug', $slug);
                if ($existingDb) {
                    $slugQuery->where('id !=', $existingDb['id']);
                }
                if ($slugQuery->countAllResults() > 0) {
                    $slug = $baseSlug . '-' . mt_rand(100, 999);
                }

                $authorId = session()->get('user_id') ?? session()->get('id') ?? null;
                $dbData = [
                    'title'       => mb_substr($title, 0, 255),
                    'slug'        => mb_substr($slug, 0, 255),
                    'category'    => mb_substr(!empty($category) ? $category : 'ข่าวประชาสัมพันธ์', 0, 100),
                    'content'     => $content,
                    'thumbnail'   => mb_substr(!empty($coverImage) ? $coverImage : 'assets/images/slider/sane_muanglung.png', 0, 255),
                    'status'      => 'published',
                    'views_count' => ($foundIndex >= 0 && isset($allNews[$foundIndex]['views'])) ? (int)$allNews[$foundIndex]['views'] : 1,
                    'author_id'   => is_numeric($authorId) ? (int)$authorId : null,
                    'updated_at'  => $now,
                ];

                if ($existingDb) {
                    $db->table('news')->where('id', $existingDb['id'])->update($dbData);
                    $insertedDbId = $existingDb['id'];
                } else {
                    $dbData['created_at'] = $now;
                    $db->table('news')->insert($dbData);
                    $insertedDbId = $db->insertID();
                }

                if ($insertedDbId) {
                    $newEntry['id'] = $insertedDbId;
                    if ($foundIndex >= 0) {
                        $allNews[$foundIndex]['id'] = $insertedDbId;
                    } else {
                        $allNews[0]['id'] = $insertedDbId;
                    }
                }
            }
        } catch (\Throwable $e) {
            $dbError = $e->getMessage();
            log_message('error', 'Dual-sync news to DB error: ' . $e->getMessage());
        }

        // 3. บันทึกลง JSON เป็นแคชสำรอง
        @file_put_contents($path, json_encode(array_values($allNews), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $msg = 'บันทึกข่าวสารประชาสัมพันธ์เรียบร้อยแล้ว';
        if ($insertedDbId) {
            $msg .= " (บันทึกลงฐานข้อมูล MySQL ตาราง news [ID: {$insertedDbId}] เรียบร้อย)";
        }
        if ($dbError) {
            $msg .= " (หมายเหตุ: บันทึกไฟล์สำเร็จ แต่ฐานข้อมูล MySQL แจ้งเตือน: {$dbError})";
        }

        return $this->respond([
            'status'  => 'success',
            'message' => $msg,
            'data'    => $newEntry
        ]);
    }

    /**
     * ลบข่าวสาร (Officer/Admin only)
     */
    public function delete($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่มีสิทธิ์เข้าถึง'], 401);
        }

        if (empty($id)) {
            $id = $this->request->getPost('id');
        }

        helper('settings');
        $allNews = get_site_news(null, null, false);
        $newNews = [];
        $deleted = false;
        $deletedTitle = '';

        foreach ($allNews as $item) {
            if (strval($item['id']) !== strval($id)) {
                $newNews[] = $item;
            } else {
                $deleted = true;
                $deletedTitle = $item['title'] ?? '';
            }
        }

        if ($deleted || is_numeric($id)) {
            $path = $this->getNewsPath();
            @file_put_contents($path, json_encode(array_values($newNews), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            // ลบจาก DB
            try {
                $db = \Config\Database::connect();
                if ($db->tableExists('news')) {
                    if (is_numeric($id)) {
                        $db->table('news')->where('id', (int)$id)->delete();
                    } elseif (!empty($deletedTitle)) {
                        $db->table('news')->where('title', $deletedTitle)->delete();
                    }
                }
            } catch (\Throwable $e) {
                log_message('error', 'Delete news from DB error: ' . $e->getMessage());
            }

            return $this->respond(['status' => 'success', 'message' => 'ลบรายการข่าวเรียบร้อยแล้ว']);
        }

        return $this->respond(['status' => 'error', 'message' => 'ไม่พบรายการที่ต้องการลบ']);
    }

    /**
     * API สำหรับอัปโหลดภาพ (Image Upload to public/uploads/news/)
     */
    public function uploadImage()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->respond(['status' => 'error', 'message' => 'กรุณาสู่ระบบก่อนอัปโหลด'], 401);
        }

        $file = $this->request->getFile('image');
        if (!$file || !$file->isValid()) {
            return $this->respond(['status' => 'error', 'message' => 'ไฟล์รูปภาพไม่ถูกต้อง หรือขนาดใหญ่เกินไป']);
        }

        // Validate extensions
        $ext = strtolower($file->getExtension());
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $allowed)) {
            return $this->respond(['status' => 'error', 'message' => 'อนุญาตเฉพาะไฟล์ภาพนามสกุล jpg, png, webp, gif เท่านั้น']);
        }

        $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'news';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        $newName = 'news_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
        if ($file->move($uploadDir, $newName)) {
            $relPath = 'uploads/news/' . $newName;
            $fullUrl = base_url($relPath);
            return $this->respond([
                'status' => 'success',
                'message' => 'อัปโหลดภาพเสร็จสิ้น',
                'path' => $relPath,
                'url' => $fullUrl
            ]);
        }

        return $this->respond(['status' => 'error', 'message' => 'ไม่สามารถบันทึกภาพในเซิร์ฟเวอร์ได้']);
    }

    /**
     * API สำหรับอัปโหลดไฟล์เอกสาร (Document Upload to public/uploads/docs/)
     */
    public function uploadDoc()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->respond(['status' => 'error', 'message' => 'กรุณาสู่ระบบก่อนอัปโหลด'], 401);
        }

        $file = $this->request->getFile('document');
        if (!$file || !$file->isValid()) {
            return $this->respond(['status' => 'error', 'message' => 'ไฟล์เอกสารไม่ถูกต้อง หรือขนาดเกินจำกัด']);
        }

        $ext = strtolower($file->getExtension());
        $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', 'txt'];
        if (!in_array($ext, $allowed)) {
            return $this->respond(['status' => 'error', 'message' => 'อนุญาตเฉพาะไฟล์ PDF, Word, Excel, PowerPoint และ Zip เท่านั้น']);
        }

        $sizeKB = round($file->getSizeByUnit('kb'), 1);
        $sizeStr = $sizeKB > 1024 ? round($sizeKB / 1024, 2) . ' MB' : $sizeKB . ' KB';
        $originalName = $file->getClientName();

        $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'docs';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        $newName = 'doc_' . time() . '_' . mt_rand(100, 999) . '.' . $ext;
        if ($file->move($uploadDir, $newName)) {
            $relPath = 'uploads/docs/' . $newName;
            $fullUrl = base_url($relPath);
            
            // Map icon by extension
            $icon = 'fa-solid fa-file';
            if ($ext === 'pdf') $icon = 'fa-solid fa-file-pdf text-danger';
            elseif (in_array($ext, ['doc', 'docx'])) $icon = 'fa-solid fa-file-word text-primary';
            elseif (in_array($ext, ['xls', 'xlsx'])) $icon = 'fa-solid fa-file-excel text-success';
            elseif (in_array($ext, ['zip', 'rar'])) $icon = 'fa-solid fa-file-zipper text-warning';

            return $this->respond([
                'status' => 'success',
                'message' => 'อัปโหลดไฟล์เอกสารเสร็จสิ้น',
                'file_name' => $originalName,
                'path' => $relPath,
                'url' => $fullUrl,
                'size' => $sizeStr,
                'ext' => strtoupper($ext),
                'icon_class' => $icon
            ]);
        }

        return $this->respond(['status' => 'error', 'message' => 'ไม่สามารถบันทึกเอกสารในเซิร์ฟเวอร์ได้']);
    }

    /**
     * บันทึกหรือเพิ่มหมวดหมู่ข่าวใหม่
     */
    public function saveCategory()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่มีสิทธิ์เข้าถึง'], 401);
        }

        $newCategory = trim((string)$this->request->getPost('category_name'));
        if (empty($newCategory)) {
            return $this->respond(['status' => 'error', 'message' => 'กรุณาระบุชื่อหมวดหมู่ที่ต้องการสร้าง']);
        }

        helper('settings');
        $cats = get_news_categories();

        if (!in_array($newCategory, $cats)) {
            $cats[] = $newCategory;
            @file_put_contents($this->getCategoriesPath(), json_encode(array_values($cats), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        return $this->respond([
            'status' => 'success',
            'message' => 'เพิ่มหมวดหมู่ใหม่เรียบร้อยแล้ว',
            'categories' => $cats
        ]);
    }

    /**
     * ปรับสัดส่วนขนาดภาพหน้าปก (Cover Image Resizer & Fit Optimizer via GD)
     */
    public function resizeCover()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->respond(['status' => 'error', 'message' => 'กรุณาสู่ระบบก่อนจัดการภาพ'], 401);
        }

        $imagePath = trim((string)$this->request->getPost('image_path'));
        $mode = trim((string)$this->request->getPost('mode')) ?: '16_9';

        if (empty($imagePath)) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่พบข้อมูลเส้นทางรูปภาพ']);
        }

        // Clean base_url if present to find local file
        $baseUrl = rtrim(base_url(), '/') . '/';
        $relPath = (strpos($imagePath, $baseUrl) === 0) ? substr($imagePath, strlen($baseUrl)) : $imagePath;
        $relPath = ltrim($relPath, '/');

        if ((strpos($relPath, 'http://') === 0) || (strpos($relPath, 'https://') === 0)) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่สามารถปรับขนาดภาพจากเซิร์ฟเวอร์ภายนอกได้ กรุณาอัปโหลดภาพเข้าสู่ระบบก่อน']);
        }

        $fullPath = FCPATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relPath);
        if (!file_exists($fullPath)) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่พบไฟล์รูปภาพในระบบ: ' . esc($relPath)]);
        }

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            return $this->respond(['status' => 'error', 'message' => 'รองรับเฉพาะไฟล์รูปภาพ (jpg, png, webp)']);
        }

        $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'news';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        $newName = 'cover_fit_' . time() . '_' . mt_rand(100, 999) . '.' . $ext;
        $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $newName;

        try {
            $image = \Config\Services::image();
            $handler = $image->withFile($fullPath);

            if ($mode === '16_9') {
                $handler->fit(1280, 720, 'center');
            } elseif ($mode === '4_3') {
                $handler->fit(1024, 768, 'center');
            } elseif ($mode === '1_1') {
                $handler->fit(800, 800, 'center');
            } elseif ($mode === 'optimize') {
                $handler->resize(1280, 1280, true, 'auto');
            } else {
                $handler->fit(1280, 720, 'center');
            }

            $handler->save($targetPath);
            $newRelPath = 'uploads/news/' . $newName;

            return $this->respond([
                'status' => 'success',
                'message' => 'ปรับมิติสัดส่วนภาพหน้าปกเรียบร้อยแล้ว!',
                'path' => $newRelPath,
                'url' => base_url($newRelPath),
                'mode' => $mode
            ]);
        } catch (\Throwable $e) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่สามารถประมวลผลกราฟิกได้: ' . $e->getMessage()]);
        }
    }
}
