<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
} else {
  require 'header.php';
  require_once('../modelos/Sesion.php');
  $usuario = new Usuario();

?>
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <?php
      switch ($_SESSION['tipousuario']) {
        case 'Administrador':
          require('modulos/becarios.php');
          require('modulos/cancilleria.php');
          require('modulos/tecno.php');
          break;
        case 'Administrador Cancillería':
          require('modulos/cancilleria.php');
          break;
        case 'Administrador Tecno':
          require('modulos/tecno.php');
          break;
        default:
        ?>
         <div class="row">
            <div class="col-md-12">
              <div class="box">
              <div class="panel-body">
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="small-box bg-green">
                    <a href="../../qrlib/index.php" type="button" class="small-box-footer">
                      <div class="small-box-footer">
                        <div class="inner">
                          <h5 style="font-size: 20px;">
                            <strong>Obtener mi  codigo QR </strong>
                          </h5>
                          <h1><strong>📗</strong></h1>
                          <h7><strong>

                            </strong></h7>


                        </div>
                        <div class="icon">
                          <i class="fa fa-list" aria-hidden="true"></i>
                        </div>&nbsp;
                        <div class="small-box-footer">
                          <i class="fa"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="small-box bg-aqua">
                      <a href="../../becarios/vistas/rptasistenciau.php" class="small-box-footer">
                        <div class="inner">
                          <h5 style="font-size: 20px;">
                            <strong>Reporte</strong>
                          </h5>
                          <h1><strong>Asistencias</strong></h1>
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
                      <a class="small-box-footer">
                        <div class="inner">
                          <h5 style="font-size: 20px;">
                            <strong>Horas acumuladas </strong>
                          </h5>
                          <h1><strong>
                              <?php
                              require('../../becarios/modelos/Asistencia.php');
                              $asistencia = new Asistencia();
                              $codigopersona = $_SESSION['codigo_persona'];

                              $rspsta = $asistencia->horasacumuladas($codigopersona);
                              $horas = $rspsta['Horas_acumuladas'];
                              echo $horas;
                              ?>
                            </strong></h1>
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
                </div>
              </div>
            </div>
          </div>
      <?php
      }
      ?>
    </section>
    <!-- /.content -->
  </div>
<?php
  require 'footer.php';
}
ob_end_flush();
?>