<?php

namespace App\Models;

use CodeIgniter\Model;

class ProcurementModel extends Model
{
    protected $table            = 'procurements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'budget', 'method', 'category', 'status', 
        'doc_path', 'published_date'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
