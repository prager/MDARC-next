<?php namespace App\Models;

use CodeIgniter\Model;

class Member_model extends Model {
    public function add_fam_mem($param) {
        $retval['err'] = '';
    
        $dups = $this->check_dup($param);
    
        $flag['flag'] = TRUE;
    
        if($dups['spouse_dup']) {
          $retval['err'] .= '<p class="text-danger fw-bold">You may enter only one spouse. No data was saved</p>';
          $flag['flag'] = FALSE;
        }
    
        if($dups['fam_mem']) {
          $retval['err'] .= '<p class="text-danger fw-bold">This family member already exists in database. No data was saved</p>';
          $flag['flag'] = FALSE;
        }
    
        if($dups['callsign']) {
         $retval['err'] .= '<p class="text-danger fw-bold">This callsign '. $param['callsign'] . ' already exists in database. No data was saved.</p>';
          $flag['flag'] = FALSE;
        }
    
        $retval['flag'] = $flag;
    
        if($flag['flag']) {
          $db      = \Config\Database::connect();
          $builder = $db->table('tMembers');
          $builder->resetQuery();
    
      // update parent from individual to primary
          $mem_array = array('id_mem_types' => 2, 'mem_type' => 'Primary');
          $builder->update($mem_array, ['id_members' => $param['parent_primary']]);
          $builder->resetQuery();
          $builder->where('id_members', $param['parent_primary']);
          $mem_obj = $builder->get()->getRow();
          $param['address'] = $mem_obj->address;
          $param['city'] = $mem_obj->city;
          $param['state'] = $mem_obj->state;
          $param['zip'] = $mem_obj->zip;
          $param['pay_date'] = $mem_obj->pay_date;
          $param['cur_year'] = $mem_obj->cur_year;
          $builder->resetQuery();
          $builder = $db->table('tMembers');
          $builder->resetQuery();
          $builder->insert($param);
          $db->close();
        }
        return $retval;
      }
    
      public function add_fam_existing($param) {
        $db      = \Config\Database::connect();
        $builder = $db->table('tMembers');
        $id = $param['id_members'];
        unset($param['id_members']);
        $builder->update($param, ['id_members' => $id]);
        $builder->resetQuery();
      }  

  /**
  * Checks for duplicate family member and callsign
  * If return is true then there is a duplicate
  */
  private function check_dup($param) {
    $retarr = array();
    $db      = \Config\Database::connect();
    $builder = $db->table('tMembers');
    $builder->where('parent_primary', $param['parent_primary']);
    $builder->where('id_mem_types', 3);
    $builder->countAllResults() > 0 && $param['id_mem_types'] == 3 ? $retarr['spouse_dup'] = TRUE : $retarr['spouse_dup'] = FALSE;

    $builder->resetQuery();
    $builder = $db->table('tMembers');
    $builder->where('lname', $param['lname']);
    $builder->where('fname', $param['fname']);
    $builder->where('mem_type', $param['mem_type']);
    $builder->where('parent_primary', $param['parent_primary']);

//check for duplicate family member
    $builder->countAllResults() > 0 ? $retarr['fam_mem'] = TRUE : $retarr['fam_mem'] = FALSE;

//check for duplicate callsign
    $retarr['callsign'] = FALSE;
    $sum = 0;
    if($param['callsign'] == '') {$sum++;}
    if(strtolower($param['callsign'] ?? '') == 'none'){$sum++;}
    if(strtolower($param['callsign'] ?? '') == 'swl'){$sum++;}

//if(($param['callsign'] != '') || (strtolower($param['callsign']) != 'none') || (strtolower($param['callsign']) != 'swl')) {
    if($sum == 0) {
      $builder->resetQuery();
      $builder->where('callsign', $param['callsign']);
      $builder->countAllResults() > 0 ? $retarr['callsign'] = TRUE : $retarr['callsign'] = FALSE;
    }

//check for duplicate email
    $retarr['email'] = FALSE;
    $builder->resetQuery();
    $builder->where('email', $param['email']);
    $builder->where('parent_primary!=', $param['parent_primary']);
    $builder->where('id_members!=', $param['parent_primary']);
    $builder->countAllResults() > 0 ? $retarr['email'] = TRUE : $retarr['email'] = FALSE;

    return $retarr;
  }

