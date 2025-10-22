<?php namespace App\Models;

use CodeIgniter\Model;

class Staff_model extends Model {
    public $db;
    public $session;
    protected function initialize()
    {
        $this->session = session();
    }

    public function get_faqs() {
        $db      = \Config\Database::connect();
        $builder = $db->table('faqs');
        $cnt = $builder->countAllResults();
        $retarr = array();
        $retarr['faqs'] = array();
        if($cnt > 0) {
          $builder->resetQuery();
          $res = $builder->get()->getResult();
          $login_mod = new \App\Models\Login_model();
          foreach ($res as $key => $faq) {
            $elem = array();
            $elem['id'] = $faq->id_faqs;
            $elem['theq'] = $faq->theq;
            $elem['thea'] = $faq->thea;
            $elem['id_user_type'] = $faq->id_user_type;
            $elem['id_user'] = $faq->id_user;
            $usr_arr = $login_mod->get_user_arr($elem['id_user']);
            $elem['fname'] = $usr_arr['fname'];
            $elem['lname'] = $usr_arr['lname'];
            array_push($retarr['faqs'], $elem);
          }
        }
        else {
          $retarr['faqs'] = NULL;
        }
        $user_mod = new \App\Models\User_model();
        $retarr['mem_types'] = $user_mod->get_user_types();
        return $retarr;
      }
      public function edit_faq($param) {
        $id = $param['id'];
        unset($param['id']);
        $db      = \Config\Database::connect();
        $builder = $db->table('faqs');
        $cnt = $builder->countAllResults();
        $builder->resetQuery();
        if($cnt > 0) {
          $id != NULL ? $builder->update($param, ['id_faqs' => $id]) : $builder->insert($param);
        }
        else {
          $builder->insert($param);
        }
  
        $db->close();
      }
      public function delete_faq($id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('faqs');
        $builder->where('id_faqs', $id);
        $builder->resetQuery();
        $builder->delete(['id_faqs' => $id]);
        $db->close();
      }

      public function get_mem_types() {
        $db      = \Config\Database::connect();
        $builder = $db->table('tMemTypes');
        $builder->orderBy('id_mem_types', 'DESC');
        $types = $builder->get()->getResult();
        $retarr = array();
        foreach($types as $type) {
          $retarr[$type->id_mem_types] = $type->description;
        }
        $db->close();
        return $retarr;
      }

    public function get_mem($id) {
      $elem = array();
      $mem_types = $this->get_mem_types();
      $db      = \Config\Database::connect();
      $builder = $db->table('tMembers');
      $builder->where('id_members', $id);
      $member = $builder->get()->getRow();
      if($member->parent_primary > 0) {
        $builder->resetQuery();
        $elem['id_parent'] = $member->parent_primary;
        $builder->where('id_members', $elem['id_parent']);
        $parent = $builder->get()->getRow();
        $elem['parent_fname'] = $parent->fname;
        $elem['parent_lname'] = $parent->lname;
      }
      else {
        $elem['id_parent'] = 0;
        $elem['parent_fname'] = '';
        $elem['parent_lname'] = '';
      }
      $db->close();
      $data_mod = new \App\Models\Data_model();
      $master_mod = new \App\Models\Master_model();
      $param['lic'] = $data_mod->get_lic();
      $param['mem_types'] = $master_mod->get_member_types();

      $elem['id'] = $member->id_members;
      $fam_mems = $this->get_fam_mems($elem['id']);
      $elem['fam_mems'] = $fam_mems['fam_mems'];
      $elem['fam_flag'] = $fam_mems['fam_flag'];

      $elem['carrier'] = trim(strtoupper($member->hard_news ?? ''));
      $elem['dir'] = trim(strtoupper($member->hard_dir ?? ''));
      $elem['arrl'] = trim(strtoupper($member->arrl_mem ?? ''));
      $elem['mem_card'] = trim(strtoupper($member->mem_card ?? ''));
      $member->h_phone == NULL ? $elem['h_phone'] = '000-000-0000' : $elem['h_phone'] = $member->h_phone;
      $member->w_phone == NULL ? $elem['w_phone'] = '000-000-0000' : $elem['w_phone'] = $member->w_phone;
      $member->comment == NULL ? $elem['comment'] = '' : $elem['comment'] = $member->comment;
      $elem['phone_unlisted'] = $member->h_phone_unlisted;
      $elem['cell_unlisted'] = $member->w_phone_unlisted;
      $elem['email_unlisted'] = $member->email_unlisted;
      $elem['fname'] = $member->fname;
      $elem['lname'] = $member->lname;
      $elem['mem_types'] = $mem_types;
      $member->address == NULL ? $elem['address'] = 'N/A' : $elem['address'] = $member->address;
      $member->city == NULL ? $elem['city'] = 'N/A' : $elem['city'] = $member->city;
      $member->state == NULL ? $elem['state'] = 'CA' : $elem['state'] = $member->state;
      $member->zip == NULL ? $elem['zip'] = '00000' : $elem['zip'] = $member->zip;
      $elem['active'] = $member->active;
      $member->cur_year == NULL ? $elem['cur_year'] = 'N/A' : $elem['cur_year'] = $member->cur_year;
      $elem['mem_type'] = $mem_types[$member->id_mem_types];
      $elem['id_mem_types'] = $member->id_mem_types;
      $elem['callsign'] = $member->callsign;
      $elem['license'] = $member->license;
      $elem['hard_news'] = strtoupper($member->hard_news ?? '');
      $elem['spouse_name'] = $member->spouse_name;
      $elem['spouse_call'] = $member->spouse_call;
      $elem['pay_date'] = date('Y-m-d', $member->paym_date);
      $elem['pay_date_file'] = date('Y/m/d', $member->paym_date);
      if($member->silent_date > 0 || $member->silent_year > 0) {
        $elem['silent_date'] = date('Y-m-d', $member->silent_date);  
        if($member->silent_date == 0) $elem['silent_date'] = $member->silent_year;    
      }
      else {
        $elem['silent_date'] = 0;
      }
      $member->mem_since == NULL ? $elem['mem_since'] = 'N/A' : $elem['mem_since'] = $member->mem_since;
      $member->email == NULL ? $elem['email'] = 'N/A' : $elem['email'] = $member->email;
      $elem['ok_mem_dir'] = $member->ok_mem_dir;
      //$member->silent_date > 1 ? $elem['silent_date'] = date('Y-m-d', $member->silent_date) : $elem['silent_date'] = 'No Date';
      $elem['silent_year'] = $member->silent_year;
      $member->usr_type == 98 ? $elem['silent'] = TRUE : $elem['silent'] = FALSE;

      return $elem;
    }
    public function get_fam_mems($id) {
      $db      = \Config\Database::connect();
      $builder = $db->table('tMembers');
      $db->close();
      $builder->where('parent_primary', $id);
      $retarr = array();
      $retarr['fam_mems'] = array();
      if($builder->countAllResults() > 0) {
        $builder->resetQuery();
        $builder->where('parent_primary', $id);
        $res = $builder->get()->getResult();
        foreach($res as $mem) {
          $fam_mem = $this->get_fam_mem($mem->id_members);
          array_push($retarr['fam_mems'], $this->get_fam_mem($mem->id_members));
        }
      }
      count($retarr['fam_mems']) > 0 ? $retarr['fam_flag'] = TRUE : $retarr['fam_flag'] = FALSE;
      return $retarr;
    }
}