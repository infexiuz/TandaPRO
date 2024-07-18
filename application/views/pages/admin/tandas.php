<div class="page">
   <section class="page-header">
      <button type="button" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#modalTanda">+Nueva tanda</button>
      <h1>Tandas</h1>
      <p>Gestiona tandas de forma eficiente: agrega, edita y elimina, organizalas por fecha y proximos pagos.</p>
   </section>
   <div class="card">
      <table class="table" id="tableTanda">
         <thead>
            <tr>
               <th>#</th>
               <th><span class='d-phone-none'>Contacto</span><span class='d-lg-none'>Tanda</span></th>
               <th>Monto</th>
               <th>Duración</th>
               <th>Próximo pago</th>
               <th class="text-center">Opciones</th>
            </tr>
         </thead>
      </table>
   </div>
</div>
<div class="modal fade slide-right" id="modalTanda" role="dialog" aria-labelledby="modalTanda" aria-hidden="true">
   <div class="modal-dialog modal-dialog-center">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title m-0">Formulario de nueva tanda</h5>
            <button type="button" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
         </div>
         <form id="formTanda" class="pd-0">
            <div class="modal-body">
               <div class="row">
                  <div class="form-group col-6 col-md-6">
                     <input type="number" name="numbers" id="numbers" placeholder=" " value="<?=$this->numbers?>" onchange="calculateDate()" required>
                     <label for="numbers" class="ml-1">Números</label>
                  </div>
                  <div class="form-group col-6 col-md-6">
                     <div class="select">
                        <select class="select-text" name="frecuency" id="frecuency" required  onchange="calculateDate()">
                           <option value="7" <?= $this->frecuency == 7 ? 'selected' : ''?>>Semanal</option>
                           <option value="15"<?= $this->frecuency == 15 ? 'selected' : ''?>>Quincenal</option>
                           <option value="30"<?= $this->frecuency == 30 ? 'selected' : ''?>>Mensual</option>
                        </select>
                        <span class="select-highlight"></span>
                        <span class="select-bar"></span>
                        <label class="select-label">Pagos</label>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-6 col-md-6">
                     <input type="hidden" id="today" value="<?=date('Y-m-d')?>">
                     <input type="date" name="start" id="start" placeholder=" " value="<?=date('Y-m-d')?>" onchange="calculateDate()">
                     <label for="start" class="ml-1">Fecha inicio</label>
                  </div>
                  <div class="form-group col-6 col-md-6">
                     <?php $duration = "+".$this->numbers*$this->frecuency." day" ?>
                     <input type="date" name="end" id="end" placeholder=" " value="<?=date('Y-m-d', strtotime($duration))?>" readonly>
                     <label for="end" class="ml-1">Fecha fin</label>
                  </div>
               </div>
               <div class="form-group">
                  <label for="customer_id" class="sr-only">Contacto</label>
                  <select name="customer_id" id="selectCustomer" class="form-control js-example-basic-single" required style="width:100%;line-height:1.5;">
                     <option value="">Seleccionar</option>
                     <?php
                     $clients = $this->db->get('customer')->result_array();
                     foreach ($clients as $client) {
                        echo "<option value='" . $client['customer_id'] . "'>" . $client['name'] . "</option>";
                     }
                     ?>
                  </select>
               </div>
               <div class="form-group">
                  <input type="number" name="amount" id="amount" required placeholder=" ">
                  <label for="amount">Monto</label>
               </div>
               <div class="form-textarea">
                  <textarea placeholder=" " name="description" id="description"></textarea>
                  <label for="address">Escribe detalles de la tanda</label>
               </div>
            </div>
            <div class="modal-footer">
               <span class='switch switch-modal' onclick="switchCheckbox()">
                  <input type='checkbox' id="checkNewTanda">
                  <span class='slider success round'></span>
               </span>
               <?php 
                  $totalDays = $this->frecuency * $this->numbers;
                  $tandaCode= $this->Tanda->makeCodebyId();
                  $nextPayment= $this->Tanda->nextPayment(date('Y-m-d'),date('Y-m-d', strtotime('+'.$totalDays.' day')));
               ?>
               <input type="hidden" id="tandaId" name="tanda_id">
               <input type="hidden" id="methodForm" name="method" value="insert">
               <input type="hidden" id="status" name="status" value="0">
               <input type="hidden" id="code" name="code" value="<?=$tandaCode?>">
               <input type="text" class="d-none" name="nextpayment" value="<?=$nextPayment?>">
               <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
            </div>
         </form>
      </div>
   </div>
