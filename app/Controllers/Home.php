<?php

/* updated 2 */

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function default(): RedirectResponse {
        // load public header first
		echo view('template/header');

        // if not logged in load main public page
            if(!($this->login_mod->is_logged()))  {
                return redirect()->to('public');
            }
            else {
                // $data['msg'] = 'Feature Not Ready Yet';

                // $data['title'] = 'Not Ready';
                // echo view('status/status_view.php', $data);  
                // if logged in then redirect to a proper page according to user level (every level has its own controller)
			    return redirect()->route($this->login_mod->get_cur_user()['controller']);
            }
    }

    public function mainpg(): void {
        echo view('template/header');
        echo view('public/main_view', array('msg' => '', 'map_key' => getenv('GOOGLE_MAP_API_KEY')));
        echo view('template/footer');
    }
    public function faqs() {
        echo view('template/header_light');
        $data = $this->staff_mod->get_faqs();
        echo view('public/faqs_view', $data);
        echo view('template/footer');
      }

  public function terms() {
	echo view('public/terms');
  }

    public function register() {
        echo view('template/header_light');
    $email = $this->request->getPost('email') ?? 'none';
    if(!$this->user_mod->check_email($email)){
        $new_usr = $this->mem_mod->get_member_by_email(strtolower($email));
        if($new_usr['flag']) {
            $data = array();
            helper(['form', 'url']);
            $data['fname'] = '';
            $data['lname'] = '';
            $data['email'] = $new_usr['email'];
            $data['callsign'] = $new_usr['callsign'];
            $data['phone'] = '';
            $data['street']= '';
            $data['city'] = '';
            $data['state_cd'] = '';
            $data['zip_cd'] = '';
            $data['msg'] = '';
            $data['twitter'] = '';
            $data['facebook'] = '';
            $data['linkedin'] = '';
            $data['googleplus'] = '';
            $data['user_types'] = $this->user_mod->get_user_types();
            $data['states'] = $this->data_mod->get_states_array();
            echo view('public/register_view', $data);
        }
        else {
            $data['title'] = 'Not MDARC Member!';
        if($new_usr['empty']) {
            $data['msg'] = 'Please, enter your MDARC email.<br><br>';
        }
        else {
                $data['msg'] = '<p class="text-danger">You need to be MDARC member.</p> Your email is not listed in the MDARC database. Please, enter your MDARC email to register as a new user.<br><br>';
        }
            echo view('status/status_view', $data);
        }
    }
    else {
        $data['title'] = "Already a User!";
        $data['msg'] = "You already are registered as a user on this system.";
        echo view('status/status_view', $data);
    }
        
        echo view('template/footer');
    }

    /**
* The first step of user registration when the user sends the initial data. The second step will include setting the username and password
* via the confirm_reg() below
*/
	public function send_reg() {
		helper(['form', 'url']);
		$param = array();
		$param['fname'] = $this->request->getPost('fname');
		$param['lname'] = $this->request->getPost('lname');
		$param['email'] = $this->request->getPost('email');
		$param['street'] = $this->request->getPost('street');
		$param['city'] = $this->request->getPost('city');
		$param['state_cd'] = $this->request->getPost('state_cd');
		$param['zip_cd'] = $this->request->getPost('zip_cd');
		$param['phone'] = $this->request->getPost('phone');
		$param['callsign'] = $this->request->getPost('callsign');
		$param['facebook'] = $this->request->getPost('facebook');
		$param['twitter'] = $this->request->getPost('twitter');
		$param['linkedin'] = $this->request->getPost('linkedin');
		$param['id_user_type'] = 0;

		$email_flag = TRUE;
		if (!filter_var($param['email'], FILTER_VALIDATE_EMAIL)) {
  		$email_flag = FALSE;
		}

		$isPhoneNum = FALSE;
		//eliminate every char except 0-9
		$justNums = preg_replace("/[^0-9]/", '', $param['phone']);

		//eliminate leading 1 if its there
		if (strlen($justNums) == 11) $justNums = preg_replace("/^1/", '',$justNums);

		//if we have 10 digits left, it's probably valid.
		if (strlen($justNums) == 10) $isPhoneNum = TRUE;

		$param['ip'] = $this->get_ip();

		//echo 'ip: ' . $param['ip'];
		echo view('template/header');
		if($param['lname'] == '' || $param['fname'] == '' || $email_flag == FALSE || $param['street'] == '' || $param['city'] == '' || $param['zip_cd'] == ''
				|| $isPhoneNum == FALSE || $param['fname'] == $param['lname']) {
            $data = $param;
            $data['state_cd'] = $param['state_cd'];
            $data['zip_cd'] = $param['zip_cd'];
            $data['phone'] = $param['phone'];
            $data['title'] = 'Error!';
            $data['msg'] = '<span style="color: red">Please, fill all the required information marked by *. Thank you!</span>';
            if(!$email_flag) {
              $data['msg'] .= ' Note: Your email is wrong';
            }
            elseif(!$isPhoneNum) {
              $data['msg'] .= ' Note: Your phone number is wrong';
            }
				$data['states'] = $this->data_mod->get_states_array();
				$data['user_types'] = $this->user_mod->get_user_types();
            	echo view('public/register_view', $data);
        }
        else {
			$retarr = $this->user_mod->register($param);
          	if($retarr['flag']) {

              $data['title'] = 'Thank you!';

							$msg_str = 'Your registration has been sent. You will get an email with further instructions within 72 hours.<p class="text-danger"> Please, also check your spam messages since this email can be wrongly flagged as spam by your email server.</p> Thank you! <br><br>';

							//$msg_str = 'Still working on it. Check again later. Click to go back to ' . anchor(base_url(), 'home page here') . '<br><br>';

              $data['msg'] = $msg_str;
          }
          else {
              $data['title'] = 'Error!';
              $data['msg'] = '<span style="color: red">' . $retarr['msg'];
          }
          echo view('status/status_view', $data);
        }
        echo view('template/footer');
    	}

    public function set_pass(string $token): void {
        $user = $this->user_mod->get_user_by_registration_token($token);

        echo view('template/header_light');
        if($user === NULL) {
            echo view('status/status_view', array(
                'title' => 'Invalid Registration Link',
                'msg' => 'This registration link is invalid or has already been used.',
            ));
        }
        else {
            echo view('public/set_password_view', array(
                'token' => $token,
                'user' => $user,
                'username' => '',
                'msg' => '',
            ));
        }
        echo view('template/footer');
    }

    public function complete_registration(): void {
        $param = array(
            'token' => (string) $this->request->getPost('token'),
            'username' => (string) $this->request->getPost('username'),
            'pass' => (string) $this->request->getPost('pass'),
            'pass2' => (string) $this->request->getPost('pass2'),
        );
        $flags = $this->user_mod->complete_registration($param);

        echo view('template/header_light');
        if($flags['flag']) {
            echo view('status/status_view', array(
                'title' => 'Registration Complete',
                'msg' => 'Your username and password have been saved. You can now sign in.',
            ));
        }
        elseif(!$flags['token']) {
            echo view('status/status_view', array(
                'title' => 'Invalid Registration Link',
                'msg' => 'This registration link is invalid or has already been used.',
            ));
        }
        else {
            $user = $this->user_mod->get_user_by_registration_token($param['token']);
            $messages = array();
            if(!$flags['username']) {
                $messages[] = 'That username is unavailable. Please choose another username.';
            }
            if(!$flags['pass_comp']) {
                $messages[] = 'The password must contain at least 12 characters, including two uppercase letters, two lowercase letters, two numbers, and two special characters.';
            }
            if(!$flags['pass_match']) {
                $messages[] = 'The passwords do not match.';
            }
            echo view('public/set_password_view', array(
                'token' => $param['token'],
                'user' => $user,
                'username' => $param['username'],
                'msg' => '<div class="alert alert-danger">' . implode('<br>', $messages) . '</div>',
            ));
        }
        echo view('template/footer');
    }

    public function request_password_reset(): void {
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $this->user_mod->request_password_reset($email);

        echo view('template/header_light');
        echo view('status/status_view', array(
            'title' => 'Check Your Email',
            'msg' => 'If that email address belongs to an account, a recovery message has been sent. The link in the message expires in 20 minutes.',
        ));
        echo view('template/footer');
    }

    public function reset_password(string $token): void {
        $user = $this->user_mod->get_user_by_password_reset_token($token);

        echo view('template/header_light');
        if($user === NULL) {
            echo view('status/status_view', array(
                'title' => 'Invalid or Expired Link',
                'msg' => 'This password recovery link is invalid, expired, or has already been used. Please request a new link.',
            ));
        }
        else {
            echo view('public/reset_password_view', array(
                'token' => $token,
                'user' => $user,
                'msg' => '',
            ));
        }
        echo view('template/footer');
    }

    public function complete_password_reset(): void {
        $param = array(
            'token' => (string) $this->request->getPost('token'),
            'pass' => (string) $this->request->getPost('pass'),
            'pass2' => (string) $this->request->getPost('pass2'),
        );
        $flags = $this->user_mod->complete_password_reset($param);

        echo view('template/header_light');
        if($flags['flag']) {
            echo view('status/status_view', array(
                'title' => 'Password Updated',
                'msg' => 'Your password has been updated. You can now sign in with your username.',
            ));
        }
        elseif(!$flags['token']) {
            echo view('status/status_view', array(
                'title' => 'Invalid or Expired Link',
                'msg' => 'This password recovery link is invalid, expired, or has already been used. Please request a new link.',
            ));
        }
        else {
            $messages = array();
            if(!$flags['pass_comp']) {
                $messages[] = 'The password must contain at least 12 characters, including two uppercase letters, two lowercase letters, two numbers, and two special characters.';
            }
            if(!$flags['pass_match']) {
                $messages[] = 'The passwords do not match.';
            }
            echo view('public/reset_password_view', array(
                'token' => $param['token'],
                'user' => $this->user_mod->get_user_by_password_reset_token($param['token']),
                'msg' => '<div class="alert alert-danger">' . implode('<br>', $messages) . '</div>',
            ));
        }
        echo view('template/footer');
    }

        /**
    * Inspired by: https://www.w3resource.com/php-exercises/php-basic-exercise-5.php
    */
    	private function get_ip() {
    		$ip_address = NULL;
    		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        	$ip_address = $_SERVER['HTTP_CLIENT_IP'];
      	}
    	//whether ip is from proxy
    		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    		  $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    		}
    	//whether ip is from remote address
    		else {
    		  $ip_address = $_SERVER['REMOTE_ADDR'];
    		}
    		return $ip_address;
    	}
}
