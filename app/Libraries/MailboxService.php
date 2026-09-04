<?php

namespace App\Libraries;

use App\Models\OfficialEmailModel;

class MailboxService
{
    /**
     * Get All Configured Mailbox Accounts
     */
    public static function getAccounts(): array
    {
        $siteSettings = function_exists('get_site_settings') ? get_site_settings() : [];

        $acc1 = [
            'key'        => 'main',
            'name'       => 'อีเมลกลางจังหวัดพัทลุง (Citizen & Public Portal)',
            'email'      => env('MAILBOX_USER', $siteSettings['mailbox_user'] ?? 'phatthalung@moi.go.th'),
            'host'       => env('MAILBOX_HOST', $siteSettings['mailbox_host'] ?? 'mail.moi.go.th'),
            'port'       => env('MAILBOX_PORT', $siteSettings['mailbox_port'] ?? '993'),
            'protocol'   => env('MAILBOX_PROTOCOL', $siteSettings['mailbox_protocol'] ?? 'imap'),
            'encryption' => env('MAILBOX_ENCRYPTION', $siteSettings['mailbox_encryption'] ?? 'ssl'),
            'password'   => env('MAILBOX_PASSWORD', $siteSettings['mailbox_password'] ?? 'ptl93000'),
            'badge'      => 'primary',
            'label'      => 'อีเมลกลาง'
        ];

        $acc2 = [
            'key'        => 'saraban',
            'name'       => 'งานสารบรรณจังหวัดพัทลุง (Official Saraban E-Doc)',
            'email'      => env('MAILBOX_SARABAN_USER', $siteSettings['mailbox_saraban_user'] ?? 'saraban_phatthalung@moi.go.th'),
            'host'       => env('MAILBOX_SARABAN_HOST', $siteSettings['mailbox_saraban_host'] ?? 'mail.moi.go.th'),
            'port'       => env('MAILBOX_SARABAN_PORT', $siteSettings['mailbox_saraban_port'] ?? '993'),
            'protocol'   => 'imap',
            'encryption' => 'ssl',
            'password'   => env('MAILBOX_SARABAN_PASSWORD', $siteSettings['mailbox_saraban_password'] ?? 'ptl93000'),
            'badge'      => 'success',
            'label'      => 'งานสารบรรณ'
        ];

        return [
            'phatthalung@moi.go.th'         => $acc1,
            'saraban_phatthalung@moi.go.th' => $acc2
        ];
    }

    /**
     * Get Settings for Default or Specific Account
     */
    public static function getSettings(string $accountEmail = ''): array
    {
        $accounts = self::getAccounts();
        if (!empty($accountEmail) && isset($accounts[$accountEmail])) {
            return $accounts[$accountEmail];
        }
        return reset($accounts);
    }

