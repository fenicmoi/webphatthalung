<?php

namespace App\Controllers;

use App\Models\DirectoryModel;

class Directory extends BaseController
{
    protected DirectoryModel $directoryModel;

    public function __construct()
    {
        helper(['settings', 'url']);
        $this->directoryModel = new DirectoryModel();
    }

    /**
     * หน้าหลักสมุดโทรศัพท์และทำเนียบหัวหน้าส่วนราชการจังหวัดพัทลุง
     */
    public function index()
    {
        $selectedMinistry = $this->request->getGet('ministry') ? (int)$this->request->getGet('ministry') : null;
        $selectedDept = $this->request->getGet('dept') ? (int)$this->request->getGet('dept') : null;
        $search = trim((string)$this->request->getGet('search'));
        $viewMode = $this->request->getGet('view') ?: 'table';
        $page = max(1, (int)$this->request->getGet('page'));
        $perPage = $this->request->getGet('per_page') ? min(200, max(10, (int)$this->request->getGet('per_page'))) : 50;

        $filters = [
            'ministry_id'   => $selectedMinistry,
            'department_id' => $selectedDept,
            'search'        => $search
        ];

        $allContacts = $this->directoryModel->getContacts($filters);
        $totalResults = count($allContacts);

        // คำนวณแบ่งหน้า (Pagination) เฉพาะเมื่อไม่ได้เลือกแบบแสดงทั้งกลุ่ม
        $totalPages = max(1, (int)ceil($totalResults / $perPage));
        if ($page > $totalPages) $page = $totalPages;
        $offset = ($page - 1) * $perPage;

        $contacts = array_slice($allContacts, $offset, $perPage);

        $ministries = $this->directoryModel->getMinistries();
        $departments = $this->directoryModel->getDepartments($selectedMinistry);

        // สถิติรวม
        $totalAllContacts = count($this->directoryModel->getContacts());
        $totalMinistries = count($ministries);
        $totalDepts = count($this->directoryModel->getDepartments());

        $data = [
            'title'             => 'ทำเนียบส่วนราชการและสมุดโทรศัพท์ดิจิทัล | จังหวัดพัทลุง',
            'contacts'          => $contacts,
            'totalResults'      => $totalResults,
            'totalAllContacts'  => $totalAllContacts,
            'totalMinistries'   => $totalMinistries,
            'totalDepts'        => $totalDepts,
            'ministries'        => $ministries,
            'departments'       => $departments,
            'selectedMinistry'  => $selectedMinistry,
            'selectedDept'      => $selectedDept,
            'search'            => $search,
            'viewMode'          => $viewMode,
            'currentPage'       => $page,
            'totalPages'        => $totalPages,
            'perPage'           => $perPage,
            'offset'            => $offset
        ];

        return view('directory/index', $data);
    }

    /**
     * ดึงรายละเอียดของบุคคลในรูปแบบ JSON
     */
    public function detail(int $id)
    {
        $contact = $this->directoryModel->getContactById($id);
        if (!$contact) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูล']);
        }
        return $this->response->setJSON(['status' => 'success', 'data' => $contact]);
    }

    /**
     * ส่งออกสมุดโทรศัพท์เป็นไฟล์ vCard (.vcf)
     */
    public function exportVcf()
    {
        $ministryId = $this->request->getGet('ministry') ? (int)$this->request->getGet('ministry') : null;
        $filters = $ministryId ? ['ministry_id' => $ministryId] : [];
        $contacts = $this->directoryModel->getContacts($filters);

        $vcfContent = "";
        foreach ($contacts as $c) {
            $name = trim($c['name'] ?? '');
            if (!$name) continue;

            $vcfContent .= "BEGIN:VCARD\r\n";
            $vcfContent .= "VERSION:3.0\r\n";
            $vcfContent .= "FN;CHARSET=UTF-8:" . $name . "\r\n";
            $vcfContent .= "N;CHARSET=UTF-8:;" . $name . ";;;\r\n";
            if (!empty($c['position'])) {
                $vcfContent .= "TITLE;CHARSET=UTF-8:" . trim($c['position']) . "\r\n";
            }
            if (!empty($c['department_name'])) {
                $vcfContent .= "ORG;CHARSET=UTF-8:" . trim($c['ministry_name'] ?? 'จังหวัดพัทลุง') . ";" . trim($c['department_name']) . "\r\n";
            }
            if (!empty($c['phone'])) {
                $vcfContent .= "TEL;TYPE=WORK,VOICE:" . preg_replace('/[^0-9]/', '', $c['phone']) . "\r\n";
            }
            if (!empty($c['mobile'])) {
                $vcfContent .= "TEL;TYPE=CELL,VOICE:" . preg_replace('/[^0-9]/', '', $c['mobile']) . "\r\n";
            }
            if (!empty($c['fax'])) {
                $vcfContent .= "TEL;TYPE=WORK,FAX:" . preg_replace('/[^0-9]/', '', $c['fax']) . "\r\n";
            }
            if (!empty($c['email'])) {
                $vcfContent .= "EMAIL;TYPE=PREF,INTERNET:" . trim($c['email']) . "\r\n";
            }
            $vcfContent .= "URL:https://www.phatthalung.go.th\r\n";
            $vcfContent .= "END:VCARD\r\n";
        }

        $filename = 'phatthalung_contacts_' . date('Ymd') . '.vcf';
        return $this->response
            ->setHeader('Content-Type', 'text/vcard; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($vcfContent);
    }

    /**
     * ส่งออกข้อมูลเป็นไฟล์ Excel / CSV พร้อม UTF-8 BOM
     */
    public function exportExcel()
    {
        $ministryId = $this->request->getGet('ministry') ? (int)$this->request->getGet('ministry') : null;
        $filters = $ministryId ? ['ministry_id' => $ministryId] : [];
        $contacts = $this->directoryModel->getContacts($filters);

        // UTF-8 BOM for Excel on Windows
        $csvContent = "\xef\xbb\xbf";
        $csvContent .= "ลำดับ,ชื่อ-สกุล,ตำแหน่ง,หน่วยงาน/ส่วนราชการ,สังกัดกระทรวง,เบอร์โทรศัพท์โต๊ะ,เบอร์มือถือ,เบอร์ภายใน,วันที่ปรับปรุง\r\n";

        foreach ($contacts as $idx => $c) {
            $row = [
                $idx + 1,
                '"' . str_replace('"', '""', $c['name'] ?? '') . '"',
                '"' . str_replace('"', '""', $c['position'] ?? '') . '"',
                '"' . str_replace('"', '""', $c['department_name'] ?? '') . '"',
                '"' . str_replace('"', '""', $c['ministry_name'] ?? '') . '"',
                '"' . str_replace('"', '""', $c['phone'] ?? '') . '"',
                '"' . str_replace('"', '""', $c['mobile'] ?? '') . '"',
                '"' . str_replace('"', '""', $c['hotline'] ?? '') . '"',
                '"' . str_replace('"', '""', $c['updated_at'] ?? '') . '"'
            ];
            $csvContent .= implode(',', $row) . "\r\n";
        }

        $filename = 'phatthalung_directory_' . date('Ymd') . '.csv';
        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csvContent);
    }
}
