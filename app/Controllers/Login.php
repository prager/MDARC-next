<?php

namespace App\Controllers;

class Login extends BaseController
{    var $username;
    public function index(): void
    {
        if($this->validate_credentials()) {
            $data['title'] = 'You Are Logged In!';
            $data['msg'] = 'Click below to continue on your main page:';
            echo view('template/header_light');
            echo view('status/status_view', $data);
            echo view('template/footer');
        }
        else {
          echo view('template/header');
          $data['title'] = '<p style="color: red;">Login Error';
          $data['msg'] = 'There was an error while checking your credentials. Please, check your username and password. Thank you!<br><br>';
          echo view('status/status_view', $data);
          echo view('template/footer');
        }
    }

    public function validate_credentials() {
	    $this->username = strtolower($this->request->getPost('user') ?? '');
	    $password = $this->request->getPost('pass');
	    $data['user'] = $this->username;
	    $data['pass'] = $password;
		return $this->login_mod->check_credentials($data);
	}

    public function logout() {
        $this->login_mod->logout();
        echo view('template/header');
		echo view('public/main_view', array('msg' => '<p class="text-success lead"><i class="bi bi-check-circle"></i> You have succesfuly logged out. Thank you and have a great day!</p>', 'map_key' => getenv('GOOGLE_MAP_API_KEY')));
		echo view('template/footer');
    }
}
