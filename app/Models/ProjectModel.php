<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table            = 'provincial_projects';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'emenscr_code',
        'fiscal_year',
        'project_name',
        'agency',
        'pillar_number',
        'pillar_title',
        'budget',
        'disbursed_budget',
        'objectives',
        'kpis',
        'progress_pct',
        'status',
        'status_desc',
        'district',
        'subdistrict',
        'location_name',
        'latitude',
        'longitude',
        'photos',
        'documents',
        'is_featured',
        'last_sync_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * ดึงข้อมูลโครงการพร้อมตัวกรอง (ปีงบประมาณ, อำเภอ, สถานะ, ประเด็นยุทธศาสตร์, คำค้นหา)
     */
    public function getFilteredProjects(array $filters = [])
    {
        $builder = $this->builder();

        if (!empty($filters['year'])) {
            $builder->where('fiscal_year', (int)$filters['year']);
        }
        if (!empty($filters['district'])) {
            $builder->where('district', $filters['district']);
        }
        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }
        if (!empty($filters['pillar'])) {
            $builder->where('pillar_number', (int)$filters['pillar']);
        }
        if (!empty($filters['q'])) {
            $q = trim($filters['q']);
            $builder->groupStart()
                ->like('project_name', $q)
                ->orLike('agency', $q)
                ->orLike('emenscr_code', $q)
                ->orLike('location_name', $q)
                ->orLike('district', $q)
                ->groupEnd();
        }

        $builder->orderBy('fiscal_year', 'DESC');
        $builder->orderBy('budget', 'DESC');

        $projects = $builder->get()->getResultArray();

        // Decode JSON fields
        foreach ($projects as &$p) {
            $p['photos_array'] = !empty($p['photos']) ? json_decode($p['photos'], true) : [];
            $p['documents_array'] = !empty($p['documents']) ? json_decode($p['documents'], true) : [];
            $p['disbursed_pct'] = ($p['budget'] > 0) ? round(($p['disbursed_budget'] / $p['budget']) * 100, 1) : 0;
        }

        return $projects;
    }

    /**
     * สรุปสถิติภาพรวมงบประมาณและโครงการ (Executive Summary)
     */
    public function getExecutiveSummary($year = null)
    {
        $builder = $this->builder();
        if (!empty($year)) {
            $builder->where('fiscal_year', (int)$year);
        }
        $projects = $builder->get()->getResultArray();

        $totalBudget = 0;
        $totalDisbursed = 0;
        $statusCounts = [
            'completed'   => 0,
            'in_progress' => 0,
            'pending'     => 0,
            'delayed'     => 0,
        ];
        $districtBudgets = [];
        $pillarBudgets = [];

        foreach ($projects as $p) {
            $b = (float)$p['budget'];
            $d = (float)$p['disbursed_budget'];
            $totalBudget += $b;
            $totalDisbursed += $d;

            $st = $p['status'] ?? 'in_progress';
            if (isset($statusCounts[$st])) {
                $statusCounts[$st]++;
            } else {
                $statusCounts['in_progress']++;
            }

            // By district
            $dist = $p['district'] ?: 'ไม่ระบุ';
            if (!isset($districtBudgets[$dist])) {
                $districtBudgets[$dist] = ['count' => 0, 'budget' => 0, 'disbursed' => 0];
            }
            $districtBudgets[$dist]['count']++;
            $districtBudgets[$dist]['budget'] += $b;
            $districtBudgets[$dist]['disbursed'] += $d;

            // By pillar
            $pil = 'ประเด็นที่ ' . ($p['pillar_number'] ?: 1);
            if (!isset($pillarBudgets[$pil])) {
                $pillarBudgets[$pil] = ['count' => 0, 'budget' => 0, 'title' => $p['pillar_title'] ?: $pil];
            }
            $pillarBudgets[$pil]['count']++;
            $pillarBudgets[$pil]['budget'] += $b;
        }

        $disbursedPct = ($totalBudget > 0) ? round(($totalDisbursed / $totalBudget) * 100, 1) : 0;
        $totalCount = count($projects);
        $completedPct = ($totalCount > 0) ? round(($statusCounts['completed'] / $totalCount) * 100, 1) : 0;

        return [
            'total_projects'    => $totalCount,
            'total_budget'      => $totalBudget,
            'total_disbursed'   => $totalDisbursed,
            'disbursed_pct'     => $disbursedPct,
            'completed_pct'     => $completedPct,
            'status_counts'     => $statusCounts,
            'district_budgets'  => $districtBudgets,
            'pillar_budgets'    => $pillarBudgets,
        ];
    }
}
