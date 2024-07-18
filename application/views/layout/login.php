<!DOCTYPE html>
<html>

<head>
   <title>Iniciar sesion</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
   <link rel="shortcut icon" href="<?=base_url('assets/img/favicon.png');?>" type="image/x-icon">
   <link rel="icon" href="<?=base_url('assets/img/favicon.png');?>" type="image/x-icon">
   <script src="https://kit.fontawesome.com/a81368914c.js"></script>
   <link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/login.css');?>">
</head>

<body>
   <img class="wave" src="<?=base_url('assets/img/bg/wave.png');?>">
   <div class="container">
      <div class="img">     
         <img src="<?=base_url('assets/img/vectorlogin.svg');?>">
      </div>
      <div class="login-content">
         <div class="form">

            <img src="<?=base_url()?>assets/img/logo.png" alt="logo">
            <h2>Inicia Sesión</h2>
            <?php if(isset($message)){echo "<div class='red alert-red'>".$message."</div>";}?>
            <form action="<?=base_url('Login/checkLogin')?>" method="POST" class="login">
               <div class="input-div one">
                  <div class="i">
                     <i class="fas fa-user"></i>
                  </div>
                  <div class="div">
                     <h5>Usuario</h5>
                     <input type="text" autofocus class="input" name="user" value="<?=isset($username)?$username:''?>"
                     class="mb-form" required>
                  </div>
               </div>
               <div class="input-div pass">
                  <div class="i">
                     <i class="fas fa-lock"></i>
                  </div>
                  <div class="div">
                     <h5>Contraseña</h5>
                     <input type="password" name="password" class="input"  required>
                  </div>
               </div>
               <a href="#">Olvidé mi contraseña</a>
               <button type="submit" class="btn">Iniciar sesión</button>
            </form>
         </div>
      </div>
   </div>
   <script>
   const inputs = document.querySelectorAll(".input");

   function addcl() {
      let parent = this.parentNode.parentNode;
      parent.classList.add("focus");
   }

   function remcl() {
      let parent = this.parentNode.parentNode;
      if (this.value == "") {
         parent.classList.remove("focus");
      }
   }

   inputs.forEach(input => {
      input.addEventListener("focus", addcl);
      input.addEventListener("blur", remcl);
   });
   </script>
</body>

</html>