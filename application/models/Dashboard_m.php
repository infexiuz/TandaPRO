<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_m extends CI_Model
{
   public function getStats(){
      $tanda = $this->db->where('active', 1)->get('tanda')->num_rows();
      $customers = $this->db->where('active', 1)->get('customer')->num_rows();
      $payments = $this->db->where(array('status'=>1,"active"=>1))->select_sum('paid', 'total')->get('payment')->row();
      $today = $this->db->where(array('status'=>1,"active"=>1,"DATE(create_at)"=>date("Y-m-d")))->select_sum('paid', 'total')->get('payment')->row();
      
      return array(
         "tandas" => $tanda,
         "total" => $payments->total <= 0 ? "0.00" : $payments->total ,
         "customers" => $customers,
         "today" => $today->total <= 0 ? "0.00" : $today->total ,
      );
   }
     
}
