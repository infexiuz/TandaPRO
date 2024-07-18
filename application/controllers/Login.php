<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{

   public function __construct()
   {
      parent::__construct();

      $this->load->model('App_m', 'App');
		date_default_timezone_set("Mexico/General");
   }

   public function index()
   {
      if ($this->session->userdata('user_id') === null) {
         $this->load->view('layout/login');
      } else {
         redirect('tandas');
      }
   }

   public function checkLogin()
   {
      if (!empty($this->input->post('user')) && !empty($this->input->post('password'))) :

         $user = $this->input->post('user');
         $pass = $this->input->post('password');
         $login = $this->App->checkLogin($user,$pass);
         $params = array(
            "numbers" => $this->db->get_where('config', array('name' => 'numbers'))->row_array()['value'],
            "frecuency" => $this->db->get_where('config', array('name' => 'frecuency'))->row_array()['value']
         );
         /*  echo '<pre>' . var_export($UserLogin, true) . '</pre>'; */

         if ($login) :
            $datosSesion = array(
               'user' => $login->user,
               'role' => $login->role,
               'username' => $login->username,
               'user_id' => $login->user_id,
               'params' => $params
            );
            
            $this->session->set_userdata($datosSesion);
            redirect('tandas');
         else :
            redirect('Login');
         endif;
      else :
         redirect('error');
      endif;
 
   }

   public function logout()
   {
      $this->session->sess_destroy();
      redirect(base_url());
   }
}

/* End of file welcome.php */
