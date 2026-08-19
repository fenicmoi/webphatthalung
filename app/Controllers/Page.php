<?php

namespace App\Controllers;

use App\Models\PageModel;

class Page extends BaseController
{
    public function view($slug = null)
    {
        if (empty($slug)) {
            return redirect()->to('/');
        }

        $pageModel = new PageModel();
        $page = $pageModel->where('slug', $slug)->first();

        if (!$page) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // อัปเดตจำนวนผู้เข้าชม
        $pageModel->update($page['id'], [
            'views' => $page['views'] + 1
        ]);

        // ดึงเพจย่อย ถ้ามี
        $children = $pageModel->where('parent_id', $page['id'])
                              ->orderBy('order_num', 'ASC')
                              ->findAll();

        $data = [
            'title'    => $page['title'],
            'page'     => $page,
            'children' => $children
        ];

        return view('pages/view', $data);
    }
}
