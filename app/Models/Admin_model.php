<?php namespace App\Models;

use CodeIgniter\Model;
/**
* This model is for special functions for Master user
*/
class Admin_model extends Model {
    public function get_mem_cost() {
        $db      = \Config\Database::connect();    
        $builder = $db->table('payactions');
        $builder->where('id_payaction', 1);
        return $builder->get()->getRow()->amount;
      }
}