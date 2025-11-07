<?php

namespace App\Controllers;

class Member extends BaseController
{    var $username;
    public function get_member(string $msg = '' ): void {
        if($this->check_mem()) {
          $data = $this->get_mem_data();
        //  echo '<br><br><br>OK';
          echo view('template/header_member.php');
          if($msg != '') {
              if($msg == 'mem_ok') {
                  $data['msg'] = '<div class="text-success"><strong>Success!</strong> Thank you for updating your membership!</div><br>';
              }
              if($msg == 'memdon_ok') {
                  $data['msg'] = '<div class="text-success"><strong>Success!</strong> Thank you for updating your membership and donation!</div><br>';
              }
              if($msg == 'donation_ok') {
                  $data['msg'] = '<div class="text-success"><strong>Success!</strong> Thank you for your generosity!</div><br>';
              } 			 
          } 
          else {
              $data['msg'] = '';
          }
          echo view('members/member_view.php', $data);
          echo view('template/footer_member.php');
      }
      else {
        echo view('template/header');
        $this->login_mod->logout();
        $data['title'] = 'Login Error';
        $data['msg'] = 'There was an error while checking your credentials.<br><br>';
        echo view('status/status_view.php', $data);
        echo view('template/footer.php');
      }
    }
    private function get_mem_data() {
		$data['user'] = $this->login_mod->get_cur_user();
		$mem_arr = $this->mem_mod->get_mem($data['user']['id_user']);
		$data['primary'] = $mem_arr['primary'];
		$data['fam_arr'] = $this->mem_mod->get_fam_mems($data['user']['id_user']);
		$data['member_types'] = $this->master_mod->get_member_types();
		$data['lic'] = array('SWL', 'Technician', 'General', 'Advanced', 'Amateur Extra');
		return $data;
	}

    public function check_mem() {
		$retval = FALSE;
		$user_arr = $this->login_mod->get_cur_user();
		if($user_arr == NULL) {
			$retval = FALSE;
		}
		elseif((($user_arr['type_code'] == 99)) || (($user_arr['authorized'] == 1) && ($user_arr['type_code'] < 90))) {
			$retval = TRUE;
		}
		return $retval;
	}

/**
* Loads personal data into the form and displays it
*/
  public function pers_data() {
    if($this->check_mem()) {
	  	echo view('template/header_member.php');
			$data = $this->get_pers_data();
			$data['msg'] = NULL;
			$data['errors'] = array();
			$data['errors']['cell'] = NULL;
			$data['errors']['phone'] = NULL;
			$data['errors']['email'] = NULL;
			$data['errors']['callsign'] = NULL;
			echo view('members/pers_data_view.php', $data);
	   }
    else {
	  	echo view('template/header');
			$this->login_mod->logout();
      $data['title'] = 'Login Error';
      $data['msg'] = 'There was an error while checking your credentials.<br><br>';
      echo view('status/status_view.php', $data);
    }
		echo view('template/footer.php');
  }

	private function get_pers_data() {
		$data['user'] = $this->login_mod->get_cur_user();
		$mem_arr = $this->mem_mod->get_mem($data['user']['id_user']);
		$data['mem'] = $mem_arr['primary'];
		$data['fam_arr'] = $this->mem_mod->get_fam_mems($data['user']['id_user']);

        // Call stored procedure directly
        $db    = \Config\Database::connect();
        $query = $db->query('CALL Get_Mem_Types()');

		$data['member_types'] = $query->getResultArray();
		$data['states'] = [
            'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA',
            'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD',
            'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ',
            'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC',
            'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'
        ];
		$data['lic'] = array('SWL', 'Technician', 'General', 'Advanced', 'Amateur Extra');
		$data['errors']['cell'] = NULL;
		$data['errors']['phone'] = NULL;
		$data['errors']['email'] = NULL;
		$data['errors']['callsign'] = NULL;
		return $data;
	}
}
