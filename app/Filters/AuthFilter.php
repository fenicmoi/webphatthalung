<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // หากยังไม่ได้เข้าสู่ระบบ
        if (!session()->get('isLoggedIn')) {
            $isAjax = service('request')->isAJAX()
                || strpos($request->getHeaderLine('Accept'), 'json') !== false
                || strpos($request->getHeaderLine('X-Requested-With'), 'XMLHttpRequest') !== false;

            // หากเป็นการร้องขอผ่าน AJAX / JSON ให้ส่ง JSON 401 กลับไป
            if ($isAjax) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'status'  => 'error',
                        'code'    => 401,
                        'message' => 'เซสชันหมดอายุ กรุณาเข้าสู่ระบบใหม่อีกครั้ง (Session Expired)'
                    ]);
            }

            // บันทึกข้อความแจ้งเตือน Flashdata สำหรับการเปิดหน้าเว็บปกติ
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
