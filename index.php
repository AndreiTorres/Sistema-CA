<!DOCTYPE html>
<HTMl>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>OP Yucatán</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="admin/public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="admin/public/css/font-awesome.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="admin/public/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="admin/public/css/blue.css">
    <link rel="shortcut icon" href="admin/public/images/SRE Nuevo Logo.jpg">
</head>

<body class="hold-transition lockscreen">
    <div class="page">
        <div class="lockscreen-wrapper">
            <div class="lockscreen-logo">
            <a href="#"><b>CONTROL </b> DE ACCESO</a>
            <a>Seleccione una opcion</a>
            </div>
            <div class="page">
                <a href="#" class="boton" id="btnabrir">Registro entradas/salidas</a>
            </div>
            <br>    
            <div class="page">
            <a href="admin/general/vistas/login.html" class="boton" id="btnabrir">Ingreso al sistema</a>
            </div>
        </div>
    </div>

    <div class="fondo_transparente">
        <form action="post" id="frmValidar">
            <div class="modal_index">
                <div class="modal_titulo">Por favor coloque su clave</div>
                <div class="modal_mensaje">
                    <p>Por motivos de seguridad, para poder registrar entradas y/o salidas, es necesario colocar la clave proporcionada.</p>
                    <div class="clavepol">
                        <input class="form-control" type="password" name="clavepolicia" id="clavepolicia" maxlength="64" placeholder="Clave" style="width:150px;" required>
                    </div>
                    <div>  </div>                
                </div>
                <div class="modal_botones">
                    <button type="submit" class="boton"> INGRESAR</button>
                    <a href="" class="boton">CERRAR</a>
                </div>
            </div>
        </form>
    </div>

    <!-- jQuery -->
    <script src="admin/public/js/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="admin/public/js/bootstrap.min.js"></script>
     <!-- Bootbox -->
    <script src="admin/public/js/bootbox.min.js"></script>
    <script src="scripts/validar.js"></script>
    <script src="scripts/modal.js"></script>
</body>

</HTMl>

