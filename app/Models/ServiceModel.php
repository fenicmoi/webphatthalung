<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $allowedFields = ['service_name', 'description', 'icon_class', 'external_url', 'is_active', 'sort_order'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = false;

    /**
     * ดึงเมนูบริการออนไลน์ทั้งหมดที่กำลังเผยแพร่ เรียงตาม sort_order
     */
    public function getActiveServices()
    {
        return $this->where('is_active', 1)
                    ->orderBy('sort_order', 'ASC')
                    ->findAll();
    }
}
