<?php
$stats=$this->Dashboard->getStats();
?>
<div class="page">
   <section class="page-header">
      <h1>Panel de Control</h1>
      <p>Este es tu panel de control, aquí puedes ver un analisis general de toda la información del sistema.</p>
   </section>
   <!--   <div id="map" style="width: 100%; height: 400px;"></div> -->
   <div class="row">
      <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
         <div class="card">
            <div class="d-flex align-items-center">
               <section>
                  <i class="fal fa-users fa-2x mr-3"></i>
               </section>
               <section>
                  <h3 class="m-0">Contactos</h2>
                  <span class="totalCustomers"><?=$stats["customers"]?></span>
               </section>
            </div>
         </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
         <div class="card">
            <div class="d-flex align-items-center">
               <section>
                  <i class="fal fa-hands-usd fa-2x mr-3"></i>
               </section>
               <section>
                  <h3 class="m-0">Tandas</h2>
                  <span class="totalTandas"><?=$stats["tandas"]?></span>
               </section>
            </div>
         </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
         <div class="card">
            <div class="d-flex align-items-center">
               <section>
                  <i class="fal fa-hand-holding-usd fa-2x mr-3"></i>
               </section>
               <section>
                  <h3 class="m-0">Pagos</h2>
                  <span class="totalPayments"><?=$stats["total"]?></span>
               </section>
            </div>
         </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
         <div class="card">
            <div class="d-flex align-items-center">
               <section>
                  <i class="fal fa-usd-circle fa-2x mr-3"></i>
               </section>
               <section>
                  <h3 class="m-0">Ganancia de hoy</h2>
                  <span class="totalToday"><?=$stats["today"]?></span>
               </section>
            </div>
         </div>
      </div>
   </div>

</div>