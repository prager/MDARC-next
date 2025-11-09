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

      $data['states'] = [
        'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA',
        'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD',
        'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ',
        'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC',
        'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'
      ];

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

  public function update_mem(int $id = null) {
		if($this->check_mem()) {
			$param['id'] = $id;
			$param['email'] =  strtolower(trim($this->request->getPost('email')));
			$param['callsign'] = strtoupper(trim($this->request->getPost('callsign')));
			$param['address'] = $this->request->getPost('address');
			$param['city'] = $this->request->getPost('city');
			$param['state'] = $this->request->getPost('state');
			$param['zip'] = $this->request->getPost('zip');
			$param['fname'] = $this->request->getPost('fname');
			$param['lname'] = trim($this->request->getPost('lname'));
			$param['license'] = $this->request->getPost('sel_lic');
			$param['w_phone'] = $this->request->getPost('w_phone');
			$param['h_phone'] = $this->request->getPost('h_phone');
			$email = $this->request->getPost('email');
			$this->request->getPost('dir_ok') == 'on' ? $param['ok_mem_dir'] = 'TRUE' : $param['ok_mem_dir'] = 'FALSE';
			filter_var($email, FILTER_VALIDATE_EMAIL) ? $param['email'] = $email : $param['email'] = 'none';
			$this->request->getPost('arrl') == 'on' ? $param['arrl_mem'] = 'TRUE' : $param['arrl_mem'] = 'FALSE';

			echo view('template/header_member.php');
			$update_arr = $this->mem_mod->update_mem($param);

			if(!$update_arr['flag']) {
				$val_str = '';
				foreach ($update_arr['msg'] as $key => $value) {
					if($value != NULL) {$val_str .= $value . '<br>';}
				}
				$data = $this->get_pers_data();
				$data['msg'] = '<p class="text-danger"><strong>Errors!</strong> ';
				$data['msg'] .= $val_str . '</p>';
				$data['errors'] = $update_arr['msg'];
        echo view('members/pers_data_view.php', $data);
			}
			else {
				$data = $this->get_mem_data();
				$data['msg'] = '<div class="text-success"><strong>Success!</strong> Your changes have been saved</div><br>';
				echo view('members/member_view.php', $data);
			}
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
