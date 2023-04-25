<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: ../../general/vistas/login.html");
} else {
    if ($_SESSION['tipousuario'] == 'Administrador' || $_SESSION['tipousuario'] == 'Administrador Tecno') {
        require '../../general/vistas/header.php';
?>
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <!-- Default box -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h1 class="box-title">Reporte Grupo Tecno</h1>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <!--box-header-->
                            <!--centro-->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <div class="row align-items-start justify-content-between">
                                    <div class="col-6">
                                        <div class="form-group col-lg-3 col-md-2 col-sm-3 col-xs-12">
                                            <!--Prueba semana-->
                                            <label>Dia</label>
                                            <input type="date" class="form-control" name="dia" id="dia" value="<?php echo date("Y-m-d"); ?>">
                                        </div>
                                        <div class="form-inline col-lg-2 col-md-2 col-sm-2 col-xs-6">
                                            <br>
                                            <button class="btn btn-primary" onclick="listar_dia();"> Mostrar</button>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                            <label>Fecha Inicio</label>
                                            <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                                        </div>
                                        <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xs-12">
                                            <label>Fecha Fin</label>
                                            <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                                        </div>
                                        <div class="form-inline col-lg-2 col-md-2 col-sm-2 col-xs-6">
                                            <br>
                                            <button class="btn btn-primary" onclick="listar();">
                                                Mostrar</button>
                                        </div>
                                    </div>
                                </div>
                                <div id="tdia">

                                    <table id="tbllistadodia" class="table table-striped table-bordered table-condensed table-hover">
                                        <thead>
                                            <th>No.</th>
                                            <th>Nombre</th>
                                            <th>Hora de entrada</th>
                                            <th>Hora de salida</th>
                                            <th>Turno</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <th>No.</th>
                                            <th>Nombre</th>
                                            <th>Hora de entrada</th>
                                            <th>Hora de salida</th>
                                            <th>Turno</th>
                                        </tfoot>
                                    </table>
                                </div>
                                <div id="tperiodo">
                                    <table id="tbllistadoperiodo" class="table table-striped table-bordered table-condensed table-hover">
                                        <thead>
                                            <th>No.</th>
                                            <th>Nombre</th>
                                            <th>Turno</th>
                                            <th>Puntuales</th>
                                            <th>Retardos</th>
                                            <th>Incidencias</th>
                                            <th>Total</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                            <th>No.</th>
                                            <th>Nombre</th>
                                            <th>Turno</th>
                                            <th>Puntuales</th>
                                            <th>Retardos</th>
                                            <th>Incidencias</th>
                                            <Th>Total</Th>
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                            <!--fin centro-->
                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </section>
            <!-- /.content -->
        </div>
    <?php
    } else {
        header("Location: ../../general/vistas/escritorio.php");
    }
    require '../../general/vistas/footer.php';
    ?>
    <script src="scripts/reporte.js"></script>
    <script src="../../public/images/base64/tecno_logo.js"></script>
<?php
}

ob_end_flush();
?>