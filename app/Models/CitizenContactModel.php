<?php

namespace App\Models;

use CodeIgniter\Model;

class CitizenContactModel extends Model
{
    protected $table            = 'citizen_contacts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tracking_code',
        'full_name',
        'phone',
        'email',
        'district',
        'category',
        'subject',
        'message',
        'attachment',
        'status',
        'officer_note',
        'resolved_at',
        'ip_address',
        'created_at',
        'updated_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Categories Mapping in Thai
     */
    public static function getCategories(): array
    {
        return [
            'general'       => ['name' => 'สอบถามข้อมูลทั่วไป / ขอรับบริการ', 'icon' => 'fa-circle-info', 'color' => 'primary'],
            'damrongtham'   => ['name' => 'ร้องเรียน / ร้องทุกข์ (ศูนย์ดำรงธรรม 1567)', 'icon' => 'fa-scale-balanced', 'color' => 'danger'],
            'disaster'      => ['name' => 'แจ้งเหตุสาธารณภัย / อุบัติภัยเร่งด่วน', 'icon' => 'fa-triangle-exclamation', 'color' => 'warning'],
            'corruption'    => ['name' => 'แจ้งเบาะแสการทุจริต / ประพฤติมิชอบ', 'icon' => 'fa-shield-halved', 'color' => 'purple'],
            'suggestion'    => ['name' => 'ข้อเสนอแนะการพัฒนาจังหวัดพัทลุง', 'icon' => 'fa-lightbulb', 'color' => 'success'],
        ];
    }

    /**
     * Districts in Phatthalung Province
     */
    public static function getDistricts(): array
    {
        return [
            'เมืองพัทลุง',
            'กงหรา',
            'เขาชัยสน',
            'ควนขนุน',
            'ตะโหมด',
            'บางแก้ว',
            'ปากพะยูน',
            'ป่าบอน',
            'ป่าพะยอม',
            'ศรีนครินทร์',
            'ศรีบรรพต',
        ];
    }

    /**
     * Statuses Mapping
     */
    public static function getStatuses(): array
    {
        return [
            'pending'     => ['name' => 'รอดำเนินการ (Pending)', 'badge' => 'bg-warning text-dark', 'icon' => 'fa-clock'],
            'in_progress' => ['name' => 'กำลังดำเนินการ/ประสานงาน', 'badge' => 'bg-info text-white', 'icon' => 'fa-spinner fa-spin'],
            'resolved'    => ['name' => 'ดำเนินการเรียบร้อยแล้ว', 'badge' => 'bg-success text-white', 'icon' => 'fa-circle-check'],
            'rejected'    => ['name' => 'ยุติเรื่อง / ไม่อยู่ในอำนาจ', 'badge' => 'bg-secondary text-white', 'icon' => 'fa-circle-xmark'],
        ];
    }

    /**
     * Generate Unique Tracking Code
     * Example: PTL-260831-A79B
     */
    public static function generateTrackingCode(): string
    {
        $prefix = 'PTL-' . date('ymd') . '-';
        $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $randStr = '';
        for ($i = 0; $i < 4; $i++) {
            $randStr .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $prefix . $randStr;
    }
}
