<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'พัทลุงพอร์ทัล | ศูนย์รวมบริการดิจิทัลจังหวัดพัทลุงร่วมสมัย',
            'services' => [
                [
                    'title' => 'ยื่นคำร้องศูนย์ดำรงธรรม',
                    'desc' => 'รับเรื่องร้องทุกข์ ติดต่อเสนอแนะ ปัญหาความเดือดร้อนออนไลน์ตลอด 24 ชั่วโมง',
                    'icon' => 'fa-solid fa-scale-balanced',
                    'color' => '#ef4444',
                    'action' => 'openRequestModal("ศูนย์ดำรงธรรม / ร้องเรียนร้องทุกข์")'
                ],
                [
                    'title' => 'ตรวจสถานะและติดตามเรื่อง',
                    'desc' => 'เช็คสถานะการดำเนินการคำร้องด้วยรหัสติดตาม PHAT-XXXXX อย่างทันท่วงที',
                    'icon' => 'fa-solid fa-clipboard-check',
                    'color' => '#10b981',
                    'action' => 'App.toast("คุณสามารถพิมพ์รหัสติดตามเรื่องที่ช่องค้นหาด้านบนได้ทันที!", "info")'
                ],
                [
                    'title' => 'ระบบบริการประชาชน e-Service',
                    'desc' => 'ขอรับใบอนุญาต จดทะเบียนหนังสือรับรอง และบริการภาษีบำรุงท้องถิ่นร่วมสมัย',
                    'icon' => 'fa-solid fa-laptop-file',
                    'color' => '#3b82f6',
                    'action' => 'openRequestModal("ขอคำแนะนำบริการงานขึ้นทะเบียน e-Service")'
                ],
                [
                    'title' => 'ประกาศจัดซื้อจัดจ้างภาครัฐ',
                    'desc' => 'ระบบค้นหาข่าวสาร مناقصة, e-bidding, ข้อมูลราคากลาง เพื่อความโปร่งใสตรวจสอบได้',
                    'icon' => 'fa-solid fa-magnifying-glass-dollar',
                    'color' => '#f59e0b',
                    'action' => 'switchNewsTab("procurement", true)'
                ],
                [
                    'title' => 'คลังข้อมูลและสถิติจังหวัด',
                    'desc' => 'ข้อมูลสถิติเศรษฐกิจ การเกษตร จำนวนประชากร และแผนยุทธศาสตร์จังหวัดพัทลุง',
                    'icon' => 'fa-solid fa-chart-column',
                    'color' => '#6366f1',
                    'action' => 'App.toast("คลังสถิติเปิดให้ดาวน์โหลดในรูปแบบ Open Data JSON/CSV ครับ", "info")'
                ],
                [
                    'title' => 'ท่องเที่ยวและสายด่วนฉุกเฉิน',
                    'desc' => 'แหล่งนำเที่ยวทะเลน้อย แผนที่ นถ่ายภาพ มอเตอร์ไซค์วิน และโทรศัพท์สายด่วน 24 ชม.',
                    'icon' => 'fa-solid fa-phone-volume',
                    'color' => '#06b6d4',
                    'action' => 'switchNewsTab("tourism", true)'
                ]
            ]
        ];

        return view('home_portal', $data);
    }
}
