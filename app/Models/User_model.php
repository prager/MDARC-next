<?php namespace App\Models;

/* edited 4x*/

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

      public function get_user_by_registration_token(string $token): ?array {
        if(!preg_match('/^[a-f0-9]{24}$/', $token)) {
          return NULL;
        }

        $db = \Config\Database::connect();
        $user = $db->table('users')
          ->select('id_user, fname, lname, email')
          ->where('email_key', $token)
          ->get()
          ->getRowArray();
        $db->close();

        return $user ?: NULL;
      }

      public function complete_registration(array $param): array {
        $retarr = array(
          'flag' => TRUE,
          'username' => TRUE,
          'pass_comp' => TRUE,
          'pass_match' => TRUE,
          'token' => TRUE,
        );

        $user = $this->get_user_by_registration_token($param['token'] ?? '');
        if($user === NULL) {
          $retarr['flag'] = FALSE;
          $retarr['token'] = FALSE;
          return $retarr;
        }

        $username = strtolower(trim($param['username'] ?? ''));
        if($username === '') {
          $retarr['flag'] = FALSE;
          $retarr['username'] = FALSE;
        }

        $passFlags = $this->check_pass($param);
        $retarr['pass_comp'] = $passFlags['pass_comp'];
        $retarr['pass_match'] = $passFlags['pass_match'];
        if(!$passFlags['flag']) {
          $retarr['flag'] = FALSE;
        }

        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->where('username', $username);
        $builder->where('id_user !=', $user['id_user']);
        if($builder->countAllResults() > 0) {
          $retarr['flag'] = FALSE;
          $retarr['username'] = FALSE;
        }

        if($retarr['flag']) {
          $updated = $db->table('users')
            ->where('id_user', $user['id_user'])
            ->where('email_key', $param['token'])
            ->update(array(
              'username' => $username,
              'pass' => password_hash($param['pass'], PASSWORD_BCRYPT, array('cost' => 12)),
              'active' => 1,
              'email_key' => NULL,
            ));

          if(!$updated || $db->affectedRows() !== 1) {
            $retarr['flag'] = FALSE;
            $retarr['token'] = FALSE;
          }
        }

        $db->close();
        return $retarr;
      }

      public function request_password_reset(string $email): array {
        $result = array('success' => TRUE);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          return $result;
        }

        $db = \Config\Database::connect();
        $user = $db->table('users')
          ->select('id_user, fname, username, email')
          ->where('email', strtolower(trim($email)))
          ->get()
          ->getRowArray();

        // Return the same result for unknown addresses to prevent account discovery.
        if(!$user) {
          $db->close();
          return $result;
        }

        $token = bin2hex(openssl_random_pseudo_bytes(12));
        $expiresAt = date('Y-m-d H:i:s', time() + (20 * 60));
        $saved = $db->table('users')
          ->where('id_user', $user['id_user'])
          ->update(array(
            'password_reset_token' => $token,
            'password_reset_expires_at' => $expiresAt,
          ));

        if(!$saved) {
          $db->close();
          return array('success' => FALSE);
        }

        $escape = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
        $firstName = $escape($user['fname']);
        $username = $escape($user['username']);
        $resetUrl = $escape(site_url('reset-password/' . $token));
        $mailarr = array(
          'recipient' => $user['email'],
          'subject' => 'MDARC Member Portal Account Recovery',
          'message' => <<<HTML
            <div style="font-family: Arial, sans-serif; color: #222; line-height: 1.6; max-width: 640px; margin: 0 auto;">
              <h2 style="color: #174f78;">MDARC Account Recovery</h2>
              <p>Hello {$firstName},</p>
              <p>Your MDARC Member Portal username is <strong>{$username}</strong>.</p>
              <p>Select the button below to choose a new password.</p>
              <p style="margin: 28px 0;"><a href="{$resetUrl}" style="background-color: #174f78; border-radius: 4px; color: #fff; display: inline-block; font-weight: bold; padding: 12px 20px; text-decoration: none;">Reset Password</a></p>
              <p><strong>This recovery link expires in 20 minutes.</strong> If you did not request it, you can safely ignore this email.</p>
              <p style="font-size: 14px; color: #555;">If the button does not work, copy and paste this address into your browser:<br><a href="{$resetUrl}" style="color: #174f78; word-break: break-all;">{$resetUrl}</a></p>
            </div>
            HTML,
        );

        $mailResult = $this->send_email($mailarr);
        if(!($mailResult['success'] ?? FALSE)) {
          $db->table('users')->where('id_user', $user['id_user'])->update(array(
            'password_reset_token' => NULL,
            'password_reset_expires_at' => NULL,
          ));
          $result['success'] = FALSE;
        }

        $db->close();
        return $result;
      }

      public function get_user_by_password_reset_token(string $token): ?array {
        if(!preg_match('/^[a-f0-9]{24}$/', $token)) {
          return NULL;
        }

        $db = \Config\Database::connect();
        $user = $db->table('users')
          ->select('id_user, fname, lname, username, email')
          ->where('password_reset_token', $token)
          ->where('password_reset_expires_at >=', date('Y-m-d H:i:s'))
          ->get()
          ->getRowArray();
        $db->close();

        return $user ?: NULL;
      }

      public function complete_password_reset(array $param): array {
        $result = array(
          'flag' => TRUE,
          'token' => TRUE,
          'pass_comp' => TRUE,
          'pass_match' => TRUE,
        );
        $token = $param['token'] ?? '';
        $user = $this->get_user_by_password_reset_token($token);
        if($user === NULL) {
          $result['flag'] = FALSE;
          $result['token'] = FALSE;
          return $result;
        }

        $passFlags = $this->check_pass($param);
        $result['pass_comp'] = $passFlags['pass_comp'];
        $result['pass_match'] = $passFlags['pass_match'];
        if(!$passFlags['flag']) {
          $result['flag'] = FALSE;
          return $result;
        }

        $db = \Config\Database::connect();
        $updated = $db->table('users')
          ->where('id_user', $user['id_user'])
          ->where('password_reset_token', $token)
          ->where('password_reset_expires_at >=', date('Y-m-d H:i:s'))
          ->update(array(
            'pass' => password_hash($param['pass'], PASSWORD_BCRYPT, array('cost' => 12)),
            'password_reset_token' => NULL,
            'password_reset_expires_at' => NULL,
          ));

        if(!$updated || $db->affectedRows() !== 1) {
          $result['flag'] = FALSE;
          $result['token'] = FALSE;
        }
        $db->close();
        return $result;
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
          $param['verifystr'] = site_url('set-pass/' . $rand_str);
          $param['email_key'] = $rand_str;
    
    // as default the user type will always be the MDARC Member
          $param['id_user_type'] = 2;
          $param['type_code'] = 2;
          $bldr->resetQuery();
          $bldr->insert($param); //<--- *** for testing ***!!!!!

          $mailarr['recipient'] = 'jkulisek.us@gmail.com';
          $mailarr['subject'] = 'MDARC New User Registration';
          $escape = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
          $memberName = $escape(trim($param['fname'] . ' ' . $param['lname']));
          $street = $escape($param['street']);
          $cityStateZip = $escape(trim($param['city'] . ' ' . $param['state_cd'] . ' ' . $param['zip_cd']));
          $phone = $escape($param['phone']);
          $email = $escape($param['email']);
          $verificationUrl = $escape($param['verifystr']);

          $mailarr['message'] = <<<HTML
            <div style="font-family: Arial, sans-serif; color: #222; line-height: 1.5; max-width: 640px; margin: 0 auto;">
              <h2 style="color: #174f78; margin-bottom: 8px;">New Member Portal Registration</h2>
              <p style="margin-top: 0;">A new user has registered for the MDARC Member Portal.</p>
              <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                <tr><th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd; width: 120px;">Name</th><td style="padding: 8px; border-bottom: 1px solid #ddd;">{$memberName}</td></tr>
                <tr><th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Address</th><td style="padding: 8px; border-bottom: 1px solid #ddd;">{$street}<br>{$cityStateZip}</td></tr>
                <tr><th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Phone</th><td style="padding: 8px; border-bottom: 1px solid #ddd;">{$phone}</td></tr>
                <tr><th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Email</th><td style="padding: 8px; border-bottom: 1px solid #ddd;"><a href="mailto:{$email}" style="color: #174f78;">{$email}</a></td></tr>
              </table>
              <p><a href="{$verificationUrl}" style="color: #174f78;">Open the member verification link</a></p>
            </div>
            HTML;
    
          $adminMail = $this->send_email($mailarr);

          $memailarr['recipient'] = $param['email'];
          $memailarr['subject'] = 'MDARC Member Portal User Registration';

          $memailarr['message'] = <<<HTML
            <div style="font-family: Arial, sans-serif; color: #222; line-height: 1.6; max-width: 640px; margin: 0 auto;">
              <h2 style="color: #174f78;">Welcome to the MDARC Member Portal</h2>
              <p>Hello {$memberName},</p>
              <p>To finish your registration, select the button below to set your password.</p>
              <p style="margin: 28px 0;">
                <a href="{$verificationUrl}" style="background-color: #174f78; border-radius: 4px; color: #fff; display: inline-block; font-weight: bold; padding: 12px 20px; text-decoration: none;">Finish Registration</a>
              </p>
              <p>This link must be used within 72 hours. After that, your login information may be deactivated.</p>
              <p style="font-size: 14px; color: #555;">If the button does not work, copy and paste this address into your browser:<br><a href="{$verificationUrl}" style="color: #174f78; word-break: break-all;">{$verificationUrl}</a></p>
              <p>Thank you for your interest in the Mount Diablo Amateur Radio Club!</p>
            </div>
            HTML;

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
