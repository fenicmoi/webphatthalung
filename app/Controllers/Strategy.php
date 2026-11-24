<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Strategy extends Controller
{
    /**
     * หน้าพอร์ทัลยุทธศาสตร์การพัฒนาจังหวัดพัทลุง (พ.ศ. 2566 - 2570)
     */
    public function index()
    {
        helper(['settings']);
        
        $strategyData = get_site_strategy();
        $categories = get_strategy_categories();
        $isOfficer = (bool)session()->get('isLoggedIn');
        
        $data = [
            'title' => 'ยุทธศาสตร์การพัฒนาจังหวัดพัทลุง (พ.ศ. 2566 - 2570) | แผนพัฒนาจังหวัดและแผนปฏิบัติราชการประจำปี',
            'meta_description' => 'ศูนย์ข้อมูลยุทธศาสตร์การพัฒนาจังหวัดพัทลุง 5 ปี วิสัยทัศน์ พันธกิจ 5 เสาหลักยุทธศาสตร์ ตัวชี้วัดเป้าหมาย และดาวน์โหลดแผนปฏิบัติราชการประจำปี',
            'strategy' => $strategyData,
            'vision' => $strategyData['vision'] ?? [],
            'missions' => $strategyData['missions'] ?? [],
            'core_values' => $strategyData['core_values'] ?? [],
            'kpis' => $strategyData['kpis'] ?? [],
            'pillars' => $strategyData['pillars'] ?? [],
            'documents' => $strategyData['documents'] ?? [],
            'categories' => $categories,
            'isOfficer' => $isOfficer
        ];

        return view('strategy_portal', $data);
    }

    /**
     * ดึงรายการหมวดหมู่เอกสารยุทธศาสตร์ (JSON API)
     */
    public function getCategories()
    {
        helper(['settings']);
        return $this->response->setJSON([
            'status' => 'success',
            'categories' => get_strategy_categories()
        ]);
    }

    /**
     * บันทึกหรือเพิ่มหมวดหมู่เอกสารยุทธศาสตร์
     */
    public function saveCategory()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อนดำเนินการ'])->setStatusCode(401);
        }

        helper(['settings']);
        $categories = get_strategy_categories();
        $strategy = get_site_strategy();
        $docs = $strategy['documents'] ?? [];

        $id = trim((string)$this->request->getPost('id'));
        $name = trim((string)$this->request->getPost('name'));
        $icon = trim((string)$this->request->getPost('icon')) ?: 'fa-solid fa-folder';
        $badgeColor = trim((string)$this->request->getPost('badge_color')) ?: 'primary';
        $desc = trim((string)$this->request->getPost('desc'));
        $sortOrder = (int)$this->request->getPost('sort_order') ?: (count($categories) + 1);

        // จัดการหมวดหมู่ย่อย (Sub-categories)
        $rawSubCats = $this->request->getPost('sub_categories');
        $subCats = [];
        if (is_array($rawSubCats)) {
            $subCats = array_values(array_filter(array_map('trim', $rawSubCats)));
        } elseif (is_string($rawSubCats)) {
            $subCats = array_values(array_filter(array_map('trim', preg_split('/[,;\n]+/', $rawSubCats))));
        }

        if (empty($name)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุชื่อหมวดหมู่']);
        }

        $oldName = null;
        if (!empty($id)) {
            $found = false;
            foreach ($categories as &$cat) {
                if ($cat['id'] === $id) {
                    $oldName = $cat['name'];
                    $cat['name'] = $name;
                    $cat['icon'] = $icon;
                    $cat['badge_color'] = $badgeColor;
                    $cat['desc'] = $desc;
                    $cat['sub_categories'] = $subCats;
                    $cat['sort_order'] = $sortOrder;
                    $found = true;
                    break;
                }
            }
            unset($cat);

            if (!$found) {
                $categories[] = [
                    'id' => $id,
                    'name' => $name,
                    'icon' => $icon,
                    'badge_color' => $badgeColor,
                    'desc' => $desc,
                    'sub_categories' => $subCats,
                    'sort_order' => $sortOrder,
                    'active' => true
                ];
            }
        } else {
            $newId = 'scat_' . time() . rand(10, 99);
            $categories[] = [
                'id' => $newId,
                'name' => $name,
                'icon' => $icon,
                'badge_color' => $badgeColor,
                'desc' => $desc,
                'sub_categories' => $subCats,
                'sort_order' => $sortOrder,
                'active' => true
            ];
        }

        // หากมีการเปลี่ยนชื่อหมวดหมู่ ให้อัปเดตชื่อในเอกสารยุทธศาสตร์ที่มีอยู่เดิมด้วย
        if (!empty($oldName) && $oldName !== $name) {
            foreach ($docs as &$d) {
                if (($d['category'] ?? '') === $oldName) {
                    $d['category'] = $name;
                }
            }
            unset($d);
            $strategy['documents'] = $docs;
            save_site_strategy($strategy);
        }

        save_strategy_categories($categories);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => '🎉 บันทึกหมวดหมู่เอกสารยุทธศาสตร์ "' . $name . '" สำเร็จแล้ว!',
            'categories' => get_strategy_categories()
        ]);
    }

    /**
     * ลบหมวดหมู่เอกสารยุทธศาสตร์
     */
    public function deleteCategory($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อนดำเนินการ'])->setStatusCode(401);
        }

        if (empty($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบรหัสหมวดหมู่']);
        }

        helper(['settings']);
        $categories = get_strategy_categories();
        $strategy = get_site_strategy();
        $docs = $strategy['documents'] ?? [];

        $deletedName = null;
        $newCats = [];
        foreach ($categories as $cat) {
            if ($cat['id'] === $id) {
                $deletedName = $cat['name'];
            } else {
                $newCats[] = $cat;
            }
        }

        if ($deletedName === null) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบหมวดหมู่ที่ต้องการลบ']);
        }

        // หากมีเอกสารอยู่ในหมวดที่ลบ ให้ย้ายไปหมวดแรกที่เหลืออยู่
        $fallbackCat = !empty($newCats[0]['name']) ? $newCats[0]['name'] : 'แผนปฏิบัติราชการประจำปี';
        $reassignedCount = 0;
        foreach ($docs as &$d) {
            if (($d['category'] ?? '') === $deletedName) {
                $d['category'] = $fallbackCat;
                $reassignedCount++;
            }
        }
        unset($d);

        if ($reassignedCount > 0) {
            $strategy['documents'] = $docs;
            save_site_strategy($strategy);
        }

        save_strategy_categories($newCats);

        $msg = 'ลบหมวดหมู่ "' . $deletedName . '" เรียบร้อยแล้ว';
        if ($reassignedCount > 0) {
            $msg .= ' (ปรับย้ายเอกสาร ' . $reassignedCount . ' รายการไปไว้ที่หมวด "' . $fallbackCat . '")';
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => $msg,
            'categories' => get_strategy_categories()
        ]);
    }

    /**
     * บันทึกเอกสารยุทธศาสตร์แบบ Inline จากหน้า /strategy
     */
    public function saveDocumentInline()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อนดำเนินการ'])->setStatusCode(401);
        }

        helper(['settings']);
        $strategy = get_site_strategy();
        $docs = $strategy['documents'] ?? [];

        $id = trim((string)$this->request->getPost('id'));
        $title = trim((string)$this->request->getPost('title'));
        $category = trim((string)$this->request->getPost('category')) ?: 'แผนปฏิบัติราชการประจำปี';
        $subCategory = trim((string)$this->request->getPost('sub_category'));
        $year = trim((string)$this->request->getPost('year')) ?: (string)(date('Y') + 543);
        $fileUrl = trim((string)$this->request->getPost('file_url'));
        $fileSize = trim((string)$this->request->getPost('file_size')) ?: '5.0 MB';
        $pages = (int)$this->request->getPost('pages') ?: 100;
        $isFeatured = $this->request->getPost('is_featured') === '1' || $this->request->getPost('is_featured') === 'true';

        if (empty($title)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุชื่อเอกสาร']);
        }

        // Handle uploaded file if present
        $file = $this->request->getFile('doc_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $uploadDir = FCPATH . 'uploads/strategy/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            $newName = 'plan_' . time() . '_' . $file->getRandomName();
            $file->move($uploadDir, $newName);
            $fileUrl = 'uploads/strategy/' . $newName;
            $sizeBytes = @filesize($uploadDir . $newName);
            if ($sizeBytes) {
                $fileSize = round($sizeBytes / (1024 * 1024), 1) . ' MB';
            }
        }

        if (!empty($id)) {
            $found = false;
            foreach ($docs as &$d) {
                if ($d['id'] === $id) {
                    $d['title'] = $title;
                    $d['category'] = $category;
                    $d['sub_category'] = $subCategory;
                    $d['year'] = $year;
                    if (!empty($fileUrl)) $d['file_url'] = $fileUrl;
                    $d['file_size'] = $fileSize;
                    $d['pages'] = $pages;
                    $d['is_featured'] = $isFeatured;
                    $d['updated_at'] = date('Y-m-d');
                    $found = true;
                    break;
                }
            }
            unset($d);
            if (!$found) {
                $docs[] = [
                    'id' => $id,
                    'title' => $title,
                    'category' => $category,
                    'sub_category' => $subCategory,
                    'year' => $year,
                    'file_url' => $fileUrl ?: '#',
                    'file_size' => $fileSize,
                    'file_type' => 'pdf',
                    'pages' => $pages,
                    'downloads' => 0,
                    'is_featured' => $isFeatured,
                    'updated_at' => date('Y-m-d')
                ];
            }
        } else {
            $newId = 'doc_' . uniqid();
            $docs[] = [
                'id' => $newId,
                'title' => $title,
                'category' => $category,
                'sub_category' => $subCategory,
                'year' => $year,
                'file_url' => $fileUrl ?: '#',
                'file_size' => $fileSize,
                'file_type' => 'pdf',
                'pages' => $pages,
                'downloads' => 0,
                'is_featured' => $isFeatured,
                'updated_at' => date('Y-m-d')
            ];
        }

        $strategy['documents'] = $docs;
        save_site_strategy($strategy);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => '🎉 บันทึกเอกสารยุทธศาสตร์ "' . $title . '" สำเร็จแล้ว!'
        ]);
    }

    /**
     * ลบเอกสารยุทธศาสตร์แบบ Inline
     */
    public function deleteDocumentInline($id = null)
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบก่อนดำเนินการ'])->setStatusCode(401);
        }

        if (empty($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบรหัสเอกสาร']);
        }

        helper(['settings']);
        $strategy = get_site_strategy();
        $docs = $strategy['documents'] ?? [];

        $filtered = [];
        $deletedTitle = '';
        foreach ($docs as $d) {
            if ($d['id'] === $id) {
                $deletedTitle = $d['title'];
            } else {
                $filtered[] = $d;
            }
        }

        $strategy['documents'] = $filtered;
        save_site_strategy($strategy);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'ลบเอกสาร "' . $deletedTitle . '" เรียบร้อยแล้ว'
        ]);
    }

    /**
     * API บันทึกยอดการดาวน์โหลดเอกสารยุทธศาสตร์
     */
    public function download($id = null)
    {
        helper(['settings']);
        $strategyData = get_site_strategy();
        $docs = $strategyData['documents'] ?? [];

        foreach ($docs as &$doc) {
            if ($doc['id'] === $id) {
                $doc['downloads'] = ($doc['downloads'] ?? 0) + 1;
                break;
            }
        }
        unset($doc);

        $strategyData['documents'] = $docs;
        save_site_strategy($strategyData);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Download count updated'
        ]);
    }
}
