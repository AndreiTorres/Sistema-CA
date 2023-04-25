<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: ../../general/vistas/login.html");
} else {
  if ($_SESSION['tipousuario'] == 'Administrador' || $_SESSION['tipousuario'] == 'Administrador Cancillería') {
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
                <h1 class="box-title">Entradas/Salidas Cancillería</h1>
                <div class="box-tools pull-right">

                </div>
              </div>
              <!--box-header-->
              <!--centro-->
              <div class="panel-body table-responsive" id="listadoregistros">
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <label>Fecha Inicio</label>
                  <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                  <br> Agregar registro:

                  <!-- Button trigger modal -->
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" onclick="obtenerNombreParaSelect()">
                    Agregar
                  </button>


                  <!-- Modal -->

                  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h3 class="modal-title" id="exampleModalLabel">Agregar Registro</h3>
                        </div>
                        <div class="modal-body">

                          <div class='form-group'>
                            <label>Usuario</label>
                            <select name='codigo_persona' id='codigo_persona1' class='form-control selectpicker' data-live-search='true' required></select>
                          </div>
                          <div class='form-group'>
                            <label>Fechas <p id='alerta_fecha' class='text-danger'></p></label>
                            <input id='mdp-demo' class='form-control'><br><label>Tipo:</label><br>
                            <select name='selecttipo' id='selecttipo'>
                              <option value='Asistencia'>Asistencia</option>
                              <option value='Falta'>Falta</option>
                              <option value='Retardo'>Retardo</option>
                              <option value='Incidencia'>Incidencia</option>
                            </select>
                            <br>
                            <br>
                            <label>Anotacion:</label>
                            <textarea class='form-control' id='anotacion' rows='4' placeholder='Escriba aqui...' style='resize: none'></textarea>
                          </div>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-primary" onclick="crear_asistencia()">Agregar</button>
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                      </div>
                    </div>
                  </div>



                </div>
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                  <label>Fecha Fin</label>
                  <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">

                </div>
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-8">
                  <label>Usuario</label>
                  <select name="codigo_persona" id="codigo_persona" class="form-control selectpicker" data-live-search="true" required>
                  </select>
                </div>
                <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-4">
                  <br>
                  <button class="btn btn-primary" onclick="listar_asistencia();">
                    Mostrar</button>
                </div>

                <table id="tbllistado_asistencia" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Tipo</th>
                    <th>Anotación</th>
                    <th></th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Tipo</th>
                    <th>Anotación</th>
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
  } else {
    header("Location: ../../general/vistas/escritorio.php");
  }
  require '../../general/vistas/footer.php';
  ?>
  <script src="scripts/entrada_salida.js"></script>
<?php
}

ob_end_flush();
?>