    /**
     * Fetch & Sync Emails from All or Specific IMAP Accounts
     */
    public static function syncMailbox(int $limit = 20, string $targetEmail = ''): array
    {
        $accounts = self::getAccounts();
        $emailModel = new OfficialEmailModel();
        $totalSynced = 0;
        $messages = [];

        $targetAccounts = [];
        if (!empty($targetEmail) && isset($accounts[$targetEmail])) {
            $targetAccounts[$targetEmail] = $accounts[$targetEmail];
        } else {
            $targetAccounts = $accounts;
        }

        foreach ($targetAccounts as $emailKey => $acc) {
            if (empty($acc['password'])) {
                $messages[] = "{$emailKey}: ยังไม่ได้ระบุรหัสผ่าน";
                continue;
            }

            $encFlag = ($acc['encryption'] ?? 'ssl') === 'ssl' ? '/ssl' : (($acc['encryption'] ?? '') === 'tls' ? '/tls' : '');
            $mailboxPath = "{" . $acc['host'] . ":" . $acc['port'] . "/imap" . $encFlag . "/novalidate-cert}INBOX";

            $inbox = @imap_open($mailboxPath, $acc['email'], $acc['password'], 0, 1, ['DISABLE_AUTHENTICATOR' => 'GSSAPI']);

            if (!$inbox) {
                $lastError = imap_last_error();
                $messages[] = "{$emailKey}: ไม่สามารถเชื่อมต่อ (" . ($lastError ?: 'โปรดตรวจสอบรหัสผ่าน') . ")";
                continue;
            }

            $emails = imap_search($inbox, 'ALL');
            $accountCount = 0;

            if ($emails) {
                rsort($emails);
                $emails = array_slice($emails, 0, $limit);

                foreach ($emails as $emailNumber) {
                    $rawUid = imap_uid($inbox, $emailNumber);
                    if (!$rawUid) continue;
                    $uid = ($acc['key'] === 'saraban' ? 'srb-' : 'ptl-') . $rawUid;

                    $exists = $emailModel->where('message_uid', (string)$uid)->first();
                    if ($exists) continue;

                    $header = imap_headerinfo($inbox, $emailNumber);
                    $structure = imap_fetchstructure($inbox, $emailNumber);

                    // Sender
                    $from = $header->from[0] ?? null;
                    $senderName = '';
                    if (!empty($from->personal)) {
                        $senderName = self::decodeMimeHeader($from->personal);
                    } else {
                        $senderName = $from->mailbox ?? 'ผู้ส่ง';
                    }
                    $senderEmail = ($from->mailbox ?? '') . '@' . ($from->host ?? '');

                    // Subject
                    $subject = !empty($header->subject) ? self::decodeMimeHeader($header->subject) : '(ไม่มีหัวข้อ)';

                    // Date
                    $receivedAt = !empty($header->date) ? date('Y-m-d H:i:s', strtotime($header->date)) : date('Y-m-d H:i:s');

                    // Body & Attachments
                    $bodyData = self::getBodyAndAttachments($inbox, $emailNumber, $structure);

                    // Category
                    $category = 'inbox';
                    if ($acc['key'] === 'saraban' || str_contains(mb_strtolower($senderEmail), 'moi.go.th') || str_contains(mb_strtolower($senderEmail), 'go.th')) {
                        $category = 'official';
                    } elseif (str_contains(mb_strtolower($subject . ' ' . $bodyData['plain']), 'ร้องเรียน') || str_contains(mb_strtolower($subject . ' ' . $bodyData['plain']), 'ร้องทุกข์')) {
                        $category = 'citizen';
                    }

                    $emailModel->insert([
                        'message_uid'      => (string)$uid,
                        'sender_name'      => $senderName,
                        'sender_email'     => $senderEmail,
                        'recipient_email'  => $acc['email'],
                        'subject'          => $subject,
                        'body_plain'       => $bodyData['plain'],
                        'body_html'        => $bodyData['html'],
                        'received_at'      => $receivedAt,
                        'has_attachment'   => $bodyData['has_attachment'] ? 1 : 0,
                        'attachments_json' => !empty($bodyData['attachments']) ? json_encode($bodyData['attachments'], JSON_UNESCAPED_UNICODE) : null,
                        'is_read'          => 0,
                        'is_starred'       => 0,
                        'category'         => $category,
                    ]);

                    $accountCount++;
                    $totalSynced++;
                }
            }

            imap_close($inbox);
            $messages[] = "{$emailKey}: ดึงใหม่ {$accountCount} ฉบับ";
        }

        return [
            'success' => true,
            'status'  => 'synced',
            'message' => "ซิงค์อีเมลเสร็จสิ้น: " . implode(' | ', $messages),
            'count'   => $totalSynced
        ];
    }

    /**
     * Helper: Decode MIME Header (e.g. =?UTF-8?B?...)
     */
    private static function decodeMimeHeader(string $text): string
    {
        $elements = @imap_mime_header_decode($text);
        if (!$elements || !is_array($elements)) {
            return $text;
        }
        $decoded = '';
        foreach ($elements as $el) {
            $charset = strtoupper($el->charset ?? 'UTF-8');
            if ($charset === 'DEFAULT' || $charset === 'AUTO' || $charset === 'UTF-8') {
                $decoded .= $el->text ?? '';
            } else {
                $decoded .= @mb_convert_encoding($el->text ?? '', 'UTF-8', $charset);
            }
        }
        return $decoded ?: $text;
    }

