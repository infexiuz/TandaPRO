<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Payment_m extends CI_Model
{
   // serverside dataTables
   public function make_query(){
      $order_column = array("code", "name", "amount", "date", null, null);

      $this->db->select('payment.*, tanda.code, tanda.numbers, customer.name, customer.phone, customer.address');
      $this->db->from('payment');
      $this->db->join('customer', 'customer.customer_id = payment.customer_id');
      $this->db->join('tanda', 'tanda.tanda_id = payment.tanda_id');
      $this->db->where('payment.active', 1);

      if ($_POST["search"]["value"] != "") {
         $this->db->like("name", $_POST["search"]["value"]);
         $this->db->or_like("date", $_POST["search"]["value"]);
         $this->db->or_like("code", $_POST["search"]["value"]);
         $this->db->or_like("payment.create_at", $_POST["search"]["value"]);
      }

      if (isset($_POST["order"])) {
         $this->db->order_by($order_column[$_POST["order"]['0']['column']], $_POST['order']['0']['dir']);
      } else {
         $this->db->order_by("code", "DESC");
      }
   }
   public function make_datatables(){
      $this->make_query();

      if ($_POST["length"] != -1) {
         $this->db->limit($_POST["length"], $_POST["start"]);
      }
      $query = $this->db->get()->result_array();
      return $query;
   }
   public function get_all_data(){
      $this->db->select("*");
      $this->db->from("payment");
      $this->db->where('active', 1);
      return $this->db->count_all_results();
   }
   public function get_filtered_data(){
      $this->make_query();
      $query = $this->db->get();
      return $query->num_rows();
   }
   public function getPayments(){
      $total = $this->get_all_data();
      $filtered = $this->get_filtered_data();
      $fetch_data = $this->make_datatables();

      $data = array();
      
      foreach ($fetch_data as $key => $row) {
         $totalPaymentsByTanda = $this->db->select('COUNT(*) as total')->from('payment')->where(array('tanda_id'=>$row['tanda_id']))->get()->row();
         //Attr for data info
         $attrs = "
            data-code='{$row['code']}'
            data-customer='{$row['name']}'
            data-phone='{$row['phone']}'
            data-address='{$row['address']}'
            data-tanda_id='{$row['tanda_id']}'
            data-customer_id='{$row['customer_id']}'
         ";
         //Make badge status
         if($row['status'] == 2){
            $actions = "<span class='badge badge-warning badge-sm'>Revisión</span>";
         }else if($row['status'] == 1){
            $actions = "<span class='badge badge-success badge-sm'>Confirmado</span>";
         }else{
            $actions = "<span class='badge badge-outline-secondary badge-sm'>No confirmado</span>";
         }

         if($row['status'] == 2){
            $status = "warning";
         }else if($row['status'] == 1){
            $status = "success";
         }else{
            $status = "secondary";
         }
         //Make table view mobile
         $amount = "$".$row['amount'];
         $create_at = timeSince(strtotime($row['create_at']));
         $url = base_url('tandas?tanda="'.$row['code'].'"');
         $datePayment = formatYmd($row["date"]);
         $linkToTanda = "<a href='{$url}' class='preview' {$attrs}># {$row['code']}</a>";
         //view phone
         $viewMobile = "
            <div class='d-phone-none'>
               <span><i class='fal fa-user'></i> {$row['name']}</span> <br> 
               <small title='{$row['create_at']}'>Se creó hace {$create_at}</small>
            </div>
            <div class='tdCard {$status}'>
               <div class='d-flex justify-content-between align-items-baseline mb-2 d-lg-none preview' {$attrs}>
                  <span>
                     <b>{$row['name']}</b><br>
                  </span>
               </div>
               <div class='d-flex justify-content-between d-lg-none'>
                  <small class='text-light'>{$datePayment} {$linkToTanda}</small>
                  <b class='text-light text-lg'>{$amount}</b>
               </div>
            </div>
         ";

         $sub_array = array();

         $sub_array[] = $viewMobile;
         $sub_array[] = $amount;
         $sub_array[] = "<span>{$row['date']} <br><small>{$totalPaymentsByTanda->total} de {$row['numbers']} pagos.</small></span>";
         $sub_array[] = "<a href='{$url}' class='preview btn btn-sm' {$attrs}># {$row['code']}</a>";
         $sub_array[] = $actions;

         $data[] = $sub_array;
      }
      $output = array(
         "draw" => intval($_POST['draw']),
         "recordsTotal" => $total,
         "recordsFiltered" => $filtered,
         "data" => $data,
      );
      return $output;
   }
}