<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class EmergencyManager extends BaseController
{
    public function __construct()
    {
        helper('settings');
    }

    private function checkAuth()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized'])->setStatusCode(401);
        }
        return null;
    }

    public function getAlert()
    {
        if ($auth = $this->checkAuth()) return $auth;
        $data = get_emergency_alert();
        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }

    public function saveAlert()
    {
        if ($auth = $this->checkAuth()) return $auth;

        $current = get_emergency_alert();

        $current['is_active'] = $this->request->getPost('is_active') !== null;
        $current['level'] = trim((string)$this->request->getPost('level')) ?: 'green';
        $current['headline'] = trim((string)$this->request->getPost('headline')) ?: $current['headline'];
        $current['details'] = trim((string)$this->request->getPost('details')) ?: $current['details'];
        $current['affected_areas'] = trim((string)$this->request->getPost('affected_areas')) ?: $current['affected_areas'];
        $current['weather_temp'] = trim((string)$this->request->getPost('weather_temp')) ?: $current['weather_temp'];
        $current['weather_cond'] = trim((string)$this->request->getPost('weather_cond')) ?: $current['weather_cond'];
        $current['pm25_val'] = trim((string)$this->request->getPost('pm25_val')) ?: $current['pm25_val'];

        if (save_emergency_alert($current)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'ประกาศเตือนภัยและข้อมูลสภาพอากาศถูกเผยแพร่สู่ระบบเรียบร้อยแล้ว']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถอัปเดตระบบเตือนภัยได้']);
    }
}
