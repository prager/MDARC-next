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
  public function search() {
		if($this->check_mem()) {
			echo view('template/header_member.php');
			
			//$search_str = $this->request->getPost('search');
			$q  = trim((string) ($this->request->getPost('search') ?? ''));
			$db = \Config\Database::connect();
	
			// Call the stored procedure (it already returns empty set if q is empty)
			$query = $db->query('CALL Search_Members_for_Mem(?)', [$q]);
			$rows  = $query->getResultArray() ?? [];
	
			// Always clean up after CALL to avoid "Commands out of sync"
			$query->freeResult();
			while ($db->connID->more_results() && $db->connID->next_result()) {
				if ($extra = $db->connID->store_result()) {
					$extra->free();
				}
			}
			$this->flushMultiResults($db);

			// Call stored procedure directly
			$query = $db->query('CALL Get_Mem_Types()');
			$types = $query->getResultArray();
	
			// IMPORTANT: free the result set to avoid "commands out of sync"
			$query->freeResult();
			while ($db->connID->more_results() && $db->connID->next_result()) {
				if ($extra = $db->connID->store_result()) {
					$extra->free();
				}
			}
			$this->flushMultiResults($db);
	
			$count = count($rows);
	
			if ($count === 0) {
				$flash = 'No results found';
				$flashType = 'warning';
			} elseif ($count === 100) {
				$flash = 'The first 100 records is shown. You need to refine your search.';
				$flashType = 'danger';
			} else {
				$flash = $count . ' members found';
				$flashType = 'success';
			}

			$licence = array('SWL', 'Technician', 'General', 'Advanced', 'Amateur Extra');

			// All U.S. state abbreviations
			$states = [
				'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA',
				'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD',
				'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ',
				'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC',
				'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'
			];

			$mem_cost = $this->admin_mod->get_mem_cost();

			$data = array(
				'q'         => $q,
				'rows'      => $rows,
				'count'     => $count,
				'flash'     => $flash,
				'flashType' => $flashType,
				'lic'	=> $licence,
				'types' => $types, 
				'states' => $states,
				'mem_cost' => $mem_cost
			);

			echo view('members/search_res_view.php', $data);
			echo view('template/footer_member');
	   }
		else {
			echo view('template/header');
			$this->login_mod->logout();
			$data['title'] = 'Login Error';
			$data['msg'] = 'There was an error while checking your credentials.<br><br>';
			echo view('status/status_view.php', $data);
			echo view('template/footer');
		}
	}
  private function flushMultiResults($db): void
    {
        // Clear any remaining result sets after CALL so subsequent queries run cleanly
        $conn = $db->connID; // MySQLi
        if (method_exists($conn, 'more_results')) {
            while ($conn->more_results() && $conn->next_result()) {
                $extra = $conn->use_result();
                if ($extra instanceof \mysqli_result) {
                    $extra->free();
                }
            }
        }
    }
	public function remove_fam_mem(int $id) {
		if($this->check_mem()) {
			$this->mem_mod->remove_fam_mem($id);
			$this->get_member();
		}
		else {
			echo view('template/header');
			 $data['title'] = 'Login Error';
			 $data['msg'] = 'There was an error while checking your credentials. Click ' . anchor('Home/reset_password', 'here') .
			 ' to reset your password or go to home page ' . anchor('Home', 'here'). '<br><br>';
			 echo view('status/status_view', $data);
			 echo view('template/footer');
		}
	}
  public function edit_fam_mem(int $id = null) {
    if($this->check_mem()) {
      $param['id'] = $id;
      $param['callsign'] = strtoupper(trim($this->request->getPost('callsign')));
      $param['fname'] = $this->request->getPost('fname');
      $param['lname'] = trim($this->request->getPost('lname'));
      $param['license'] = $this->request->getPost('sel_lic');
      $w_phone = $this->mem_mod->do_phone($this->request->getPost('w_phone'));
      $h_phone = $this->mem_mod->do_phone($this->request->getPost('h_phone'));
      $param['w_phone'] = $w_phone['phone'];
      $param['h_phone'] = $h_phone['phone'];
      $param['id_mem_types'] = $this->request->getPost('mem_types');
      $param['mem_type'] = $this->staff_mod->get_mem_types()[$param['id_mem_types']];
      $param['active'] = TRUE;
      $param['comment'] = $this->request->getPost('comment');
      $email = strtolower($this->request->getPost('email'));
      filter_var($email, FILTER_VALIDATE_EMAIL) ? $param['email'] = $email : $param['email'] = 'none';
      $this->request->getPost('arrl') == 'on' ? $param['arrl_mem'] = 'TRUE' : $param['arrl_mem'] = 'FALSE';
      $this->request->getPost('dir_ok') == 'on' ? $param['ok_mem_dir'] = 'TRUE' : $param['ok_mem_dir'] = 'FALSE';
      $ret_str = $this->mem_mod->edit_fam_mem($param);

      $data = $this->get_pers_data();
      $data['msg'] = '';

      $flag = TRUE;
      echo view('template/header_member');
      if(!$w_phone['flag']){
        $data['msg'] .= '<br><span class="text-danger">Family member cell phone was in wrong format and was not saved.</span>';
        $flag = FALSE;
      }

      if(!$h_phone['flag']) {
        $data['msg'] .= '<br><span class="text-danger">Family member other phone number was in wrong format and was not saved.</span>';
        $flag = FALSE;
      }

      if($ret_str != NULL) {
        $data['msg'] .= '<br><span class="text-danger">Family member error(s): ' . $ret_str . '</span>';
        $flag = FALSE;
      }

      if($flag) {
        $data['msg'] = '<p class="text-success"><strong>Success!</strong> ';
        $data['msg'] .= 'Your changes to family member data have been saved</p>';
      }

      echo view('members/pers_data_view.php', $data);
      echo view('template/footer_member');

    }
    else {
      echo view('template/header');
      $this->login_mod->logout();
      $data['title'] = 'Login Error';
      $data['msg'] = 'There was an error while checking your credentials.<br><br>';
      echo view('status/status_view.php', $data);
    }
  }
  public function add_fam_mem(int $id = null) {
		if($this->check_mem()) {
			$param['parent_primary'] = $id;
			$param['callsign'] =  strtoupper(trim($this->request->getPost('callsign')));
			$param['fname'] = $this->request->getPost('fname');
			$param['lname'] = trim($this->request->getPost('lname'));
			$param['license'] = $this->request->getPost('sel_lic');
			$param['w_phone'] = $this->request->getPost('w_phone');
			$param['h_phone'] = $this->request->getPost('h_phone');
			$param['id_mem_types'] = $this->request->getPost('mem_types');
			$param['mem_type'] = $this->staff_mod->get_mem_types()[$param['id_mem_types']];
			$param['active'] = TRUE;
			$param['mem_since'] = date('Y', time());
			$param['comment'] = $this->request->getPost('comment');
			$email = strtolower($this->request->getPost('email'));
			filter_var($email, FILTER_VALIDATE_EMAIL) ? $param['email'] = $email : $param['email'] = 'none';
			$this->request->getPost('arrl') == 'on' ? $param['arrl_mem'] = 'TRUE' : $param['arrl_mem'] = 'FALSE';
			$this->request->getPost('dir_ok') == 'on' ? $param['ok_mem_dir'] = 'TRUE' : $param['ok_mem_dir'] = 'FALSE';
			$ret_str = $this->mem_mod->add_fam_mem($param);

			echo view('template/header_member');
			if($ret_str == NULL) {
				$data = $this->get_mem_data();
				$data['msg'] = '<p class="text-success fw-bold">Your family member was added!<br>';
				echo view('members/member_view.php', $data);
			}
			else {
	      $data = $this->get_pers_data();
	      $data['msg'] = $ret_str;
				echo view('members/pers_data_view.php', $data);
			}
			echo view('template/footer_member');
		}
		else {
			echo view('template/header');
			$this->login_mod->logout();
			$data['title'] = 'Login Error';
			$data['msg'] = 'There was an error while checking your credentials.<br><br>';
			echo view('status/status_view.php', $data);
			echo view('template/footer');
		}
	}

  /**
  * Show form to update username and password
  */
	public function show_update() {
		if($this->check_mem()) {
			echo view('template/header_member.php');
			$data = $this->get_mem_data();
			$data['msg'] = '';
			echo view('members/change_login_view', $data);
		}
		else {
			echo view('template/header');
			$data['title'] = 'Authorization Error';
			$data['msg'] = 'You may not be authorized to view this page.<br><br>';
			echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}

  /**
	* Do the update of username and password
	*/
	public function do_update() {
		if($this->check_mem()) {
			echo view('template/header_member.php');
			$data = $this->get_mem_data();
			$param['id'] = $data['user']['id_user'];
			$param['pass'] = $this->request->getPost('pass');
			$param['pass2'] = $this->request->getPost('pass2');
			$param['username'] = strtolower($this->request->getPost('username'));
			$param['cur_username'] = $data['user']['username'];
			$flags = $this->user_mod->do_update($param);
			if($flags['flag']) {
				$data['msg'] = '<p class="text-success"><strong>Success!</strong> ';
				$data['msg'] .= 'Your changes were saved!</p>';
				echo view('members/member_view', $data);
			}
			else {
				$data['msg'] = '<p class="text-danger"><strong>Error(s)!</strong> ';
				if(!$flags['pass_comp']) {
					$data['msg'] .= '<br>The pasword requirements not met. It must be at least 12 characters long, 2 upper case, 2 lower case, 2 numbers and 2 special characters such as !@#$%^&*()\-_+.<br>'; }
				if(!$flags['pass_match']) {
					$data['msg'] .= 'The paswords did not match<br>';
				}
				if(!$flags['username']) {
					$data['msg'] .= 'The username is already taken. Please, use different username<br>';
				}
				$data['msg'] .= '</p>';
				echo view('members/change_login_view', $data);			}
		}
		else {
			echo view('template/header');
			$data['title'] = 'Authorization Error';
			$data['msg'] = 'You may not be authorized to view this page.<br><br>';
			echo view('status/status_view', $data);
		}
		echo view('template/footer_member');
	}
  public function print_dir(int $direction = 1) {
		if($this->check_mem()) {
			echo view('template/header_member');
        $model = $this->dir_mod;
        $rows  = $model->getDirectory($direction);

        echo view('members/dir_view', [
            'rows'             => $rows,
            'orderBy'  => $direction,
        ]);
        echo view ('template/footer_member');
		}
		else {
			echo view('template/header');
			$data['title'] = 'Authorization Error';
			$data['msg'] = 'You may not be authorized to view this page. Go back and try again ' . anchor(base_url(), 'here'). '<br><br>';
			echo view('status/status_view', $data);
		}
	}
}
