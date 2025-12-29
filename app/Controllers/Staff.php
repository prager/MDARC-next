<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;

class Staff extends BaseController
{
    public function index(): void
    {
        if($this->check_staff()) {
            echo view('template/header_admin.php');
            echo view('staff/staff_view.php');
			echo view('template/footer_master');
        }
        else {
            $this->login_mod->logout();
            echo view('template/header');
            $data['title'] = 'Login Error';
            $data['msg'] = 'There was an error while checking your credentials. Click ' . anchor('Home/reset_password', 'here') . ' to reset your password <br><br>';
            echo view('status/status_view', $data);
			echo view('template/footer');
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

  /**
    * Checks for master user according to the type code
    */
    private function check_staff() {
        $retval = FALSE;
        $user_arr = $this->login_mod->get_cur_user();
        if((($user_arr['type_code'] == 99)) || (($user_arr != NULL) && ($user_arr['type_code'] == 3 && $user_arr['authorized'] == 1))) {
			$retval = TRUE;
		}
        return $retval;
    }
}

