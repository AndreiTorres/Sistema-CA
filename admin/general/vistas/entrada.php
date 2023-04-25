<?php
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: ../../general/vistas/login.html");
} else {
    if ($_SESSION['tipousuario'] !== 'Administrador') {
        header("Location: ../../general/vistas/escritorio.php");
    }
    require 'header.php';
?>
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h1 class="box-title">Cambio de contraseña </h1>
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <br>
                        <div class="container ">
                            <div class="row justify-content-center">
                                <div class="form-group col-md-4 col-md-offset-5 align-center ">
                                    <form class="form-inline" action="" name="formularioc" id="formularioc" method="POST">
                                        <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="inputPassword" class="sr-only">contraseña</label>
                                                <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Contraseña" maxlength="64" required>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 col-xs-12">
                                            <button type="submit" class="btn btn-primary mb-2">Confirmar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </section>
        <!-- /.content -->
    </div>

    <?php
    require 'footer.php';
    ?>
    <script src="scripts/entrada.js"></script>
<?php
}

ob_end_flush();
?>