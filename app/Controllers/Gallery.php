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
            'isOfficer'   => session()->get('isLoggedIn')
        ];

        return view('gallery_portal', $data);
    }

    public function viewAlbum($id = null)
    {
        $album = get_gallery_by_id($id);
        if (!$album) {
            return redirect()->to(base_url('gallery'))->with('error', 'ไม่พบอัลบั้มที่คุณต้องการดู');
        }

        // เพิ่มยอดเข้าชม
        $albums = get_gallery_albums(null, null, false);
        foreach ($albums as &$a) {
            if ((string)$a['id'] === (string)$id) {
                $a['views'] = ($a['views'] ?? 1) + 1;
                $album['views'] = $a['views'];
                break;
            }
        }
        save_gallery_albums($albums);

        return view('gallery_album_view', [
            'title' => esc($album['title']) . ' | จังหวัดพัทลุง',
            'album' => $album,
            'isOfficer' => session()->get('isLoggedIn')
        ]);
    }
}
