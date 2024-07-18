<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login - LilaExpress</title>
   <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="assets/css/bootstrap.css">
   <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
   <link rel="stylesheet" href="assets/css/app.css">
   <link rel="stylesheet" href="assets/css/pages/auth.css">
   <!-- Files js -->
   <script src="<?php echo base_url(); ?>assets/vendors/jquery/jquery.min.js"></script>
</head>

<body>
   <div id="auth">

      <div class="row h-100">
         <div class="col-lg-5 col-12">
            <div id="auth-left">
               <div class="auth-log text-center">
                  <a href="<?= base_url('Login') ?>"><img src="assets/images/logo/logoLogin.png" alt="Logo"></a>
               </div>
               <h1 class="auth-title">Bienvenida Hilda!</h1>

               <form id="formLogin">
                  <div class="form-group position-relative has-icon-left mb-4">
                     <input type="text" class="form-control form-control-xl" placeholder="Usuario" name="user">
                     <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                     </div>
                  </div>
                  <div class="form-group position-relative has-icon-left mb-4">
                     <input type="password" class="form-control form-control-xl" placeholder="Contraseña" name="password">
                     <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                     </div>
                  </div>

                  <button type="submit" id="btnSubmitLogin" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">Iniciar sesion</button>
               </form>

            </div>
         </div>
         <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">

            </div>
         </div>
      </div>

   </div>
   <script>
      $(document).ready(function() {
         var base_url = "<?= base_url() ?>";

         $("#formLogin").on('submit', (function(e) {
            e.preventDefault();
            var submit = $("#btnSubmitLogin");
            var formData = new FormData($(this)[0]);

            $.ajax({
               type: "POST",
               url: base_url + "Login/ajax_verify_login",
               data: formData,
               processData: false,
               contentType: false,
               beforeSend: function() {
                  submit
                     .html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cargando..`)
                     .attr("disabled", "disabled");
               },
               success: function(response) {
                  if (response === 'success') {
                     window.location.href = base_url + "Login";
                  } else {
                     submit
                        .removeClass('btn-primary')
                        .addClass('btn-danger')
                        .html('<i class="fas fa-times"></i> Usario / Contraseña incorrecta</b>');

                     setTimeout(() => {
                        submit
                           .removeAttr('disabled')
                           .removeClass('btn-danger')
                           .addClass('btn-primary')
                           .html('<i class="fas fa-unlock-alt"></i> Iniciar sesión');
                     }, 2500);

                  }
               }
            });
         }));
      });
   </script>
</body>

</html>