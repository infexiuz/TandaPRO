<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tanda_m extends CI_Model
{
   public function makeCodebyId(){
      $sql = $this->db->query("SELECT MAX(tanda_id) AS last_id FROM tanda")->row();
      $code = sprintf("#%05d", $sql->last_id + 1);
      return ltrim($code, '#');
   }
   public function createTanda($data){
      try {
         $id = $data['tanda_id'];
         $status = $data['status'];
         $method = $data['method'];

         unset($data['method']);
         
         if($method === 'insert'){
            unset($data['tanda_id']);
            $this->db->insert('tanda', $data);
            $lastId = $this->db->insert_id();
            if($status){$this->createPayment($lastId,$data); }
            return $lastId;
         }else{
            $this->db->where("tanda_id", $id);
            $this->db->update('tanda', $data);
            if($status){ $this->createPayment($id,$data);}
            return $this->db->affected_rows();
         }
      } catch (Exception $ex) {
         return $ex->getMessage();
      }
   }
   public function nextPayment($start,$end){
      $fechaInicio = new DateTime($start);
      $fechaFin = new DateTime($end);
      
      $frecuenciaPagos = 7;
      $fechaActual = new DateTime();
      // Calcular el número total de pagos
      $intervalo = $fechaInicio->diff($fechaFin);
      $numPagos = floor($intervalo->days / $frecuenciaPagos);
      // Calcular la fecha del próximo pago
      $proximaFechaPago = clone $fechaInicio;
      for ($i = 1; $i <= $numPagos; $i++) {
         $proximaFechaPago->add(new DateInterval('P' . $frecuenciaPagos . 'D'));
         if ($proximaFechaPago > $fechaActual) {
            break;
         }
      }
      // Imprimir la próxima fecha de pago
      return $proximaFechaPago->format('Y-m-d');
   }
   public function createPayment($id,$data){
      try {
         $fechaInicio = $data['start'];
         $numeroSemanas = $data['numbers'];
         $intervaloDias = $data['frecuency'];
         
         // Se convierte la fecha de inicio a un objeto DateTime
         $fechaInicioObj = new DateTime($fechaInicio);
         
         // Calcula la última fecha
         $fechaFinalObj = clone $fechaInicioObj;
         $fechaFinalObj->add(new DateInterval('P' . $intervaloDias * $numeroSemanas . 'D'));
         
         // Fecha formateada
         $datePayment = $fechaFinalObj->format('Y-m-d');

         // Monto primer pago
         $amountPayment = $data['amount'] * $this->percent;

         $data = array(
            "tanda_id" => $id,
            "status" => $data['status'],
            "customer_id" =>$data['customer_id'],
            "amount" =>$amountPayment,
            "paid" =>$amountPayment,
            "date" =>$datePayment
         );
         $this->db->insert('payment', $data);
      } catch (\Throwable $th) {
         //throw $th;
      }
   }
   public function createPayments($data){
      try {
         $dates= $data['date'];
         $status= $data['status'];
         $amounts= $data['amount'];
         $customer_id= $data['customer_id'];
         $tanda_id= $data['tanda_id'];

         $success = 0;
         foreach ($status as $key => $row) {
            $exist = $this->db->get_where('payment',array('tanda_id'=>$tanda_id,'customer_id'=>$customer_id,'date'=>$dates[$key]));
            if($row){
               if(!$exist->num_rows() > 0){
                  $data = array(
                     "status" => $row,
                     "tanda_id" => $tanda_id,
                     "customer_id" =>$customer_id,
                     "amount" =>$amounts[$key],
                     "paid" =>$amounts[$key],
                     "date" =>$dates[$key]
                  );
                  $this->db->insert('payment', $data);
                  $success = $tanda_id;
               }else{
                  $this->db->where('tanda_id',$tanda_id);
                  $this->db->where('customer_id',$customer_id);
                  $this->db->where('date',$dates[$key]);
                  $this->db->update('payment',array('status'=> 1));
                  $success = $tanda_id;
               }
            }else{
               if($exist->num_rows() > 0){
                  $this->db->where('tanda_id',$tanda_id);
                  $this->db->where('customer_id',$customer_id);
                  $this->db->where('date',$dates[$key]);
                  $this->db->update('payment',array('status'=> 0));
                  $success = $this->db->affected_rows();
               }
            }
         }
         if($success > 0){
            return $data['tanda_id'];
         }else{
            return $success;
         }
        
      } catch (\Throwable $th) {
         //throw $th;
      }
   }
   public function generatePayments(){
      $id = $this->input->post('id');
      $tanda = $this->db->select('start,numbers,frecuency,amount,customer_id')->get_where('tanda', array('tanda_id'=> $id))->row();
      $paymentsRows = $this->db->select('date')->get_where('payment', array('tanda_id'=> $id))->result_array();
      
      $fechaInicio = $tanda->start;
      $intervaloDias = $tanda->frecuency;
      $numeroSemanas = $tanda->numbers;
      
      // Genero el monto a pagar
      $amountPayment =  $tanda->amount * $this->percent;
      
      // Convierte la fecha de inicio a un objeto DateTime
      $fechaInicioObj = new DateTime($fechaInicio);
      
      // Genera las fechas intermedias
      $payments="";
      for ($i = 1; $i <= $numeroSemanas; $i++) {
          // Clona la fecha de inicio y le agrega el intervalo de días
          $fechaIntermedia = clone $fechaInicioObj;
          $fechaIntermedia->add(new DateInterval('P' . $intervaloDias * $i . 'D'));
      
          // Imprime la fecha formateada
          $fecha_pago = $fechaIntermedia->format('Y-m-d');
          $datePayment = formatYmd($fecha_pago);
          $findPaymentDate = $this->findPaymentByDate($fecha_pago,$paymentsRows) ? 1 : 0;
          $getStatus = $findPaymentDate ? $this->getPaymentStatus($id,$tanda->customer_id,$fecha_pago) : 0;
          $checked= $getStatus ? 'checked' : '';

          if($getStatus == 2){
            $status= "<span class='badge badge-warning'>Revisión</span>";
          }else if($getStatus == 1){
            $status= "<span class='badge badge-success'>Pagado</span>";
          }else{
            $status= "<span class='badge'>No pagado</span>";
          }

          $viewMobile = "
          <span class='d-phone-none'>{$datePayment}</span>
          <div class='tdCard m-0 border-bottom'>
            <div class='d-flex justify-content-between d-lg-none'>
               <span>
                  <b>$ {$amountPayment}</b> <br>
                  <small>{$datePayment}</small>
               </span>
               <span> <br>{$status}</span>
            </div>
          </div>
         ";
          $payments .="
            <tr class='pointer'>
               <td class='d-none checkPayment pd-t'>
                  <input type='checkbox' {$checked}>
                  <input type='hidden' name='status[]' value='{$getStatus}' class='statusPayment'>
                  <input type='hidden' name='date[]' value='{$fecha_pago}'>
                  <input type='hidden' name='amount[]' value='{$amountPayment}'>
               </td>
               <td class='pd-t'>{$i}</td>
               <td>{$viewMobile}</td>
               <td class='d-phone-none'>$ {$amountPayment}</td>
               <td class='d-phone-none'>{$status}</td>
            </tr>
          ";
      }
      return $payments;
   }
// serverside dataTables
   public function make_query(){
      $order_column = array("code", "name", "amount", null, null, null);

      $this->db->select('tanda.*, customer.name, customer.phone, customer.address');
      $this->db->from('tanda');
      $this->db->join('customer', 'customer.customer_id = tanda.customer_id');
      $this->db->where('tanda.active', 1);

      if ($_POST["search"]["value"] != "") {
         $this->db->like("name", $_POST["search"]["value"]);
         $this->db->or_like("amount", $_POST["search"]["value"]);
         $this->db->or_like("start", $_POST["search"]["value"]);
         $this->db->or_like("code", $_POST["search"]["value"]);
         $this->db->or_like("tanda.create_at", $_POST["search"]["value"]);
      }

      if (isset($_POST["order"])) {
         $this->db->order_by($order_column[$_POST["order"]['0']['column']], $_POST['order']['0']['dir']);
      } else {
         $this->db->order_by("tanda_id", "DESC");
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
      $this->db->from("tanda");
      $this->db->where('active', 1);
      return $this->db->count_all_results();
   }
   public function get_filtered_data(){
      $this->make_query();
      $query = $this->db->get();
      return $query->num_rows();
   }
   public function getTandas(){
      $total = $this->get_all_data();
      $filtered = $this->get_filtered_data();
      $fetch_data = $this->make_datatables();

      $data = array();
      
      foreach ($fetch_data as $key => $row) {
         //If date end is today, finish tanda and return status 
         if(date("Y-m-d") == $row['end'] && $row['status']== 1){
            $this->db->update('tanda', array('status' => 2, 'nextpayment'=>''));
            $this->db->where('tanda_id',$row['tanda_id']);
            $status = 2;
         }else{
            $status = $row['status'];
         }
         //Attr for data info
         $attrs = "
            data-code='{$row['code']}'
            data-customer='{$row['name']}'
            data-status='{$row['status']}'
            data-phone='{$row['phone']}'
            data-address='{$row['address']}'
            data-tanda_id='{$row['tanda_id']}'
            data-frecuency='{$row['frecuency']}'
            data-numbers='{$row['numbers']}'
            data-customer_id='{$row['customer_id']}'
            data-end='{$row['end']}'
            data-start='{$row['start']}'
            data-amount='{$row['amount']}'
            data-description='{$row['description']}'
         ";
         //Make badge status
         if($status == 1 || $status == 0){
            $datas = $status == 0 ? $attrs : "" ;
            $checked = $status == 1 ? "checked disabled" : "";
            $clickeable = $status == 0 ? "confirm-tanda'".$datas : "";
            
            $actions = "<label class='switch {$clickeable}' >
               <input type='checkbox' {$checked}>
               <span class='slider success round'></span>
            </label>
            ";
         }else{
            $actions = "<span class='btn btn-sm'>Finalizada</span>";
         }
         //Make table view mobile
         $create_at = timeSince(strtotime($row['create_at']));
         $viewMobile = "
            <span class='d-phone-none'>{$row['name']}</span>
            <div class='tdCard border-bottom mb-0'>
               <div class='d-flex justify-content-between d-lg-none preview' {$attrs}>
                  <span>
                     <small class='text-light'>$ {$row['amount']}</small><br>
                     <b>{$row['name']}</b>
                  </span>
                  <b class='text-light'># {$row['code']}</b>
               </div>
               <div class='d-flex justify-content-between d-lg-none'>
                  <small class='text-light'>Prox. pago {$row['nextpayment']}</small>{$actions}
               </div>
            </div>
         ";
         $nextPayment = formatYmd($row['nextpayment']);
         $startDate = formatYmd($row['start']);
         $endDate = formatYmd($row['end']);

         $sub_array = array();

         $sub_array[] = "<a href='' class='preview btn btn-sm' {$attrs}># {$row['code']}</a> <br> <small>Se creó hace {$create_at}</small>";
         $sub_array[] = $viewMobile;
         $sub_array[] = "$ {$row['amount']}";
         $sub_array[] = "<span>{$startDate}</span><br><span>{$endDate}</span>";
         $sub_array[] = $nextPayment;
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
//functions helper tanda
   public function calculateDate(){
      $duration = "+".$this->input->post('numbers')*$this->input->post('frecuency')." day";
      $start = $this->input->post('start');
      $end = new DateTime($start);
      $end->modify($duration);
      $end->format('Y-m-d');
      $end_ = $end->format('Y-m-d');

      return array(
         'end' =>  $end_,
         'next' => $this->nextPayment($start, $end_)
      );
   }
   public function convertAmountToText($amount) {
      $units = ['', 'Uno', 'Dos', 'Tres', 'Cuatro', 'Cinco', 'Seis', 'Siete', 'Ocho', 'Nueve'];
      $teens = ['', 'Once', 'Doce', 'Trece', 'Catorce', 'Quince', 'Dieciséis', 'Diecisiete', 'Dieciocho', 'Diecinueve'];
      $tens = ['', 'Diez', 'Veinte', 'Treinta', 'Cuarenta', 'Cincuenta', 'Sesenta', 'Setenta', 'Ochenta', 'Noventa'];
      $thousands = ['', 'Mil', 'Millón', 'Mil Millones', 'Billón'];

      $amount = number_format($amount, 2, '.', '');
      list($dollars, $cents) = explode('.', $amount);
  
      $dollarsText = '';
  
      if ($dollars == 0) {
          $dollarsText = 'Cero Pesos';
      } else {
          $groups = explode(',', strrev($dollars));
          foreach ($groups as $key => $group) {
              $group = (int)strrev($group);
  
              if ($group >= 10 && $group <= 19) {
                  $dollarsText .= $teens[$group - 10] . ' ';
              } elseif ($group > 0) {
                  $dollarsText .= $units[$group % 10] . ' ';
                  if ($group >= 10) {
                      $dollarsText .= $tens[($group / 10) % 10] . ' ';
                  }
              }
              if ($group > 0) {
                  $dollarsText .= $thousands[$key] . ' ';
              }
          }
  
          $dollarsText = rtrim($dollarsText);
      }
      $centsText = '';
      if ($cents > 0) {
          $centsText = ' and ' . ($cents < 10 ? $units[$cents] : $tens[($cents / 10) % 10] . ' ' . $units[$cents % 10]) . ' Cents';
      }
      return ucfirst($dollarsText) . $centsText;
   }
   public function getPaymentStatus($tanda_id,$customer,$date){
      $paymentsRow = $this->db->select('status')->get_where('payment', array('tanda_id'=> $tanda_id,'customer_id'=> $customer,'date'=> $date))->row();
      return $paymentsRow->status;
   }
   public function findPaymentByDate($date, $array){
      if(empty($array)){
         return false;
      }
      // Convertir la fecha a formato de fecha
      $date = strtotime($date);
    // Convertir todas las fechas del array a formato de fecha
      $dates = array_map(function ($item) {
         return strtotime($item['date']);
      }, $array);
      // Verificar si la fecha está en el array
      if (in_array($date, $dates)) {
        return true;
      } else {
         return false;
      }
   }
}
