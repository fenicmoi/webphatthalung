<?php

namespace App\Controllers;

class Document extends BaseController
{
    public function __construct()
    {
        helper('settings');
    }

    public function index($category = null)
    {
        $categories = get_document_categories();
        $selectedCat = $category ? urldecode((string)$category) : 'all';
        $documents = get_site_documents(null, $selectedCat === 'all' ? null : $selectedCat, true);

        $allDocuments = get_site_documents(null, null, true);

        $data = [
            'title'        => 'ศูนย์รวมไฟล์ดาวน์โหลดและคลังเอกสารดิจิทัล (Smart Document Archive) | จังหวัดพัทลุง',
            'categories'   => $categories,
            'selectedCat'  => $selectedCat,
            'documents'    => $documents,
            'allDocuments' => $allDocuments,
            'isOfficer'    => session()->get('isLoggedIn')
        ];

        return view('document_portal', $data);
    }

    /**
     * รองรับ URL จากระบบเดิม (Legacy 2022) เช่น news_form/showList?cid=7
     */
    public function legacyNewsForm()
    {
        $cid = $this->request->getGet('cid');
        if ((string)$cid === '7') {
            return redirect()->to(base_url('documents/category/' . urlencode('การบริหารความเสี่ยง')));
        }
        if ((string)$cid === '11') {
            return redirect()->to(base_url('documents/category/' . urlencode('ธรรมาภิบาล ความโปร่งใส และ ITA')));
        }
        if ((string)$cid === '5') {
            return redirect()->to(base_url('documents/category/' . urlencode('กฎหมาย ระเบียบ และหนังสือราชการ')));
        }
        if (in_array((string)$cid, ['6', '8', '10'])) {
            return redirect()->to(base_url('documents/category/' . urlencode('ยุทธศาสตร์ แผนงาน และรายงานผล')));
        }
        return redirect()->to(base_url('documents'));
    }

    /**
     * นับยอดดาวน์โหลดไฟล์เมื่อประชาชนคลิก
     */
    public function countDownload($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing ID']);
        }
        $docs = get_site_documents(null, null, false);
        $newCount = 1;
        foreach ($docs as &$d) {
            if ((string)$d['id'] === (string)$id) {
                $d['downloads'] = ($d['downloads'] ?? 0) + 1;
                $newCount = $d['downloads'];
                break;
            }
        }
        save_site_documents($docs);
        return $this->response->setJSON(['status' => 'success', 'downloads' => $newCount]);
    }
}