  /**
     * Returns all member rows (joined to tMemTypes) via stored procedure.
     */
    public function getAllMemData(): array {
        $db = \Config\Database::connect($this->DBGroup);

        // Call the stored procedure
        $query = $db->query('CALL GetAllMemData()');

        $rows = $query->getResultArray();

        // Free the result so additional queries won't fail on this connection.
        $query->freeResult();

        // (MySQLi) Make sure to flush any remaining result sets from CALL
        // so you can run another query on the same connection later.
        if (method_exists($db->connID, 'more_results')) {
            while ($db->connID->more_results() && $db->connID->next_result()) { /* flush */ }
        }

        return $rows;
    }
    public function getCurEmails(): array
    {
        $db = \Config\Database::connect($this->DBGroup);
        $query = $db->query('CALL GetCurEmails()');
        $rows = $query->getResultArray();

        // Free results so we can run more queries later
        $query->freeResult();
        if (method_exists($db->connID, 'more_results')) {
            while ($db->connID->more_results() && $db->connID->next_result()) {}
        }

        return $rows;
    }
    public function getDueEmails(): array
    {
        $db = \Config\Database::connect($this->DBGroup);
        $query = $db->query('CALL GetDueEmails()');
        $rows = $query->getResultArray();

        // clean up connection
        $query->freeResult();
        if (method_exists($db->connID, 'more_results')) {
            while ($db->connID->more_results() && $db->connID->next_result()) {}
        }

        return $rows;
    }

    public function remove_fam_mem($id) {
      $db      = \Config\Database::connect();
      $builder = $db->table('tMembers');
      $param = array('id_mem_types' => 1, 'mem_type' => 'Individual', 'parent_primary' => 0, 'cur_year' => 99);
      $builder->update($param, ['id_members' => $id]);
    }

    public function get_mem($id) {
      $db      = \Config\Database::connect();
      $builder = $db->table('tMembers');
      $db->close();
      $builder->where('id_users', $id);
      $elem = array();
      if($builder->countAllResults() > 0) {
        $builder->resetQuery();
        $builder->where('id_users', $id);
        $member = $builder->get()->getRow();
        $elem['id_members'] = $member->id_members;
  
    //set the true or false values for boolean db entries
        $elem['carrier'] = trim(strtoupper($member->hard_news));
        $elem['dir'] = trim(strtoupper($member->hard_dir));
        $elem['arrl'] =  trim(strtoupper($member->arrl_mem));
        $elem['dir_ok'] =  trim(strtoupper($member->ok_mem_dir));
        $elem['mem_card'] = trim(strtoupper($member->mem_card));
        $member->h_phone == NULL ? $elem['h_phone'] = '000-000-0000' : $elem['h_phone'] = $member->h_phone;
        $member->w_phone == NULL ? $elem['w_phone'] = '000-000-0000' : $elem['w_phone'] = $member->w_phone;
        $member->comment == NULL ? $elem['comment'] = '' : $elem['comment'] = $member->comment;
        $elem['phone_unlisted'] = $member->h_phone_unlisted;
        $elem['cell_unlisted'] = $member->w_phone_unlisted;
        $elem['email_unlisted'] = $member->email_unlisted;
        $elem['fname'] = $member->fname;
        $elem['lname'] = $member->lname;
        $member->address == NULL ? $elem['address'] = 'N/A' : $elem['address'] = $member->address;
        $member->city == NULL ? $elem['city'] = 'N/A' : $elem['city'] = $member->city;
        $member->state == NULL ? $elem['state'] = 'CA' : $elem['state'] = $member->state;
        $member->zip == NULL ? $elem['zip'] = '00000' : $elem['zip'] = $member->zip;
        $elem['active'] = $member->active;
        $member->cur_year == NULL ? $elem['cur_year'] = 'N/A' : $elem['cur_year'] = $member->cur_year;
        $elem['mem_type'] = $member->mem_type;
        $elem['callsign'] = $member->callsign;
        $elem['license'] = $member->license;
        $elem['cur_year'] = $member->cur_year;
        $elem['hard_news'] = $member->hard_news;
        $elem['spouse_name'] = $member->spouse_name;
        $elem['spouse_call'] = $member->spouse_call;
        $elem['pay_date'] = date('Y-m-d', $member->paym_date);
        $elem['pay_date_file'] = date('Y/m/d', $member->paym_date);
        $elem['silent_date'] = date('Y-m-d', $member->silent_date);
        $member->mem_since == NULL ? $elem['mem_since'] = 'N/A' : $elem['mem_since'] = $member->mem_since;
        $member->email == NULL ? $elem['email'] = 'N/A' : $elem['email'] = $member->email;
        $elem['ok_mem_dir'] = $member->ok_mem_dir;
        $cur_yr = date('Y', time());
        $elem['silent_date'] = '';
        $elem['silent_year'] = $member->silent_year;
        $member->usr_type == 98 ? $elem['silent'] = TRUE : $elem['silent'] = FALSE;
      }
      else {
        $elem = NULL;
      }
      $retarr = array();
      $retarr['primary'] = $elem;
      return $retarr;
    }
    public function get_fam_mems($id) {
      $db      = \Config\Database::connect();
      $builder = $db->table('tMembers');
      $builder->where('id_users', $id);
      $retarr = array();
      $retarr['fam_mems'] = array();
      if($builder->countAllResults() > 0) {
        $builder->resetQuery();
        $builder = $db->table('tMembers');
        $builder->where('id_users', $id);
        $member = $builder->get()->getRow();
        $id_mem = $member->id_members;
        $builder->resetQuery();
        $builder = $db->table('tMembers');
        $builder->where('parent_primary', $id_mem);
        $res = $builder->get()->getResult();
        foreach($res as $mem) {
          array_push($retarr['fam_mems'], $this->get_fam_mem($mem->id_members));
        }
      }
      count($retarr['fam_mems']) > 0 ? $retarr['fam_flag'] = TRUE : $retarr['fam_flag'] = FALSE;
      $db->close();
      return $retarr;
    }

