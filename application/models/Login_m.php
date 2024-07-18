<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_m extends CI_Model 
{

    function login_check($user,$pass) {
        try {
            $this->db->select("*");
            $this->db->from("user");
            $this->db->where("user", $user)->where("password", $pass);
            $query = $this->db->get();
            return $query->row();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function update_last_login($ID_USUARIO) {
        try {

            $data = array(
                'ULTIMO_LOGIN_USUARIO' => date('Y-m-d H:i:s')
            );

            $this->db->where('ID_USUARIO', $ID_USUARIO);
            $this->db->update('usuario', $data);
            return $this->db->affected_rows();
        } catch (Exception $ex) {

            return $ex->getMessage();
        }
    }
   function get_assignmentsById($id)
    {
       try {
          $this->db->select("asign.* , services.name");
          $this->db->from('asign');
          $this->db->where('date', date('Y-m-d'));
          $this->db->where('id_user',$id);
          $this->db->join('services', 'services.id_service = asign.id_service');
         $query = $this->db->get();
          return $query->result_array();
    
       } catch (Exception $ex) {
          return $ex->getMessage();
       }
    }
    function get_employees()
    {
       try {
          $this->db->select("*");
          $this->db->from('users');
          $this->db->where('type_user', 2);
          $this->db->where('status', 1);
          $this->db->order_by('id_user', 'DESC');
          $employees = $this->db->get()->result_array();
         
         foreach ($employees as $key => $employee) {
            $assignments = $this->get_assignmentsById($employee['id_user']);
            $employees[$key]['assignments'] = $assignments;
         }
         //$this->session->set_userdata('Employees', $employees);
        
          return $employees;
       } catch (Exception $ex) {
          return $ex->getMessage();
       }
    }
  
    function get_services()
    {
       try {
          $this->db->select("*");
          $this->db->from('services');
          $query = $this->db->get();
          return $query->result_array();
       } catch (Exception $ex) {
          return $ex->getMessage();
       }
    }
}
/* End of file Orders.php */