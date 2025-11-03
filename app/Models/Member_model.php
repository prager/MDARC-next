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
}