    public function get_fam_mem($id) {
      $db      = \Config\Database::connect();
      $builder = $db->table('tMembers');
      $builder->where('id_members', $id);
      $db->close();
      $elem = array();
      if($builder->countAllResults() > 0) {
        $builder->resetQuery();
        $builder->where('id_members', $id);
        $member = $builder->get()->getRow();
        $elem['id_members'] = $id;
  
    //set the true or false values for boolean db entries
        $elem['carrier'] = trim(strtoupper($member->hard_news));
        $elem['dir'] = trim(strtoupper($member->hard_dir));
        $elem['arrl'] =  trim(strtoupper($member->arrl_mem));
        $elem['mem_card'] = trim(strtoupper($member->mem_card));
        $member->h_phone == NULL ? $elem['h_phone'] = '000-000-0000' : $elem['h_phone'] = $member->h_phone;
        $member->w_phone == NULL ? $elem['w_phone'] = '000-000-0000' : $elem['w_phone'] = $member->w_phone;
        $member->comment == NULL ? $elem['comment'] = '' : $elem['comment'] = $member->comment;
        $elem['phone_unlisted'] = $member->h_phone_unlisted;
        $elem['cell_unlisted'] = $member->w_phone_unlisted;
        $elem['email_unlisted'] = $member->email_unlisted;
        $elem['fname'] = $member->fname;
        $elem['lname'] = $member->lname;
        $member->address == NULL ? $elem['address'] = 'N/A' : $elem['address'] = $member->address;
        $member->city == NULL ? $elem['city'] = 'N/A' : $elem['city'] = $member->city;
        $member->state == NULL ? $elem['state'] = 'CA' : $elem['state'] = $member->state;
        $member->zip == NULL ? $elem['zip'] = 'N/A' : $elem['zip'] = $member->zip;
        $elem['active'] = $member->active;
        $member->cur_year == NULL ? $elem['cur_year'] = 'N/A' : $elem['cur_year'] = $member->cur_year;
        $elem['id_mem_types'] = $member->id_mem_types;
        $elem['mem_type'] = $member->mem_type;
        $elem['callsign'] = $member->callsign;
        $elem['license'] = $member->license;
        $elem['cur_year'] = $member->cur_year;
        $elem['hard_news'] = $member->hard_news;
        $elem['spouse_name'] = $member->spouse_name;
        $elem['spouse_call'] = $member->spouse_call;
        $elem['pay_date'] = date('Y-m-d', $member->paym_date);
        $elem['pay_date_file'] = date('Y/m/d', $member->paym_date);
        $elem['silent_date'] = date('Y-m-d', $member->silent_date);
        $elem['parent_primary'] = $member->parent_primary;
        $member->mem_since == NULL ? $elem['mem_since'] = 'N/A' : $elem['mem_since'] = $member->mem_since;
        $member->email == NULL ? $elem['email'] = 'N/A' : $elem['email'] = $member->email;
        $elem['ok_mem_dir'] = $member->ok_mem_dir;
        $cur_yr = date('Y', time());
        $elem['silent_date'] = '';
        $elem['silent_year'] = $member->silent_year;
        $member->usr_type == 98 ? $elem['silent'] = TRUE : $elem['silent'] = FALSE;
      }
      else {
        $elem = NULL;
      }
      return $elem;
    }
}