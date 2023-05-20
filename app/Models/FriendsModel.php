<?php
namespace App\Models;

use CodeIgniter\Model;

class FriendsModel extends Model
{
    protected $table = 'friends';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = ['id', 'name', 'genderID', 'age'];
    protected $useTimestamps = true;

    public function __construct(){
        $connect = \Config\Database::connect();
        $this->db = $connect->table($this->table);
    }
    
    public function simpan($data){
        try {
            $this->db->set('id', 'UUID()', false);
			$this->db->set('created_at', 'NOW()', false);
			$this->db->set('updated_at', 'NOW()', false);
			$cekDbInput = $this->db->insert($data);
			return $cekDbInput;
        } catch (\Exception $e) {
           return $e->getMessage();
        }
    }
}