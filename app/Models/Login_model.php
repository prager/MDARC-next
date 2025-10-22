<?php namespace App\Models;

use CodeIgniter\Model;

class Login_model extends Model {

public $db;
public $session;
protected function initialize()
{
    $this->session = session();
}

/**
* Checks the session vars whether or not the user is logged in
*/
  public function is_logged() {
    $retval = FALSE;    

    if($this->session->get('logged') != null) {
        if($this->session->get('logged') == TRUE) $retval = TRUE;
    }
    
    return $retval;
  }

/**
* Gets the current user out of the session var
*/
    public function get_cur_user() {

      if($this->session->get('logged')) {
          return $_SESSION['user'];
      }
      else {
          return NULL;
      }
  }

  public function get_cur_user_id() {
		$session = session();
		$user = $session->get('name') ?? '';
    return $_SESSION['id_user'];
	}

/**
 * Checks user ID and password credentials
 * @param $data array with user ID and password
 */
public function check_credentials($data) {

// set the boolean return value to false and then reset to true if credentials are OK
  $retval = FALSE;
  $this->db = \Config\Database::connect();
  
  $builder = $this->db->table('users');
  $builder->where('username', $data['user']);

// get the user data from users table in a row
  $user = $builder->get()->getRow();
// if the user filled his/her credentials then check them if correct via PHP built in function password_verify
  if(isset($data['user']) && isset($user->pass) && isset($user->username)) {
    if((password_verify($data['pass'], $user->pass)) && ($data['user'] == $user->username) && ($user->active == 1 && $user->authorized == 1)) {

// if good to go then retrieve needed data from db and save the user data into session variable
      $usr_arr['user'] = $this->get_user_arr($user->id_user);
      $usr_arr['user']['session_id'] = session_id();
      $_SESSION['user'] = $usr_arr['user'];
      $_SESSION['id_user'] = $user->id_user;
      $_SESSION['logged'] = TRUE;

//role is legacy, but leaving it to be
      $_SESSION['role'] = $user->role;
      $_SESSION['type_code'] = $usr_arr['user']['type_code'];
      $_SESSION['controller'] = $usr_arr['user']['controller'];
      $this->set_logged($usr_arr['user']);
      $this->db->close();
      $retval = TRUE;
    }
  }
  return $retval;
}

/*private function check_auth($username) {
  $db = \Config\Database::connect();
  $builder = $db->table('users');
  $builder->where('username', $data['user']);

  return TRUE;
}*/

/**
* Records the user's login in the ci_sessions table
*/
  private function set_logged($user) {
    $db = \Config\Database::connect();
    $builder = $db->table('ci_sessions');

  //check for logged=1 in previous sessions for current user and set logged to 0
    $builder->where('logged', 1);
    $builder->where('id_user', $user['id_user']);
    $row = $builder->get()->getRow();
    if (isset($row)) {
      $builder->update(array('logged' => 0));
    }
    $builder->resetQuery();
    $builder = $db->table('ci_sessions');
    $session_data = array(
        'id_user' => $user['id_user'],
        'session_id' => $user['session_id'],
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'date_logged_in' => time(),
        'logged' => 1);
    $builder->insert($session_data);
    $db->close();
  }

  public function logout() {
/**
* Destroys the session vars and logs out the user
*/
    // if($this->session->get('logged')) {
    //   $db = \Config\Database::connect();
    //   $builder = $db->table('ci_sessions');
    //   $builder->where('logged', 1);
    //   $builder->where('id_user',  $_SESSION['id_user']);
    //   $builder->update(array('logged' => 0, 'date_logged_out' => time()));
    //   $db->close();
    // }
    // unset($_SESSION['logged']);
    // unset($_SESSION['role']);
    // unset($_SESSION['id_user']);
    // unset($_SESSION['user']);
    // unset($_SESSION['user_type']);
    // setcookie(session_name(), session_id(), 1); // to expire the session
    // $_SESSION = [];
    $this->session->destroy();

}

/**
* Checks whether or not user is logged in
* Note: This is legacy!
*/
  private function logged_in() {
    $retval = FALSE;
    $db = \Config\Database::connect();
    $builder = $db->table('ci_sessions');
    $builder->where('logged', 1);
    $builder->where('id_user',  $_SESSION['id_user']);
    $builder->orderBy('id_sessions', 'DESC');
    $row = $builder->get()->getRow();
    if($row->id_sessions == $_SESSION['user']['session_id']) {
      $retval = TRUE;
    }

    return $retval;
  }

  /**
  * Getter for user type
  * @param int $id_users
  * @return int $type_code
  */
  public function get_user_type($id) {
    $retval = FALSE;
    $db = \Config\Database::connect();
    $builder = $db->table('users');
    $builder = $db->where('id_users', $id);
    $row = $builder->get()->getRow();
  //finish up!
  }

  /**
  * Retrieve user class properties and copy into array
  * @param class $user
  * @return array retarr[]
  */
    public function get_user_arr($id) {
      $retarr = array();
      $db = \Config\Database::connect();
      $builder = $db->table('users');
      $builder->where('id_user', $id);
      $user = $builder->get()->getRow();
      $db->close();
      $retarr['id_user'] = $id;
      $retarr['fname'] = $user->fname;
      $retarr['lname'] = $user->lname;
      $retarr['email'] = $user->email;
      $retarr['phone'] = $user->phone;
      $retarr['street'] = $user->street;
      $retarr['city'] = $user->city;
      $retarr['state'] = $user->state_cd;
      $retarr['zip'] = $user->zip_cd;
      $retarr['role'] = $user->type_code;
      $retarr['username'] = $user->username;
      $retarr['authorized'] = $user->authorized;
      $retarr['active'] = $user->active;
  //this is legacy
      $retarr['type_code'] = $user->type_code;

  //get the type_code and controller for the user type
      $admin_types = $this->get_admin_types();
      $retarr['type_code'] = $admin_types['type_codes'][$user->id_user_type];
      //echo 'type_code: ' . $retarr['type_code'];
      $retarr['controller'] = $admin_types['controllers'][$user->id_user_type];
      $retarr['pos_name'] = $admin_types['descriptions'][$user->id_user_type];

      return $retarr;
    }

    private function get_admin_types() {
      $retarr = array();
      $db      = \Config\Database::connect();
      $builder = $db->table('admin_types');
      $res = $builder->get()->getResult();
      $db->close();
      $types_arr = array();
      foreach($res as $type) {
          $retarr['type_codes'][$type->id_user_types] = $type->type_code;
          $retarr['controllers'][$type->id_user_types] = $type->controller;
          $retarr['descriptions'][$type->id_user_types] = $type->description;
          $retarr['id_user_types'][$type->id_user_types] = $type->id_user_types;
      }
      return $retarr;
    }
}
