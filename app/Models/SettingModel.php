<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'setting_key';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = ['setting_key', 'setting_value', 'setting_group', 'updated_at'];
    protected $useTimestamps    = false;
}
