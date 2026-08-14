<?php

namespace App\Models;

use CodeIgniter\Model;

class SiteBannerModel extends Model
{
    protected $table            = 'site_banners';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'subtitle', 'badge_title', 'badge_icon', 'bg_type', 
        'image_path', 'floating_img_path', 'floating_pos', 'floating_anim', 
        'card_placement', 'desc', 'button_text', 'button_url', 'button_icon', 
        'style_class', 'active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
