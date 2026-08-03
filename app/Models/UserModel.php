<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['username', 'email', 'password_hash', 'role', 'full_name', 'avatar'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[100]',
        'email'    => 'required|valid_email',
        'role'     => 'in_list[admin,officer,citizen]',
    ];
}
