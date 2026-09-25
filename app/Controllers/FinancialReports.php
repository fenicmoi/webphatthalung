<?php

namespace App\Controllers;

use App\Models\FinancialReportModel;

class FinancialReports extends BaseController
{
    public function __construct()
    {
        helper(['settings', 'url']);
    }

    public function index()
    {
        $model = new FinancialReportModel();

        // Get filter inputs
        $selectedYear = $this->request->getGet('year');
        $selectedMonth = $this->request->getGet('month');
        $selectedDept = $this->request->getGet('dept');
        $keyword = trim((string)$this->request->getGet('search'));

        // Available Filter Options
        $years = $model->distinct()->select('fiscal_year')->where('fiscal_year IS NOT NULL')->orderBy('fiscal_year', 'DESC')->findAll();
        $yearsList = array_filter(array_column($years, 'fiscal_year'));

        $departments = $model->distinct()->select('department')->where('department IS NOT NULL')->orderBy('department', 'ASC')->findAll();
        $deptsList = array_filter(array_column($departments, 'department'));

        $thaiMonths = [
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน',
            'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
            'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
        ];

        // Build Query
        $builder = $model->orderBy('fiscal_year', 'DESC')->orderBy('id', 'DESC');

        if (!empty($selectedYear) && $selectedYear !== 'all') {
            $builder->where('fiscal_year', $selectedYear);
        }

        if (!empty($selectedMonth) && $selectedMonth !== 'all') {
            $builder->where('report_month', $selectedMonth);
        }

        if (!empty($selectedDept) && $selectedDept !== 'all') {
            $builder->where('department', $selectedDept);
        }

        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('title', $keyword)
                    ->orLike('department', $keyword)
                    ->orLike('fiscal_year', $keyword)
                    ->groupEnd();
        }

        $items = $builder->findAll();
        $totalAll = $model->countAllResults();

        $data = [
            'title'          => 'รายงานทางการเงินและงบทดลอง | จังหวัดพัทลุง',
            'items'          => $items,
            'totalCount'     => count($items),
            'totalAll'       => $totalAll,
            'years'          => $yearsList,
            'departments'    => $deptsList,
            'months'         => $thaiMonths,
            'selectedYear'   => $selectedYear,
            'selectedMonth'  => $selectedMonth,
            'selectedDept'   => $selectedDept,
            'keyword'        => $keyword
        ];

        return view('financial_reports_portal', $data);
    }
}
