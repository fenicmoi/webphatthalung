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
        $isOfficer = session()->get('isLoggedIn') ? true : false;
        
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
            'isOfficer' => $isOfficer
        ];

        return view('strategy_portal', $data);
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
