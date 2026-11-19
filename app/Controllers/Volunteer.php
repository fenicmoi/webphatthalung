<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Volunteer extends BaseController
{
    /**
     * Royal Volunteer 904 Portal (ศูนย์อำนวยการจิตอาสาพระราชทาน จังหวัดพัทลุง)
     */
    public function index()
    {
        helper(['settings', 'url', 'form']);

        $categoryName = 'จิตอาสาพระราชทาน';

        // 1. Fetch news specific to "จิตอาสาพระราชทาน"
        $newsList = get_site_news(null, $categoryName);

        // 2. Fetch photo galleries specific to "จิตอาสาพระราชทาน"
        $galleryAlbums = function_exists('get_gallery_albums') 
            ? get_gallery_albums(8, $categoryName) 
            : [];

        // 3. Check officer/admin status
        $isOfficer = false;
        try {
            $isOfficer = (bool) session()->get('isLoggedIn');
        } catch (\Throwable $e) {}

        // 4. Mission Pillars
        $pillars = [
            'development' => [
                'title'       => 'จิตอาสาพัฒนา',
                'en'          => 'Community Development',
                'description' => 'กิจกรรมพัฒนาท้องถิ่น ชุมชน สิ่งแวดล้อม ปลูกป่า เก็บขยะ ปรับภูมิทัศน์ และสร้างสาธารณประโยชน์เพื่อความเป็นอยู่ที่ดีของพี่น้องประชาชน',
                'icon'        => 'fa-hand-holding-heart',
                'badge'       => 'พัฒนาชุมชน',
                'color'       => '#f59e0b',
                'gradient'    => 'linear-gradient(135deg, #b45309 0%, #d97706 100%)'
            ],
            'disaster' => [
                'title'       => 'จิตอาสาภัยพิบัติ',
                'en'          => 'Disaster Relief',
                'description' => 'เตรียมพร้อมรับมือ บรรเทาความเดือดร้อน และฟื้นฟูหลังเกิดสาธารณภัย น้ำท่วม วาตภัย ดินถล่ม เคียงข้างผู้ประสบภัยในพื้นที่จังหวัดพัทลุง',
                'icon'        => 'fa-shield-halved',
                'badge'       => 'ช่วยเหลือเร่งด่วน',
                'color'       => '#0284c7',
                'gradient'    => 'linear-gradient(135deg, #0369a1 0%, #0284c7 100%)'
            ],
            'special' => [
                'title'       => 'จิตอาสาเฉพาะกิจ',
                'en'          => 'Royal Ceremonies & Events',
                'description' => 'ปฏิบัติหน้าที่อำนวยความสะดวก ปฐมพยาบาล จัดระเบียบจราจร ในงานพระราชพิธี รัฐพิธี และกิจกรรมสำคัญของชาติและจังหวัดพัทลุง',
                'icon'        => 'fa-award',
                'badge'       => 'งานพระราชพิธี',
                'color'       => '#7c3aed',
                'gradient'    => 'linear-gradient(135deg, #6d28d9 0%, #7c3aed 100%)'
            ]
        ];

        return view('volunteer/index', [
            'title'         => 'ศูนย์อำนวยการจิตอาสาพระราชทาน จังหวัดพัทลุง | "เราทำความ ดี ด้วยหัวใจ"',
            'categoryName'  => $categoryName,
            'newsList'      => $newsList,
            'galleryAlbums' => $galleryAlbums,
            'isOfficer'     => $isOfficer,
            'pillars'       => $pillars
        ]);
    }
}
