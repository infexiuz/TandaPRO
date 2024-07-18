

<div class="page-heading">
   <div class="page-title">
      <div class="row">
         <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Empleadas</h3>
            <p class="text-subtitle text-muted">Toda la información de tus empleados</p>
         </div>
         <div class="col-12 col-md-6 order-md-2 order-first">
            <div class="row">
               <div class="col-12">
                  <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Empleados</li>
                     </ol>
                  </nav>
               </div>
               <div class="col-12 text-end">
                  <button type="button" class="btn btn-primary block" data-bs-toggle="modal" data-bs-backdrop="false" data-bs-target="#modal-new-employee">
                     Registrar empleado
                  </button>
               </div>
            </div>
         </div>
      </div>
   </div>
   <section class="section"><br>
      <div class="row" id="users_content">

      </div>
     

   </section>

</div>
<div class="modal fade text-left" id="modal-new-employee" tabindex="-1" role="dialog" aria-labelledby="modal-new-client" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
         <div class="modal-header bg-primary">
            <h4 class="modal-title white">Nuevo empleado
            </h4>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
               <i data-feather="x"></i>
            </button>
         </div>
         <form class="form form-vertical" id="formNewEmployee">
            <div class="modal-body">
               <div class="form-body">
                  <div class="row">
                     <div class="col-12 col-lg-6">
                        <div class="form-group has-icon-left">
                           <label for="name">Nombre(s)</label>
                           <div class="position-relative">
                              <input type="text" class="form-control required" data-title="Nombre(s)" placeholder="Escribe el nombre.." name="name">
                              <div class="form-control-icon">
                                 <i class="bi bi-person"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-12 col-lg-6">
                        <div class="form-group has-icon-left">
                           <label for="last_name">Apellidos</label>
                           <div class="position-relative">
                              <input type="text" class="form-control required" data-title="Apelleido(s)" placeholder="Escribe los apellidos.." name="last_name">
                              <div class="form-control-icon">
                                 <i class="bi bi-person"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-6 col-lg-6">
                        <div class="form-group has-icon-left">
                           <label for="mobile-id-icon">Teléfono</label>
                           <div class="position-relative">
                              <input type="text" class="form-control required" data-title="Teléfono" placeholder="Introduce el teléfono.." name="phone">
                              <div class="form-control-icon">
                                 <i class="bi bi-phone"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-6 col-lg-6">
                        <div class="form-group has-icon-left">
                           <label for="mobile-id-icon">Pago x pieza</label>
                           <div class="position-relative">
                              <input type="text" class="form-control required" data-title="Pago" placeholder="0.00" name="commission">
                              <div class="form-control-icon">
                                 <i class="fas fa-hand-holding-usd"></i>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-12 col-lg-12">
                        <div class="form-group has-icon-left">
                           <label for="password-id-icon">Domicilio</label>
                           <div class="position-relative">
                              <input type="text" class="form-control" placeholder="Introduce el domicilio.." name="address">
                              <div class="form-control-icon">
                                 <i class="bi bi-map"></i>
                              </div>
                           </div>
                        </div>
                     </div>

                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="reset" class="btn btn-light-secondary me-1 mb-1" data-bs-dismiss="modal">Cerrar</button>
               <button type="submit" id="btnSubmitEmployee" class="btn btn-primary me-1 mb-1">Registrar</button>
            </div>
         </form>
      </div>
   </div>

</div>
<span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; open</span>

<script>
   function openNav() {
      document.getElementById("mySidenav").style.width = "500px";
   }

   function closeNav() {
      document.getElementById("mySidenav").style.width = "0";
   }
</script>
<script src="<?php echo base_url(); ?>assets/js/pages/users.js"></script>