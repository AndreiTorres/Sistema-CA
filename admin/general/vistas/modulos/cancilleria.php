<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                &nbsp;
                &nbsp;
                <h1 class="box-title">Cancilleria</h1>
            </div>
            <div class="panel-body">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="small-box bg-green">
                        <a href="../../cancilleria/vistas/reporte.php" class="small-box-footer">
                            <div class="inner">
                                <h5 style="font-size: 20px;">
                                    <strong>Reporte de asistencias </strong>
                                </h5>
                                <p>Módulo</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </div>&nbsp;
                            <div class="small-box-footer">
                                <i class="fa"></i>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="small-box bg-orange">
                        <a href="../../cancilleria/vistas/usuario.php" class="small-box-footer">
                            <div class="inner">
                                <h5 style="font-size: 20px;">
                                    <strong>Cancillería</strong>
                                </h5>
                                <p>Total
                                    <?php
                                    $rsptan = $usuario->cantidad_usuario_cancilleria();
                                    $reg = $rsptan->fetch_object();
                                    echo $reg->total;
                                    ?>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users" aria-hidden="true"></i>
                            </div>&nbsp;
                            <div class="small-box-footer">
                                <i class="fa"></i>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="small-box bg-aqua">
                        <a href="../../cancilleria/vistas/entradas_salidas.php" class="small-box-footer">
                            <div class="inner">
                                <h5 style="font-size: 20px;">
                                    <strong>Entradas y salidas </strong>
                                </h5>
                                <p>Módulo</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-list" aria-hidden="true"></i>
                            </div>&nbsp;
                            <div class="small-box-footer">
                                <i class="fa"></i>
                            </div>
                        </a>
                    </div>
                </div>
                <!--fin centro-->
            </div>
        </div>
    </div>
</div>