<?php

namespace App\Models;

use CodeIgniter\Model;

class FinancialReportModel extends Model
{
    protected $table            = 'financial_reports';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'fiscal_year', 'report_month', 'department',
        'doc_path', 'file_size', 'download_count', 'published_date'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
