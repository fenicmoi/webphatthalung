<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // หากยังไม่ได้เข้าสู่ระบบ ให้ส่งกลับไปที่หน้า login ทันที
        if (!session()->get('isLoggedIn')) {
            // บันทึกข้อความแจ้งเตือน Flashdata
            session()->setFlashdata('toast_msg', 'กรุณาเข้าสู่ระบบก่อนเข้าถึงพื้นที่จัดการระบบหลังบ้าน');
            session()->setFlashdata('toast_type', 'error');
            
            return redirect()->to(base_url('login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ไม่ต้องทำอะไรหลังประมวลผล Request
    }
}
