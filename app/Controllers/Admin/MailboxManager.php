<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OfficialEmailModel;
use App\Models\CitizenContactModel;
use App\Libraries\MailboxService;
use App\Libraries\LineNotifyService;

class MailboxManager extends BaseController
{
    /**
     * Display Official Government Webmail Hub
     */
    /**
     * Display Official Government Webmail Hub
     */
    public function index()
    {
        MailboxService::seedInitialEmailsIfEmpty();

        $emailModel = new OfficialEmailModel();
        $folder     = trim($this->request->getGet('folder') ?? 'inbox');
        $search     = trim($this->request->getGet('q') ?? '');
        $account    = trim($this->request->getGet('account') ?? 'all');

        $builder = $emailModel;

        if ($account !== 'all' && !empty($account)) {
            $builder = $builder->where('recipient_email', $account);
        }

        if ($folder === 'starred') {
            $builder = $builder->where('is_starred', 1)->where('category !=', 'trash');
        } elseif ($folder === 'trash') {
            $builder = $builder->where('category', 'trash');
        } elseif ($folder === 'citizen') {
            $builder = $builder->where('category', 'citizen');
        } elseif ($folder === 'official') {
            $builder = $builder->where('category', 'official');
        } else {
            $builder = $builder->where('category !=', 'trash');
        }

        if (!empty($search)) {
            $builder = $builder->groupStart()
                ->like('subject', $search)
                ->orLike('sender_name', $search)
                ->orLike('sender_email', $search)
                ->orLike('body_plain', $search)
                ->groupEnd();
        }

        $emails = $builder->orderBy('received_at', 'DESC')->paginate(20);
        $pager  = $emailModel->pager;

        // Folder Counters
        $db = \Config\Database::connect();
        $cntQuery = $db->table('official_emails');
        if ($account !== 'all' && !empty($account)) {
            $cntQuery->where('recipient_email', $account);
        }

        $counts = [
            'inbox'    => (clone $cntQuery)->where('category !=', 'trash')->countAllResults(),
            'unread'   => (clone $cntQuery)->where('is_read', 0)->where('category !=', 'trash')->countAllResults(),
            'official' => (clone $cntQuery)->where('category', 'official')->countAllResults(),
            'citizen'  => (clone $cntQuery)->where('category', 'citizen')->countAllResults(),
            'starred'  => (clone $cntQuery)->where('is_starred', 1)->where('category !=', 'trash')->countAllResults(),
            'trash'    => (clone $cntQuery)->where('category', 'trash')->countAllResults(),
        ];

        // Account Unread Summary
        $accountCounts = [];
        $accounts = MailboxService::getAccounts();
        foreach ($accounts as $emailKey => $acc) {
            $accountCounts[$emailKey] = [
                'total'  => $db->table('official_emails')->where('recipient_email', $emailKey)->where('category !=', 'trash')->countAllResults(),
                'unread' => $db->table('official_emails')->where('recipient_email', $emailKey)->where('is_read', 0)->where('category !=', 'trash')->countAllResults(),
            ];
        }

        $data = [
            'title'           => 'กล่องจดหมายกลางภาครัฐ (MOI Mailbox) | Admin Portal',
            'activeMenu'      => 'mailbox_manager',
            'emails'          => $emails,
            'pager'           => $pager,
            'folder'          => $folder,
            'search'          => $search,
            'account'         => $account,
            'accounts'        => $accounts,
            'accountCounts'   => $accountCounts,
            'counts'          => $counts,
        ];

        return view('admin/mailbox_manager', $data);
    }

