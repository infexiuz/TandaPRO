<?php
defined('BASEPATH') or exit('No direct script access allowed');

class App extends CI_Controller
{

   public function __construct()
   {
      parent::__construct();
      if(!$this->session->userdata('user_id')){redirect(base_url());}
		date_default_timezone_set("Mexico/General");
      $this->load->helper('functions');
      $this->load->model('App_m', 'App');
      $this->load->model('User_m', "User");
      $this->load->model('Tanda_m', 'Tanda');
      $this->load->model('Payment_m', 'Payment');
      $this->load->model('Dashboard_m', 'Dashboard');

      $this->percent = 0.10;
      $this->numbers = $this->db->get_where('config', array('name' => 'numbers'))->row_array()['value'];
      $this->frecuency = $this->db->get_where('config', array('name' => 'frecuency'))->row_array()['value'];
   }

   public function index($view = "")
   {
      $page = $view !== "" ? str_replace('-', '/', $view) : "tandas";
      $this->load->view('layout/header');
      $this->load->view('pages/admin/' . $page);
      $this->load->view('layout/footer');
   }
   public function getDataTables($model,$table)
   {
      if ($this->input->is_ajax_request()) {
         $data = $this->$model->$table();
         echo json_encode($data, true);
      } else {
         show_404();
      }
   }
   public function get_by_param($model,$function)
   {
      if ($this->input->is_ajax_request()) {
         $data = $this->$model->$function();
         echo json_encode($data, true);
      } else {
         show_404();
      }
   }
   public function insert_custom($model,$function)
   {
      if ($this->input->is_ajax_request()) {
         echo $this->$model->$function($this->input->post());
      } else {
         show_404();
      }
   }
   public function insert($table)
   {
      if ($this->input->is_ajax_request()) {
         echo $this->App->insert($table, $this->input->post());
      } else {
         show_404();
      }
   }
   public function update($table,$id)
   {
      if ($this->input->is_ajax_request()) {
         echo $this->App->update($table,$id, $this->input->post());
      } else {
         show_404();
      }
   }
   public function delete($table,$id)
   {
      if ($this->input->is_ajax_request()) {
         echo $this->App->delete($table,$id);
      } else {
         show_404();
      }
   }
}
