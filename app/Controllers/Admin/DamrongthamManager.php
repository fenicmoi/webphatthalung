<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DamrongthamStatsModel;
use App\Models\DamrongthamDailyModel;

class DamrongthamManager extends BaseController
{
    /**
     * ตรวจสอบสิทธิ์การเข้าถึงระบบศูนย์ดำรงธรรม
     */
    protected function ensureDamrongthamPermission(): bool
    {
        if (function_exists('has_permission')) {
            return has_permission('damrongtham_stats');
        }
        return (session()->get('role') === 'admin');
    }

    /**
     * Show Statistics Manager: Daily records database & automated cumulative totals
     */
    public function stats()
    {
        if (!$this->ensureDamrongthamPermission()) {
            return redirect()->to(base_url('admin/dashboard'))->with('toast_msg', 'ขออภัย คุณไม่มีสิทธิ์เข้าถึงระบบสถิติศูนย์ดำรงธรรม')->with('toast_type', 'error');
        }

        $dailyModel = new DamrongthamDailyModel();

        // 1. Recalculate and fetch the latest compiled stats
        $stats = DamrongthamDailyModel::recalculateTotals();

        // 2. Fetch list of daily records from MySQL
        $dailyRecords = $dailyModel->orderBy('record_date', 'DESC')->findAll(100);

        // 3. Fetch historical baseline settings
        $baseline = DamrongthamDailyModel::getBaseline();

        $data = [
            'title'        => 'ระบบจัดเก็บสถิติรายวันและยอดสะสมศูนย์ดำรงธรรม',
            'currMenu'     => 'damrongtham_stats',
            'stats'        => $stats,
            'dailyRecords' => $dailyRecords,
            'baseline'     => $baseline,
        ];

        return view('admin/damrongtham_stats', $data);
    }

    /**
     * Add or update a daily record
     */
    public function saveDaily()
    {
        if (!$this->ensureDamrongthamPermission()) {
            return redirect()->to('admin/damrongtham/stats')->with('error', 'คุณไม่มีสิทธิ์ในการบันทึกข้อมูลส่วนนี้');
        }

        if (!$this->request->is('post')) {
            return redirect()->to('admin/damrongtham/stats');
        }

        $post = $this->request->getPost();
        $dailyModel = new DamrongthamDailyModel();

        $recordDate = trim($post['record_date'] ?? date('Y-m-d'));
        if (empty($recordDate)) {
            $recordDate = date('Y-m-d');
        }

        $dateText = trim($post['date_text'] ?? '');
        if (empty($dateText)) {
            $dateText = DamrongthamDailyModel::formatThaiDate($recordDate);
        }

        $rec = (int)($post['received_count'] ?? 0);
        $res = (int)($post['resolved_count'] ?? 0);
        $pen = isset($post['pending_count']) ? (int)$post['pending_count'] : max(0, $rec - $res);

        $dailyData = [
            'record_date'       => $recordDate,
            'date_text'         => $dateText,
            'received_count'    => $rec,
            'resolved_count'    => $res,
            'pending_count'     => $pen,
            'general_inquiries' => (int)($post['general_inquiries'] ?? 0),
            // 4 Channels
            'channel_walkin'    => (int)($post['channel_walkin'] ?? 0),
            'channel_phone'     => (int)($post['channel_phone'] ?? 0),
            'channel_website'   => (int)($post['channel_website'] ?? 0),
            'channel_letter'    => (int)($post['channel_letter'] ?? 0),
            // 6 Categories
            'cat_general'       => (int)($post['cat_general'] ?? 0),
            'cat_welfare'       => (int)($post['cat_welfare'] ?? 0),
            'cat_distress'      => (int)($post['cat_distress'] ?? 0),
            'cat_officer'       => (int)($post['cat_officer'] ?? 0),
            'cat_whistleblowing'=> (int)($post['cat_whistleblowing'] ?? 0),
            'cat_land'          => (int)($post['cat_land'] ?? 0),
            'officer_name'      => trim($post['officer_name'] ?? session()->get('full_name') ?? 'เจ้าหน้าที่'),
            'notes'             => trim($post['notes'] ?? ''),
        ];

        $dailyModel->recordDaily($dailyData);

        return redirect()->to('admin/damrongtham/stats')->with('success', "บันทึกข้อมูลสถิติประจำวันที่ {$dateText} สำเร็จ ระบบคำนวณและปรับยอดสะสมอัตโนมัติแล้ว");
    }

