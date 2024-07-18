<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_m extends CI_Model
{

   public function get_clients()
   {
      try {
         $this->db->select("*");
         $this->db->from('clients');
         $this->db->where('status', 1);
         $this->db->order_by('id_client', 'DESC');
         $query = $this->db->get();
         return $query->result_array();
      } catch (Exception $ex) {
         return $ex->getMessage();
      }
   }
   public function get_users()
   {
      try {
         $this->db->select("*");
         $this->db->from('users');
         $this->db->where('type_user', 2);
         $this->db->where('status', 1);
         $this->db->order_by('id_user', 'DESC');
         $query = $this->db->get();
         return $query->result_array();
      } catch (Exception $ex) {
         return $ex->getMessage();
      }
   }

   public function new_client()
   {
      try {
         $data = array(
            'status' => 1,
            'name' => $this->input->post('name'),
            'last_name' => $this->input->post('last_name'),
            'phone' => $this->input->post('phone'),
            'address' => $this->input->post('address'),
            'date_start' => date('Y-m-d'),
            'image' => "assets/images/faces/" . $this->input->post('img'),
         );
         $this->db->insert('clients', $data);

         return $this->db->insert_id();

      } catch (Exception $ex) {
         return $ex->getMessage();
      }
   }
   public function edit_client($id)
   {
      try {
         $data = array(
            'name' => $this->input->post('name'),
            'last_name' => $this->input->post('last_name'),
            'phone' => $this->input->post('phone'),
            'address' => $this->input->post('address'),
            'image' => "assets/images/faces/" . $this->input->post('img'),
         );

         $this->db->where('id_client', $id);
         $this->db->update('clients', $data);

         return $this->db->affected_rows();

      } catch (Exception $ex) {
         return $ex->getMessage();
      }
   }
   public function delete_client($id)
   {
      try {
         $data = array(
            'status' => 0,
         );

         $this->db->where('id_client', $id);
         $this->db->update('clients', $data);

         return $this->db->affected_rows();

      } catch (Exception $ex) {
         return $ex->getMessage();
      }
   }
   public function new_user()
   {
      try {
         $data = array(
            'status' => 1,
            'type_user' => 2,
            'name' => $this->input->post('name'),
            'last_name' => $this->input->post('last_name'),
            'phone' => $this->input->post('phone'),
            'address' => $this->input->post('address'),
            'date_start' => date('Y-m-d'),
         );
         $this->db->insert('users', $data);

         return $this->db->insert_id();

      } catch (Exception $ex) {
         return $ex->getMessage();
      }
   }
// SERVERSIDE CUSTOMERS //
   public function make_query()
   {
      $order_column = array(null, "name", "phone", "address", null, "create_at", null);

      $this->db->select('*');
      $this->db->from('customer');
      $this->db->where('active', 1);

      if ($_POST["search"]["value"] != "") {
         $this->db->like("name", $_POST["search"]["value"]);
         $this->db->or_like("phone", $_POST["search"]["value"]);
         $this->db->or_like("address", $_POST["search"]["value"]);
      }

      if (isset($_POST["order"])) {
         $this->db->order_by($order_column[$_POST["order"]['0']['column']], $_POST['order']['0']['dir']);
      } else {
         $this->db->order_by("customer_id", "DESC");
      }
   }
   public function make_datatables()
   {
      $this->make_query();

      if ($_POST["length"] != -1) {
         $this->db->limit($_POST["length"], $_POST["start"]);
      }
      $query = $this->db->get()->result_array();
      return $query;
   }
   public function get_all_data()
   {
      $this->db->select("*");
      $this->db->from("customer");
      $this->db->where('active', 1);
      return $this->db->count_all_results();
   }
   public function get_filtered_data()
   {
      $this->make_query();
      $query = $this->db->get();
      return $query->num_rows();
   }
   public function getCustomers()
   {
      $fetch_data = $this->make_datatables();
      $total = $this->get_all_data();
      $filtered = $this->get_filtered_data();
      $data = array();
      $base_url = base_url();
      foreach ($fetch_data as $key => $row) {

         $actions = "<div>
            <a href='{$base_url}tandas?id={$row['customer_id']}'><i class='fa fa-plus fa-x mr-1'></i></a>
            <i class='fa fa-edit fa-x mr-1 update-customer' aria-hidden='true' data-toggle='tooltip' title='Editar' 
               data-id='{$row['customer_id']}'
               data-name='{$row['name']}'
               data-phone='{$row['phone']}'
               data-address='{$row['address']}'
            >
            </i>
            <i class='fa fa-trash fa-x delete-customer' aria-hidden='true' data-toggle='tooltip' title='Eliminar' data-id='{$row['customer_id']}'></i></div>
         ";

         $allInfo = "
         <span class='d-phone-none'>{$row['name']}</span>
         <div class='tdCard border-bottom'>
         <div class='d-flex justify-content-between mb-2 d-lg-none'>
            <b>{$row['name']}</b><span class='text-light d-inline-flex align-items-top'><i class='fab fa-whatsapp mt-1'></i> {$row['phone']}</span>
         </div>
         <div class='d-flex justify-content-between d-lg-none'>
            <small class='ellipsis text-light'><i class='fas fa-map-marker-alt'></i> {$row['address']}</small>{$actions}
         </div>
         </div>
         ";

         $sub_array = array();

         $sub_array[] = $key+1;
         $sub_array[] = $allInfo;
         $sub_array[] = $row['phone'];
         $sub_array[] = $row['address'];
         $sub_array[] = $row['create_at'];
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

/* End of file Users.php */
