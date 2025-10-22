<?php

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
}
