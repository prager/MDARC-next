<?php namespace App\Models;

use CodeIgniter\Model;
/**
* This model is for special functions for Master user
*/
class Admin_model extends Model {
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