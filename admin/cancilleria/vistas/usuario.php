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
                <h1 class="box-title">Usuarios Cancilleria <button class="btn btn-primary" onclick="mostrarform(true)" id="btnagregar"><i class="fa fa-plus-circle"></i> Agregar</button></h1>
                <div class="box-tools pull-right">
                </div>
              </div>
              <!--box-header-->
              <!--centro-->
              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>No.</th>
                    <th>Opciones</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Foto</th>
                    <th>Puesto</th>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                    <th>No.</th>
                    <th>Opciones</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Foto</th>
                    <th>Puesto</th>
                  </tfoot>
                </table>
              </div>
              <div class="panel-body" id="formularioregistros">
                <form action="" name="formulario" id="formulario" method="POST">
                  <div class="form-group col-lg-6 col-md-6 col-xs-12">
                    <label for="">Tipo usuario(*):</label>
                    <select name="idtipousuario" id="idtipousuario" class="form-control select-picker" required>
                    </select>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-xs-12">
                    <label for="">Puesto(*):</label>
                    <select name="iddepartamento" id="iddepartamento" class="form-control select-picker" required>
                    </select>
                  </div>
                  <div>
                    <div class="form-group col-lg-6 col-md-6 col-xs-12">
                      <label for="">Login(*):</label>
                      <input class="form-control id_input" type="text" name="login" id="login" maxlength="20" placeholder="Nombre de Usuario" required>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-xs-12" id="claves">
                      <label for="">Contraseña(*):</label>
                      <input class="form-control id_input" type="password" name="clave" id="clave" maxlength="64" placeholder="Clave">
                    </div>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-xs-12">
                    <label for="">Nombre(*):</label>
                    <input class="form-control" type="hidden" name="idusuario" id="idusuario">
                    <input class="form-control" type="text" onkeyup="mayus(this)" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-xs-12">
                    <label for="">Apellidos(*):</label>
                    <input class="form-control" type="text" onkeyup="mayus(this)" name="apellidos" id="apellidos" maxlength="100" placeholder="Apellidos" required>
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-xs-12" id="claves">
                    <label for="">Clave de asistencia(*):</label>
                    <button class="btn btn-info" type="button" onclick="generar(6);">Generar</button>
                    <input class="form-control" type="text" name="codigo_persona" id="codigo_persona" maxlength="64" placeholder="Clave">
                  </div>
                  <div class="form-group col-lg-6 col-md-6 col-xs-12">
                    <label for="">Imagen:</label>
                    <input class="form-control filestyle" data-buttonText="Seleccionar foto" type="file" name="imagen" id="imagen">
                    <input type="hidden" name="imagenactual" id="imagenactual">
                    <img src="" alt="" width="150px" height="120" id="imagenmuestra">
                  </div>
                  <div class="form-group col-lg-12 col-md-12 col-xs-12" id="horario">
                    <label for="">Hora de entrada:</label>
                    <div class="row">
                      <div class="col-md-3">
                        <label for="">
                          <h4 >Lunes</h4>
                        </label>
                        &nbsp;
                        &nbsp;
                        <div class="inline">
                          <label for="" class="h6">
                            <input type="checkbox" name="lunesd" id="lunesd"> Descanso
                          </label>
                        </div>
                        <input class="form-control" type="time" name="lunes" id="lunes">
                      </div>
                      <div class="col-md-3 form-group">
                        <label for="">
                          <h4>Martes</h4>
                        </label>
                        &nbsp;
                        &nbsp;
                        <div class="inline">
                          <label for="" class="h6">
                            <input type="checkbox" name="martesd" id="martesd" value="si"> Descanso
                          </label>
                        </div>
                        <input class="form-control" type="time" name="martes" id="martes">
                      </div>
                      <div class="col-md-3">
                        <label for="">
                          <h4>Miercoles</h4>
                        </label>
                        &nbsp;
                        <div class="inline">
                          <label for="" class="h6">
                            <input type="checkbox" name="miercolesd" id="miercolesd"> Descanso
                          </label>
                        </div>
                        <input class="form-control" type="time" name="miercoles" id="miercoles">
                      </div>
                      <div class="col-md-3">
                        <label for="">
                          <h4>Jueves</h4>
                        </label>
                        &nbsp;
                        &nbsp;
                        <div class="inline">
                          <label for="" class="h6">
                            <input type="checkbox" name="juevesd" id="juevesd" value="si"> Descanso
                          </label>
                        </div>
                        <input class="form-control" type="time" name="jueves" id="jueves">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3">
                        <label for="">
                          <h4>Viernes</h4>
                        </label>
                        &nbsp;
                        &nbsp;
                        <div class="inline">
                          <label for="" class="h6">
                            <input type="checkbox" name="viernesd" id="viernesd"> Descanso
                          </label>
                        </div>
                        <input class="form-control" type="time" name="viernes" id="viernes">
                      </div>
                      <div class="col-md-3">
                        <label for="">
                          <h4>Sábado</h4>
                        </label>
                        &nbsp;
                        &nbsp;
                        <div class="inline">
                          <label for="" class="h6">
                            <input type="checkbox" name="sabadod" id="sabadod"> Descanso
                          </label>
                        </div>
                        <input class="form-control" type="time" name="sabado" id="sabado">
                      </div>
                      <div class="col-md-3">
                        <label for="">
                          <h4>Domingo</h4>
                        </label>
                        &nbsp;
                        <div class="inline">
                          <label for="" class="h6">
                            <input type="checkbox" name="domingod" id="domingod"> Descanso
                          </label>
                        </div>
                        <input class="form-control" type="time" name="domingo" id="domingo">
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>
                    <button class="btn btn-default" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                  </div>
                </form>
              </div>




              <!--modal para ver la venta-->
              <div class="modal fade" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="width: 50% !important;">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title">Cambio de contraseña</h4>
                    </div>
                    <div class="modal-body">
                      <form action="" name="formularioc" id="formularioc" method="POST">
                        <div class="form-group">
                          <label for="recipient-name" class="col-form-label">Password:</label>
                          <input class="form-control" type="hidden" name="idusuarioc" id="idusuarioc">
                          <input class="form-control" type="password" name="clavec" id="clavec" maxlength="64" placeholder="Clave" required>
                        </div>
                        <button class="btn btn-primary" type="submit" id="btnGuardar_clave"><i class="fa fa-save"></i> Guardar</button>
                        <button class="btn btn-default" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                      </form>

                      <div class="modal-footer">
                        <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!--inicio de modal editar contraseña--->
                <!--fin de modal editar contraseña--->
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
  <script src="scripts/usuario.js"></script>
<?php
}

ob_end_flush();
?>

<script>
  function mayus(e) {
    e.value = e.value.toUpperCase();
  }
</script>