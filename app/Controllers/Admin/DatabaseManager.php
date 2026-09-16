<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Config\Database;
use Config\Services;

/**
 * Phatthalung Digital Government Portal - Database & Hosting Table Manager
 * เครื่องมือตรวจสอบ อัปเดตตาราง และซิงค์โครงสร้างฐานข้อมูลบน Hosting
 */
class DatabaseManager extends BaseController
{
    use ResponseTrait;

    private string $tokenFile;

    public function __construct()
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../../writable');
        if (!$writableDir) {
            $writableDir = __DIR__ . '/../../../writable';
        }
        $this->tokenFile = $writableDir . DIRECTORY_SEPARATOR . 'db_update_token.txt';
    }

    /**
     * รับรหัส Secret Token สำหรับ Hosting Direct Update
     */
    private function getSecretToken(): string
    {
        if (file_exists($this->tokenFile)) {
            $token = trim((string)@file_get_contents($this->tokenFile));
            if (!empty($token)) {
                return $token;
            }
        }

        // สร้าง Token ตั้งต้นที่ปลอดภัย
        $newToken = 'ptl_' . bin2hex(random_bytes(16));
        @file_put_contents($this->tokenFile, $newToken);
        return $newToken;
    }

    /**
     * หน้าแรกของเครื่องมือจัดการฐานข้อมูลและตาราง
     */
    public function index()
    {
        $db = Database::connect();
        
        // 1. ข้อมูลการเชื่อมต่อฐานข้อมูล
        $dbInfo = [
            'database'  => $db->getDatabase(),
            'hostname'  => property_exists($db, 'hostname') ? $db->hostname : 'localhost',
            'username'  => property_exists($db, 'username') ? $db->username : 'root',
            'driver'    => property_exists($db, 'DBDriver') ? $db->DBDriver : 'MySQLi',
            'prefix'    => $db->getPrefix(),
            'version'   => $db->getVersion(),
            'charset'   => property_exists($db, 'charset') ? $db->charset : 'utf8mb4',
            'status'    => 'connected'
        ];

        // 2. ข้อมูลตารางทั้งหมดในฐานข้อมูล
        $tables = [];
        $totalRows = 0;
        $totalSizeKb = 0;

        try {
            $query = $db->query("SHOW TABLE STATUS FROM `" . $dbInfo['database'] . "`");
            $rows = $query->getResultArray();
            foreach ($rows as $row) {
                $tableName = $row['Name'];
                $rowCount = (int)($row['Rows'] ?? 0);
                $dataLength = (int)($row['Data_length'] ?? 0);
                $indexLength = (int)($row['Index_length'] ?? 0);
                $sizeKb = round(($dataLength + $indexLength) / 1024, 2);

                $totalRows += $rowCount;
                $totalSizeKb += $sizeKb;

                $tables[] = [
                    'name'       => $tableName,
                    'engine'     => $row['Engine'] ?? 'InnoDB',
                    'rows'       => $rowCount,
                    'size_kb'    => $sizeKb,
                    'collation'  => $row['Collation'] ?? 'utf8mb4_unicode_ci',
                    'updated_at' => $row['Update_time'] ?? '-'
                ];
            }
        } catch (\Throwable $e) {
            log_message('error', 'Error fetching table status: ' . $e->getMessage());
        }

        // 3. รายการ Migrations (ประวัติการรันและไฟล์ที่รอรัน)
        $migrationData = $this->getMigrationsStatus();

        // 4. ตรวจสอบคอลัมน์สำคัญ (Schema Health Check)
        $schemaChecks = $this->checkCriticalColumns();

        // 5. ข้อมูลไฟล์ JSON สำรองใน writable
        $jsonCaches = $this->getJsonCachesStatus();

        // 6. ข้อมูลไฟล์ db/webphatthalung.sql บนเซิร์ฟเวอร์
        $defaultSqlFile = [
            'exists'         => false,
            'path'           => 'db/webphatthalung.sql',
            'size'           => 0,
            'size_formatted' => '0 KB',
            'mtime'          => '-'
        ];
        $sqlPath = ROOTPATH . 'db' . DIRECTORY_SEPARATOR . 'webphatthalung.sql';
        if (file_exists($sqlPath)) {
            $fsize = filesize($sqlPath);
            $defaultSqlFile = [
                'exists'         => true,
                'path'           => 'db/webphatthalung.sql',
                'size'           => $fsize,
                'size_formatted' => round($fsize / 1024, 1) . ' KB (' . round($fsize / (1024 * 1024), 2) . ' MB)',
                'mtime'          => date('Y-m-d H:i:s', filemtime($sqlPath))
            ];
        }

        $secretToken = $this->getSecretToken();
        $directUpdateUrl = base_url('admin/database-manager/direct-update?token=' . $secretToken);

        return view('admin/database_manager', [
            'title'           => 'จัดการโครงสร้างตาราง & ฐานข้อมูล | Phatthalung Admin',
            'activeMenu'      => 'database_manager',
            'dbInfo'          => $dbInfo,
            'tables'          => $tables,
            'totalTables'     => count($tables),
            'totalRows'       => $totalRows,
            'totalSizeMb'     => round($totalSizeKb / 1024, 2),
            'migrationData'   => $migrationData,
            'schemaChecks'    => $schemaChecks,
            'jsonCaches'      => $jsonCaches,
            'defaultSqlFile'  => $defaultSqlFile,
            'secretToken'     => $secretToken,
            'directUpdateUrl' => $directUpdateUrl
        ]);
    }

    /**
     * ตรวจสอบสถานะของ Migrations ทั้งหมด
     */
    private function getMigrationsStatus(): array
    {
        $db = Database::connect();
        $hasMigrationsTable = $db->tableExists('migrations');
        $executed = [];

        if ($hasMigrationsTable) {
            $rows = $db->table('migrations')->orderBy('id', 'ASC')->get()->getResultArray();
            foreach ($rows as $r) {
                $executed[$r['version']] = $r;
            }
        }

        $allFiles = [];
        $migrationPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR . 'Migrations';
        if (is_dir($migrationPath)) {
            $files = scandir($migrationPath);
            foreach ($files as $f) {
                if (preg_match('/^([0-9\-_]+)_(.+)\.php$/', $f, $m)) {
                    $version = $m[1];
                    $className = $m[2];
                    $isRun = isset($executed[$version]);

                    $allFiles[] = [
                        'filename'    => $f,
                        'version'     => $version,
                        'class'       => $className,
                        'is_executed' => $isRun,
                        'batch'       => $isRun ? ($executed[$version]['batch'] ?? 1) : null,
                        'run_at'      => $isRun ? date('d/m/Y H:i:s', (int)($executed[$version]['time'] ?? time())) : null
                    ];
                }
            }
        }

        // เรียงลำดับตามเวอร์ชัน
        usort($allFiles, function($a, $b) {
            return strcmp($a['version'], $b['version']);
        });

        $pendingCount = 0;
        foreach ($allFiles as $item) {
            if (!$item['is_executed']) {
                $pendingCount++;
            }
        }

        return [
            'total'     => count($allFiles),
            'executed'  => count($executed),
            'pending'   => $pendingCount,
            'list'      => $allFiles
        ];
    }

    /**
     * ตรวจสอบคอลัมน์สำคัญในตารางหลัก
     */
    private function checkCriticalColumns(): array
    {
        $db = Database::connect();
        $checks = [
            'news' => [
                'name' => 'news (ข่าวสารและประกาศ)',
                'expected' => [
                    'id', 'title', 'slug', 'category', 'content', 'thumbnail',
                    'images_gallery', 'attachments', 'cover_fit', 'is_event',
                    'event_start_date', 'event_end_date', 'event_location', 'event_coordinates',
                    'status', 'views_count', 'created_at', 'updated_at'
                ]
            ],
            'pages' => [
                'name' => 'pages (หน้าเว็บและเนื้อหาอิสระ)',
                'expected' => [
                    'id', 'title', 'slug', 'header_image', 'parent_id', 'content', 'status', 'views_count'
                ]
            ],
            'gallery_albums' => [
                'name' => 'gallery_albums (อัลบั้มภาพกิจกรรม)',
                'expected' => [
                    'id', 'title', 'slug', 'category', 'cover_image', 'status', 'created_at'
                ]
            ],
            'gallery_photos' => [
                'name' => 'gallery_photos (รูปภาพในอัลบั้ม)',
                'expected' => [
                    'id', 'album_id', 'image_path', 'caption', 'order_num'
                ]
            ],
            'official_emails' => [
                'name' => 'official_emails (กล่องจดหมายกลาง MOI)',
                'expected' => [
                    'id', 'email_number', 'subject', 'attachments_json'
                ]
            ],
            'citizen_contacts' => [
                'name' => 'citizen_contacts (เรื่องติดต่อ & ร้องเรียน)',
                'expected' => [
                    'id', 'tracking_code', 'fullname', 'topic', 'status'
                ]
            ]
        ];

        $results = [];
        foreach ($checks as $tbl => $info) {
            if (!$db->tableExists($tbl)) {
                $results[$tbl] = [
                    'title'       => $info['name'],
                    'exists'      => false,
                    'missing'     => $info['expected'],
                    'status'      => 'missing_table',
                    'status_text' => 'ยังไม่มีตารางนี้ในฐานข้อมูล'
                ];
                continue;
            }

            $fields = $db->getFieldNames($tbl);
            $missing = array_diff($info['expected'], $fields);

            $results[$tbl] = [
                'title'       => $info['name'],
                'exists'      => true,
                'fields_count'=> count($fields),
                'missing'     => array_values($missing),
                'status'      => empty($missing) ? 'healthy' : 'warning',
                'status_text' => empty($missing) ? 'โครงสร้างสมบูรณ์ครบถ้วน' : 'ขาดคอลัมน์สำคัญ (' . count($missing) . ' รายการ)'
            ];
        }

        return $results;
    }

    /**
     * ตรวจสอบสถานะไฟล์ JSON แคช
     */
    private function getJsonCachesStatus(): array
    {
        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../../writable');
        $caches = [
            'site_news.json' => 'คลังข่าวสารและประกาศ (รวมแกลลอรี & ไฟล์แนบ)',
            'gallery_albums.json' => 'คลังอัลบั้มภาพกิจกรรมและสื่อ',
            'site_settings.json' => 'การตั้งค่าระบบและธีมเว็บไซต์',
            'dump_pages.json' => 'สำรองข้อมูลหน้าเว็บไซต์',
            'news_categories.json' => 'หมวดหมู่ข่าวสาร'
        ];

        $result = [];
        foreach ($caches as $file => $desc) {
            $path = $writableDir . DIRECTORY_SEPARATOR . $file;
            $exists = file_exists($path);
            $count = 0;
            $size = 0;
            $modTime = '-';

            if ($exists) {
                $size = round(filesize($path) / 1024, 2);
                $modTime = date('d/m/Y H:i:s', filemtime($path));
                $data = json_decode(@file_get_contents($path), true);
                if (is_array($data)) {
                    $count = count($data);
                }
            }

            $result[] = [
                'file'        => $file,
                'description' => $desc,
                'exists'      => $exists,
                'size_kb'     => $size,
                'items_count' => $count,
                'updated_at'  => $modTime
            ];
        }

        return $result;
    }

    /**
     * รัน Migrations อัตโนมัติ (One-Click Spark Migration Equivalent)
     */
    public function runMigrations()
    {
        $messages = [];
        $errors = [];

        try {
            $migrate = Services::migrations();
            
            // สั่งรัน Migration ทั้งหมดที่ยังค้างอยู่
            $success = $migrate->latest('default');

            if ($success) {
                $messages[] = 'รัน Migration โครงสร้างฐานข้อมูลสำเร็จเรียบร้อยแล้ว!';
            } else {
                $messages[] = 'ระบบตรวจสอบแล้ว ไม่พบไฟล์ Migration ใหม่ที่ต้องอัปเดต (โครงสร้างเป็นเวอร์ชันล่าสุดแล้ว)';
            }
        } catch (\Throwable $e) {
            $errors[] = $e->getMessage();
            log_message('error', 'Web Migration Error: ' . $e->getMessage());
        }

        $newStatus = $this->getMigrationsStatus();

        return $this->respond([
            'status'   => empty($errors) ? 'success' : 'error',
            'message'  => !empty($messages) ? implode(' ', $messages) : 'เกิดข้อผิดพลาดในการรัน Migration',
            'errors'   => $errors,
            'executed' => $newStatus['executed'],
            'pending'  => $newStatus['pending']
        ]);
    }

    /**
     * ซ่อมแซมและเพิ่มคอลัมน์สำคัญที่ขาดหายแบบ Direct Alter Table
     */
    public function repairColumns()
    {
        $db = Database::connect();
        $repaired = [];
        $errors = [];

        // 1. ตาราง news
        if ($db->tableExists('news')) {
            $cols = $db->getFieldNames('news');
            $newsAlter = [];

            if (!in_array('images_gallery', $cols)) {
                $newsAlter[] = "ADD COLUMN `images_gallery` LONGTEXT NULL AFTER `thumbnail`";
            }
            if (!in_array('attachments', $cols)) {
                $newsAlter[] = "ADD COLUMN `attachments` LONGTEXT NULL AFTER `images_gallery`";
            }
            if (!in_array('cover_fit', $cols)) {
                $newsAlter[] = "ADD COLUMN `cover_fit` VARCHAR(50) NOT NULL DEFAULT 'cover' AFTER `attachments`";
            }
            if (!in_array('is_event', $cols)) {
                $newsAlter[] = "ADD COLUMN `is_event` TINYINT(1) NOT NULL DEFAULT 0 AFTER `cover_fit`";
            }
            if (!in_array('event_start_date', $cols)) {
                $newsAlter[] = "ADD COLUMN `event_start_date` VARCHAR(50) NULL AFTER `is_event`";
            }
            if (!in_array('event_end_date', $cols)) {
                $newsAlter[] = "ADD COLUMN `event_end_date` VARCHAR(50) NULL AFTER `event_start_date`";
            }
            if (!in_array('event_location', $cols)) {
                $newsAlter[] = "ADD COLUMN `event_location` VARCHAR(255) NULL AFTER `event_end_date`";
            }
            if (!in_array('event_coordinates', $cols)) {
                $newsAlter[] = "ADD COLUMN `event_coordinates` VARCHAR(255) NULL AFTER `event_location`";
            }

            if (!empty($newsAlter)) {
                try {
                    $sql = "ALTER TABLE `news` " . implode(', ', $newsAlter);
                    $db->query($sql);
                    $repaired[] = 'ตาราง news: เพิ่มคอลัมน์ ' . count($newsAlter) . ' รายการเรียบร้อย';
                } catch (\Throwable $e) {
                    $errors[] = 'ตาราง news: ' . $e->getMessage();
                }
            }
        }

        // 2. ตาราง pages
        if ($db->tableExists('pages')) {
            $cols = $db->getFieldNames('pages');
            $pageAlter = [];

            if (!in_array('header_image', $cols)) {
                $pageAlter[] = "ADD COLUMN `header_image` VARCHAR(255) NULL AFTER `slug`";
            }
            if (!in_array('parent_id', $cols)) {
                $pageAlter[] = "ADD COLUMN `parent_id` INT UNSIGNED NULL AFTER `id`";
            }

            if (!empty($pageAlter)) {
                try {
                    $sql = "ALTER TABLE `pages` " . implode(', ', $pageAlter);
                    $db->query($sql);
                    $repaired[] = 'ตาราง pages: เพิ่มคอลัมน์ ' . count($pageAlter) . ' รายการเรียบร้อย';
                } catch (\Throwable $e) {
                    $errors[] = 'ตาราง pages: ' . $e->getMessage();
                }
            }
        }

        // 3. ตาราง official_emails
        if ($db->tableExists('official_emails')) {
            $cols = $db->getFieldNames('official_emails');
            if (!in_array('attachments_json', $cols)) {
                try {
                    $db->query("ALTER TABLE `official_emails` ADD COLUMN `attachments_json` LONGTEXT NULL AFTER `body_html`");
                    $repaired[] = 'ตาราง official_emails: เพิ่มคอลัมน์ attachments_json เรียบร้อย';
                } catch (\Throwable $e) {
                    $errors[] = 'ตาราง official_emails: ' . $e->getMessage();
                }
            }
        }

        $msg = !empty($repaired) 
            ? 'ซ่อมแซมและตรวจสอบคอลัมน์สำเร็จ: ' . implode(' | ', $repaired)
            : 'ทุกตารางมีคอลัมน์ครบถ้วนสมบูรณ์แล้ว ไม่พบคอลัมน์ที่ต้องซ่อมแซมเพิ่มเติม';

        return $this->respond([
            'status'   => empty($errors) ? 'success' : 'warning',
            'message'  => $msg,
            'repaired' => $repaired,
            'errors'   => $errors
        ]);
    }

    /**
     * ซิงค์ข้อมูลจากไฟล์ JSON แคชเข้าสู่ MySQL
     */
    public function syncData()
    {
        helper('settings');
        $db = Database::connect();
        $target = $this->request->getPost('target') ?? 'all';
        $results = [];

        // 1. ซิงค์ข่าวสารจาก site_news.json
        if ($target === 'all' || $target === 'news') {
            $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../../writable');
            $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_news.json';
            if (file_exists($jsonPath)) {
                $newsList = json_decode(@file_get_contents($jsonPath), true);
                if (is_array($newsList) && !empty($newsList)) {
                    save_site_news($newsList);
                    $results[] = 'ซิงค์ข้อมูลข่าวสาร (' . count($newsList) . ' ข่าว) เข้าสู่ MySQL ตาราง news เรียบร้อย (รวมรูปภาพและเอกสารแนบ)';
                }
            }
        }

        // 2. ซิงค์การตั้งค่าเว็บไซต์จาก site_settings.json
        if ($target === 'all' || $target === 'settings') {
            if ($db->tableExists('settings')) {
                $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../../writable');
                $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_settings.json';
                if (file_exists($jsonPath)) {
                    $setList = json_decode(@file_get_contents($jsonPath), true);
                    if (is_array($setList)) {
                        try {
                            $keyCol = in_array('setting_key', $db->getFieldNames('settings')) ? 'setting_key' : 'key';
                            $valCol = in_array('setting_value', $db->getFieldNames('settings')) ? 'setting_value' : 'value';

                            foreach ($setList as $k => $v) {
                                $strVal = is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : (string)$v;
                                $exist = $db->table('settings')->where($keyCol, $k)->get()->getRowArray();
                                if ($exist) {
                                    $db->table('settings')->where($keyCol, $k)->update([$valCol => $strVal, 'updated_at' => date('Y-m-d H:i:s')]);
                                } else {
                                    $insData = [$keyCol => $k, $valCol => $strVal, 'updated_at' => date('Y-m-d H:i:s')];
                                    if (in_array('setting_group', $db->getFieldNames('settings'))) {
                                        $insData['setting_group'] = 'general';
                                    }
                                    $db->table('settings')->insert($insData);
                                }
                            }
                            $results[] = 'ซิงค์ค่าติดตั้งเว็บไซต์ (' . count($setList) . ' รายการ) เข้าสู่ตาราง settings สำเร็จ';
                        } catch (\Throwable $setErr) {
                            $results[] = 'ซิงค์ค่าติดตั้ง settings: ' . $setErr->getMessage();
                        }
                    }
                }
            }
        }

        return $this->respond([
            'status'  => 'success',
            'message' => !empty($results) ? implode('<br>', $results) : 'ไม่พบข้อมูลที่ต้องซิงค์',
            'details' => $results
        ]);
    }

    /**
     * ดึงโครงสร้างคอลัมน์ของตารางเพื่อแสดงใน Modal
     */
    public function inspectTable($tableName = null)
    {
        if (empty($tableName)) {
            return $this->respond(['status' => 'error', 'message' => 'ระบุชื่อตารางไม่ถูกต้อง'], 400);
        }

        $db = Database::connect();
        if (!$db->tableExists($tableName)) {
            return $this->respond(['status' => 'error', 'message' => 'ไม่พบตาราง ' . esc($tableName)], 404);
        }

        try {
            $cols = $db->query("SHOW FULL COLUMNS FROM `" . $tableName . "`")->getResultArray();
            return $this->respond([
                'status'  => 'success',
                'table'   => $tableName,
                'columns' => $cols
            ]);
        } catch (\Throwable $e) {
            return $this->respond(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * รีเซ็ตรหัส Secret Token ใหม่
     */
    public function resetToken()
    {
        $newToken = 'ptl_' . bin2hex(random_bytes(16));
        @file_put_contents($this->tokenFile, $newToken);

        return $this->respond([
            'status'  => 'success',
            'token'   => $newToken,
            'url'     => base_url('admin/database-manager/direct-update?token=' . $newToken)
        ]);
    }

    /**
     * รันคำสั่ง SQL ผ่านเว็บเบราว์เซอร์โดยตรง (Web SQL Console)
     */
    public function executeSql()
    {
        $sql = trim((string)$this->request->getPost('sql'));
        if (empty($sql)) {
            return $this->respond(['status' => 'error', 'message' => 'กรุณากรอกคำสั่ง SQL ที่ต้องการรัน'], 400);
        }

        $db = Database::connect();
        try {
            $queries = $this->splitSql($sql);
            if (empty($queries)) {
                return $this->respond(['status' => 'error', 'message' => 'ไม่พบคำสั่ง SQL ที่ถูกต้อง'], 400);
            }

            $results = [];
            foreach ($queries as $q) {
                $trimmed = trim($q);
                if (empty($trimmed)) continue;

                $startTime = microtime(true);
                $queryRes = $db->query($trimmed);
                $duration = round((microtime(true) - $startTime) * 1000, 2);

                if ($queryRes instanceof \CodeIgniter\Database\BaseResult) {
                    $rows = $queryRes->getResultArray();
                    $columns = !empty($rows) ? array_keys($rows[0]) : [];
                    $results[] = [
                        'query'    => $trimmed,
                        'type'     => 'select',
                        'columns'  => $columns,
                        'rows'     => array_slice($rows, 0, 250), // แสดงสูงสุด 250 แถวเพื่อความเร็ว
                        'total'    => count($rows),
                        'duration' => $duration . ' ms'
                    ];
                } else {
                    $results[] = [
                        'query'    => $trimmed,
                        'type'     => 'exec',
                        'affected' => $db->affectedRows(),
                        'duration' => $duration . ' ms'
                    ];
                }
            }

            return $this->respond([
                'status'  => 'success',
                'message' => 'ประมวลผลคำสั่ง SQL สำเร็จ (' . count($results) . ' คำสั่ง)',
                'results' => $results
            ]);
        } catch (\Throwable $e) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'เกิดข้อผิดพลาด SQL: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * นำเข้าไฟล์ SQL (Import .sql file หรือไฟล์ db/webphatthalung.sql)
     */
    public function importSqlFile()
    {
        @set_time_limit(600);
        @ini_set('memory_limit', '512M');
        $db = Database::connect();

        $source = $this->request->getPost('source') ?? 'file';
        $sqlContent = '';
        $sourceName = '';

        if ($source === 'default_file') {
            $possible = [
                ROOTPATH . 'db' . DIRECTORY_SEPARATOR . 'webphatthalung.sql',
                ROOTPATH . 'public' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'webphatthalung.sql',
                ROOTPATH . 'webphatthalung.sql'
            ];
            foreach ($possible as $p) {
                if (file_exists($p)) {
                    $sqlContent = file_get_contents($p);
                    $sourceName = 'db/webphatthalung.sql บนเซิร์ฟเวอร์';
                    break;
                }
            }
            if (empty($sqlContent)) {
                return $this->respond(['status' => 'error', 'message' => 'ไม่พบไฟล์ db/webphatthalung.sql ในเซิร์ฟเวอร์'], 404);
            }
        } else {
            $file = $this->request->getFile('sql_file');
            if (!$file || !$file->isValid() || $file->hasMoved()) {
                return $this->respond(['status' => 'error', 'message' => 'กรุณาอัปโหลดไฟล์นามสกุล .sql ที่ถูกต้อง'], 400);
            }
            $sqlContent = file_get_contents($file->getTempName());
            $sourceName = $file->getClientName();
        }

        try {
            $db->query("SET FOREIGN_KEY_CHECKS = 0;");
            $queries = $this->splitSql($sqlContent);
            $executed = 0;
            $errors = [];

            foreach ($queries as $q) {
                $trimmed = trim($q);
                if (empty($trimmed)) continue;
                try {
                    $db->query($trimmed);
                    $executed++;
                } catch (\Throwable $qe) {
                    $errors[] = mb_substr($trimmed, 0, 80) . '... [Error: ' . $qe->getMessage() . ']';
                }
            }
            $db->query("SET FOREIGN_KEY_CHECKS = 1;");

            return $this->respond([
                'status'   => empty($errors) ? 'success' : 'warning',
                'message'  => "นำเข้าฐานข้อมูลจาก {$sourceName} สำเร็จ (รันคำสั่งทั้งหมด {$executed} คำสั่ง)",
                'executed' => $executed,
                'errors'   => array_slice($errors, 0, 10)
            ]);
        } catch (\Throwable $e) {
            return $this->respond(['status' => 'error', 'message' => 'การนำเข้าล้มเหลว: ' . $e->getMessage()], 500);
        }
    }

    /**
     * ส่งออกและสำรองฐานข้อมูลเป็นไฟล์ .sql (Download Database Backup)
     */
    public function exportSqlFile()
    {
        @set_time_limit(600);
        @ini_set('memory_limit', '512M');
        $db = Database::connect();
        $dbName = $db->getDatabase();

        $dump = "-- ========================================================\n";
        $dump .= "-- Phatthalung Government Portal Database Backup\n";
        $dump .= "-- Database: `{$dbName}`\n";
        $dump .= "-- Exported on: " . date('Y-m-d H:i:s') . "\n";
        $dump .= "-- Server: " . $db->getVersion() . "\n";
        $dump .= "-- ========================================================\n\n";
        $dump .= "SET NAMES utf8mb4;\n";
        $dump .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

        $tables = $db->listTables();
        foreach ($tables as $tbl) {
            // โครงสร้าง CREATE TABLE
            $createRow = $db->query("SHOW CREATE TABLE `{$tbl}`")->getRowArray();
            $createSql = $createRow['Create Table'] ?? '';
            $dump .= "-- --------------------------------------------------------\n";
            $dump .= "-- Table structure for table `{$tbl}`\n";
            $dump .= "-- --------------------------------------------------------\n";
            $dump .= "DROP TABLE IF EXISTS `{$tbl}`;\n";
            $dump .= $createSql . ";\n\n";

            // ข้อมูลแถว
            $rows = $db->table($tbl)->get()->getResultArray();
            if (!empty($rows)) {
                $dump .= "-- Dumping data for table `{$tbl}`\n";
                $cols = array_keys($rows[0]);
                $colNames = implode('`, `', $cols);

                foreach (array_chunk($rows, 50) as $chunk) {
                    $insertSql = "INSERT INTO `{$tbl}` (`{$colNames}`) VALUES \n";
                    $valLines = [];
                    foreach ($chunk as $r) {
                        $escaped = array_map(function($v) use ($db) {
                            if ($v === null) return 'NULL';
                            return $db->escape($v);
                        }, $r);
                        $valLines[] = "(" . implode(', ', $escaped) . ")";
                    }
                    $insertSql .= implode(",\n", $valLines) . ";\n";
                    $dump .= $insertSql;
                }
                $dump .= "\n";
            }
        }

        $dump .= "SET FOREIGN_KEY_CHECKS = 1;\n";
        $dump .= "-- End of Database Backup\n";

        $filename = 'backup_' . $dbName . '_' . date('Ymd_His') . '.sql';

        return $this->response
            ->setHeader('Content-Type', 'application/sql')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($dump);
    }

    /**
     * ซ่อมแซมและ Optimize ตารางทั้งหมดในคลิกเดียว (Check, Repair & Optimize)
     */
    public function repairOptimizeTables()
    {
        $db = Database::connect();
        $tables = $db->listTables();
        $reports = [];

        foreach ($tables as $tbl) {
            try {
                $chk = $db->query("CHECK TABLE `{$tbl}`")->getRowArray();
                $opt = $db->query("OPTIMIZE TABLE `{$tbl}`")->getRowArray();
                $reports[] = [
                    'table'    => $tbl,
                    'check'    => $chk['Msg_text'] ?? 'OK',
                    'optimize' => $opt['Msg_text'] ?? 'OK'
                ];
            } catch (\Throwable $e) {
                $reports[] = [
                    'table'    => $tbl,
                    'check'    => 'Error: ' . $e->getMessage(),
                    'optimize' => '-'
                ];
            }
        }

        return $this->respond([
            'status'  => 'success',
            'message' => 'ตรวจสอบและ Optimize ตารางทั้งหมด (' . count($tables) . ' ตาราง) สำเร็จ',
            'reports' => $reports
        ]);
    }

    /**
     * ผู้ช่วยตัดแบ่งคำสั่ง SQL โดยคำนึงถึง Quote และ Comment
     */
    private function splitSql(string $sql): array
    {
        $sql = preg_replace('/--.*$/m', '', $sql); // ตัด inline comment --
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // ตัด block comment /* */

        $queries = [];
        $current = '';
        $inQuote = false;
        $quoteChar = '';
        $len = strlen($sql);

        for ($i = 0; $i < $len; $i++) {
            $char = $sql[$i];
            $prev = ($i > 0) ? $sql[$i - 1] : '';

            if (!$inQuote && ($char === "'" || $char === '"' || $char === '`')) {
                $inQuote = true;
                $quoteChar = $char;
                $current .= $char;
            } elseif ($inQuote && $char === $quoteChar && $prev !== '\\') {
                $inQuote = false;
                $current .= $char;
            } elseif (!$inQuote && $char === ';') {
                $trimmed = trim($current);
                if (!empty($trimmed)) {
                    $queries[] = $trimmed;
                }
                $current = '';
            } else {
                $current .= $char;
            }
        }

        $trimmed = trim($current);
        if (!empty($trimmed)) {
            $queries[] = $trimmed;
        }

        return $queries;
    }

    /**
     * Direct Update Webhook / URL สำหรับ Hosting โดยเฉพาะ (ไม่ต้องล็อกอิน ขอแค่มี Token ถูกต้อง)
     * เรียกใช้งานผ่านเบราว์เซอร์ เช่น https://domain.com/admin/database-manager/direct-update?token=ptl_...
     */
    public function directUpdate()
    {
        $providedToken = $this->request->getGet('token') ?? $this->request->getPost('token');
        $validToken = $this->getSecretToken();

        if (empty($providedToken) || !hash_equals($validToken, (string)$providedToken)) {
            return $this->response->setStatusCode(403)->setContentType('text/html', 'UTF-8')->setBody('
                <div style="font-family: sans-serif; text-align: center; padding: 50px;">
                    <h2 style="color: #ef4444;">403 Forbidden: Invalid Secret Token</h2>
                    <p>รหัสความปลอดภัยไม่ถูกต้อง กรุณาตรวจสอบ Token จากเมนูจัดการฐานข้อมูลในระบบผู้ดูแลระบบ</p>
                </div>
            ');
        }

        // 1. Run Migrations
        $migrationLogs = [];
        try {
            $migrate = Services::migrations();
            $res = $migrate->latest('default');
            $migrationLogs[] = $res ? 'รัน Migration สำเร็จ' : 'ไม่มี Migration ใหม่ที่ต้องรัน';
        } catch (\Throwable $e) {
            $migrationLogs[] = 'Migration Notice: ' . $e->getMessage();
        }

        // 2. Repair Columns
        $repairResponse = $this->repairColumns();
        $repairData = json_decode($repairResponse->getBody(), true);

        // 3. Sync Data
        $syncResponse = $this->syncData();
        $syncData = json_decode($syncResponse->getBody(), true);

        // Return rich executive HTML confirmation
        $html = '
        <!DOCTYPE html>
        <html lang="th">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Phatthalung Hosting Table Updater</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
            <style>
                body { background: #0f172a; color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
                .card-update { background: rgba(30, 41, 59, 0.9); border: 1px solid rgba(56, 189, 248, 0.3); border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.5); }
                .step-box { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; }
            </style>
        </head>
        <body class="d-flex align-items-center justify-content-center min-vh-100 p-3">
            <div class="card card-update p-4 p-md-5" style="max-width: 650px; width: 100%;">
                <div class="text-center mb-4">
                    <div class="d-inline-flex p-3 rounded-circle bg-success bg-opacity-20 text-success mb-3" style="width: 70px; height: 70px; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-server fs-1"></i>
                    </div>
                    <h3 class="fw-bold text-white mb-1">อัปเดตโครงสร้างฐานข้อมูลบน Hosting เรียบร้อย</h3>
                    <small class="text-info">Phatthalung Database & Table Direct Updater</small>
                </div>

                <div class="step-box p-3 mb-3">
                    <h6 class="fw-bold text-success mb-2"><i class="fa-solid fa-circle-check me-2"></i>1. รันระบบ Migration</h6>
                    <p class="m-0 small text-light">' . htmlspecialchars(implode(' | ', $migrationLogs)) . '</p>
                </div>

                <div class="step-box p-3 mb-3">
                    <h6 class="fw-bold text-success mb-2"><i class="fa-solid fa-circle-check me-2"></i>2. ตรวจสอบและซ่อมแซมคอลัมน์</h6>
                    <p class="m-0 small text-light">' . htmlspecialchars($repairData['message'] ?? 'เรียบร้อย') . '</p>
                </div>

                <div class="step-box p-3 mb-4">
                    <h6 class="fw-bold text-success mb-2"><i class="fa-solid fa-circle-check me-2"></i>3. ซิงค์ข้อมูล JSON เข้าสู่ MySQL</h6>
                    <p class="m-0 small text-light">' . ($syncData['message'] ?? 'เรียบร้อย') . '</p>
                </div>

                <div class="d-flex gap-2 justify-content-center">
                    <a href="' . base_url() . '" class="btn btn-outline-light rounded-pill px-4 py-2">
                        <i class="fa-solid fa-house me-1"></i> กลับหน้าแรกเว็บไซต์
                    </a>
                    <a href="' . base_url('admin/database-manager') . '" class="btn btn-primary rounded-pill px-4 py-2" style="background: linear-gradient(135deg, #0284c7, #2563eb); border: none;">
                        <i class="fa-solid fa-sliders me-1"></i> ไปยังแผงจัดการตาราง
                    </a>
                </div>
            </div>
        </body>
        </html>
        ';

        return $this->response->setContentType('text/html', 'UTF-8')->setBody($html);
    }
}
