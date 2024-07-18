<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="theme-color" content="#ffff">
   <meta name="description" content="Aplicacion Web Progresiva">
   <title>TandaPRO</title>
   <link rel="manifest" href="<?=base_url('assets/manifest.json')?>">
   <!-- Files css -->
   <link rel="stylesheet" href="<?=base_url('assets/css/plugins/modal.css')?>">
   <link rel="stylesheet" href="<?=base_url('assets/css/inter.css')?>">
   <link rel="stylesheet" href="<?=base_url('assets/css/styles.css')?>">
   <link rel="stylesheet" href="<?=base_url('assets/vendors/dataTables/dataTables.bootstrap.min.css');?>">
   <link type="image/png" href="<?=base_url('assets/img/logo.png')?>" rel="icon">

   <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro@4cac1a6/css/all.css" type="text/css" />
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap-grid.min.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css">
   
   <script type="text/javascript" src="<?=base_url('assets/vendors/dataTables/dataTables.jquery.min.js');?>" ></script>
   <script type="text/javascript" src="<?=base_url('assets/js/pages/helpers.js')?>"></script>
   <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
   
   <link rel="stylesheet" href="<?=base_url('assets/vendors/select2/select2.min.css')?>">
   <link rel="stylesheet" href="<?=base_url('assets/vendors/toastify/toastify.css')?>">
   <link rel="stylesheet" href="<?=base_url('assets/vendors/sweetalert2/sweetalert2.min.css')?>">
   <script> const base_url = "<?=base_url()?>";</script>
</head>

<body class="sidebar-mini">
   <header class="navbar">
      <button class="sidebar-toggler d-phone-none" onclick="toggleSidebar()">
         <i class="fas fa-bars"></i>
      </button>
      <h1 class="d-lg-none" id="titlePage"></h1>
      <button class="navbar-toggler" onclick="toggleNavbar()">
         <i class="fas fa-user"></i>
      </button>
      <a class="navbar-brand" href=""></a>
      <ul class="navbar-nav">
         <li class="nav-item d-lg-none"><a href="" class="nav-link">Perfil</a></li>
         <li class="nav-item d-lg-none"><a href="" class="nav-link">Configuración</a></li>
         <li class="nav-item d-lg-none"><a href="<?=base_url('logout')?>" class="nav-link">Cerrar sesion</a></li>
         <li class="nav-item dropdown d-phone-none">
            <a class="nav-link dropdown-toggle" href="" id="navbarDropdown" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false">
               Sesion
            </a>
            <div class="dropdown-menu dropdown-right" aria-labelledby="navbarDropdown">
               <a class="dropdown-item" href="">Perfil</a>
               <a class="dropdown-item" href="">Configuracion</a>
               <a class="dropdown-item" href="<?=base_url('logout')?>">Cerrar sesión</a>
            </div>
         </li>

      </ul>
   </header>
   <nav class="sidebar">
      <ul class="menu-aside">
         <li><a href="javascript:void(0)" onclick="toggleSidebar()"><img src="<?=base_url('./assets/img/logo.png')?>"><b>TandaPRO</b></a></li>
         <li><a href="<?=base_url('panel')?>"><i class="fas fa-analytics"></i> <span>Inicio</span></a></li>
         <li><a href="<?=base_url('tandas')?>"><i class="fas fa-hands-usd"></i> <span>Tandas</span></a></li>
         <li><a href="<?=base_url('contactos')?>"><i class="far fa-users"></i> <span>Contactos</span></a></li>
         <li><a href="<?=base_url('payments')?>"><i class="fas fa-credit-card"></i> <span>Pagos</span></a></li>
         <li><a href="<?=base_url('config')?>"><i class="far fa-cog"></i> <span>Configuracion</span></a></li>
      </ul>
   </nav>
   <nav class="sidebar-phone d-flex justify-content-center d-lg-none">
      <a href="<?=base_url('panel')?>" class="">
         <i class="fal fa-analytics"></i>
      </a>
      <a href="<?=base_url('tandas')?>" class="">
         <i class="fal fa-hands-usd"></i>
      </a>
      <a href="<?=base_url('payments')?>" class="">
         <i class="fal fa-credit-card"></i>
      </a>
      <a href="<?=base_url('contactos')?>" class="">
         <i class="fal fa-users"></i>
      </a>
   </nav>
   <main class="main-content">
   <style>
      .page{
         padding-bottom:60px;
      }
      .sidebar-phone{
         background-color:#111111; 
         position: fixed;
         bottom: 0;
         left:0;
         right:0;
         z-index:100;
         & a {
            padding: 1rem;
            margin:0 1rem;
            & i{
               color:white;
               font-size: 24px;
            }
         } 
      }
   </style>