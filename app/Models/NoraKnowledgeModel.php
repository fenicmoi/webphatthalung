<?php

namespace App\Models;

use CodeIgniter\Model;

class NoraKnowledgeModel extends Model
{
    protected $table            = 'nora_knowledge';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'intent', 'keywords', 'answer_text', 'action_link'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
