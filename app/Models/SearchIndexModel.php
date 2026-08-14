<?php

namespace App\Models;

use CodeIgniter\Model;

class SearchIndexModel extends Model
{
    protected $table            = 'search_indexes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'source_type',
        'source_id',
        'title',
        'description',
        'url',
        'image_url',
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
