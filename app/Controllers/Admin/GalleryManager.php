<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class GalleryManager extends BaseController
{
    public function __construct()
    {
        helper(['settings', 'url', 'form']);
    }

    private function checkOfficerAuth(): ?ResponseInterface
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized Access. กรุณาเข้าสู่ระบบก่อนดำเนินการ']);
        }
        return null;
    }

    /**
     * ดึงข้อมูลอัลบั้มสำหรับแก้ไขใน Studio
     */
    public function getItem($id = null): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        $album = get_gallery_by_id($id);
        if (!$album) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลอัลบั้ม']);
        }
        return $this->response->setJSON(['status' => 'success', 'data' => $album]);
    }

    /**
     * บันทึกหรือแก้ไขอัลบั้ม (รองรับอัปโหลดภาพปกและอัปโหลดภาพหลายภาพในชุด)
     */
    public function saveItem(): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        $id = $this->request->getPost('id');
        $title = trim((string)$this->request->getPost('title'));
        $category = trim((string)$this->request->getPost('category'));
        $date = trim((string)$this->request->getPost('date'));

        if (empty($title)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'กรุณาระบุชื่ออัลบั้มกิจกรรม']);
        }

        $albums = get_gallery_albums(null, null, false);
        $existingIndex = null;

        if (!empty($id)) {
            foreach ($albums as $idx => $item) {
                if ((string)($item['id'] ?? '') === (string)$id) {
                    $existingIndex = $idx;
                    break;
                }
            }
        }

        $albumId = $id ?: 'gal_' . uniqid();
        $views = ($existingIndex !== null) ? ($albums[$existingIndex]['views'] ?? 1) : 1;
        $coverImage = ($existingIndex !== null) ? ($albums[$existingIndex]['cover_image'] ?? '') : '';
        $existingPhotos = ($existingIndex !== null) ? ($albums[$existingIndex]['photos'] ?? []) : [];

        // อัปโหลดไฟล์ภาพปกใหม่ (ถ้ามี)
        $coverFile = $this->request->getFile('cover_file');
        if ($coverFile && $coverFile->isValid() && !$coverFile->hasMoved()) {
            $uploadPath = FCPATH . 'uploads/gallery';
            if (!is_dir($uploadPath)) {
                @mkdir($uploadPath, 0777, true);
            }
            $newName = 'cover_' . time() . '_' . $coverFile->getRandomName();
            $coverFile->move($uploadPath, $newName);
            $coverImage = 'uploads/gallery/' . $newName;
        } elseif (!$coverImage) {
            // ถ้าระบุเป็นลิงก์เว็บ
            $coverImage = trim((string)$this->request->getPost('cover_url')) ?: 'https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?auto=format&fit=crop&w=800&q=80';
        }

        // อัปโหลดภาพกิจกรรมในอัลบั้ม (Multiple photo files)
        $photoFiles = $this->request->getFileMultiple('gallery_photos');
        if ($photoFiles) {
            $uploadPath = FCPATH . 'uploads/gallery';
            if (!is_dir($uploadPath)) {
                @mkdir($uploadPath, 0777, true);
            }
            foreach ($photoFiles as $pFile) {
                if ($pFile->isValid() && !$pFile->hasMoved()) {
                    $pName = 'img_' . uniqid() . '_' . $pFile->getRandomName();
                    $pFile->move($uploadPath, $pName);
                    $existingPhotos[] = 'uploads/gallery/' . $pName;
                }
            }
        }

        // ถ้าระบุ URL ภาพเพิ่มเติม (คั่นด้วยบรรทัดหรือจุลภาค)
        $externalUrls = trim((string)$this->request->getPost('external_urls'));
        if (!empty($externalUrls)) {
            $urlLines = preg_split('/[\r\n,]+/', $externalUrls, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($urlLines as $line) {
                $u = trim($line);
                if (!empty($u) && !in_array($u, $existingPhotos)) {
                    $existingPhotos[] = $u;
                }
            }
        }

        // ถ้าไม่มีรูปในอัลบั้มเลย ให้นำภาพปกมาเป็นรูปแรก
        if (empty($existingPhotos) && !empty($coverImage)) {
            $existingPhotos[] = $coverImage;
        }

        $albumData = [
            'id'          => $albumId,
            'title'       => $title,
            'category'    => $category ?: 'กิจกรรมสาธารณประโยชน์',
            'date'        => $date ?: date('Y-m-d'),
            'views'       => $views,
            'cover_image' => $coverImage,
            'photos'      => array_values($existingPhotos),
            'active'      => true
        ];

        if ($existingIndex !== null) {
            $albums[$existingIndex] = $albumData;
            $msg = 'อัปเดตข้อมูลอัลบั้มเรียบร้อยแล้ว';
        } else {
            array_unshift($albums, $albumData);
            $msg = 'สร้างอัลบั้มภาพกิจกรรมใหม่เรียบร้อยแล้ว';
        }

        save_gallery_albums($albums);
        return $this->response->setJSON(['status' => 'success', 'message' => $msg]);
    }

    /**
     * ลบรายการอัลบั้ม
     */
    public function deleteItem($id = null): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        if (empty($id)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่ระบุรหัสอัลบั้มที่ต้องการลบ']);
        }

        $albums = get_gallery_albums(null, null, false);
        $newAlbums = array_filter($albums, static function($item) use ($id) {
            return (string)($item['id'] ?? '') !== (string)$id;
        });

        if (count($albums) === count($newAlbums)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบอัลบั้มในระบบ']);
        }

        save_gallery_albums(array_values($newAlbums));
        return $this->response->setJSON(['status' => 'success', 'message' => 'ลบอัลบั้มออกจากคลังภาพเรียบร้อยแล้ว']);
    }

    /**
     * ลบเฉพาะภาพภายในอัลบั้ม
     */
    public function deletePhoto(): ResponseInterface
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        $albumId = $this->request->getPost('album_id');
        $photoUrl = trim((string)$this->request->getPost('photo_url'));

        if (empty($albumId) || empty($photoUrl)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ข้อมูลไม่ถูกต้อง']);
        }

        $albums = get_gallery_albums(null, null, false);
        $found = false;

        foreach ($albums as $idx => $album) {
            if ((string)($album['id'] ?? '') === (string)$albumId) {
                $photos = $album['photos'] ?? [];
                $newPhotos = array_filter($photos, static function($p) use ($photoUrl) {
                    return trim($p) !== trim($photoUrl);
                });
                $albums[$idx]['photos'] = array_values($newPhotos);
                $found = true;
                break;
            }
        }

        if (!$found) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูลอัลบั้ม']);
        }

        save_gallery_albums($albums);
        return $this->response->setJSON(['status' => 'success', 'message' => 'ลบภาพออกจากอัลบั้มแล้ว']);
    }
}
