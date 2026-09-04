<?php

namespace App\Models;

use CodeIgniter\Model;

class OfficialEmailModel extends Model
{
    protected $table            = 'official_emails';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'message_uid',
        'sender_name',
        'sender_email',
        'recipient_email',
        'subject',
        'body_plain',
        'body_html',
        'received_at',
        'has_attachment',
        'attachments_json',
        'is_read',
        'is_starred',
        'category',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get unread count
     */
    public function getUnreadCount(): int
    {
        return $this->where('is_read', 0)->where('category !=', 'trash')->countAllResults();
    }
}
