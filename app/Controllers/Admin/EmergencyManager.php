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

    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('auth/login'));
        }

        $data = [
            'title'      => 'ระบบจัดการแจ้งเตือนภัย & สภาพอากาศฉุกเฉิน | Phatthalung Admin',
            'activeMenu' => 'emergency',
            'alertData'  => get_emergency_alert()
        ];

        return view('admin/emergency_manager', $data);
    }

    public function getAlert()
    {
        if ($auth = $this->checkAuth()) return $auth;
        $data = get_emergency_alert();
        return $this->response->setJSON(['status' => 'success', 'data' => $data]);
    }

    public function syncWeather()
    {
        if ($auth = $this->checkAuth()) return $auth;
        $weather = function_exists('fetch_live_phatthalung_weather') ? fetch_live_phatthalung_weather(true) : [];
        if (!empty($weather)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'data'    => $weather,
                'message' => '⚡ ดึงข้อมูลสภาพอากาศสดสำหรับจังหวัดพัทลุงสำเร็จแล้ว'
            ]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์สภาพอากาศได้']);
    }

    public function autoApplyWeather()
    {
        if ($auth = $this->checkAuth()) return $auth;
        $weather = function_exists('fetch_live_phatthalung_weather') ? fetch_live_phatthalung_weather(true) : [];
        if (!empty($weather)) {
            save_emergency_alert($weather);
            return $this->response->setJSON([
                'status'  => 'success',
                'data'    => $weather,
                'message' => '🎉 ซิงค์และเผยแพร่สภาพอากาศสดพัทลุงสู่หน้าแรกของเว็บไซต์เรียบร้อยแล้ว!'
            ]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถซิงค์ข้อมูลสภาพอากาศได้']);
    }

    public function toggle()
    {
        if ($auth = $this->checkAuth()) return $auth;

        $current = get_emergency_alert();
        $isActive = $this->request->getPost('is_active') === '1' || $this->request->getPost('is_active') === 'true' || $this->request->getPost('is_active') === 'on';
        $current['is_active'] = $isActive;
        $current['updated_at'] = date('Y-m-d H:i');

        if (save_emergency_alert($current)) {
            return $this->response->setJSON([
                'status'    => 'success',
                'is_active' => $isActive,
                'message'   => $isActive ? '🟢 เปิดการแสดงผลแถบแจ้งเตือนบนหน้าเว็บเรียบร้อยแล้ว' : '⚪ ปิดการแสดงผลแถบแจ้งเตือนบนหน้าเว็บแล้ว'
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถบันทึกข้อมูลได้']);
    }

    public function saveAlert()
    {
        if ($auth = $this->checkAuth()) return $auth;

        $current = get_emergency_alert();

        $current['is_active'] = $this->request->getPost('is_active') !== null;
        $current['level'] = trim((string)$this->request->getPost('level')) ?: 'orange';
        $current['headline'] = trim((string)$this->request->getPost('headline')) ?: $current['headline'];
        $current['details'] = trim((string)$this->request->getPost('details')) ?: $current['details'];
        $current['affected_areas'] = trim((string)$this->request->getPost('affected_areas')) ?: $current['affected_areas'];
        $current['weather_temp'] = trim((string)$this->request->getPost('weather_temp')) ?: '26°C';
        $current['weather_cond'] = trim((string)$this->request->getPost('weather_cond')) ?: 'ฝนตกหนัก';
        $current['pm25_val'] = trim((string)$this->request->getPost('pm25_val')) ?: '12 µg/m³ (อากาศดี)';
        $current['action_link'] = trim((string)$this->request->getPost('action_link')) ?: '';
        $current['action_text'] = trim((string)$this->request->getPost('action_text')) ?: 'รายละเอียด & สายด่วน';
        $current['updated_at'] = date('Y-m-d H:i');

        if (save_emergency_alert($current)) {
            return $this->response->setJSON([
                'status'  => 'success', 
                'message' => '🎉 ประกาศเตือนภัยและข้อมูลสภาพอากาศฉุกเฉินถูกเผยแพร่สู่หน้าเว็บเรียบร้อยแล้ว!'
            ]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถอัปเดตระบบเตือนภัยได้']);
    }
}
