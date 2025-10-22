<?php namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model {
    public $db;
    public $session;
    protected function initialize()
    {
        $this->session = session();
    }

    public function get_user_types() {
        $db      = \Config\Database::connect();
        $builder = $db->table('admin_types');
        $res = $builder->get()->getResult();
        $types_arr = array();
        foreach($res as $type) {
            if($type->id_user_types > 1) $types_arr[$type->id_user_types] = $type->description;
          }
        $db->close();
        return $types_arr;
    }

}