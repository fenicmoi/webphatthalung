<?php

namespace App\Controllers;

class Gallery extends BaseController
{
    public function __construct()
    {
        helper('settings');
    }

    public function index($category = null)
    {
        $categories = get_gallery_categories();
        $selectedCat = $category ? urldecode((string)$category) : 'all';

        // โหลดข้อมูลทั้งหมดให้หน้าเว็บสามารถกรองหมวดหมู่แบบกึ่ง Instant ได้ทันที
        $albums = get_gallery_albums(null, $selectedCat === 'all' ? null : $selectedCat, true);

        $data = [
            'title'       => 'คลังภาพกิจกรรมและประเพณี | จังหวัดพัทลุง',
            'categories'  => $categories,
            'selectedCat' => $selectedCat,
            'albums'      => $albums,
            'isOfficer'   => function_exists('can_manage_gallery') ? can_manage_gallery() : false
        ];

        return view('gallery_portal', $data);
    }

    public function viewAlbum($id = null)
    {
        $album = get_gallery_by_id($id);
        if (!$album) {
            return redirect()->to(base_url('gallery'))->with('error', 'ไม่พบอัลบั้มที่คุณต้องการดู');
        }

        // เพิ่มยอดเข้าชม (ทั้งในฐานข้อมูล MySQL และ Cache)
        $newViews = function_exists('increment_gallery_views') ? increment_gallery_views($id) : (int)($album['views'] ?? 1);
        $album['views'] = $newViews;

        return view('gallery_album_view', [
            'title' => esc($album['title']) . ' | จังหวัดพัทลุง',
            'album' => $album,
            'isOfficer' => function_exists('can_manage_gallery') ? can_manage_gallery() : false
        ]);
    }
}
