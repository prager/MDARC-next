<?php namespace App\Models;
// Updated 3

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
          $param['ok_mem_dir'] = 'TRUE'; //sets the dir listing
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
      public function get_payments($dates) {

        $date_from = strtotime($dates['date_from']);

        $date_to = strtotime('+1 days', strtotime($dates['date_to']));
        $date_to = $date_to - 1;

        $db      = \Config\Database::connect();
        $builder = $db->table('mem_payments');
        $builder->where('result', 'success');
        $builder->where('paydate >=', $date_from);
        $builder->where('paydate <=', $date_to);
        $builder->orderBy('id_payment', 'ASC');

        $res = $builder->get()->getResult();

        //echo '<br><br><br><br>cnt: ' . count($res);
        $retarr = array();
        $retarr['payments'] = array();
        $fname = '';
        $lname = '';
        $total = 0;

        // save data in .csv file
        $data_str = "ID-Payments, ID-Member, ID-Transaction, First-Name, Last-Name, Payment-Date, Pay-Action, Method, Amount, Fee, Note\n";
        $fee_total_amt = 0;
        $got_trans = false;
        $id_trans = -1;
        foreach($res as $payment) {
          $payment_flag = (int) $payment->flag;
          $trans_amt = 0;
          $fee_total = 0;
          if($payment->id_transaction != 0) {
            if($id_trans != $payment->id_transaction) {
              $id_trans = $payment->id_transaction;
              $builder->resetQuery();
              $builder = $db->table('transactions');
              $builder->resetQuery();
              $builder->where('id_transactions', $id_trans);
              $trans_amt = $builder->get()->getRow()->fee_amt;
              $fee_total += $trans_amt;
            }
          }

        // get member
          $id_mem = $payment->id_member;
          if($id_mem != 0) {
            $builder->resetQuery();
            $builder = $db->table('tMembers');
            $builder->resetQuery();
            $builder->where('id_members', $id_mem);
            $mem_obj = $builder->get()->getRow();
            if($mem_obj != null) {
              $fname = $mem_obj->fname;
              $lname = $mem_obj->lname;
            }
            else {
              $lname = 'anonymous';
            }
          }
          else {
            $lname = 'anonymous';
          }
        // get payaction
          $builder->resetQuery();
          $builder = $db->table('payactions');
          $builder->resetQuery();
          $builder->where('id_payaction', $payment->id_payaction);
          $payaction = $builder->get()->getRow()->description;
          //if($payment->id_payaction == 7 || $payment->id_payaction == 5) $payaction = substr($payaction, 0, 8);
          $pay_amt = '$' . number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $payment->amount)), 2);
          $mode = 'Stripe';
          if($payment->val_string == 'man-payment') {
            $mode = 'manual';
          }

          $rep_amt = 0;
          if($payment_flag === 0) $rep_amt = $payment->amount;

          $data_str .= strval($payment->id_payment).",".strval($payment->id_member).",".strval($payment->id_transaction).",".$fname.",".$lname.",".date("Y-m-d", $payment->paydate).",".$payaction.",".$mode.",".$rep_amt."," .$trans_amt. ",".$payment->note ."\n";

          $fee_total_amt += $trans_amt;

          if($got_trans) {

          }
          $rec_arr = array(
            'id_payments' => $payment->id_payment,
            'id_trans' => $payment->id_transaction,
            'id_member' => $id_mem,
            'fname' => $fname,
            'lname' => $lname,
            'payaction' => $payaction,
            'amount' => $pay_amt,
            'paydate' => $payment->paydate,
            'mode' => $mode,
            'fee' => $trans_amt,
            'flag' => $payment_flag,
            'note' => $payment->note
          );
          if($payment_flag === 0) $total += $payment->amount;
          array_push($retarr['payments'], $rec_arr);
        }
        // get transactions
        $this->get_fees($date_from, $date_to);

        $db->close();
        $this->write_report_file('paym_rep.csv', $data_str);
        $total_fomatted = "$" . number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $total)), 2);

        $retarr['dates'] = $dates;
        $retarr['total'] = $total_fomatted;
        $retarr['total_fee'] = "$" . number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $fee_total_amt)), 2);

        return $retarr;
      }
      private function get_fees($date_from, $date_to) {
        $db      = \Config\Database::connect();
        $builder = $db->table('transactions');
        $builder->where('date >=', $date_from);
        $builder->where('date <=', $date_to);
        $res = $builder->get()->getResult();
        $data_str = "ID-Transaction, ID-Member, First-Name, Last-Name, Payment-Date, Total, Fee\n";

        foreach($res as $trans) {
          $id_mem = $trans->id_member;
          $fname = "";
          $lname = "";
          if($id_mem != 0) {
            $builder->resetQuery();
            $builder = $db->table('tMembers');
            $builder->resetQuery();
            $builder->where('id_members', $id_mem);
            $mem_obj = $builder->get()->getRow();
            if($mem_obj != null) {
              $fname = $mem_obj->fname;
              $lname = $mem_obj->lname;
            }
            else {
              $lname = 'anonymous';
            }
          }
          else {
            $lname = 'anonymous';
          }
          $data_str .= strval($trans->id_transactions).",".strval($trans->id_member).",".$fname.",".$lname.",".date("Y-m-d", $trans->date).",".strval($trans->total_amt).",".strval($trans->fee_amt) ."\n";
        }
        $db->close();
        $this->write_report_file('transactions.csv', $data_str);
      }

      private function write_report_file(string $filename, string $contents): void {
        $directory = WRITEPATH . 'exports' . DIRECTORY_SEPARATOR;
        if(!is_dir($directory) && !mkdir($directory, 0775, TRUE) && !is_dir($directory)) {
          throw new \RuntimeException('Unable to create the report export directory.');
        }

        if(file_put_contents($directory . $filename, $contents, LOCK_EX) === FALSE) {
          throw new \RuntimeException('Unable to write report file: ' . $filename);
        }
      }
      public function update_payment($param) {
        $db      = \Config\Database::connect();
        $builder = $db->table('mem_payments');
        $builder->where('id_payment', $param['id_payment']);
        $builder->update(array('flag' => $param['flag'], 'note' => $param['note']), ['id_payment' => $param['id_payment']]);
        $db->close();
      }
       /**
  * Gets data for displaying master_view
  * @return string array $retval
  */
  public function get_users_data() {
    $retarr = array();
    $db      = \Config\Database::connect();
    $builder = $db->table('users');
    $res = $builder->get()->getResult();

//user types are from admin_types table
    $usr_types = $this->get_user_types();
    $users = array();
    foreach($res as $user) {

      if($user->type_code != 99) {
        $usr_arr = array(
          'id' => $user->id_user,
          'id_type_code' => $user->type_code,
          'id_user_type' => $user->id_user_type,
          'fname' => $user->fname,
          'lname' => $user->lname,
          'callsign' => $user->callsign,
          'active' => $user->active,
          'authorized' => $user->authorized,
          'usr_types' => $usr_types,
          'street' =>$user->street,
          'city' => $user->city,
          'state' => $user->state_cd,
          'zip' => $user->zip_cd,
          'phone' => $user->phone,
          'email' => $user->email
        );
        $user->username != NULL ? $usr_arr['username'] = $user->username : $usr_arr['username'] = 'Not Set!';
        $user->id_user_type != 0 ? $usr_arr['type_desc'] = $usr_types[$user->id_user_type] : $usr_arr['type_desc'] = $usr_types[$user->id_user_type];
        $user->comment == NULL ? $usr_arr['comment'] = '' : $usr_arr['comment'] = $user->comment;
        $user->facebook == NULL ? $usr_arr['facebook'] = '' : $usr_arr['facebook'] = $user->facebook;
        $user->twitter == NULL ? $usr_arr['twitter'] = '' : $usr_arr['twitter'] = $user->twitter;
        $user->linkedin == NULL ? $usr_arr['linkedin'] = '' : $usr_arr['linkedin'] = $user->linkedin;
        $user->comment == NULL ? $usr_arr['comment'] = '' : $usr_arr['comment'] = $user->comment;
        $user->city == NULL ? $usr_arr['city'] = '' : $usr_arr['city'] = $user->city;
        $user->zip_cd == NULL ? $usr_arr['zip'] = '' : $usr_arr['zip'] = $user->zip_cd;
        array_push($users, $usr_arr);
      }

    }
    $db->close();
    $retarr['usr_types'] = $usr_types;
    $retarr['users'] = $users;
    return $retarr;
  }

  /**
  * Gets users and puts them into csv file
  */
  public function put_users() {
    $db      = \Config\Database::connect();
    $builder = $db->table('users');
    $res = $builder->get()->getResult();
    $users_str = "id,type code,role,username,authorized,active\n";
    foreach($res as $user) {
      $users_str .= $user->id_user.",".$user->type_code.",".$user->role.",".$user->username.",".$user->authorized.",".$user->active."\n";
    }
    file_put_contents('files/users.csv', $users_str);
    $db->close();
  }

  /**
  * Gets user types and puts them into csv file
  */
  public function put_user_types() {
    $db      = \Config\Database::connect();
    $builder = $db->table('user_types');
    $res = $builder->get()->getResult();
    $types_str = "id,type code,description,code string,controller\n";
    foreach($res as $type) {
      $types_str .= $type->id_user_types.",".$type->type_code.",".$type->description.",".$type->code_str.",".$type->controller."\n";
    }
    file_put_contents('files/user_types.csv', $types_str);
    $db->close();
  }
}
