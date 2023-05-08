<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: ../../general/vistas/login.html");
} else {
  if ($_SESSION['tipousuario'] !== 'Administrador') {
    header("Location: ../../general/vistas/escritorio.php");
  }
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
              <h1 class="box-title">Asistencias Becarios</h1>
             <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" onclick="obtenerNombreParaSelect()">
                <i class="fa fa-plus-circle"></i>
                Agregar
              </button>
              <!-- Modal -->
              <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h3 class="modal-title" id="exampleModalLabel">Agregar Asistencia</h3>
                    </div>
                    <div class="modal-body">
                    <input type="button" class="btn btn-primary" id="selectAll" name="selectAll" value="Seleccionar Todos">
                      <input type="button" class="btn btn-primary" id="unselectAll" name="unselectAll" value="Quitar Todos"><br /><br />
                      <div class='form-group'>
                        <label>Becario</label>
                        <select multiple name='codigo_persona1' id='codigo_persona1' class='form-control selectpicker' data-live-search='true' required>
                        </select>
                      </div>
                      <div class='form-group'>
                        <label>Fecha <p id='alerta_fecha' class='text-danger'></p></label>
                        <input id='mdp-demo' class='form-control'>
                      </div>
                      <div class='form-group'><label>Horas</label>
                        <div>
                          <input id='horasmodal' type='number' min='0' max='23' placeholder='00'>:<input id='minutosmodal' type='number' min='0' max='59' placeholder='00'>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-primary" onclick="crear_asistencia()">Agregar</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="box-tools pull-right">
              </div>
            </div>
            <!--box-header-->
            <!--centro-->
            <div class="panel-body table-responsive" id="listadoregistros">
              <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <label>Fecha Inicio</label>
                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
              </div>
              <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <label>Fecha Fin</label>
                <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
              </div>
              <div class="form-inline col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Becario</label>
                <select name="codigo_persona" id="codigo_persona" class="form-control selectpicker" data-live-search="true" required>
                </select>
                <br>
                <br>
                <button class="btn btn-primary" onclick="listar_asistencia();">
                  Mostrar</button> <br> <br>
              </div>
              <table id="tbllistado_asistencia" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                  <th>Entrada</th>
                  <th>Salida</th>
                  <th>Horas</th>
                  <th></th>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <th>Entrada</th>
                  <th>Salida</th>
                  <th>Horas</th>
                  <th></th>
                </tfoot>
              </table>
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


  require '../../general/vistas/footer.php';
  ?>
  <script src="scripts/asistencia.js"></script>
  <script>
      $('#selectAll').click(function() {
        $('#codigo_persona1 option').prop('selected', true);
        $("#codigo_persona1").selectpicker("refresh");
      });

      $('#unselectAll').click(function() {
        $('#codigo_persona1 option').prop('selected', false);
        $("#codigo_persona1").selectpicker("refresh");
      });

      </script>
<?php
}

ob_end_flush();
?>