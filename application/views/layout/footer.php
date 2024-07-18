</main>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/lang/summernote-es-ES.min.js"></script>

<script src="<?=base_url('assets/js/main.js')?>"></script>
<script src="<?=base_url('assets/js/dropzone.js')?>"></script>
<script src="<?=base_url('assets/js/wizard.js')?>"></script>
<script src="<?=base_url('assets/js/utilities.js')?>"></script>
<!-- /////// -->

<script src="<?php echo base_url(); ?>assets/vendors/apexcharts/apexcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/toastify/toastify.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/select2/select2.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/sweetalert2/sweetalert2.all.min.js"></script>

<script>
   const Toast = Swal.mixin({
		toast: true,
		position: "top",
		showConfirmButton: false,
		timer: 1500,
		didOpen: (toast) => {
			toast.addEventListener("mouseenter", Swal.stopTimer);
			toast.addEventListener("mouseleave", Swal.resumeTimer);
		},
	});
   const ToastRight = Swal.mixin({
		toast: true,
		position: "center",
		showConfirmButton: false,
		timer: 1500,
		didOpen: (toast) => {
			toast.addEventListener("mouseenter", Swal.stopTimer);
			toast.addEventListener("mouseleave", Swal.resumeTimer);
		},
	});

  function date_format(date_order)
   {
      const meses = ['Ene', 'Feb', 'Mar', 'Abril', 'May', 'Jun', 'Jul', 'Ago', 'Sept', 'Oct', 'Nov', 'Dic'];
      const dias_semana = ['Dom','Lun', 'Mar', 'Miér', 'Jue', 'Vie', 'Sáb'];
      const fecha = new Date(date_order.replace(/-/g, '\/'));
      const date =   meses[fecha.getMonth()]+' ' +  fecha.getDate() +',' + fecha.getUTCFullYear();                                                            
      return date;
   }
   $(document).ready(function () {
      $("#titlePage").html($(".page h1").html())
   });
   if ('serviceWorker' in navigator) {
      window.addEventListener('load', function () {
         navigator.serviceWorker.register(base_url+'assets/service-worker.js')
            .then(function (registration) {
               console.log('Service Worker registrado con éxito:', registration);
            })
            .catch(function (error) {
               console.log('Error al registrar el Service Worker:', error);
            });
      });
   }
</script>

</body>

</html>