<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Budget extends Controller
{
    /**
     * หน้าสรุปงบประมาณจังหวัดพัทลุง (เชื่อมรายงานตรงจากสำนักงบประมาณ พ.ศ. 2568 - 2570)
     */
    public function index()
    {
        helper(['settings']);

        $provinceCode = '04930000'; // รหัสพื้นที่จังหวัดพัทลุง (สำนักงบประมาณเขตที่ 4)

        $years = [
            [
                'year'       => '2570',
                'short'      => '70',
                'badge'      => 'ปีงบประมาณล่าสุด',
                'badge_cls'  => 'bg-warning text-dark',
                'is_latest'  => true,
                'reports'    => [
                    [
                        'type'        => 'item',
                        'title'       => 'รายการจัดสรรงบประมาณรายรายการ จังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2570',
                        'short_title' => 'รายการจัดสรรงบประมาณรายรายการ',
                        'desc'        => 'รายละเอียดโครงการและรายการงบประมาณที่ได้รับการจัดสรรลงในพื้นที่จังหวัดพัทลุง จำแนกตามหน่วยงานและโครงการ',
                        'icon'        => 'fa-solid fa-list-check',
                        'accent'      => '#2563eb',
                        'accent_bg'   => '#eff6ff',
                        'url'         => "https://www.bb.go.th/web/budget/province/province_bud70/PDF/item/{$provinceCode}.pdf",
                    ],
                    [
                        'type'        => '5bud',
                        'title'       => 'สรุปงบประมาณจำแนกตาม 5 งบรายจ่าย จังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2570',
                        'short_title' => 'สรุปงบประมาณจำแนกตาม 5 งบรายจ่าย',
                        'desc'        => 'ภาพรวมงบประมาณรายจ่ายจำแนกตามงบบุคลากร งบดำเนินงาน งบลงทุน งบเงินอุดหนุน และงบรายจ่ายอื่น',
                        'icon'        => 'fa-solid fa-chart-pie',
                        'accent'      => '#059669',
                        'accent_bg'   => '#ecfdf5',
                        'url'         => "https://www.bb.go.th/web/budget/province/province_bud70/PDF/5bud/bis60rchang1007_{$provinceCode}.pdf",
                    ],
                    [
                        'type'        => 'plan',
                        'title'       => 'สรุปงบประมาณจำแนกตามแผนงาน จังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2570',
                        'short_title' => 'สรุปงบประมาณจำแนกตามแผนงาน',
                        'desc'        => 'สรุปวงเงินงบประมาณจำแนกตามแผนงานพื้นฐาน แผนงานยุทธศาสตร์ และแผนงานบูรณาการพัฒนาพื้นที่',
                        'icon'        => 'fa-solid fa-layer-group',
                        'accent'      => '#d97706',
                        'accent_bg'   => '#fffbeb',
                        'url'         => "https://www.bb.go.th/web/budget/province/province_bud70/PDF/plan/bis61rchang2001_{$provinceCode}.pdf",
                    ],
                ],
            ],
            [
                'year'       => '2569',
                'short'      => '69',
                'badge'      => 'ปีงบประมาณ 2569',
                'badge_cls'  => 'bg-primary text-white',
                'is_latest'  => false,
                'reports'    => [
                    [
                        'type'        => 'item',
                        'title'       => 'รายการจัดสรรงบประมาณรายรายการ จังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2569',
                        'short_title' => 'รายการจัดสรรงบประมาณรายรายการ',
                        'desc'        => 'รายละเอียดโครงการและรายการงบประมาณที่ได้รับการจัดสรรลงในพื้นที่จังหวัดพัทลุง จำแนกตามหน่วยงานและโครงการ',
                        'icon'        => 'fa-solid fa-list-check',
                        'accent'      => '#2563eb',
                        'accent_bg'   => '#eff6ff',
                        'url'         => "https://www.bb.go.th/web/budget/province/province_bud69/PDF/item/{$provinceCode}.pdf",
                    ],
                    [
                        'type'        => '5bud',
                        'title'       => 'สรุปงบประมาณจำแนกตาม 5 งบรายจ่าย จังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2569',
                        'short_title' => 'สรุปงบประมาณจำแนกตาม 5 งบรายจ่าย',
                        'desc'        => 'ภาพรวมงบประมาณรายจ่ายจำแนกตามงบบุคลากร งบดำเนินงาน งบลงทุน งบเงินอุดหนุน และงบรายจ่ายอื่น',
                        'icon'        => 'fa-solid fa-chart-pie',
                        'accent'      => '#059669',
                        'accent_bg'   => '#ecfdf5',
                        'url'         => "https://www.bb.go.th/web/budget/province/province_bud69/PDF/5bud/bis60rchang1007_{$provinceCode}.pdf",
                    ],
                    [
                        'type'        => 'plan',
                        'title'       => 'สรุปงบประมาณจำแนกตามแผนงาน จังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2569',
                        'short_title' => 'สรุปงบประมาณจำแนกตามแผนงาน',
                        'desc'        => 'สรุปวงเงินงบประมาณจำแนกตามแผนงานพื้นฐาน แผนงานยุทธศาสตร์ และแผนงานบูรณาการพัฒนาพื้นที่',
                        'icon'        => 'fa-solid fa-layer-group',
                        'accent'      => '#d97706',
                        'accent_bg'   => '#fffbeb',
                        'url'         => "https://www.bb.go.th/web/budget/province/province_bud69/PDF/plan/bis61rchang2001_{$provinceCode}.pdf",
                    ],
                ],
            ],
            [
                'year'       => '2568',
                'short'      => '68',
                'badge'      => 'ปีงบประมาณ 2568',
                'badge_cls'  => 'bg-secondary text-white',
                'is_latest'  => false,
                'reports'    => [
                    [
                        'type'        => 'item',
                        'title'       => 'รายการจัดสรรงบประมาณรายรายการ จังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2568',
                        'short_title' => 'รายการจัดสรรงบประมาณรายรายการ',
                        'desc'        => 'รายละเอียดโครงการและรายการงบประมาณที่ได้รับการจัดสรรลงในพื้นที่จังหวัดพัทลุง จำแนกตามหน่วยงานและโครงการ',
                        'icon'        => 'fa-solid fa-list-check',
                        'accent'      => '#2563eb',
                        'accent_bg'   => '#eff6ff',
                        'url'         => "https://www.bb.go.th/web/budget/province/province_bud68/PDF/item/{$provinceCode}.pdf",
                    ],
                    [
                        'type'        => '5bud',
                        'title'       => 'สรุปงบประมาณจำแนกตาม 5 งบรายจ่าย จังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2568',
                        'short_title' => 'สรุปงบประมาณจำแนกตาม 5 งบรายจ่าย',
                        'desc'        => 'ภาพรวมงบประมาณรายจ่ายจำแนกตามงบบุคลากร งบดำเนินงาน งบลงทุน งบเงินอุดหนุน และงบรายจ่ายอื่น',
                        'icon'        => 'fa-solid fa-chart-pie',
                        'accent'      => '#059669',
                        'accent_bg'   => '#ecfdf5',
                        'url'         => "https://www.bb.go.th/web/budget/province/province_bud68/PDF/5bud/bis60rchang1007_{$provinceCode}.pdf",
                    ],
                    [
                        'type'        => 'plan',
                        'title'       => 'สรุปงบประมาณจำแนกตามแผนงาน จังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2568',
                        'short_title' => 'สรุปงบประมาณจำแนกตามแผนงาน',
                        'desc'        => 'สรุปวงเงินงบประมาณจำแนกตามแผนงานพื้นฐาน แผนงานยุทธศาสตร์ และแผนงานบูรณาการพัฒนาพื้นที่',
                        'icon'        => 'fa-solid fa-layer-group',
                        'accent'      => '#d97706',
                        'accent_bg'   => '#fffbeb',
                        'url'         => "https://www.bb.go.th/web/budget/province/province_bud68/PDF/plan/bis61rchang2001_{$provinceCode}.pdf",
                    ],
                ],
            ],
        ];

        return view('budget_portal', [
            'title'            => 'สรุปงบประมาณจังหวัดพัทลุง (พ.ศ. 2568 – 2570) | รายงานจากสำนักงบประมาณ',
            'meta_description' => 'ศูนย์เปิดเผยข้อมูลงบประมาณจังหวัดพัทลุง ประจำปีงบประมาณ พ.ศ. 2568 - 2570 เชื่อมตรงจากสำนักงบประมาณ เปิดอ่านแบบ E-Book และดาวน์โหลด PDF ได้ทันที',
            'budgetYears'      => $years,
            'provinceCode'     => $provinceCode,
        ]);
    }
}
