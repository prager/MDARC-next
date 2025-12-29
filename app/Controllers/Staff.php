<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\I18n\Time;
use Config\Services;

class Staff extends BaseController
{
    public function index(): void
    {
        if($this->check_staff()) {
            echo view('template/header_admin.php');
            echo view('staff/staff_view.php');
			echo view('template/footer_staff');
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
/* AI gen -2 */
	public function show_members() {
		if($this->check_staff()) {
			echo view('template/header_admin');
			$db    = \Config\Database::connect();
			$pager = Services::pager();

			// Sorting (whitelist)
			$allowedSort = ['id_members','fname','lname','email','callsign'];
			$sort = $this->request->getGet('sort') ?? 'lname';
			if (!in_array($sort, $allowedSort, true)) $sort = 'lname';

			$dir  = strtoupper($this->request->getGet('dir') ?? 'ASC');
			$dir  = in_array($dir, ['ASC','DESC'], true) ? $dir : 'ASC';

			// Pagination
			$perPage = 17;
			$page    = max(1, (int)($this->request->getGet('page') ?? 1));
			$offset  = ($page - 1) * $perPage;

			// Filter: cur_year >= current year
			$now  = Time::now('America/Los_Angeles');
			$year = (int)$now->format('Y');

			// 1) Total rows via SP
			$total = 0;
			$res   = $db->query('CALL CountMembers(?)', [$year]);
			if ($res) {
				$row = $res->getRowArray();
				$total = (int)($row['total'] ?? 0);
				$totalMems = $total;
				$res->freeResult();
			}
			$this->flushMultiResults($db);

			// 2) Page rows via SP
			$members = [];
			$res2 = $db->query('CALL GetMembersPaged(?,?,?,?,?)', [$year, $sort, $dir, $perPage, $offset]);
			if ($res2) {
				$members = $res2->getResultArray();
				$res2->freeResult();
			}
			$this->flushMultiResults($db);	

			// 3) Page rows for deactivated
			$deact = [];
			$res3 = $db->query('CALL GetMembers99()');
			if ($res3) {
				$deact = $res3->getResultArray();
				$res3->freeResult();
			}
			$this->flushMultiResults($db);

			// 4) Page rows for receiving the Carrier
			$carr = [];
			$res4 = $db->query('CALL GetHardNewsMembers()');
			if ($res4) {
				$carr = $res4->getResultArray();
				$res4->freeResult();
			}
			$this->flushMultiResults($db);

			// 5) Page rows for the silent keys
			$silents = [];
			$res5 = $db->query('CALL GetSilentKeys()');
			if ($res5) {
				$silents = $res5->getResultArray();
				$res5->freeResult();
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

			$licence = array('SWL', 'Technician', 'General', 'Advanced', 'Amateur Extra');

			// All U.S. state abbreviations
			$states = [
				'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA',
				'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD',
				'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ',
				'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC',
				'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'
			];

			// Build pager HTML (we’re not using Model::paginate())
			$data = [
				'silents' => $silents,
				'carr' => $carr,
				'deact' => $deact,
				'members'    => $members,
				'pager'      => $pager,        // keep if you want, not used directly
				'sort'       => $sort,
				'dir'        => $dir,
				'page'       => $page,
				'total'      => $total,
				'perPage'    => $perPage,
				'forYear'	=> $year,
				'numMems'	=> $totalMems,
				'lic'	=> $licence,
				'types' => $types, 
				'states' => $states
			];


			echo view('staff/members_view', $data);
			echo view('template/footer_staff');
		}
		else {
			echo view('template/header');
			$data['title'] = 'Authorization Error';
			$data['msg'] = 'You may not be authorized to view this page. Go back and try again ' . anchor(base_url(), 'here'). '<br><br>';
			echo view('status/status_view', $data);
			echo view('template/footer');
		}
	}

	public function show_all_members() {
		if($this->check_staff()) {
			echo view('template/header_admin');
			$db    = \Config\Database::connect();
			$pager = Services::pager();

			// Sorting (whitelist)
			$allowedSort = ['id_members','fname','lname','email','callsign'];
			$sort = $this->request->getGet('sort') ?? 'lname';
			if (!in_array($sort, $allowedSort, true)) $sort = 'lname';

			$dir  = strtoupper($this->request->getGet('dir') ?? 'ASC');
			$dir  = in_array($dir, ['ASC','DESC'], true) ? $dir : 'ASC';

			// Pagination
			$perPage = 17;
			$page    = max(1, (int)($this->request->getGet('page') ?? 1));
			$offset  = ($page - 1) * $perPage;

			// Filter: cur_year >= current year
			$year = 0;

			// 1) Total rows via SP
			$total = 0;
			$res   = $db->query('CALL CountMembers(?)', [$year]);
			if ($res) {
				$row = $res->getRowArray();
				$total = (int)($row['total'] ?? 0);
				$totalMems = $total;
				$res->freeResult();
			}
			$this->flushMultiResults($db);

			// 2) Page rows via SP
			$members = [];
			$res2 = $db->query('CALL GetMembersPaged(?,?,?,?,?)', [$year, $sort, $dir, $perPage, $offset]);
			if ($res2) {
				$members = $res2->getResultArray();
				$res2->freeResult();
			}
			$this->flushMultiResults($db);

			// 3) Page rows for deactivated
			$deact = [];
			$res3 = $db->query('CALL GetMembers99()');
			if ($res3) {
				$deact = $res3->getResultArray();
				$res3->freeResult();
			}
			$this->flushMultiResults($db);

			// 4) Page rows for receiving the Carrier
			$carr = [];
			$res4 = $db->query('CALL GetHardNewsMembers()');
			if ($res4) {
				$carr = $res4->getResultArray();
				$res4->freeResult();
			}
			$this->flushMultiResults($db);

			// 5) Page rows for the silent keys
			$silents = [];
			$res5 = $db->query('CALL GetSilentKeys()');
			if ($res5) {
				$silents = $res5->getResultArray();
				$res5->freeResult();
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

			$licence = array('SWL', 'Technician', 'General', 'Advanced', 'Amateur Extra');

			// All U.S. state abbreviations
			$states = [
				'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA',
				'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD',
				'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ',
				'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC',
				'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'
			];

			// Build pager HTML (we’re not using Model::paginate())
			$data = [
				'silents' => $silents,
				'carr' => $carr,
				'deact' => $deact,
				'members'    => $members,
				'pager'      => $pager,        // keep if you want, not used directly
				'sort'       => $sort,
				'dir'        => $dir,
				'page'       => $page,
				'total'      => $total,
				'perPage'    => $perPage,
				'forYear'	=> $year,
				'numMems'	=> $totalMems,
				'lic'	=> $licence,
				'types' => $types, 
				'states' => $states
			];

			echo view('staff/members_view', $data);
			echo view('template/footer_staff');
		}
		else {
			echo view('template/header');
			$data['title'] = 'Authorization Error';
			$data['msg'] = 'You may not be authorized to view this page. Go back and try again ' . anchor(base_url(), 'here'). '<br><br>';
			echo view('status/status_view', $data);
			echo view('template/footer');
		}
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
