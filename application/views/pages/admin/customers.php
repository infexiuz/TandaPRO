<div class="page">
   <section class="page-header">
      <button type="button" class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#modalCustomer">+Nuevo</button>
      <h1>Contactos</h1>
      <p>Gestiona clientes de forma eficiente: agrega, edita y elimina perfiles, centralizando información clave.</p>
   </section>
   <div class="card ">
      <table class="table hoverable" id="tableCustomer">
         <thead>
            <tr>
               <th>#</th>
               <th>Contacto</th>
               <th>Teléfono</th>
               <th>Direccion</th>
               <th>Creado</th>
               <th>Opciones</th>
            </tr>
         </thead>
      </table>
   </div>
</div>
<div class="modal fade slide-right" tabindex="-1" id="modalCustomer">
   <div class="modal-dialog modal-dialog-center">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Nuevo Contacto</h5>
            <button type="button" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
         </div>
         <form id="formCustomer" class="pd-0">
         <div class="modal-body">
            <div class="form-group">
               <input type="text" name="name" required placeholder=" ">
               <label for="name">Nombre</label>
            </div>
            <div class="form-group">
               <input type="number" name="phone" required placeholder=" ">
               <label for="phone">Teléfono</label>
            </div>
            <div class="form-textarea">
               <textarea placeholder=" " required name="address"></textarea>
               <label for="address">Escribe el domicilio del contacto</label>
            </div>
         </div>
         <div class="modal-footer">
            <input type="text" class='d-none' id="addmore" value="false">
            <input type="hidden" id="methodForm" value="insert">
            <input type="hidden" id="customerId">
            <button type="button" class="btn btn-sm btn-primary" id="btnAddMore" onclick="addMore('#formCustomer', '#btnAddMore')">+Guardar sin cerrar</button>
            <button type="submit" class="btn btn-sm btn-success">+Guardar</button>
         </div>
         </form>
      </div>
   </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/pages/users.js"></script>
