<?php

namespace App\Models;

use CodeIgniter\Model;

class DirectoryModel extends Model
{
    protected $table = 'directory_contacts';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'department_id',
        'ministry_id',
        'order',
        'name',
        'position',
        'phone',
        'mobile',
        'fax',
        'hotline',
        'email',
        'address',
        'photo',
        'photo_url',
        'updated_at'
    ];

    /**
     * ดึงรายชื่อสังกัด/กระทรวงทั้งหมด
     */
    public function getMinistries(): array
    {
        $file = WRITEPATH . 'directory_ministries.json';
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            if (is_array($data)) {
                return $data;
            }
        }
        return [];
    }

    /**
     * ดึงรายชื่อส่วนราชการ/หน่วยงานทั้งหมด (หรือกรองตามสังกัด)
     */
    public function getDepartments(?int $ministryId = null): array
    {
        $file = WRITEPATH . 'directory_departments.json';
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            if (is_array($data)) {
                if ($ministryId !== null && $ministryId > 0) {
                    return array_values(array_filter($data, fn($d) => (int)($d['ministry_id'] ?? 0) === $ministryId));
                }
                return $data;
            }
        }
        return [];
    }

    /**
     * ดึงข้อมูลสังกัด/กระทรวงตาม ID
     */
    public function getMinistryById(int $id): ?array
    {
        foreach ($this->getMinistries() as $m) {
            if ((int)($m['id'] ?? 0) === $id) {
                return $m;
            }
        }
        return null;
    }

    /**
     * ค้นหาส่วนราชการ/หน่วยงานด้วย ID หรือชื่อหน่วยงาน
     */
    public function findDepartment($identifier): ?array
    {
        if (empty($identifier)) {
            return null;
        }

        $departments = $this->getDepartments();

        // 1. ตรวจสอบกรณีเป็น ID ตัวเลข
        if (is_numeric($identifier)) {
            $id = (int)$identifier;
            foreach ($departments as $d) {
                if ((int)($d['id'] ?? 0) === $id) {
                    return $d;
                }
            }
        }

        $str = trim((string)$identifier);
        if ($str === '') {
            return null;
        }

        // 2. ตรวจสอบแบบตรงชื่อทุกประการ (Exact Match)
        foreach ($departments as $d) {
            if (trim($d['name'] ?? '') === $str) {
                return $d;
            }
        }

        // 3. ตรวจสอบแบบมีชื่อบางส่วน (Partial Match)
        $lowerStr = mb_strtolower($str);
        foreach ($departments as $d) {
            $lowerD = mb_strtolower(trim($d['name'] ?? ''));
            if ($lowerD !== '' && (mb_strpos($lowerD, $lowerStr) !== false || mb_strpos($lowerStr, $lowerD) !== false)) {
                return $d;
            }
        }

        // 4. Fallback สำหรับกลุ่มงานภายใต้สำนักงานจังหวัด (เช่น ศูนย์ดำรงธรรม)
        if (mb_strpos($lowerStr, 'ศูนย์ดำรงธรรม') !== false) {
            foreach ($departments as $d) {
                if ((int)($d['id'] ?? 0) === 2) {
                    return $d;
                }
            }
        }

        return null;
    }

    /**
     * ดึงข้อมูลผู้บริหาร/ข้าราชการทั้งหมดจากไฟล์ JSON พร้อมตัวกรอง
     */
    public function getContacts(array $filters = []): array
    {
        $file = WRITEPATH . 'directory_contacts.json';
        if (!file_exists($file)) {
            return [];
        }

        $all = json_decode(file_get_contents($file), true);
        if (!is_array($all)) {
            return [];
        }

        $minObjMap = [];
        $ministries = [];
        foreach ($this->getMinistries() as $m) {
            $mId = (int)$m['id'];
            $minObjMap[$mId] = $m;
            $ministries[$mId] = $m['name'];
        }

        $deptObjMap = [];
        $departments = [];
        foreach ($this->getDepartments() as $d) {
            $dId = (int)$d['id'];
            $deptObjMap[$dId] = $d;
            $departments[$dId] = $d['name'];
        }

        $selectedMinistry = !empty($filters['ministry_id']) ? (int)$filters['ministry_id'] : null;
        $selectedDept = !empty($filters['department_id']) ? (int)$filters['department_id'] : null;
        $keyword = !empty($filters['search']) ? trim(mb_strtolower($filters['search'])) : '';

        $filtered = [];
        foreach ($all as $item) {
            $mId = (int)($item['ministry_id'] ?? 0);
            $dId = (int)($item['department_id'] ?? 0);

            if ($selectedMinistry && $mId !== $selectedMinistry) {
                continue;
            }

            if ($selectedDept && $dId !== $selectedDept) {
                continue;
            }

            $mName = $ministries[$mId] ?? 'สังกัดอื่นๆ';
            $dName = $departments[$dId] ?? 'หน่วยงานทั่วไป';

            if ($keyword !== '') {
                $searchCorpus = mb_strtolower(
                    ($item['name'] ?? '') . ' ' .
                    ($item['position'] ?? '') . ' ' .
                    ($item['phone'] ?? '') . ' ' .
                    ($item['mobile'] ?? '') . ' ' .
                    $mName . ' ' .
                    $dName
                );
                if (mb_strpos($searchCorpus, $keyword) === false) {
                    continue;
                }
            }

            $item['ministry_name'] = $mName;
            $item['department_name'] = $dName;
            $filtered[] = $item;
        }

        // จัดเรียงลำดับชั้นยศและโครงสร้างหน่วยงานอย่างเป็นระเบียบ:
        // 1. เรียงตามลำดับกระทรวง/สังกัด (Ministry Order)
        // 2. เรียงตามลำดับส่วนราชการ (Department Order) โดยให้ฝ่าย "ผู้บริหาร" อยู่บนสุดเสมอ
        // 3. เรียงตามลำดับชั้นยศ/ความสำคัญของบุคคล (Contact Rank Order)
        usort($filtered, function ($a, $b) use ($minObjMap, $deptObjMap) {
            $mIdA = (int)($a['ministry_id'] ?? 0);
            $mIdB = (int)($b['ministry_id'] ?? 0);
            $minOrdA = $minObjMap[$mIdA]['order'] ?? 999;
            $minOrdB = $minObjMap[$mIdB]['order'] ?? 999;
            if ($minOrdA !== $minOrdB) return $minOrdA <=> $minOrdB;
            if ($mIdA !== $mIdB) return $mIdA <=> $mIdB;

            $dIdA = (int)($a['department_id'] ?? 0);
            $dIdB = (int)($b['department_id'] ?? 0);
            $deptOrdA = $deptObjMap[$dIdA]['order'] ?? 999;
            $deptOrdB = $deptObjMap[$dIdB]['order'] ?? 999;
            
            // ให้ส่วนราชการผู้บริหาร (หรือ ID 1) อยู่บนสุดของกระทรวงเสมอ
            if ($dIdA === 1 || ($deptObjMap[$dIdA]['name'] ?? '') === 'ผู้บริหาร') $deptOrdA = -1;
            if ($dIdB === 1 || ($deptObjMap[$dIdB]['name'] ?? '') === 'ผู้บริหาร') $deptOrdB = -1;
            if ($deptOrdA !== $deptOrdB) return $deptOrdA <=> $deptOrdB;
            if ($dIdA !== $dIdB) return $dIdA <=> $dIdB;

            // ลำดับชั้นยศภายในหน่วยงาน (เช่น 1=ผู้ว่าฯ/หัวหน้าส่วน, 2=รอง, 3=ผอ.กลุ่ม)
            $ordA = (int)($a['order'] ?? 999);
            $ordB = (int)($b['order'] ?? 999);
            if ($ordA !== $ordB) return $ordA <=> $ordB;

            return (int)($a['id'] ?? 0) <=> (int)($b['id'] ?? 0);
        });

        return $filtered;
    }

    /**
     * ค้นหาข้อมูลผู้บริหารตาม ID
     */
    public function getContactById(int $id): ?array
    {
        $all = $this->getContacts();
        foreach ($all as $c) {
            if ((int)$c['id'] === $id) {
                return $c;
            }
        }
        return null;
    }

    /**
     * บันทึกหรืออัปเดตข้อมูลผู้บริหาร
     */
    public function saveContact(array $data): int
    {
        $file = WRITEPATH . 'directory_contacts.json';
        $all = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        if (!is_array($all)) $all = [];

        $id = !empty($data['id']) ? (int)$data['id'] : 0;
        $now = date('Y-m-d');

        if ($id > 0) {
            // Update
            $found = false;
            foreach ($all as &$item) {
                if ((int)$item['id'] === $id) {
                    $item = array_merge($item, $data);
                    $item['updated_at'] = $now;
                    $found = true;
                    break;
                }
            }
            unset($item);
            if (!$found) {
                $data['id'] = $id;
                $data['updated_at'] = $now;
                $all[] = $data;
            }
        } else {
            // New
            $maxId = 0;
            foreach ($all as $item) {
                if ((int)$item['id'] > $maxId) {
                    $maxId = (int)$item['id'];
                }
            }
            $id = $maxId + 1;
            $data['id'] = $id;
            $data['updated_at'] = $now;
            array_unshift($all, $data);
        }

        file_put_contents($file, json_encode($all, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return $id;
    }

    /**
     * ลบข้อมูลผู้บริหารตาม ID
     */
    public function deleteContact(int $id): bool
    {
        $file = WRITEPATH . 'directory_contacts.json';
        if (!file_exists($file)) return false;

        $all = json_decode(file_get_contents($file), true);
        if (!is_array($all)) return false;

        $filtered = array_values(array_filter($all, fn($item) => (int)$item['id'] !== $id));
        file_put_contents($file, json_encode($filtered, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return true;
    }

    /**
     * บันทึกหรืออัปเดตสังกัด/กระทรวง
     */
    public function saveMinistry(array $data): int
    {
        $file = WRITEPATH . 'directory_ministries.json';
        $all = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        if (!is_array($all)) $all = [];

        $id = !empty($data['id']) ? (int)$data['id'] : 0;
        $name = trim($data['name'] ?? '');
        $order = !empty($data['order']) ? (int)$data['order'] : 99;

        if ($id > 0) {
            $found = false;
            foreach ($all as &$m) {
                if ((int)$m['id'] === $id) {
                    $m['name'] = $name;
                    $m['order'] = $order;
                    $found = true;
                    break;
                }
            }
            unset($m);
            if (!$found) {
                $all[] = ['id' => $id, 'order' => $order, 'name' => $name];
            }
        } else {
            $maxId = 0;
            foreach ($all as $m) {
                if ((int)$m['id'] > $maxId) {
                    $maxId = (int)$m['id'];
                }
            }
            $id = $maxId + 1;
            $all[] = ['id' => $id, 'order' => $order, 'name' => $name];
        }

        // เรียงตาม order
        usort($all, fn($a, $b) => ($a['order'] ?? 99) <=> ($b['order'] ?? 99));

        file_put_contents($file, json_encode($all, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return $id;
    }

    /**
     * ลบสังกัด/กระทรวง
     */
    public function deleteMinistry(int $id): bool
    {
        $file = WRITEPATH . 'directory_ministries.json';
        if (!file_exists($file)) return false;

        $all = json_decode(file_get_contents($file), true);
        if (!is_array($all)) return false;

        $filtered = array_values(array_filter($all, fn($m) => (int)$m['id'] !== $id));
        file_put_contents($file, json_encode($filtered, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return true;
    }

    /**
     * บันทึกหรืออัปเดตส่วนราชการ/หน่วยงาน
     */
    public function saveDepartment(array $data): int
    {
        $file = WRITEPATH . 'directory_departments.json';
        $all = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        if (!is_array($all)) $all = [];

        $id = !empty($data['id']) ? (int)$data['id'] : 0;
        $minId = !empty($data['ministry_id']) ? (int)$data['ministry_id'] : 0;
        $name = trim($data['name'] ?? '');
        $order = !empty($data['order']) ? (int)$data['order'] : 99;

        if ($id > 0) {
            $found = false;
            foreach ($all as &$d) {
                if ((int)$d['id'] === $id) {
                    $d['ministry_id'] = $minId;
                    $d['name'] = $name;
                    $d['order'] = $order;
                    $found = true;
                    break;
                }
            }
            unset($d);
            if (!$found) {
                $all[] = ['id' => $id, 'ministry_id' => $minId, 'order' => $order, 'name' => $name];
            }
        } else {
            $maxId = 0;
            foreach ($all as $d) {
                if ((int)$d['id'] > $maxId) {
                    $maxId = (int)$d['id'];
                }
            }
            $id = $maxId + 1;
            $all[] = ['id' => $id, 'ministry_id' => $minId, 'order' => $order, 'name' => $name];
        }

        // เรียงตาม ministry_id และ order
        usort($all, function($a, $b) {
            $mA = (int)($a['ministry_id'] ?? 0);
            $mB = (int)($b['ministry_id'] ?? 0);
            if ($mA !== $mB) {
                return $mA <=> $mB;
            }
            return ($a['order'] ?? 99) <=> ($b['order'] ?? 99);
        });

        file_put_contents($file, json_encode($all, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return $id;
    }

    /**
     * ลบส่วนราชการ/หน่วยงาน
     */
    public function deleteDepartment(int $id): bool
    {
        $file = WRITEPATH . 'directory_departments.json';
        if (!file_exists($file)) return false;

        $all = json_decode(file_get_contents($file), true);
        if (!is_array($all)) return false;

        $filtered = array_values(array_filter($all, fn($d) => (int)$d['id'] !== $id));
        file_put_contents($file, json_encode($filtered, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return true;
    }

    /**
     * ดึงข้อมูล Meta การซิงค์ล่าสุด
     */
    public function getSyncMeta(): array
    {
        $file = WRITEPATH . 'directory_sync_meta.json';
        if (file_exists($file)) {
            $meta = json_decode(file_get_contents($file), true);
            if (is_array($meta)) {
                return $meta;
            }
        }
        return [
            'last_synced_at'    => date('Y-m-d H:i:s'),
            'synced_by'         => 'system',
            'ministries_count'  => count($this->getMinistries()),
            'departments_count' => count($this->getDepartments()),
            'contacts_count'    => count($this->getContacts()),
            'status'            => 'ready'
        ];
    }

    /**
     * ดึงและประมวลผลข้อมูลจากระบบ phone.phatthalung.go.th มายังระบบใหม่แบบอัตโนมัติ
     */
    public function syncFromRemote(string $triggeredBy = 'manual'): array
    {
        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        $fetchCleanJson = function(string $url): array {
            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 30,
                    'header'  => "User-Agent: PhatthalungDirectorySync/2026\r\n"
                ],
                'ssl'  => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);
            $raw = @file_get_contents($url, false, $ctx);
            if (!$raw) return [];
            
            $pos = strpos($raw, '[');
            if ($pos !== false) {
                $clean = substr($raw, $pos);
                $data = json_decode($clean, true);
                if (is_array($data)) return $data;
            }
            return [];
        };

        $cleanPhone = function($str): string {
            $str = trim((string)$str);
            if ($str === '-' || $str === '' || $str === '0') return '';
            return $str;
        };

        // 1. ดึงสังกัด/กระทรวง
        $ministriesRaw = $fetchCleanJson('https://www.phone.phatthalung.go.th/api/show_ministry.php');
        if (empty($ministriesRaw)) {
            return [
                'success' => false,
                'message' => 'ไม่สามารถเชื่อมต่อ API สังกัด/กระทรวง ของ phone.phatthalung.go.th ได้'
            ];
        }

        $ministries = [];
        foreach ($ministriesRaw as $m) {
            $mId = (int)($m['m_id'] ?? 0);
            if (!$mId) continue;
            $ministries[$mId] = [
                'id'    => $mId,
                'order' => (int)($m['m_impo'] ?? 999),
                'name'  => trim($m['m_name'] ?? '')
            ];
        }

        // 2. ดึงส่วนราชการ/หน่วยงาน
        $departments = [];
        foreach (array_keys($ministries) as $mId) {
            $deps = $fetchCleanJson("https://www.phone.phatthalung.go.th/api/show_depart.php?m_id={$mId}");
            foreach ($deps as $d) {
                $depId = (int)($d['dep_id'] ?? 0);
                if (!$depId) continue;
                $departments[$depId] = [
                    'id'          => $depId,
                    'ministry_id' => $mId,
                    'order'       => (int)($d['dep_impo'] ?? 999),
                    'name'        => trim($d['dep_name'] ?? '')
                ];
            }
        }

        // 3. ดึงรายชื่อข้าราชการ/ผู้บริหาร
        $governRaw = $fetchCleanJson('https://www.phone.phatthalung.go.th/api/show_govern.php');
        $contacts = [];
        foreach ($governRaw as $idx => $g) {
            $name = trim($g['g_head_th'] ?? '');
            $position = trim($g['g_position'] ?? '');
            if (!$name && !$position) continue;

            $depId = (int)($g['g_dep'] ?? $g['dep_id'] ?? 0);
            $pic = trim($g['g_pic'] ?? '');
            if ($pic === 'no_pic.jpg' || $pic === 'no-image.png' || $pic === 'none') {
                $pic = '';
            }

            $cId = $idx + 1;
            $contacts[] = [
                'id'             => $cId,
                'department_id'  => $depId,
                'ministry_id'    => $departments[$depId]['ministry_id'] ?? 0,
                'order'          => (int)($g['g_impo'] ?? 999),
                'name'           => $name,
                'position'       => $position,
                'phone'          => $cleanPhone($g['g_phone'] ?? ''),
                'mobile'         => $cleanPhone($g['g_mobile'] ?? ''),
                'fax'            => '',
                'hotline'        => '',
                'email'          => '',
                'address'        => '',
                'photo'          => $pic,
                'photo_url'      => $pic ? "https://www.phone.phatthalung.go.th/image/pic_head/{$pic}" : "",
                'updated_at'     => date('Y-m-d')
            ];
        }

        // 4. ผสานข้อมูลรายละเอียดและวันอัปเดตจาก report.php
        $ctxRep = stream_context_create([
            'http' => ['timeout' => 30],
            'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false]
        ]);
        $reportHtml = @file_get_contents('https://www.phone.phatthalung.go.th/report.php', false, $ctxRep);
        if ($reportHtml) {
            preg_match_all('/<tr>\s*<td>(.*?)<\/td>\s*<td>(.*?)<\/td>\s*<td>(.*?)<\/td>\s*<td>(.*?)<\/td>/is', $reportHtml, $matches, PREG_SET_ORDER);
            $extraByCleanName = [];
            foreach ($matches as $m) {
                $n = trim(strip_tags($m[1]));
                $p = $cleanPhone(strip_tags($m[2]));
                $mob = $cleanPhone(strip_tags($m[3]));
                $upd = trim(strip_tags($m[4]));
                if ($n) {
                    $extraByCleanName[preg_replace('/\s+/', '', $n)] = [
                        'phone'   => $p,
                        'mobile'  => $mob,
                        'updated' => $upd
                    ];
                }
            }

            foreach ($contacts as &$c) {
                $k = preg_replace('/\s+/', '', $c['name']);
                if (isset($extraByCleanName[$k]['updated']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $extraByCleanName[$k]['updated'])) {
                    $c['updated_at'] = $extraByCleanName[$k]['updated'];
                }
            }
            unset($c);
        }

        // จัดเรียง
        usort($contacts, function($a, $b) use ($ministries, $departments) {
            $mOrderA = $ministries[$a['ministry_id']]['order'] ?? 9999;
            $mOrderB = $ministries[$b['ministry_id']]['order'] ?? 9999;
            if ($mOrderA !== $mOrderB) return $mOrderA <=> $mOrderB;

            $dOrderA = $departments[$a['department_id']]['order'] ?? 9999;
            $dOrderB = $departments[$b['department_id']]['order'] ?? 9999;
            if ($dOrderA !== $dOrderB) return $dOrderA <=> $dOrderB;

            return ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
        });

        foreach ($contacts as $idx => &$c) {
            $c['id'] = $idx + 1;
        }
        unset($c);

        // บันทึกไฟล์ JSON ทั้งหมด
        file_put_contents(WRITEPATH . 'directory_ministries.json', json_encode(array_values($ministries), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        file_put_contents(WRITEPATH . 'directory_departments.json', json_encode(array_values($departments), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        file_put_contents(WRITEPATH . 'directory_contacts.json', json_encode($contacts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        $meta = [
            'last_synced_at'    => date('Y-m-d H:i:s'),
            'synced_by'         => $triggeredBy,
            'ministries_count'  => count($ministries),
            'departments_count' => count($departments),
            'contacts_count'    => count($contacts),
            'status'            => 'success'
        ];
        file_put_contents(WRITEPATH . 'directory_sync_meta.json', json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return [
            'success' => true,
            'message' => 'ซิงค์ข้อมูลจาก phone.phatthalung.go.th สำเร็จครบถ้วนสมบูรณ์',
            'meta'    => $meta
        ];
    }
}

