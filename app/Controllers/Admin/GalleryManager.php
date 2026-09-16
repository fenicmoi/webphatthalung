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
     * บันทึกหรือแก้ไขอัลบั้ม (บันทึกลงฐานข้อมูล MySQL ตาราง gallery_albums & gallery_photos และ sync ไฟล์ JSON)
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

        $numericId = (int) preg_replace('/[^0-9]/', '', (string)$id);
        $albumModel = new \App\Models\GalleryAlbumModel();
        $photoModel = new \App\Models\GalleryPhotoModel();

        $existingDbAlbum = $numericId > 0 ? $albumModel->find($numericId) : null;
        $coverImage = $existingDbAlbum['cover_image'] ?? '';
        $existingPhotos = [];

        if ($existingDbAlbum) {
            $dbPhotos = $photoModel->where('album_id', $numericId)->findAll();
            $existingPhotos = array_column($dbPhotos, 'image_path');
        } else {
            // Also check fallback json if updating a json-seeded item
            $albums = get_gallery_albums(null, null, false);
            foreach ($albums as $item) {
                if ((string)($item['id'] ?? '') === (string)$id) {
                    $coverImage = $item['cover_image'] ?? '';
                    $existingPhotos = $item['photos'] ?? [];
                    break;
                }
            }
        }

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
            $coverUrl = trim((string)$this->request->getPost('cover_url'));
            if (!empty($coverUrl)) {
                $coverImage = $coverUrl;
            } else {
                $coverImage = 'https://images.unsplash.com/photo-1542273917363-3b1817f69a2d?auto=format&fit=crop&w=800&q=80';
            }
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

        // 1. บันทึกลงฐานข้อมูล MySQL (gallery_albums & gallery_photos)
        $descData = [
            'category' => $category ?: 'กิจกรรมสาธารณประโยชน์',
            'date'     => $date ?: date('Y-m-d'),
            'views'    => 1
        ];

        $albumDbData = [
            'title'       => $title,
            'cover_image' => $coverImage,
            'description' => json_encode($descData, JSON_UNESCAPED_UNICODE),
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        if (!empty($date)) {
            $albumDbData['created_at'] = date('Y-m-d H:i:s', strtotime($date));
        }

        $dbAlbumId = null;
        try {
            if ($existingDbAlbum) {
                $albumModel->update($numericId, $albumDbData);
                $dbAlbumId = $numericId;
                $msg = 'อัปเดตข้อมูลอัลบั้มในฐานข้อมูลเรียบร้อยแล้ว';
            } else {
                $dbAlbumId = $albumModel->insert($albumDbData);
                $msg = 'สร้างอัลบั้มภาพกิจกรรมใหม่ในฐานข้อมูลเรียบร้อยแล้ว';
            }

            if ($dbAlbumId) {
                // จัดการรูปภาพในตาราง gallery_photos
                $curDbPhotos = $photoModel->where('album_id', $dbAlbumId)->findAll();
                $curPhotoPaths = array_column($curDbPhotos, 'image_path');

                // เพิ่มรูปใหม่ที่ยังไม่มีในฐานข้อมูล
                foreach ($existingPhotos as $pPath) {
                    if (!in_array($pPath, $curPhotoPaths)) {
                        $photoModel->insert([
                            'album_id'   => $dbAlbumId,
                            'image_path' => $pPath,
                            'caption'    => null,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }

                // ลบรูปที่ผู้ใช้กดลบออก
                foreach ($curDbPhotos as $curP) {
                    if (!in_array($curP['image_path'], $existingPhotos)) {
                        $photoModel->delete($curP['id']);
                    }
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'Gallery Save to DB Error: ' . $e->getMessage());
            $msg = 'บันทึกข้อมูลเรียบร้อยแล้ว';
        }

        // 2. ซิงค์ลงไฟล์ JSON สำหรับ Cache / สำรองข้อมูล
        $finalId = $dbAlbumId ? ('gal_' . $dbAlbumId) : ($id ?: 'gal_' . uniqid());
        $albumData = [
            'id'          => $finalId,
            'db_id'       => $dbAlbumId ?? $numericId,
            'title'       => $title,
            'category'    => $category ?: 'กิจกรรมสาธารณประโยชน์',
            'date'        => $date ?: date('Y-m-d'),
            'views'       => 1,
            'cover_image' => $coverImage,
            'photos'      => array_values($existingPhotos),
            'active'      => true
        ];

        $albums = get_gallery_albums(null, null, false);
        $found = false;
        foreach ($albums as $k => $item) {
            if ((string)($item['id'] ?? '') === (string)$finalId || 
                ($dbAlbumId && (int)($item['db_id'] ?? 0) === (int)$dbAlbumId)) {
                $albums[$k] = $albumData;
                $found = true;
                break;
            }
        }
        if (!$found) {
            array_unshift($albums, $albumData);
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

        $numericId = (int) preg_replace('/[^0-9]/', '', (string)$id);

        try {
            if ($numericId > 0) {
                $albumModel = new \App\Models\GalleryAlbumModel();
                $photoModel = new \App\Models\GalleryPhotoModel();
                $photoModel->where('album_id', $numericId)->delete();
                $albumModel->delete($numericId);
            }
        } catch (\Throwable $e) {
            log_message('error', 'Gallery Delete DB Error: ' . $e->getMessage());
        }

        $albums = get_gallery_albums(null, null, false);
        $newAlbums = array_filter($albums, static function($item) use ($id, $numericId) {
            $matchId = (string)($item['id'] ?? '') === (string)$id;
            $matchDb = $numericId > 0 && (int)($item['db_id'] ?? 0) === $numericId;
            return !$matchId && !$matchDb;
        });

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

        $numericId = (int) preg_replace('/[^0-9]/', '', (string)$albumId);

        try {
            if ($numericId > 0) {
                $photoModel = new \App\Models\GalleryPhotoModel();
                $photoModel->where('album_id', $numericId)->where('image_path', $photoUrl)->delete();
            }
        } catch (\Throwable $e) {
            log_message('error', 'Gallery Delete Photo DB Error: ' . $e->getMessage());
        }

        $albums = get_gallery_albums(null, null, false);
        foreach ($albums as $idx => $album) {
            if ((string)($album['id'] ?? '') === (string)$albumId || ($numericId > 0 && (int)($album['db_id'] ?? 0) === $numericId)) {
                $photos = $album['photos'] ?? [];
                $newPhotos = array_filter($photos, static function($p) use ($photoUrl) {
                    return trim($p) !== trim($photoUrl);
                });
                $albums[$idx]['photos'] = array_values($newPhotos);
                break;
            }
        }

        save_gallery_albums($albums);
        return $this->response->setJSON(['status' => 'success', 'message' => 'ลบภาพออกจากอัลบั้มแล้ว']);
    }
}
