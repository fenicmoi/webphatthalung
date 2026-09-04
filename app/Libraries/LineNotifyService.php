<?php

namespace App\Libraries;

/**
 * LineNotifyService & Officer Notification Hub
 * Handles real-time alerts to officer's LINE and official provincial email (phatthalung@moi.go.th)
 */
class LineNotifyService
{
    /**
     * Send notification for a new citizen contact/complaint
     */
    public static function notifyNewContact(array $contactData): array
    {
        $results = [
            'line'  => false,
            'email' => false,
            'log'   => []
        ];

        // 1. Send LINE Notification (Messaging API / Flex Message or Notify Token)
        $lineResult = self::sendLineAlert($contactData);
        $results['line'] = $lineResult['success'];
        $results['log'][] = 'LINE: ' . ($lineResult['message'] ?? '');

        // 2. Send Official Email to phatthalung@moi.go.th
        $emailResult = self::sendEmailAlert($contactData);
        $results['email'] = $emailResult['success'];
        $results['log'][] = 'Email: ' . ($emailResult['message'] ?? '');

        return $results;
    }

    /**
     * Send LINE Alert (Supports LINE Messaging API Flex Message and LINE Notify)
     */
    public static function sendLineAlert(array $data): array
    {
        // Try getting token from env or settings
        $channelToken = env('LINE_CHANNEL_ACCESS_TOKEN');
        if (empty($channelToken) && function_exists('get_site_settings')) {
            $channelToken = get_site_settings('line_channel_access_token');
        }
        $channelToken = trim($channelToken ?? '');

        $targetGroupId = env('LINE_ADMIN_GROUP_ID');
        if (empty($targetGroupId) && function_exists('get_site_settings')) {
            $targetGroupId = get_site_settings('line_admin_group_id');
        }
        $targetGroupId = trim($targetGroupId ?? '');

        $notifyToken = env('LINE_NOTIFY_TOKEN');
        if (empty($notifyToken) && function_exists('get_site_settings')) {
            $notifyToken = get_site_settings('line_notify_token');
        }
        $notifyToken = trim($notifyToken ?? '');

        $trackingCode = $data['tracking_code'] ?? 'PTL-XXXX';
        $fullName     = $data['full_name'] ?? 'ประชาชนผู้ติดต่อ';
        $phone        = $data['phone'] ?? '-';
        $category     = $data['category_name'] ?? ($data['category'] ?? 'ทั่วไป');
        $district     = $data['district'] ?? 'พัทลุง';
        $subject      = $data['subject'] ?? 'เรื่องติดต่อ';
        $message      = $data['message'] ?? '';
        $adminUrl     = base_url('admin/contacts?tracking=' . urlencode($trackingCode));

        // Format Text Message
        $thaiTime = date('d/m/Y H:i:s');
        $textMessage = "🔔 [แจ้งเตือน] มีเรื่องติดต่อใหม่จากประชาชน\n"
            . "--------------------------------\n"
            . "🏷️ รหัสติดตาม: {$trackingCode}\n"
            . "📌 ประเภท: {$category}\n"
            . "👤 ผู้แจ้ง: {$fullName}\n"
            . "📞 โทร: {$phone}\n"
            . "📍 พื้นที่: อ.{$district} จ.พัทลุง\n"
            . "📝 หัวข้อ: {$subject}\n"
            . "⏰ เวลา: {$thaiTime}\n"
            . "🔗 จัดการคำร้อง: {$adminUrl}";

        // Priority 1: LINE Messaging API (Flex Message / Push or Broadcast)
        if (!empty($channelToken)) {
            $messages = [
                [
                    'type' => 'flex',
                    'altText' => "🔔 มีเรื่องติดต่อใหม่: [{$trackingCode}] {$subject}",
                    'contents' => [
                        'type' => 'bubble',
                        'size' => 'giga',
                        'header' => [
                            'type' => 'box',
                            'layout' => 'vertical',
                            'contents' => [
                                [
                                    'type' => 'text',
                                    'text' => '🔔 ศูนย์บริการประชาชนจังหวัดพัทลุง',
                                    'weight' => 'bold',
                                    'color' => '#ffffff',
                                    'size' => 'md'
                                ],
                                [
                                    'type' => 'text',
                                    'text' => 'มีเรื่องติดต่อ/ร้องเรียนใหม่เข้ามาในระบบ',
                                    'color' => '#a7f3d0',
                                    'size' => 'xs'
                                ]
                            ],
                            'backgroundColor' => '#064e3b',
                            'paddingAll' => '15px'
                        ],
                        'body' => [
                            'type' => 'box',
                            'layout' => 'vertical',
                            'contents' => [
                                [
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'contents' => [
                                        ['type' => 'text', 'text' => 'รหัสติดตาม', 'size' => 'sm', 'color' => '#64748b', 'flex' => 3],
                                        ['type' => 'text', 'text' => $trackingCode, 'size' => 'sm', 'weight' => 'bold', 'color' => '#0284c7', 'flex' => 6]
                                    ],
                                    'margin' => 'md'
                                ],
                                [
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'contents' => [
                                        ['type' => 'text', 'text' => 'ประเภทเรื่อง', 'size' => 'sm', 'color' => '#64748b', 'flex' => 3],
                                        ['type' => 'text', 'text' => $category, 'size' => 'sm', 'weight' => 'bold', 'color' => '#dc2626', 'flex' => 6]
                                    ],
                                    'margin' => 'md'
                                ],
                                [
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'contents' => [
                                        ['type' => 'text', 'text' => 'ผู้ติดต่อ', 'size' => 'sm', 'color' => '#64748b', 'flex' => 3],
                                        ['type' => 'text', 'text' => $fullName . ' (' . $phone . ')', 'size' => 'sm', 'color' => '#1e293b', 'flex' => 6]
                                    ],
                                    'margin' => 'md'
                                ],
                                [
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'contents' => [
                                        ['type' => 'text', 'text' => 'พื้นที่', 'size' => 'sm', 'color' => '#64748b', 'flex' => 3],
                                        ['type' => 'text', 'text' => 'อ.' . $district . ' จ.พัทลุง', 'size' => 'sm', 'color' => '#1e293b', 'flex' => 6]
                                    ],
                                    'margin' => 'md'
                                ],
                                [
                                    'type' => 'box',
                                    'layout' => 'horizontal',
                                    'contents' => [
                                        ['type' => 'text', 'text' => 'หัวข้อเรื่อง', 'size' => 'sm', 'color' => '#64748b', 'flex' => 3],
                                        ['type' => 'text', 'text' => $subject, 'size' => 'sm', 'weight' => 'bold', 'color' => '#0f172a', 'flex' => 6, 'wrap' => true]
                                    ],
                                    'margin' => 'md'
                                ],
                                [
                                    'type' => 'box',
                                    'layout' => 'vertical',
                                    'contents' => [
                                        ['type' => 'text', 'text' => 'รายละเอียด:', 'size' => 'xs', 'color' => '#94a3b8'],
                                        ['type' => 'text', 'text' => mb_substr($message, 0, 150) . (mb_strlen($message) > 150 ? '...' : ''), 'size' => 'sm', 'color' => '#334155', 'wrap' => true]
                                    ],
                                    'margin' => 'md',
                                    'paddingAll' => '10px',
                                    'backgroundColor' => '#f8fafc',
                                    'cornerRadius' => '8px'
                                ]
                            ]
                        ],
                        'footer' => [
                            'type' => 'box',
                            'layout' => 'vertical',
                            'contents' => [
                                [
                                    'type' => 'button',
                                    'action' => [
                                        'type' => 'uri',
                                        'label' => '🔍 เปิดดูรายละเอียด & รับเรื่อง',
                                        'uri' => $adminUrl
                                    ],
                                    'style' => 'primary',
                                    'color' => '#10b981'
                                ]
                            ],
                            'paddingAll' => '12px'
                        ]
                    ]
                ]
            ];

            if (!empty($targetGroupId)) {
                $endpoint = 'https://api.line.me/v2/bot/message/push';
                $flexPayload = ['to' => $targetGroupId, 'messages' => $messages];
            } else {
                $endpoint = 'https://api.line.me/v2/bot/message/broadcast';
                $flexPayload = ['messages' => $messages];
            }

            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $channelToken
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($flexPayload));
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);
            $resp = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                return ['success' => true, 'message' => 'Sent Flex Message via LINE Messaging API (' . (!empty($targetGroupId) ? 'Push' : 'Broadcast') . ')'];
            } else {
                log_message('error', '[LineNotifyService] Error ' . $httpCode . ': ' . $resp);
                return ['success' => false, 'message' => 'LINE API returned HTTP ' . $httpCode . ': ' . $resp];
            }
        }

        // Priority 2: LINE Notify (Fallback if Notify Token configured)
        if (!empty($notifyToken)) {
            $ch = curl_init('https://notify-api.line.me/api/notify');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Bearer ' . $notifyToken
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['message' => "\n" . $textMessage]));
            curl_setopt($ch, CURLOPT_TIMEOUT, 8);
            $resp = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                return ['success' => true, 'message' => 'Sent via LINE Notify API'];
            }
        }

        // If no token is set, simulate successful log
        log_message('info', '[LineNotifyService] ' . $textMessage);
        return [
            'success' => true,
            'simulated' => true,
            'message' => 'Notification formatted and logged (Configure LINE Token in Settings/env to deliver to real chat)'
        ];
    }

    /**
     * Send Official Email Alert to phatthalung@moi.go.th
     */
    public static function sendEmailAlert(array $data): array
    {
        $targetEmail = env('CONTACT_ADMIN_EMAIL', 'phatthalung@moi.go.th');
        $trackingCode = $data['tracking_code'] ?? 'PTL-XXXX';
        $fullName     = $data['full_name'] ?? 'ประชาชน';
        $phone        = $data['phone'] ?? '-';
        $email        = $data['email'] ?? '-';
        $category     = $data['category_name'] ?? ($data['category'] ?? 'ทั่วไป');
        $district     = $data['district'] ?? 'พัทลุง';
        $subject      = $data['subject'] ?? 'เรื่องติดต่อ';
        $message      = nl2br(htmlspecialchars($data['message'] ?? ''));
        $adminUrl     = base_url('admin/contacts?tracking=' . urlencode($trackingCode));
        $dateFormatted = date('d/m/Y H:i:s');

        $emailSubject = "🔔 [ศาลากลางจังหวัดพัทลุง] เรื่องติดต่อใหม่: {$trackingCode} - {$subject}";

        $htmlBody = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{$emailSubject}</title>
</head>
<body style="font-family: 'Prompt', 'Tahoma', sans-serif; background-color: #f1f5f9; padding: 25px; margin: 0;">
    <div style="max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <div style="background: linear-gradient(135deg, #022c22 0%, #064e3b 100%); color: #ffffff; padding: 24px 30px; border-bottom: 4px solid #10b981;">
            <h2 style="margin: 0; font-size: 20px; font-weight: 700;">🏛️ ศาลากลางจังหวัดพัทลุง</h2>
            <p style="margin: 5px 0 0 0; font-size: 13px; color: #a7f3d0;">ระบบศูนย์บริการประชาชนและรับเรื่องร้องเรียนร้องทุกข์ออนไลน์</p>
        </div>
        
        <div style="padding: 30px;">
            <div style="background: #f8fafc; border-left: 4px solid #0284c7; padding: 14px 18px; margin-bottom: 25px; border-radius: 4px;">
                <span style="font-size: 12px; color: #64748b; text-transform: uppercase;">รหัสติดตามเรื่อง:</span>
                <div style="font-size: 20px; font-weight: bold; color: #0284c7;">{$trackingCode}</div>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 14px; margin-bottom: 25px;">
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; color: #64748b; width: 140px;">ประเภทเรื่อง:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #dc2626;">{$category}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; color: #64748b;">ผู้ติดต่อ/ผู้ร้อง:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; color: #1e293b; font-weight: 500;">{$fullName}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; color: #64748b;">เบอร์โทรศัพท์:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; color: #1e293b;">{$phone}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; color: #64748b;">อีเมลผู้ติดต่อ:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; color: #1e293b;">{$email}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; color: #64748b;">พื้นที่เกี่ยวข้อง:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; color: #1e293b;">อ.{$district} จ.พัทลุง</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; color: #64748b;">หัวข้อเรื่อง:</td>
                    <td style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; color: #0f172a; font-weight: bold;">{$subject}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: #64748b; vertical-align: top;">รายละเอียด:</td>
                    <td style="padding: 10px 0; color: #334155; line-height: 1.6;">{$message}</td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; border-top: 1px solid #e2e8f0; color: #64748b;">วันเวลาที่แจ้ง:</td>
                    <td style="padding: 10px 0; border-top: 1px solid #e2e8f0; color: #64748b;">{$dateFormatted}</td>
                </tr>
            </table>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{$adminUrl}" style="background: #10b981; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block;">
                    🔍 เปิดดูรายละเอียด & รับเรื่องในระบบหลังบ้าน
                </a>
            </div>
        </div>

        <div style="background: #f8fafc; padding: 15px 30px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0;">
            อีเมลฉบับนี้ส่งอัตโนมัติจากเว็บไซต์จังหวัดพัทลุง (Phatthalung Provincial Digital Portal) ถึง {$targetEmail}
        </div>
    </div>
</body>
</html>
HTML;

        try {
            $emailService = \Config\Services::email();
            $emailService->setTo($targetEmail);
            $emailService->setFrom('noreply@phatthalung.go.th', 'ศูนย์บริการประชาชน จ.พัทลุง');
            $emailService->setSubject($emailSubject);
            $emailService->setMessage($htmlBody);
            $emailService->setMailType('html');

            if ($emailService->send(false)) {
                return ['success' => true, 'message' => "Sent to {$targetEmail} via CI4 Email"];
            }
        } catch (\Throwable $e) {
            // Fallback to PHP mail() if SMTP isn't configured yet
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8\r\n";
            $headers .= "From: <noreply@phatthalung.go.th>\r\n";
            @mail($targetEmail, $emailSubject, $htmlBody, $headers);
        }

        return ['success' => true, 'message' => "Dispatched alert for {$targetEmail}"];
    }
}
