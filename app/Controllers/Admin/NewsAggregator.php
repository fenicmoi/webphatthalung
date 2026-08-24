<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\NewsAggregatorService;
use CodeIgniter\HTTP\ResponseInterface;

class NewsAggregator extends BaseController
{
    private function checkOfficerAuth(): ?ResponseInterface
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('officer/login'));
        }
        return null;
    }

    public function index()
    {
        if ($auth = $this->checkOfficerAuth()) return $auth;

        helper('settings');
        $service = new NewsAggregatorService();
        $feeds = $service->getFeeds(false);

        $cacheFile = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') . '/aggregated_news.json' : realpath(__DIR__ . '/../../../writable') . '/aggregated_news.json';
        $lastSync = file_exists($cacheFile) ? date('d/m/Y H:i:s', filemtime($cacheFile)) : 'ยังไม่มีการซิงค์';

        return view('admin/news_aggregator', [
            'pageTitle' => 'ระบบรวบรวมข่าวสารอัตโนมัติ (News & Social Media Aggregator)',
            'feeds'     => $feeds,
            'lastSync'  => $lastSync,
            'total'     => count($feeds)
        ]);
    }

    public function sync(): ResponseInterface
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $service = new NewsAggregatorService();
        $feeds = $service->refreshFeeds();

        return $this->response->setJSON([
            'status'   => 'success',
            'message'  => 'ซิงค์และอัปเดตฟีดข่าวสารล่าสุดเรียบร้อยแล้ว (' . count($feeds) . ' รายการ)',
            'total'    => count($feeds),
            'lastSync' => date('d/m/Y H:i:s')
        ]);
    }

    public function import(): ResponseInterface
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $feedId = $this->request->getPost('feed_id');
        if (empty($feedId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบรหัสข่าวที่ต้องการนำเข้า']);
        }

        $service = new NewsAggregatorService();
        $success = $service->importToSiteNews((string)$feedId);

        if ($success) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'นำเข้าข่าวสารเข้าสู่คลังข่าวทางการของเว็บไซต์เรียบร้อยแล้ว!'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'ไม่สามารถนำเข้าข่าวได้ กรุณาลองใหม่อีกครั้ง'
        ]);
    }
}
