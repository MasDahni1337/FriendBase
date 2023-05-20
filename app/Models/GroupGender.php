<?php
namespace App\Models;

use CodeIgniter\Model;

class GroupGender extends Model
{
    protected $table = 'groupGender';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = ['id', 'name'];
    protected $useTimestamps = false;
}