    /**
     * Helper: Extract Body & Attachments from IMAP Structure
     */
    private static function getBodyAndAttachments($inbox, int $emailNumber, $structure): array
    {
        $plain = '';
        $html  = '';
        $attachments = [];
        $hasAttachment = false;

        if (empty($structure->parts)) {
            // Single part email
            $body = imap_body($inbox, $emailNumber);
            if ($structure->encoding == 3) $body = base64_decode($body);
            elseif ($structure->encoding == 4) $body = quoted_printable_decode($body);

            if ($structure->subtype === 'HTML') {
                $html = $body;
                $plain = strip_tags($body);
            } else {
                $plain = $body;
                $html = nl2br(htmlspecialchars($body));
            }
        } else {
            // Multi-part email
            foreach ($structure->parts as $partNum => $part) {
                $partIndex = $partNum + 1;
                $data = imap_fetchbody($inbox, $emailNumber, (string)$partIndex);

                if ($part->encoding == 3) $data = base64_decode($data);
                elseif ($part->encoding == 4) $data = quoted_printable_decode($data);

                if ($part->subtype === 'PLAIN' && empty($plain)) {
                    $plain = $data;
                } elseif ($part->subtype === 'HTML' && empty($html)) {
                    $html = $data;
                }

                // Check for Attachment
                $filename = '';
                if ($part->ifdparameters) {
                    foreach ($part->dparameters as $param) {
                        if (strtolower($param->attribute) === 'filename') {
                            $filename = self::decodeMimeHeader($param->value);
                        }
                    }
                }
                if ($part->ifparameters && empty($filename)) {
                    foreach ($part->parameters as $param) {
                        if (strtolower($param->attribute) === 'name') {
                            $filename = self::decodeMimeHeader($param->value);
                        }
                    }
                }

                if (!empty($filename)) {
                    $hasAttachment = true;
                    $attachments[] = [
                        'name' => $filename,
                        'size' => $part->bytes ?? 0,
                        'type' => ($part->type ?? 0) . '/' . ($part->subtype ?? 'octet-stream')
                    ];
                }
            }
        }

        return [
            'plain'          => trim($plain),
            'html'           => $html ?: nl2br(htmlspecialchars($plain)),
            'has_attachment' => $hasAttachment,
            'attachments'    => $attachments
        ];
    }

