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

/**
* Adds or edits a member
* @param mixed $param[] for db insert and update
* @return bool $retval whether or not the email param was ok
*/
  public function edit_mem($param) {
    $param['mem_type'] = $this->get_mem_type($param['id_mem_types']);
    $retval = TRUE;
    $id = $param['id'];
    unset($param['id']);
    $db      = \Config\Database::connect();
    $builder = $db->table('tMembers');
    $builder->where('email', $param['email']);
    $builder->where('callsign', $param['callsign']);
    $builder->where('lname', $param['lname']);
    $builder->where('fname', $param['fname']);
    if($id != NULL) {
      $builder->resetQuery();
      $builder->update($param, ['id_members' => $id]);

  //must figure if primary member and update the family members as well
      $builder->resetQuery();
      $up_arr = array('paym_date' => $param['paym_date'],
                      'cur_year' => $param['cur_year']);
      $builder->update($up_arr, ['parent_primary' => $id]);

    }
    elseif(($builder->countAllResults() == 0) && $this->check_dups($param)) {
          $param['update_type'] = 'Initial insert';
          $param['mem_type'] = 'Individual';
          $param['mem_since'] = date('Y', time());
          $param['cur_year'] = date('Y', time());
          $param['ok_mem_dir'] = true;
          if(date('m', $param['paym_date']) > 9) $param['cur_year']++;
          $builder->resetQuery();
          $builder->insert($param);
        }
    else {
        $retval = FALSE;
      }
    $db->close();

    return $retval;
  }

  /**
* Check for duplicate members within 5 years
*/
private function check_dups($param) {
  $retval = TRUE;
  $db      = \Config\Database::connect();
  $builder = $db->table('old_mems_2020');
  $res = $builder->get()->getResult();
  foreach($res as $mem) {
    if(($param['email'] == $mem->email) && ((date('Y', time()) - 5) > $mem->cur_year)) {
        $retval = FALSE;
        break;
      }
  }
  return $retval;
}

  /**
* Returns the type description
*/
public function get_mem_type($type) {
  $types = $this->get_mem_types();
  return $types[$type];
}

/**
* Returns the new members for a given period
* @param int from date via unix timestamp
* @param int to date via unix timestamp
* @return array the member data
*/
public function get_new_mems($from, $to) {
    $db = \Config\Database::connect();
    $builder = $db->table('tMembers');
    //$builder->where('paym_date >', $from);
    //$builder->where('paym_date <', $to);
    //$builder->where('mem_since', date('Y', time()));
    //echo '<br><br><br><br>to: ' . $to;
    $builder->where('mem_since', date('Y', $to));
    $db->close;
    $res = $builder->get()->getResult();
    $retarr = array();
    foreach($res as $mem) {
      $mem_arr = array(
        'id' => $mem->id_members,
        'fname' => $mem->fname,
        'lname' => $mem->lname,
        'callsign' => $mem->callsign,
        'license' => $mem->license,
        'payment_date' => $mem->paym_date
      );
      array_push($retarr, $mem_arr);
    }
  return $retarr;
  }

  /**
  * This doesn't delete a member, only inactivates by setting current year to 99
  * @param int as id_members
  */
  public function delete_mem($id) {
    $db      = \Config\Database::connect();
    $builder = $db->table('tMembers');

  //must also inactivate the family members for primaries
    $builder->resetQuery();
    $builder->update(array('cur_year' => 99), ['id_members' => $id]);
    $builder->resetQuery();
    $builder->update(array('cur_year' => 99), ['parent_primary' => $id]);
  }
  public function purge_mem($id) {
    $db      = \Config\Database::connect();
    $builder = $db->table('tMembers');
    $builder->where('parent_primary', $id);

// we must also purge the family members for the primary member
    $res = $builder->get()->getResult();
    foreach($res as $child) {
      $builder->resetQuery();
      $builder->delete(['id_members' => $child->id_members]);
    }
    $builder->resetQuery();
    $builder->delete(['id_members' => $id]);
  }
}