    /**
     * Delete a daily record by ID
     */
    public function deleteDaily($id = null)
    {
        if (!$this->ensureDamrongthamPermission()) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'คุณไม่มีสิทธิ์ในการลบข้อมูลส่วนนี้']);
            }
            return redirect()->to('admin/damrongtham/stats')->with('error', 'คุณไม่มีสิทธิ์ในการลบข้อมูลส่วนนี้');
        }

        $id = $id ?? $this->request->getPost('id') ?? $this->request->getGet('id');

        if (!$id) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบรหัสรายการที่ต้องการลบ']);
            }
            return redirect()->to('admin/damrongtham/stats')->with('error', 'ไม่พบรหัสรายการที่ต้องการลบ');
        }

        $dailyModel = new DamrongthamDailyModel();
        $record = $dailyModel->find($id);

        if ($record) {
            $dailyModel->delete($id);
            DamrongthamDailyModel::recalculateTotals();

            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => true, 'message' => "ลบรายการประจำวันที่ {$record['date_text']} เรียบร้อยแล้ว"]);
            }
            return redirect()->to('admin/damrongtham/stats')->with('success', "ลบรายการประจำวันที่ {$record['date_text']} เรียบร้อยแล้ว ระบบคำนวณยอดสะสมใหม่เรียบร้อย");
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบรายการที่ต้องการลบในฐานข้อมูล']);
        }
        return redirect()->to('admin/damrongtham/stats')->with('error', 'ไม่พบรายการที่ต้องการลบ');
    }

    /**
     * Save historical baseline settings
     */
    public function saveBaseline()
    {
        if (!$this->ensureDamrongthamPermission()) {
            return redirect()->to('admin/damrongtham/stats')->with('error', 'คุณไม่มีสิทธิ์ในการตั้งค่ายอดสะสมตั้งต้น');
        }

        if (!$this->request->is('post')) {
            return redirect()->to('admin/damrongtham/stats');
        }

        $post = $this->request->getPost();

        $baselineData = [
            'announcement_title' => trim($post['announcement_title'] ?? ''),
            'period_text'        => trim($post['period_text'] ?? ''),
            'base_received'      => (int)($post['base_received'] ?? 0),
            'base_resolved'      => (int)($post['base_resolved'] ?? 0),
            'base_pending'       => (int)($post['base_pending'] ?? 0),
            'base_ch_walkin'     => (int)($post['base_ch_walkin'] ?? 0),
            'base_ch_phone'      => (int)($post['base_ch_phone'] ?? 0),
            'base_ch_website'    => (int)($post['base_ch_website'] ?? 0),
            'base_ch_letter'     => (int)($post['base_ch_letter'] ?? 0),
            'base_cat_general'   => (int)($post['base_cat_general'] ?? 0),
            'base_cat_welfare'   => (int)($post['base_cat_welfare'] ?? 0),
            'base_cat_distress'  => (int)($post['base_cat_distress'] ?? 0),
            'base_cat_officer'   => (int)($post['base_cat_officer'] ?? 0),
            'base_cat_whistleblowing' => (int)($post['base_cat_whistleblowing'] ?? 0),
            'base_cat_land'      => (int)($post['base_cat_land'] ?? 0),
        ];

        DamrongthamDailyModel::saveBaseline($baselineData);

        return redirect()->to('admin/damrongtham/stats')->with('success', 'บันทึกการตั้งค่ายอดสะสมตั้งต้นสำเร็จ ระบบคำนวณยอดสะสมใหม่เรียบร้อยแล้ว');
    }

    /**
     * Save full statistics (Direct override)
     */
    public function saveStats()
    {
        if (!$this->ensureDamrongthamPermission()) {
            return redirect()->to('admin/damrongtham/stats')->with('error', 'คุณไม่มีสิทธิ์ในการบันทึกข้อมูลส่วนนี้');
        }

        if (!$this->request->is('post')) {
            return redirect()->to('admin/damrongtham/stats');
        }

        $post = $this->request->getPost();

        $statsData = [
            'cumulative' => [
                'title'          => trim($post['cum_title'] ?? ''),
                'period_text'    => trim($post['cum_period'] ?? ''),
                'total_received' => (int)($post['cum_received'] ?? 0),
                'total_resolved' => (int)($post['cum_resolved'] ?? 0),
                'total_pending'  => (int)($post['cum_pending'] ?? 0),
            ],
            'daily' => [
                'date_text'         => trim($post['daily_date'] ?? ''),
                'received'          => (int)($post['daily_received'] ?? 0),
                'resolved'          => (int)($post['daily_resolved'] ?? 0),
                'pending'           => (int)($post['daily_pending'] ?? 0),
                'general_inquiries' => (int)($post['daily_inquiries'] ?? 0),
            ],
            'monthly' => [
                'month_name'           => trim($post['monthly_name'] ?? ''),
                'period_text'          => trim($post['monthly_period'] ?? ''),
                'received'             => (int)($post['monthly_received'] ?? 0),
                'resolved'             => (int)($post['monthly_resolved'] ?? 0),
                'pending'              => (int)($post['monthly_pending'] ?? 0),
                'consultation_general' => (int)($post['monthly_consult'] ?? 0),
            ],
        ];

        DamrongthamStatsModel::saveStats($statsData);

        return redirect()->to('admin/damrongtham/stats')->with('success', 'บันทึกข้อมูลสถิติศูนย์ดำรงธรรมเรียบร้อยแล้ว');
    }

    /**
     * Reset to default stats
     */
    public function resetStats()
    {
        if (!$this->ensureDamrongthamPermission()) {
            return redirect()->to('admin/damrongtham/stats')->with('error', 'คุณไม่มีสิทธิ์ในการคืนค่าข้อมูล');
        }

        DamrongthamStatsModel::saveStats(DamrongthamStatsModel::getDefaults());
        DamrongthamDailyModel::recalculateTotals();
        return redirect()->to('admin/damrongtham/stats')->with('success', 'คืนค่าข้อมูลสถิติเริ่มต้นตามรายงานทางการเรียบร้อยแล้ว');
    }

    /**
     * Reset baseline and daily stats to 0
     */
    public function resetToZero()
    {
        if (!$this->ensureDamrongthamPermission()) {
            return redirect()->to('admin/damrongtham/stats')->with('error', 'คุณไม่มีสิทธิ์ในการรีเซ็ตข้อมูล');
        }

        DamrongthamDailyModel::resetBaselineToZero();
        return redirect()->to('admin/damrongtham/stats')->with('success', 'รีเซ็ตข้อมูลสถิติตั้งต้นเป็น 0 ทั้งหมดเรียบร้อยแล้ว');
    }
}
