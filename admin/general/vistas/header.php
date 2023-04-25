 <?php
 	date_default_timezone_set('America/Mexico_City');
  if (strlen(session_id()) < 1)
    session_start();
  ?>
 <!DOCTYPE html>
 <html>

 <head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>OP Yucatán</title>
   <!-- Tell the browser to be responsive to screen width -->
   <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <!-- Bootstrap 3 -->
   <link rel="stylesheet" href="../../public/css/bootstrap.min.css">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="../../public/css/font-awesome.css">
   <!-- Theme style -->
   <link rel="stylesheet" href="../../public/css/AdminLTE.min.css">
   <!-- multidates picker -->
   <link rel="stylesheet" href="../../public/css/jquery-ui.multidatespicker.css">
   <!-- jquery-ui -->
   <link rel="stylesheet" href="../../public/css/jquery-ui.min.css">
   <!-- include the style -->
   <link rel="stylesheet" href="../../public/css/alertify.min.css" />
   <!-- include a theme -->
   <link rel="stylesheet" href="../../public/css/themes/default.min.css" />

   <!--Personal-->
   <link rel="stylesheet" href="../../public/css/styles.css" />

   <!-- include a theme -->
   <link rel="stylesheet" href="../../public/css/style_alertify.css" />
   <!-- include a theme -->
   <link rel="stylesheet" href="../../public/css/styles.css" />



   <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
   <link rel="stylesheet" href="../../public/css/_all-skins.min.css">
   <link rel="sre-logo" href="../../public/images/SRE Nuevo Logo.png">
   <link rel="shortcut icon" href="../../public/images/SRE Nuevo Logo.png">

   <!-- DATATABLES -->
   <link rel="stylesheet" type="text/css" href="../../public/datatables/jquery.dataTables.min.css">
   <link href="../../public/datatables/buttons.dataTables.min.css" rel="stylesheet" />
   <link href="../../public/datatables/responsive.dataTables.min.css" rel="stylesheet" />

   <link rel="stylesheet" type="text/css" href="../../public/css/bootstrap-select.min.css">

 </head>

 <body class="hold-transition skin-blue sidebar-mini">
   <!-- Load Facebook SDK for JavaScript -->
   <div id="fb-root"></div>

   <!-- Your customer chat code -->
   <div class="fb-customerchat" attribution=setup_tool page_id="280144326139427" theme_color="#0084ff">
   </div>
   <div class="wrapper">

     <header class="main-header">
       <!-- Logo -->
       <a href="../../general/vistas/escritorio.php" class="logo">
         <img src="../../public/images/logo rojo.png" alt="">
       </a>
       <!-- Header Navbar: style can be found in header.less -->
       <nav class="navbar navbar-static-top">
         <!-- Sidebar toggle button-->
         <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
           <span class="sr-only">Navegación</span>
         </a>
         <div class="navbar-custom-menu">
           <ul class="nav navbar-nav">
             <li class="dropdown user user-menu">
               <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                 <img src="../../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="user-image" alt="User Image">
                 <span id="nombreu" class="hidden-xs"><?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellidos']; ?></span>
               </a>
               <ul class="dropdown-menu">
                 <!-- User image -->
                 <li class="user-header">
                   <img src="../../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-circle" alt="User Image">
                   <p>
                     <?php echo $_SESSION['nombre']; ?>
                     <small><?php
                            require_once '../../config/Conexion.php';
                            $sql = "SELECT nombre FROM departamento WHERE iddepartamento =" . $_SESSION['departamento'] . ";";
                            $rspsta = ejecutarConsulta($sql);
                            $reg = $rspsta->fetch_object();
                            echo $reg->nombre;
                            ?>
                     </small>
                   </p>
                 </li>
                 <!-- Menu Footer-->
                 <li class="user-footer">
                   <div class="pull-left">
                     <a href="#" class="btn btn-default btn-flat">Perfil</a>
                   </div>
                   <div class="pull-right">
                     <a href="../../general/ajax/sesion.php?op=salir" class="btn btn-default btn-flat">Salir</a>
                   </div>
                 </li>
               </ul>
             </li>
             <!-- Control Sidebar Toggle Button -->
           </ul>
         </div>
       </nav>
     </header>
     <!-- Left side column. contains the logo and sidebar -->
     <aside class="main-sidebar">
       <!-- sidebar: style can be found in sidebar.less -->
       <section class="sidebar">
         <!-- Sidebar user panel -->
         <div class="user-panel">
           <div class="pull-left image">
             <img src="../../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-circle" style="width: 50px; height: 50px;" alt="User Image">
           </div>
           <div class="pull-left info">
             <p><?php echo $_SESSION['nombre']; ?></p>
             <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
           </div>
         </div>
         <!-- sidebar menu: : style can be found in sidebar.less -->
         <ul class="sidebar-menu tree" data-widget="tree">
           <li class="header">MENÚ DE NAVEGACIÓN</li>
           <li><a href="../../general/vistas/escritorio.php"><i class="fa fa-home" style="font-size:24px;"></i> <span>Escritorio</span></a></li>
           <?php
            switch ($_SESSION['tipousuario']) {
              case 'Administrador':
            ?>
               <li class="treeview">
                 <a href="#">
                   <i class="fa fa-graduation-cap"></i> 
                   <span class="pull-right-container">
                     <span>Becarios</span>
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
                 </a>
                 <ul class="treeview-menu">
                   <li><a href="../../becarios/vistas/usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                   <li><a href="../../becarios/vistas/departamento.php"><i class="fa fa-circle-o"></i> Universidades</a></li>
                   <li><a href="../../becarios/vistas/asistencia.php"><i class="fa fa-circle-o"></i> Reporte</a></li>
                   <li><a href="../../becarios/vistas/rptasistencia.php"><i class="fa fa-circle-o"></i> Entradas/Salidas</a></li>
                 </ul>
               </li>

               <li class="treeview">
                 <a href="#">
                   <i class="fa fa-institution" aria-hidden="true"></i> 
                   <span class="pull-right-container">
                     <span>Cancillería</span>
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
                 </a>
                 <ul class="treeview-menu">
                   <li><a href="../../cancilleria/vistas/usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                   <li><a href="../../cancilleria/vistas/departamento.php"><i class="fa fa-circle-o"></i> Puestos</a></li>
                   <li><a href="../../cancilleria/vistas/reporte.php"><i class="fa fa-circle-o"></i> Reporte</a></li>
                   <li><a href="../../cancilleria/vistas/entradas_salidas.php"><i class="fa fa-circle-o"></i> Entradas/Salidas</a></li>
                 </ul>

               <li class="treeview">
                 <a href="#">
                   <i class="fa fa-camera"></i> 
                   <span class="pull-right-container">
                     <span>Grupo Tecno</span>
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
                 </a>
                 <ul class="treeview-menu">
                   <li><a href="../../tecno/vistas/usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                   <li><a href="../../tecno/vistas/reporte.php"><i class="fa fa-circle-o"></i> Reporte</a></li>
                   <li><a href="../../tecno/vistas/entradas_salidas.php"><i class="fa fa-circle-o"></i> Entradas/Salidas</a></li>
                 </ul>
               </li>
               <li><a href="../../general/vistas/entrada.php"><i class="fa fa-desktop" style="font-size:20px;"></i> <span> Entrada</span></a></li>

             <?php
                break;
              case 'Administrador Cancillería':
              ?>
               <li><a href="../../cancilleria/vistas/usuario.php"><i class="fa fa-user" aria-hidden="true"> </i><span>Usuarios</span></a></li>
               <li><a href="../../cancilleria/vistas/reporte.php"><i class="fa fa-file-o"></i> <span>Reporte</span></a></li>
               <li><a href="../../cancilleria/vistas/entradas_salidas.php"><i class="fa fa-list-ul"></i> <span>Entradas/Salidas</span></a></li>
             <?php
                break;
              case 'Administrador Tecno':
              ?>
               <li><a href="../../tecno/vistas/usuario.php"><i class="fa fa-user" aria-hidden="true"> </i><span>Usuarios</span></a></li>
               <li><a href="../../tecno/vistas/reporte.php"><i class="fa fa-file-o"></i> <span>Reporte</span></a></li>
               <li><a href="../../tecno/vistas/entradas_salidas.php"><i class="fa fa-list-ul"></i> <span>Entradas/Salidas</span></a></li>
             <?php
                break;
              default:
              ?>
               <li class="treeview">
                 <a href="#">
                   <i class="fa fa-folder"></i> 
                   <span class="pull-right-container">
                     <span>Mis Asistencias</span>
                     <i class="fa fa-angle-left pull-right"></i>
                   </span>
                 </a>
                 <ul class="treeview-menu">
                   <li><a href="../../becarios/vistas/rptasistenciau.php"><i class="fa fa-circle-o"></i> Reportes</a></li>
                 </ul>
               </li>
           <?php
            }
            ?>
         </ul>
       </section>
       <!-- /.sidebar -->
     </aside>