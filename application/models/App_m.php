<?php

defined('BASEPATH') or exit('No direct script access allowed');

class App_m extends CI_Model
{
   function checkLogin($user,$pass) {
      try {
          $this->db->select("*");
          $this->db->from("users");
          $this->db->where("user", $user)->where("password", $pass);
          $query = $this->db->get();
          return $query->row();
      } catch (Exception $e) {
          return $e->getMessage();
      }
  }
   public function insert($table, $data)
   {
      try {
         $this->db->insert($table, $data);
         return $this->db->insert_id();
      } catch (Exception $ex) {
         return $ex->getMessage();
      }
   }
   public function update($table, $id, $data)
   {
      try {
         $this->db->where($table."_id", $id);
         $this->db->update($table, $data);
         return $this->db->affected_rows();
      } catch (Exception $ex) {
         return $ex->getMessage();
      }
   }
   public function delete($table, $id)
   {
      try {
         $this->db->where($table."_id", $id);
         $this->db->update($table, array('active'=>0));
         return $this->db->affected_rows();
      } catch (Exception $ex) {
         return $ex->getMessage();
      }
   }
}
