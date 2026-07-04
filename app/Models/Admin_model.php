<?php namespace App\Models;

use CodeIgniter\Model;
/**
* This model is for special functions for Master user
*/
class Admin_model extends Model {
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

      public function get_mem_type($type) {
        $types = $this->get_mem_types();
        return $types[$type];
      }

      /**
      * Adds or edits a member.
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
      * Check for duplicate members within 5 years.
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
        $db->close();
        return $retval;
      }

    public function get_mem_cost() {
        $db      = \Config\Database::connect();    
        $builder = $db->table('payactions');
        $builder->where('id_payaction', 1);
        return $builder->get()->getRow()->amount;
      }

      public function man_payment($param) {
        $retval = true;
        
        $pay_data = array();
        $pay_data['id_payaction'] = 1;
    
        $pay_data['id_member'] = $param['id_member'];
        $pay_data['id_entity'] = 2;
        $pay_data['amount'] = floatval($param['amount']);
        $pay_data['paydate'] = strtotime($param['paydate']);
        $pay_data['result'] = 'success';
        $pay_data['val_string'] = 'man-payment';
        $pay_data['flag'] = 0;
        $pay_data['id_transaction'] = 0;
        $pay_data['fee_amt'] = 0;
        
        $donation = floatval($param['donation']);
        $don_rep = floatval($param['don_rep']);
        unset($param['donation']);
        unset($param['don_rep']);
    
        $carrier = 'false';
        $car_val = $param['carrier'];
        unset($param['carrier']);
    
        if($car_val == 'carrier') {
          $carrier = 'true';
        }
    
      // get current year for the member
        $mem_year = 0;
        $db      = \Config\Database::connect();    
        $builder = $db->table('tMembers');
        $builder->where('id_members', $param['id_member']);
          
        $yearToday = strval(date('Y'));
        $monthToday = strval(date('m'));
        
        $test_year = $builder->get()->getRow()->cur_year;
        $mem_year = 0;
        if($test_year < $yearToday) {
          if($monthToday > 9) {
            $mem_year = $yearToday + 1;
          }
          else {
            $mem_year = $yearToday;
          }
        }
        else {
          $mem_year = $test_year + 1;
        }
    
        if($pay_data['amount'] > 0) {
          $builder->resetQuery();
          $builder = $db->table('tMembers');
          $builder->resetQuery();
          $builder->update(array('cur_year' => $mem_year, 'paym_date' => $pay_data['paydate'], 'hard_news' => $carrier), ['id_members' => $pay_data['id_member']]);
          $builder->update(array('cur_year' => $mem_year, 'paym_date' => $pay_data['paydate'], 'hard_news' => $carrier), ['parent_primary' => $pay_data['id_member']]);
          $pay_data['for_year'] = $mem_year;
    
          $builder->resetQuery();
          $builder = $db->table('mem_payments');
          $this->db->transStart();
          $builder->insert($pay_data);
          $this->db->transComplete();
        }     
    
        if($donation >= 5) {
          $pay_data['id_payaction'] = 7;
          $pay_data['amount'] = $donation;
          $builder->resetQuery();
          $builder = $db->table('mem_payments');
          $this->db->transStart();
          $builder->insert($pay_data);
          $this->db->transComplete();
        }
    
        if($don_rep >= 5) {
          $pay_data['id_payaction'] = 5;
          $pay_data['amount'] = $don_rep;
          $builder->resetQuery();
          $builder = $db->table('mem_payments');
          $this->db->transStart();
          $builder->insert($pay_data);
          $this->db->transComplete();
        }
    
        if($car_val == 'carrier') {
          $builder->resetQuery();
          $builder = $db->table('payactions');
          $builder->where('id_payaction', 10);
          $pay_data['amount'] = $builder->get()->getRow()->amount;
    
          $builder->resetQuery();
          $pay_data['id_payaction'] = 10;      
          $builder = $db->table('mem_payments');
          $this->db->transStart();
          $builder->insert($pay_data);
          $this->db->transComplete();
        }
    
        if ($this->db->transStatus() === false) {
          $retval = false;
        }    
        return $retval;
      }
}
