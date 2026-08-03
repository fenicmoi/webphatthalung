<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table = 'news';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['title', 'slug', 'category', 'content', 'thumbnail', 'status', 'views_count', 'author_id'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'title'   => 'required|min_length[5]|max_length[255]',
        'content' => 'required',
        'status'  => 'in_list[draft,published,archived]',
    ];

    /**
     * ดึงรายการข่าวที่ประกาศล่าสุด
     */
    public function getPublishedNews($limit = 6)
    {
        return $this->where('status', 'published')
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
