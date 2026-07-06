<?php namespace App\Models;

/* edited 1x*/

use App\Libraries\MailService;
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

      public function register($param) {
        //echo '<br><br><br><br> email: ' . $param['email'];
        $retarr = array();
        $retarr['flag'] = TRUE;
        $db  = \Config\Database::connect();
        $bldr = $db->table('users');
    //check for duplicate email
        $bldr->resetQuery();
        $bldr->where('email', $param['email']);
        $cnt_email = $bldr->countAllResults();
    //check for duplicate fname and lname
        $bldr->resetQuery();
        $bldr = $db->table('users');
        $bldr->where('fname', $param['fname']);
        $bldr->where('lname', $param['lname']);
        $cnt_name = $bldr->countAllResults();
    
        if(!$this->is_member($param['email'])) {
          $retarr['flag'] = FALSE;
        }
    
        if(($cnt_email == 0) && ($cnt_name == 0) && $retarr['flag']) {
          $rand_str = bin2hex(openssl_random_pseudo_bytes(12));
          $param['verifystr'] = base_url() . '/index.php/set-pass/' . $rand_str;
          $param['email_key'] = $rand_str;
    
    // as default the user type will always be the MDARC Member
          $param['id_user_type'] = 2;
          $param['type_code'] = 2;
          $bldr->resetQuery();
          $bldr->insert($param); //<--- *** for testing ***!!!!!

          $mailarr['recipient'] = 'jkulisek.us@gmail.com';
          $mailarr['subject'] = 'MDARC New User Registration';
          $mailarr['message'] = $param['fname'] . ' ' . $param['lname'] . "<br><br>".
                $param['street'] . "\n\n" .$param['city'] . ' ' . $param['state_cd'] . $param['zip_cd'] . "<br><br>".
                ' Phone: ' . $param['phone'] . ' | Email: ' . $param['email'] . "<br><br>" . $param['verifystr'];
    
          $adminMail = $this->send_email($mailarr);

          $memailarr['recipient'] = $param['email'];
          $memailarr['subject'] = 'MDARC Member Portal User Registration';
    
          $memailarr['message'] = 'To finish your registration for MDARC Membership Portal click on the following link or copy paste in the browser: ' . $param['verifystr'] . "\n\n";
          $memailarr['message'] .= 'You must do so within 72 hours otherwise you login information may be deactivated.
                      Thank you for your interest in Mount Diablo Amateur Radio Club!';

          $memberMail = $this->send_email($memailarr);

          if (!($adminMail['success'] ?? false) || !($memberMail['success'] ?? false)) {
            $retarr['flag'] = FALSE;
            $retarr['msg'] = 'The user registration was saved, but one or more notification emails failed to send. Check the application log for the mail error.';
          }
        }
        else {
          $retarr['flag'] = FALSE;
          $retarr['msg'] = 'There was an error in your data: ';
          if($cnt_email > 0 && $cnt_name == 0) {
            $retarr['msg'] .= 'the email you entered is already in the database. Please, use a different email.';
          }
          elseif($cnt_email == 0 && $cnt_name > 0) {
            $retarr['msg'] .= 'first and last name is already in the database. Please, use the lost username and password utility.';
          }
          elseif($cnt_email > 0 && $cnt_name > 0) {
            $retarr['msg'] .= 'email including first and last name is already in the database. Please, use the lost username and password utility.';
          }
          else {
            $retarr['msg'] .= 'most likely you are not an MDARC member or you entered a different email than you use for your MDARC membership. This portal is for MDARC members only.';
          }
        }
        $db->close;
        return $retarr;
      }

      public function send_email($param) {
        $mail = new MailService();
    
        $to = $param['recipient'];
        $subject = $param['subject'];
        $message = $param['message'];
    
        $result = $mail->sendMail($to, $subject, $message);

        if (!($result['success'] ?? false)) {
          log_message('error', 'Email send failed for {recipient}: {message}', [
            'recipient' => $to,
            'message' => $result['message'] ?? 'Unknown mail error',
          ]);
        }

        return $result;
      }
    
      public function is_member($email) {
        $retval = FALSE;
        $db  = \Config\Database::connect();
        $builder = $db->table('tMembers');
        $builder->where('email', $email);
        if($builder->countAllResults() > 0) {
          $retval = TRUE;
        }
        return $retval;
      }

      public function check_email($email) {
        $retval = false;
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->where('email', $email);
        if($builder->countAllResults() > 0) {
          $retval = true;
        }
  
        return $retval;
      }
  
    
}