</div>
<div class="modal fade slide-up" id="modalPreview" role="dialog" aria-labelledby="modalPreview" aria-hidden="true">
   <div class="modal-dialog modal-dialog-md">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title title-preview">#</h5>
            <button type="button" onclick="history.go(-1)"><i class="fas fa-times"></i></button>
         </div>
         <form id="formPayment" class="pd-0">
            <input type="hidden" name="tanda_id">
            <input type="hidden" name="customer_id">
            <div class="modal-body">
             <div class="row">
               <section class="col-12 col-sm-6">
                  <b class="customer">...</b>
                  <p class="customerInformation m-0 text-light">...</p>
               </section>
               <section class="col-12 col-sm-6">
                  <div class="row">
                     <div class="col-6 col-sm-6"><br>
                        <p class='m-0 text-light'><i class="fal fa-calendar-plus"></i> Inicio</p>
                        <b class='v_start'></b>
                     </div>
                     <div class="col-6 col-sm-6"><br>
                        <p class='m-0 text-light'><i class="fal fa-calendar-minus"></i> Fin</p>
                        <b class='v_end'></b>
                     </div>
                  </div>
               </section>
             </div>
             <br>
             <br>
             <section class="payments">
               <button type="button" class="btn btn-sm float-right btn-primary" id="btnUpdateTanda"><i class="fal fa-pencil-alt"></i>  Editar</button>
               <button type="submit" class="btn btn-sm float-right btn-success" id="btnSubmitUpdate" style='display:none'>+Guardar</button>

                <h2 class="fs-title">Pagos</h2>
                <div class="scrolleable">
                  <table class="table" id="tablePayments">
                  <thead>
                   <tr>
                     <th class="d-none thcheckPayment"></th>
                      <th>#</th>
                      <th class="thViewMobile"><span class="d-lg-none">Detalles</span><span class="d-phone-none">Fecha pago</span></th>
                      <th class="d-phone-none">Monto</th>
                      <th class="d-phone-none">Estado</th>
                   </tr>
                </thead>
                  <tbody id="tbodyPayments">
                  </tbody>
               </table>
               </div>

             </section>
            </div>
      
            <div class="modal-footer justify-content-between">
               <button type="button" class="btn btn-sm" onclick="window.history.back();">Volver</button>
            </div>
         </form>
      </div>
   </div>
</div>
<style>
.select2-container {
   & .select2-selection--single {
      height: 47px;
      border: 1px solid #dadce0;

      & .select2-selection__arrow {
         top: 10px;
         right: 5px;
      }
      & .select2-selection__rendered {
         padding-left: 15px;
         line-height: 44px;
      }
      & .select2-selection__clear {
         margin-right: 35px;
         padding-top: 14px;
      }
   }
   & .select2-results__option--highlighted.select2-results__option--selectable {
      background-color: #1652f0;
   }
   & .select2-search--dropdown > .select2-search__field {
      line-height: 2;
      outline:0
   }
}
#tableTanda td:last-child{
   text-align:center;
}
</style>

<script src="<?php echo base_url(); ?>assets/js/pages/tandas.js"></script>
<script>
   $(document).ready(function () {
      <?php if($this->input->get('id')){ ?>
         $("#selectCustomer").val(<?=$this->input->get('id')?>).trigger('change')
         $("#modalTanda").modal('show')
      <?php } ?>
      <?php if($this->input->get('tanda')){ ?>
         setTimeout(() => {
            $("#tableTanda_filter input").val(<?=$this->input->get('tanda')?>).trigger('input')
         }, 100);
         setTimeout(() => {
            $("#tableTanda tbody tr td .preview").click()
         }, 250);
      <?php } ?>
   });
</script>