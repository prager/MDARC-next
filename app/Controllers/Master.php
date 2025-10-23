<?php

namespace App\Controllers;

class Master extends BaseController
{    var $username;
    public function index(): void
    {
        if($this->check_master()) {
            echo view('template/header_master.php');
            echo view('master/master_view.php');
        }
        else {
            $this->login_mod->logout();
            echo view('template/header');
            $data['title'] = 'Login Error';
            $data['msg'] = 'There was an error while checking your credentials. Click ' . anchor('Home/reset_password', 'here') . ' to reset your password <br><br>';
            echo view('status/status_view', $data);
        }
            echo view('template/footer');
    }
    /**
    * Checks for master user according to the type code
    */
    private function check_master() {
        $retval = FALSE;
        $user_arr = $this->login_mod->get_cur_user();
        if(($user_arr != NULL) && ($user_arr['type_code'] == 99 && $user_arr['authorized'] == 1)) {
            $retval = TRUE;
        }
        return $retval;
    }
    public function master_faqs() {
		if($this->check_master()) {
			echo view('template/header_master');
			$data = $this->staff_mod->get_faqs();
			$data['msg'] = '';
			echo view('master/faqs_view', $data);
		}
		else {
			echo view('template/header');
			$data['title'] = 'Login Error';
			$data['msg'] = 'There was an error while checking your credentials. Click ' . anchor('Home/reset_password', 'here') .
			' to reset your password or go to home page ' . anchor('Home', 'here'). '<br><br>';
			echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}

    public function edit_faq($id = null) {
		if($this->check_master()) {
			echo view('template/header_master');
			$param['id'] = $id;
			$param['id_user'] = $this->login_mod->get_cur_user()['id_user'];
			$param['theq'] = $this->request->getPost('theQ');
			$param['thea'] = $this->request->getPost('theA');
			$param['id_user_type'] = $this->request->getPost('mem_types');
			$this->staff_mod->edit_faq($param);
			$data = $this->staff_mod->get_faqs();
			$data['msg'] = '<p class="text-danger"> Record updated!</p>';
			echo view('master/faqs_view', $data);
		}
		else {
			echo view('template/header');
			$data['title'] = 'Login Error';
			$data['msg'] = 'There was an error while checking your credentials. Click ' . anchor('Home/reset_password', 'here') .
			' to reset your password or go to home page ' . anchor('Home', 'here'). '<br><br>';
			echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}
	public function delete_faq($id = null) {
		if($this->check_master()) {
			echo view('template/header_master');
			$this->staff_mod->delete_faq($id);
			$data = $this->staff_mod->get_faqs();
			$data['msg'] = '<p class="text-danger"> Record updated!</p>';
			echo view('master/faqs_view', $data);
		}
		else {
			echo view('template/header');
			$data['title'] = 'Login Error';
			$data['msg'] = 'There was an error while checking your credentials. Click ' . anchor('Home/reset_password', 'here') .
			' to reset your password or go to home page ' . anchor('Home', 'here'). '<br><br>';
			echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}
	/**
	* Enables master user edit users
	*/
	public function edit_users() {
		echo view('template/header_master');
		if($this->check_master()) {
			$users_data = $this->master_mod->get_users_data();
			$data['usr_types'] = $users_data['usr_types'];
			$data['users'] = $users_data['users'];
			$data['states'] = $this->data_mod->get_states_array();
			$data['msg'] = '';
			$data['errmsg'] = '';
			echo view('master/edit_users_view', $data);
		}
		else {
			$data['title'] = 'Login Error';
			$data['msg'] = 'There was an error while checking your credentials. Click ' . anchor('Home/reset_password', 'here') .
			' to reset your password or go to home page ' . anchor('Home', 'here'). '<br><br>';
			echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}

	/**
	* Saves the updated admin user data into db
	*/
	public function load_admin($id = null) {
		if($this->check_master()) {
			echo view('template/header_master');
			$param['id_user'] = $id;
			$param['fname'] = $this->request->getPost('fname');
			$param['lname'] = $this->request->getPost('lname');
			$param['phone'] = $this->request->getPost('phone');
			$param['facebook'] = $this->request->getPost('facebook');
			$param['twitter'] = $this->request->getPost('twitter');
			$param['linkedin'] = $this->request->getPost('linkedin');
			$param['email'] = $this->request->getPost('email');
			$param['street'] = $this->request->getPost('street');
			$param['city'] = $this->request->getPost('city');
			$param['state_cd'] = $this->request->getPost('state');
			$param['zip_cd'] = $this->request->getPost('zip');
			$param['comment'] = $this->request->getPost('comment');
			$param['callsign'] = $this->request->getPost('callsign');
			$param['id_user_type'] = $this->request->getPost('usr_type');
			$this->master_mod->load_admin($param);
			$data = $this->master_mod->get_users_data();
			$data['states'] = $this->data_mod->get_states_array();
			$data['msg'] = 'Updated user. Thank you!';
			$data['errmsg'] = NULL;
			echo view('master/edit_users_view', $data);
		}
		else {
				echo view('template/header');
 				 $data['title'] = 'Login Error';
 				 $data['msg'] = 'There was an error while checking your credentials. Click ' . anchor('Home/reset_password', 'here') .
 				 ' to reset your password or go to home page ' . anchor('Home', 'here'). '<br><br>';
 				 echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}
	public function reset_user($id = null) {
		if($this->check_master()) {
			echo view('template/header_master');
				$param['id_user'] = $id;
				$param['username'] = $this->request->getPost('username');
				$param['pass'] = $this->request->getPost('pass');
				$param['pass2'] = $this->request->getPost('pass2');
				$flags = $this->master_mod->reset_user($param);
				if ($flags['flag']) {
 			 		$data = $this->master_mod->get_users_data();
		 		 	$data['states'] = $this->data_mod->get_states_array();
				 	$data['msg'] = 'Username and pasword were succesfuly reset. Thank you!';
					$data['errmsg'] = NULL;
		 		  	echo view('master/edit_users_view', $data);
				}
				else {
					$data['errmsg'] = 'Please, fix the following error(s):<br>';
					$data['id_user'] = $param['id_user'];
					if($flags['usr_dup']) $data['errmsg'] .= '<p style="color:red;">Duplicate username</p>';
					if(!($flags['pass_match'])) $data['errmsg'] .= '<p style="color:red;">Passwords do not match</p>';
					if(!($flags['pass_comp'])) $data['errmsg'] .= '<p style="color:red;">Password complexity requirement not met</p>';
					echo view('master/edit_users_view', $data);
				}
		}
		else {
			echo view('template/header');
			 $data['title'] = 'Login Error';
			 $data['msg'] = 'There was an error while checking your credentials. Click ' . anchor('Home/reset_password', 'here') .
			 ' to reset your password or go to home page ' . anchor('Home', 'here'). '<br><br>';
			 echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}
	public function activate($id = null) {
		if($this->check_master()) {
			echo view('template/header_master');
			 $this->master_mod->activate($id);
			 $data = $this->master_mod->get_users_data();
	 		 $data['states'] = $this->data_mod->get_states_array();
			 $data['msg'] = 'Activated / deactivated user. Thank you!';
			 $data['errmsg'] = NULL;
	 		 echo view('master/edit_users_view', $data);
		}
		else {
			echo view('template/header');
			 $data['title'] = 'Login Error';
			 $data['msg'] = 'There was an error while checking your credentials. Click ' . anchor('Home/reset_password', 'here') .
			 ' to reset your password or go to home page ' . anchor('Home', 'here'). '<br><br>';
			 echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}
	public function authorize($id = null) {
		if($this->check_master()) {
			echo view('template/header_master');
			 $this->master_mod->authorize($id);
			 $data = $this->master_mod->get_users_data();
	 		 $data['states'] = $this->data_mod->get_states_array();
			 $data['msg'] = 'Authorized / Unauthorized user. Thank you!';
			 $data['errmsg'] = NULL;
	 		 echo view('master/edit_users_view', $data);
		}
		else {
			echo view('template/header');
			 $data['title'] = 'Login Error';
			 $data['msg'] = 'There was an error while checking your credentials. Click ' . anchor('Home/reset_password', 'here') .
			 ' to reset your password or go to home page ' . anchor('Home', 'here'). '<br><br>';
			 echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}

	public function search() {
		if($this->check_master()) {
	  		echo view('template/header_master.php');
			$search_str = $this->request->getPost('search');
			$res = $this->master_mod->search($search_str);
			$data['msg'] = $res['msg'];
			$data['mems'] = $res['mems'];
			$data = $this->master_mod->search($search_str);
			$data['states'] = $this->data_mod->get_states_array();
			$data['lic'] = $this->data_mod->get_lic();
			$data['mem_types'] = $this->staff_mod->get_mem_types();
			echo view('master/search_res_view.php', $data);
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
	public function add_mem() {
		if($this->check_master()) {
			echo view('template/header_master');
			$data['states'] = $this->data_mod->get_states_array();
			$data['lic'] = $this->data_mod->get_lic();
			$data['mem_types'] = $this->master_mod->get_member_types();
			echo view('master/add_mem_view', $data);
		}
		else {
			echo view('template/header');
			$data['title'] = 'Login Error';
			$data['msg'] = 'There was an error while checking your credentials. Click ' . anchor('Home/reset_password', 'here') .
			' to reset your password or go to home page ' . anchor('Home', 'here'). '<br><br>';
			echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}
	
	public function edit_mem($id = null) {
		if($this->check_master()) {
			echo view('template/header_master');
			$param['email'] =  trim($this->request->getPost('email'));
			$param['callsign'] =  trim($this->request->getPost('callsign'));
			$param['paym_date'] = strtotime($this->request->getPost('pay_date'));
			$param['address'] = $this->request->getPost('address');
			$param['city'] = $this->request->getPost('city');
			$param['state'] = $this->request->getPost('state');
			$param['zip'] = $this->request->getPost('zip');
			$param['fname'] = $this->request->getPost('fname');
			$param['lname'] = trim($this->request->getPost('lname'));
			$param['license'] = $this->request->getPost('sel_lic');
			$param['cur_year'] = intval(trim($this->request->getPost('cur_year')));
			$param['mem_since'] = trim($this->request->getPost('mem_since'));
			$param['w_phone'] = $this->request->getPost('w_phone');
			$param['h_phone'] = $this->request->getPost('h_phone');
			$param['comment'] = trim($this->request->getPost('comment'));
			$param['id_mem_types'] = $this->request->getPost('mem_types');
			$param['timestamp'] = time();
			$email = $this->request->getPost('email');
			filter_var($email, FILTER_VALIDATE_EMAIL) ? $param['email'] = $email : $param['email'] = 'none';
			$this->request->getPost('arrl') == 'on' ? $param['arrl_mem'] = 'TRUE' : $param['arrl_mem'] = 'FALSE';
			$this->request->getPost('hard_news') == 'on' ? $param['hard_news'] = 'TRUE' : $param['hard_news'] = 'FALSE';
			$this->request->getPost('dir') == 'on' ? $param['hard_dir'] = 'TRUE' : $param['hard_dir'] = 'FALSE';
			$this->request->getPost('mem_card') == 'on' ? $param['mem_card'] = 'TRUE' : $param['mem_card'] = 'FALSE';
			$this->request->getPost('dir_ok') == 'on' ? $param['ok_mem_dir'] = 'TRUE' : $param['ok_mem_dir'] = 'FALSE';

			$param['id'] = $id;

			if ($this->staff_mod->edit_mem($param)) {
				$param['states'] = $this->data_mod->get_states_array();
				$param['lic'] = $this->data_mod->get_lic();
				$param['member_types'] = $this->master_mod->get_member_types();
				$param['page'] = 0;
				echo view('master/members_view', $this->staff_mod->get_mems($param));
			}
			else {
				$data['title'] = 'Douplicate Entry Error!';
				$data['msg'] = 'This is duplicate entry. The member ' . $param['lname'] . ' with callsign ' . $param['callsign'] . ' is already in the database.<br><br>';
				$data['msg'] .= 'Go back to ' . anchor('members', 'members listing');
				echo view('status/status_view', $data);
			}
		}
		else {
			echo view('template/header');
			$data['title'] = 'Authorization Error';
			$data['msg'] = 'You may not be authorized to view this page. Go back and try again ' . anchor(base_url(), 'here'). '<br><br>';
			echo view('status/status_view', $data);
		}
		echo view('template/footer');
	}

	public function show_members() {

		echo view('template/header_master');
   
		// --- read sort parameters from URL query (e.g., ?sort=first_name&dir=DESC)
        $sort = $this->request->getGet('sort') ?? 'lname';
        $dir  = strtoupper($this->request->getGet('dir') ?? 'ASC');
        $dir  = in_array($dir, ['ASC', 'DESC']) ? $dir : 'ASC';

        $data = [
            'members' => $this->mems_mod->getList(17, $sort, $dir),
            'pager'   => $this->mems_mod->pager,
            'sort'    => $sort,
            'dir'     => $dir,
        ];

		echo view('master/members_view', $data);

		echo view('template/footer');
	}

	public function show_parent(int $id) {
		
		$parent = $this->mems_mod->select('id_members, fname, lname, email')
		->where('id_members', $id)
		->first();
		
		if (!$parent) {
            return $this->response->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Parent not found']);
        }

		return $this->response->setJSON([
            'status' => 'ok',
            'data'   => [
                'id_members'  => (int)$parent['id_members'],
                'fname' => (string)($parent['fname'] ?? ''),
                'lname'  => (string)($parent['lname'] ?? ''),
                'email'      => (string)($parent['email'] ?? ''),
            ]
        ]);
	}
}