    /**
     * Get Email Detail (JSON) & Mark as Read
     */
    public function detail($id)
    {
        $emailModel = new OfficialEmailModel();
        $email = $emailModel->find($id);

        if (!$email) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'error',
                'message' => 'ไม่พบอีเมล'
            ]);
        }

        // Mark as read
        if ($email['is_read'] == 0) {
            $emailModel->update($id, ['is_read' => 1]);
            $email['is_read'] = 1;
        }

        $email['received_at_fmt'] = date('d/m/Y H:i:s น.', strtotime($email['received_at']));
        $email['attachments'] = !empty($email['attachments_json']) ? json_decode($email['attachments_json'], true) : [];

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $email
        ]);
    }

    /**
     * Trigger Live IMAP Sync
     */
    public function sync()
    {
        $target = trim($this->request->getPost('account') ?? '');
        $res = MailboxService::syncMailbox(20, $target);
        return $this->response->setJSON($res);
    }

    /**
     * Toggle Star (Favorite)
     */
    public function toggleStar($id)
    {
        $emailModel = new OfficialEmailModel();
        $email = $emailModel->find($id);

        if (!$email) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูล']);
        }

        $newStar = $email['is_starred'] ? 0 : 1;
        $emailModel->update($id, ['is_starred' => $newStar]);

        return $this->response->setJSON([
            'status'     => 'success',
            'is_starred' => $newStar
        ]);
    }

    /**
     * Move to Trash / Delete
     */
    public function deleteEmail($id)
    {
        $emailModel = new OfficialEmailModel();
        $email = $emailModel->find($id);

        if (!$email) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูล']);
        }

        if ($email['category'] === 'trash') {
            $emailModel->delete($id);
            $msg = 'ลบอีเมลฉบับนี้ถาวรแล้ว';
        } else {
            $emailModel->update($id, ['category' => 'trash']);
            $msg = 'ย้ายอีเมลไปถังขยะเรียบร้อยแล้ว';
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => $msg
        ]);
    }

    /**
     * Save Mailbox IMAP Configuration
     */
    public function saveSettings()
    {
        $host       = trim($this->request->getPost('mailbox_host') ?? 'mail.moi.go.th');
        $port       = trim($this->request->getPost('mailbox_port') ?? '993');
        $protocol   = trim($this->request->getPost('mailbox_protocol') ?? 'imap');
        $encryption = trim($this->request->getPost('mailbox_encryption') ?? 'ssl');
        $user       = trim($this->request->getPost('mailbox_user') ?? 'phatthalung@moi.go.th');
        $password   = trim($this->request->getPost('mailbox_password') ?? '');

        $writableDir = defined('WRITABLE') ? rtrim(\WRITABLE, '/\\') : realpath(__DIR__ . '/../../../writable');
        $jsonPath = $writableDir . DIRECTORY_SEPARATOR . 'site_settings.json';

        $saved = is_file($jsonPath) ? json_decode(file_get_contents($jsonPath), true) : [];
        if (!is_array($saved)) $saved = [];

        $saved['mailbox_host']       = $host;
        $saved['mailbox_port']       = $port;
        $saved['mailbox_protocol']   = $protocol;
        $saved['mailbox_encryption'] = $encryption;
        $saved['mailbox_user']       = $user;
        if (!empty($password)) {
            $saved['mailbox_password'] = $password;
        }

        file_put_contents($jsonPath, json_encode($saved, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'บันทึกการตั้งค่าการเชื่อมต่อ Mail Server เรียบร้อยแล้ว'
        ]);
    }

    /**
     * Convert Email to Citizen Complaint/Request Record
     */
    public function convertToRequest($id)
    {
        $emailModel = new OfficialEmailModel();
        $email = $emailModel->find($id);

        if (!$email) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบอีเมล']);
        }

        $contactModel = new CitizenContactModel();
        $trackingCode = CitizenContactModel::generateTrackingCode();

        $contactData = [
            'tracking_code' => $trackingCode,
            'full_name'     => $email['sender_name'] ?: 'ผู้ติดต่อทางอีเมล',
            'phone'         => 'ติดต่อทางอีเมล',
            'email'         => $email['sender_email'],
            'district'      => 'เมืองพัทลุง',
            'category'      => 'damrongtham',
            'subject'       => $email['subject'],
            'message'       => $email['body_plain'] ?: strip_tags($email['body_html']),
            'status'        => 'pending',
            'officer_note'  => 'แปลงมาจากอีเมลทางการ (' . $email['sender_email'] . ') เมื่อ ' . date('d/m/Y H:i น.'),
        ];

        $contactModel->insert($contactData);

        // Notify LINE
        $contactData['category_name'] = 'เรื่องร้องเรียน (แปลงจากอีเมล)';
        LineNotifyService::notifyNewContact($contactData);

        return $this->response->setJSON([
            'status'        => 'success',
            'tracking_code' => $trackingCode,
            'message'       => "แปลงอีเมลเป็นคำร้องรหัส {$trackingCode} และส่งแจ้งเตือนเข้า LINE เรียบร้อยแล้ว"
        ]);
    }
}
