<?php

namespace App\Models;

use CodeIgniter\Model;

class PageModel extends Model
{
    protected $table            = 'pages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 
        'slug', 
        'content', 
        'parent_id',
        'order_num',
        'views',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'title' => 'required|min_length[3]|max_length[255]',
        'slug'  => 'required|alpha_dash|is_unique[pages.slug,id,{id}]',
    ];
    protected $validationMessages   = [
        'slug' => [
            'is_unique' => 'URL (Slug) นี้ถูกใช้งานแล้ว กรุณาเปลี่ยนใหม่'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
