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

    public function do_update($param) {
        $retarr = array();
        $pass_flags = $this->check_pass($param);
        $retarr['username'] = TRUE;
        $retarr['pass_comp'] = TRUE;
        $retarr['pass_match'] = TRUE;
        $retarr['flag'] = TRUE;
        if(!($this->check_username($param)['usr_flag'] && $pass_flags['flag'])) {
        //if(!(TRUE && $pass_flags['flag'])) {
          if($param['username'] != $param['cur_username']) $retarr['username'] = FALSE;
          $retarr['pass_comp'] = $pass_flags['pass_comp'];
          $retarr['pass_match'] = $pass_flags['pass_match'];
          $retarr['flag'] = FALSE;
        }
  
        if($retarr['flag']) {
          $db = \Config\Database::connect();
          $builder = $db->table('users');
          $builder->where('id_user', $param['id']);
          $pass = password_hash($param['pass'], PASSWORD_BCRYPT, array('cost' => 12));
          $update = array('pass' => $pass, 'username' => $param['username']);
          $builder->update($update, ['id_user' => $param['id']]);
          $db->close();
        }
  
        return $retarr;
      }

      private function check_pass($param) {
        $retarr = array();
        $retarr['pass_comp'] = TRUE;
        $retarr['flag'] = TRUE;
        $retarr['pass_match'] = TRUE;
        if(!preg_match('/^(?=(.*[a-z]){2,})(?=(.*[A-Z]){2,})(?=(.*[0-9]){2,})(?=(.*[!@#$%^&*()\-_+.]){2,}).{12,}$/', $param['pass'])) {
          $retarr['pass_comp'] = FALSE;
          $retarr['flag'] = FALSE;
        }
    //check if passwords match
        if($param['pass'] != $param['pass2']) {
          $retarr['pass_match'] = FALSE;
          $retarr['flag'] = FALSE;
        }
        return $retarr;
      }
      public function check_username($param) {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->where('id_user !=', $param['id']);
        $builder->where('username', $param['username']);
        $retarr = array();
        $retarr['usr_flag'] = TRUE;
        $builder->countAllResults() > 0 ? $retarr['usr_flag'] = FALSE : $retarr['usr_flag'] = TRUE;
        return $retarr;
      }
}
