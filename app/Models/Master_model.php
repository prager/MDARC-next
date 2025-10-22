<?php namespace App\Models;

use CodeIgniter\Model;

class Master_model extends Model {
    public $db;
    public $session;
    protected function initialize()
    {
        $this->session = session();
    }

    /**
     * Gets data for displaying master_view
    * @return string array $retval
    */
    public function get_users_data() {
        $retarr = array();
        $usr_types = $this->get_user_types();
        $db      = \Config\Database::connect();
        $builder = $db->table('users');
        $res = $builder->get()->getResult();

    //user types are from admin_types table
        $users = array();
        foreach($res as $user) {
            $usr_arr = array(
                'id' => $user->id_user,
                'id_type_code' => $user->type_code,
                'id_user_type' => $user->id_user_type,
                'fname' => $user->fname,
                'lname' => $user->lname,
                'callsign' => $user->callsign,
                'active' => $user->active,
                'authorized' => $user->authorized,
                'usr_types' => $usr_types,
                'street' =>$user->street,
                'city' => $user->city,
                'state' => $user->state_cd,
                'zip' => $user->zip_cd,
                'phone' => $user->phone,
                'email' => $user->email
            );
            $user->username != NULL ? $usr_arr['username'] = $user->username : $usr_arr['username'] = 'Not Set!';
            $user->id_user_type != 0 ? $usr_arr['type_desc'] = $usr_types[$user->id_user_type] : $usr_arr['type_desc'] = $usr_types[$user->id_user_type];
            $user->comment == NULL ? $usr_arr['comment'] = '' : $usr_arr['comment'] = $user->comment;
            $user->facebook == NULL ? $usr_arr['facebook'] = '' : $usr_arr['facebook'] = $user->facebook;
            $user->twitter == NULL ? $usr_arr['twitter'] = '' : $usr_arr['twitter'] = $user->twitter;
            $user->linkedin == NULL ? $usr_arr['linkedin'] = '' : $usr_arr['linkedin'] = $user->linkedin;
            $user->comment == NULL ? $usr_arr['comment'] = '' : $usr_arr['comment'] = $user->comment;
            $user->city == NULL ? $usr_arr['city'] = '' : $usr_arr['city'] = $user->city;
            $user->zip_cd == NULL ? $usr_arr['zip'] = '' : $usr_arr['zip'] = $user->zip_cd;
            array_push($users, $usr_arr);
        }
        $db->close();
        $retarr['usr_types'] = $usr_types;
        $retarr['users'] = $users;
        return $retarr;
    }
    private function get_user_types() {
        $db      = \Config\Database::connect();
        $builder = $db->table('admin_types');
        $res = $builder->get()->getResult();
        $types_arr = array();
        foreach($res as $key => $type) {
            $types_arr[$type->id_user_types] = $type->description;
        }
        $db->close();
        return $types_arr;
    }
    public function load_admin($param) {
        $id = $param['id_user'];
        unset($param['id_user']);
        $db      = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->resetQuery();
        $builder->update($param, ['id_user' => $id]);
        $builder->resetQuery();
        $builder = $db->table('tMembers');
        $builder->where('id_users', $id);
        if($builder->countAllResults() > 0) {
          $builder->resetQuery();
          $arr = array('fname' => $param['fname'],
            'lname' => $param['lname'],
            'callsign' => $param['callsign']);
          $builder->update($arr, ['id_users' => $id]);
        }
        $db->close();
    }
    public function reset_user($param) {
        $db      = \Config\Database::connect();
        $builder = $db->table('users');
        $db->close();
    
        $retarr = array();
    
        $retarr['pass_match'] = TRUE;
        $retarr['pass_comp'] = TRUE;
        $retarr['flag'] = TRUE;
        $retarr['usr_dup'] = FALSE;
    
    //check password complexity
        if(!preg_match('/^(?=(.*[a-z]){2,})(?=(.*[A-Z]){2,})(?=(.*[0-9]){2,})(?=(.*[!@#$%^&*()\-_+.]){2,}).{8,}$/', $param['pass'])) {
          $retarr['pass_comp'] = FALSE;
          $retarr['flag'] = FALSE;
        }
    
    //check if passwords match
        if($param['pass'] != $param['pass2']) {
          $retarr['pass_match'] = FALSE;
          $retarr['flag'] = FALSE;
        }
    
    //if not flagged and all good then update username and password
        if($retarr['flag']) {
          $param['username'] = strtolower($param['username'] ?? '');
          $param['pass'] = password_hash($param['pass'], PASSWORD_BCRYPT, array('cost' => 12));
          $update = array('pass' => $param['pass'], 'username' => $param['username'], 'active' => 1);
          $builder->update($update, ['id_user' => $param['id_user']]);
        }
    
        return $retarr;
      }
    public function activate($id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('users');
        $db->close();
        $builder->where('id_user', $id);
        $flag = $builder->get()->getRow()->active;
        if($flag == 1) {
        $builder->resetQuery();
        $builder->update(array('active' => 0), ['id_user' => $id]);
        }
        else {
        $builder->resetQuery();
        $builder->update(array('active' => 1), ['id_user' => $id]);
        }
    }
    public function authorize($id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->where('id_user', $id);
        $usr = $builder->get()->getRow();
        $flag = $usr->authorized;
        if($flag == 1) {
          $builder->resetQuery();
          $builder->update(array('authorized' => 0), ['id_user' => $id]);
        }
        else {
          $builder->resetQuery();
          $builder->update(array('authorized' => 1), ['id_user' => $id]);
    
          $subject = 'MDARC Portal: You Are Now Authorized Access';
          $message = $usr->fname . ' ' . $usr->lname . "\n\n".
                 'You are now authorized to access MDARC membership portal at ' . base_url() . '. Thank you!';
               $headers = array('From' => 'mdarc-memberships@arrleb.org', 'Reply-To' => 'mdarc-memberships@arrleb.org' );
             mail($usr->email, $subject, $message, $headers);
    
          $subject = 'MDARC Portal: User Authorization';
          $message = $usr->fname . ' ' . $usr->lname . "\n\n".
                 'Was just authorized to use MDARC membership portal.';
          mail('jkulisek.us@gmail.com', $subject, $message, $headers);
        }
        $builder->resetQuery();
        $builder = $db->table('tMembers');
        $builder->where('email', $usr->email);
        if($builder->countAllResults() > 0) {
          $builder->resetQuery();
          $builder = $db->table('tMembers');
          $builder->where('email', $usr->email);
          $id_mem = $builder->get()->getRow()->id_members;
          $builder->resetQuery();
          $builder = $db->table('tMembers');
          $builder->update(array('id_users' => $id), ['id_members' => $id_mem]);
        }
        $db->close();
      }
      public function search($search) {
        $retarr['mems'] = array();
        $retarr['msg'] = NULL;
        $staff_mod = new \App\Models\Staff_model();
        if(strlen($search) > 0) {
          $db      = \Config\Database::connect();
          $builder = $db->table('tMembers');
          $builder->like('lname', $search);
          $builder->orLike('fname', $search);
          $builder->orLike('callsign', $search);
          $builder->orLike('cur_year', $search);
          $builder->orLike('email', $search);
          $builder->orLike('id_members', strval($search));
          $cnt = $builder->countAllResults();
          if($cnt > 0) {
            $builder->resetQuery();
            $builder->like('lname', $search);
            $builder->orLike('fname', $search);
            $builder->orLike('callsign', $search);
            $builder->orLike('cur_year', $search);
            $builder->orLike('email', $search);
            $builder->orLike('id_members', strval($search));
            $res = $builder->get()->getResult();
            foreach ($res as $key => $mem) {
             $mem_arr = $staff_mod->get_mem($mem->id_members);
             array_push($retarr['mems'], $mem_arr);
            }
          }
        }
        return $retarr;
      }

    public function get_member_types() {
        $db      = \Config\Database::connect();
        $builder = $db->table('tMemTypes');
        $builder->orderBy('id_mem_types', 'DESC');
        $types = $builder->get()->getResult();
        $retarr = array();
        foreach($types as $type) {
        $type_arr= array('id' => $type->id_mem_types, 'description' => $type->description);
        array_push($retarr, $type_arr);
        }
        $db->close();
        return $retarr;
    }
}