    /**
     * Populate realistic official seed emails for first-time use
     */
    public static function seedInitialEmailsIfEmpty(): void
    {
        $emailModel = new OfficialEmailModel();
        if ($emailModel->countAllResults() > 0) {
            return;
        }

        $now = date('Y-m-d H:i:s');
        $yesterday = date('Y-m-d H:i:s', strtotime('-1 day'));
        $twoDaysAgo = date('Y-m-d H:i:s', strtotime('-2 days'));

        $sampleEmails = [
            [
                'message_uid'     => 'moi-gov-1001',
                'sender_name'     => 'สำนักงานปลัดกระทรวงมหาดไทย',
                'sender_email'    => 'saraban_ops@moi.go.th',
                'recipient_email' => 'phatthalung@moi.go.th',
                'subject'         => 'ด่วนที่สุด! ซักซ้อมแนวทางขับเคลื่อนโครงการพัฒนาจังหวัดและกลุ่มจังหวัด ประจำปีงบประมาณ พ.ศ. 2570',
                'body_plain'      => "เรียน ผู้ว่าราชการจังหวัดพัทลุง\n\nด้วยกระทรวงมหาดไทย ขอซักซ้อมแนวทางการจัดทำแผนปฏิบัติราชการประจำปีของจังหวัดและกลุ่มจังหวัดภาคใต้ฝั่งอ่าวไทย ขอให้จังหวัดดำเนินการรวบรวมข้อมูลโครงการให้สอดคล้องกับยุทธศาสตร์ชาติ 20 ปี และบันทึกผ่านระบบ e-MENSCR ภายในกำหนดเวลา\n\nจึงเรียนมาเพื่อโปรดพิจารณาดำเนินการ\nสำนักงานปลัดกระทรวงมหาดไทย",
                'body_html'       => "<p><strong>เรียน ผู้ว่าราชการจังหวัดพัทลุง</strong></p><p>ด้วยกระทรวงมหาดไทย ขอซักซ้อมแนวทางการจัดทำแผนปฏิบัติราชการประจำปีของจังหวัดและกลุ่มจังหวัดภาคใต้ฝั่งอ่าวไทย ขอให้จังหวัดดำเนินการรวบรวมข้อมูลโครงการให้สอดคล้องกับยุทธศาสตร์ชาติ 20 ปี และบันทึกผ่านระบบ e-MENSCR ภายในกำหนดเวลา</p><p>จึงเรียนมาเพื่อโปรดพิจารณาดำเนินการ<br><strong>สำนักงานปลัดกระทรวงมหาดไทย</strong></p>",
                'received_at'     => $now,
                'has_attachment'  => 1,
                'attachments_json'=> json_encode([['name' => 'แนวทางการจัดทำแผน_2570.pdf', 'size' => 1420000, 'type' => 'application/pdf']], JSON_UNESCAPED_UNICODE),
                'is_read'         => 0,
                'is_starred'      => 1,
                'category'        => 'official',
            ],
            [
                'message_uid'     => 'moi-gov-1002',
                'sender_name'     => 'คุณวิรัช รัตนศิลป์ (ประชาชน อ.ควนขนุน)',
                'sender_email'    => 'wirat.rattana@gmail.com',
                'recipient_email' => 'phatthalung@moi.go.th',
                'subject'         => 'ขอความอนุเคราะห์ซ่อมแซมคอสะพานข้ามคลองลำปำ ชำรุดจากฝนตกหนัก',
                'body_plain'      => "กราบเรียน ท่านผู้ว่าราชการจังหวัดพัทลุง\n\nเนื่องจากช่วงสัปดาห์ที่ผ่านมามีฝนตกหนักต่อเนื่อง ทำให้คอสะพานข้ามคลองลำปำ รอยต่อ ต.ทะเลน้อย - ต.พนางตุง เกิดการทรุดตัว รถจักรยานยนต์และรถยนต์สัญจรลำบากมาก เกรงว่าจะเกิดอุบัติเหตุแก่พี่น้องประชาชน จึงใคร่ขอความอนุเคราะห์หน่วยงานที่เกี่ยวข้องช่วยส่งทีมช่างเข้าตรวจสอบและซ่อมแซมเป็นการเร่งด่วนครับ\n\nขอขอบพระคุณเป็นอย่างสูง\nวิรัช รัตนศิลป์ (โทร 089-765-4321)",
                'body_html'       => "<p><strong>กราบเรียน ท่านผู้ว่าราชการจังหวัดพัทลุง</strong></p><p>เนื่องจากช่วงสัปดาห์ที่ผ่านมามีฝนตกหนักต่อเนื่อง ทำให้คอสะพานข้ามคลองลำปำ รอยต่อ ต.ทะเลน้อย - ต.พนางตุง เกิดการทรุดตัว รถจักรยานยนต์และรถยนต์สัญจรลำบากมาก เกรงว่าจะเกิดอุบัติเหตุแก่พี่น้องประชาชน จึงใคร่ขอความอนุเคราะห์หน่วยงานที่เกี่ยวข้องช่วยส่งทีมช่างเข้าตรวจสอบและซ่อมแซมเป็นการเร่งด่วนครับ</p><p>ขอขอบพระคุณเป็นอย่างสูง<br><strong>วิรัช รัตนศิลป์ (โทร 089-765-4321)</strong></p>",
                'received_at'     => $yesterday,
                'has_attachment'  => 1,
                'attachments_json'=> json_encode([['name' => 'ภาพถ่ายคอสะพานทรุด.jpg', 'size' => 850000, 'type' => 'image/jpeg']], JSON_UNESCAPED_UNICODE),
                'is_read'         => 0,
                'is_starred'      => 0,
                'category'        => 'citizen',
            ],
            [
                'message_uid'     => 'moi-gov-1003',
                'sender_name'     => 'กรมป้องกันและบรรเทาสาธารณภัย (ปภ.)',
                'sender_email'    => 'disaster_alert@disaster.go.th',
                'recipient_email' => 'phatthalung@moi.go.th',
                'subject'         => 'รายงานสถานการณ์สภาพอากาศและแจ้งเตือนเฝ้าระวังน้ำป่าไหลหลาก พื้นที่เทือกเขาบรรทัด',
                'body_plain'      => "แจ้งเตือนกองอำนวยการป้องกันและบรรเทาสาธารณภัยจังหวัดพัทลุง\n\nกรมอุตุนิยมวิทยาคาดการณ์มรสุมตะวันออกเฉียงเหนือพัดปกคลุมอ่าวไทยและภาคใต้มีกำลังแรง ขอให้เฝ้าระวังพื้นที่ลาดเชิงเขาและชุมชนริมน้ำตก ในเขต อ.กงหรา อ.ศรีนครินทร์ และ อ.ตะโหมด ระหว่างวันที่ 1-4 กันยายน 2569\n\nกรมป้องกันและบรรเทาสาธารณภัย",
                'body_html'       => "<p><strong>แจ้งเตือนกองอำนวยการป้องกันและบรรเทาสาธารณภัยจังหวัดพัทลุง</strong></p><p>กรมอุตุนิยมวิทยาคาดการณ์มรสุมตะวันออกเฉียงเหนือพัดปกคลุมอ่าวไทยและภาคใต้มีกำลังแรง ขอให้เฝ้าระวังพื้นที่ลาดเชิงเขาและชุมชนริมน้ำตก ในเขต อ.กงหรา อ.ศรีนครินทร์ และ อ.ตะโหมด ระหว่างวันที่ 1-4 กันยายน 2569</p><p><strong>กรมป้องกันและบรรเทาสาธารณภัย</strong></p>",
                'received_at'     => $twoDaysAgo,
                'has_attachment'  => 0,
                'attachments_json'=> null,
                'is_read'         => 1,
                'is_starred'      => 1,
                'category'        => 'official',
            ]
        ];

        foreach ($sampleEmails as $item) {
            $emailModel->insert($item);
        }
    }
}
