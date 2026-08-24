<?php

namespace App\Controllers;

class Executive extends BaseController
{
    public function __construct()
    {
        helper('settings');
    }

    public function index($category = null)
    {
        $categories = get_executive_categories();
        $selectedCat = $category ? urldecode((string)$category) : 'all';
        $executives = get_site_executives(null, $selectedCat === 'all' ? null : $selectedCat, false);

        // Group executives by row_num
        $groupedByRow = [];
        foreach ($executives as $exec) {
            $row = (int)($exec['row_num'] ?? 1);
            if (!isset($groupedByRow[$row])) {
                $groupedByRow[$row] = [];
            }
            $groupedByRow[$row][] = $exec;
        }
        ksort($groupedByRow);

        $data = [
            'title'        => 'คณะผู้บริหารจังหวัดพัทลุง (Executive Leadership) | จังหวัดพัทลุง',
            'categories'   => $categories,
            'selectedCat'  => $selectedCat,
            'executives'   => $executives,
            'groupedByRow' => $groupedByRow,
            'isOfficer'    => session()->get('isLoggedIn')
        ];

        return view('executive_portal', $data);
    }

    /**
     * หน้าแสดงประวัติการรับราชการและวิสัยทัศน์ผู้บริหารแบบละเอียด (พร้อมพิมพ์ / PDF)
     */
    public function detail($id = null)
    {
        if (empty($id)) {
            return redirect()->to(base_url('executives'));
        }

        $executives = get_site_executives(null, null, false);
        $executive = null;

        foreach ($executives as $item) {
            if ((string)($item['id'] ?? '') === (string)$id) {
                $executive = $item;
                break;
            }
        }

        if (!$executive) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('ไม่พบข้อมูลประวัติผู้บริหารท่านนี้ในระบบ');
        }

        $data = [
            'title'     => 'ประวัติ ' . esc($executive['name']) . ' - ' . esc($executive['position']) . ' | จังหวัดพัทลุง',
            'executive' => $executive,
            'isOfficer' => session()->get('isLoggedIn')
        ];

        return view('executive_detail', $data);
    }
}
