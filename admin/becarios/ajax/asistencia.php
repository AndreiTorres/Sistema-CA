<?php
require_once "../modelos/Asistencia.php";
if (strlen(session_id()) < 1)
	session_start();
$asistencia = new Asistencia();

$codigo_persona = isset($_POST["codigo_persona"]) ? limpiarCadena($_POST["codigo_persona"]) : "";
$iddepartamento = isset($_POST["iddepartamento"]) ? limpiarCadena($_POST["iddepartamento"]) : "";



switch ($_GET["op"]) {
	case 'guardaryeditar':
		$result = $asistencia->verificarcodigo_persona($codigo_persona);

		if ($result > 0) {
			date_default_timezone_set('America/Mexico_City');
			$fecha = date("Y-m-d");
			$hora = date("H:i:s");

			$result2 = $asistencia->seleccionarcodigo_persona($codigo_persona);

			$par = abs($result2 % 2);

			if ($par == 0) {

				$tipo = "Entrada";
				$rspta = $asistencia->registrar_entrada($codigo_persona, $tipo);
				//$movimiento = 0;
				echo $rspta ? '<h3><strong>Nombres: </strong> ' . $result['nombre'] . ' ' . $result['apellidos'] . '</h3><div class="alert alert-success"> Ingreso registrado ' . $hora . '</div>' : 'No se pudo registrar el ingreso';
			} else {
				$tipo = "Salida";
				$rspta = $asistencia->registrar_salida($codigo_persona, $tipo);
				//$movimiento = 1;
				echo $rspta ? '<h3><strong>Nombres: </strong> ' . $result['nombre'] . ' ' . $result['apellidos'] . '</h3><div class="alert alert-danger"> Salida registrada ' . $hora . '</div>' : 'No se pudo registrar la salida';
			}
		} else {
			echo '<div class="alert alert-danger">
                       <i class="icon fa fa-warning"></i> No hay empleado registrado con esa código...!
                         </div>';
		}

		break;


		/* 	case 'mostrar':
		$rspta=$asistencia->mostrar($idasistencia);
		echo json_encode($rspta);
	break;

 */

	case 'listar':
		$stringCod  = '';
		$fecha_inicio = $_REQUEST["fecha_inicio"];
		$fecha_fin = $_REQUEST["fecha_fin"];
		$rspta = $asistencia->listar($fecha_inicio, $fecha_fin);
		
		//declaramos un array
		$data = array();
		while ($reg = $rspta->fetch_object()) {
			
			$acumuladas = $asistencia->horasacumuladas($reg->codigo_persona);
			$tipo = $asistencia->tipo_usuario($reg->idtipousuario);
			$dias = $asistencia->listar_dias_asistidos($fecha_inicio,$fecha_fin,$reg->codigo_persona);
			$stringCod = $reg->codigo_persona;
			
			$data[] = array(
				"0"=>"",
				"1"=>'<div id="cellhover" onclick="mostrarUsuario('."'".$reg->idusuario."'".')"><p>'.$reg->apellidos.'</p></div>',
				"2"=>'<div id="cellhover" onclick="mostrarUsuario('."'".$reg->idusuario."'".')"><p>'.$reg->nombre.'</p></div>',
				"3"=>$tipo["tipo"],
				"4"=>$dias["DiasA"],
				"5"=>($reg->Total)?$reg->Total.' <button class="btn btn-warning btn-xs" onclick="mostrarES('."'".$stringCod."'".','."'".$fecha_inicio."'".','."'".$fecha_fin."'".')"><i class="fa fa-pencil"></i></button>': '',
				"6"=>$acumuladas["Horas_acumuladas"]
			);
		}

		$results = array(
			"sEcho" => 1, //info para datatables
			"iTotalRecords" => count($data), //enviamos el total de registros al datatable
			"iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);

		break;

	case 'numero_asistencias':
		$fecha_inicio = $_REQUEST["fecha_inicio"];
		$fecha_fin = $_REQUEST["fecha_fin"];
		$codigo_persona = $_REQUEST["codigo_persona"];
		$dias = $asistencia->listar_dias_asistidos($fecha_inicio, $fecha_fin, $codigo_persona);
		echo $dias["DiasA"];
	break;	

	case 'horas_periodo':
		$fecha_inicio = $_REQUEST["fecha_inicio"];
		$fecha_fin = $_REQUEST["fecha_fin"];
		$codigo_persona = $_REQUEST["codigo_persona"];
		$rspta = $asistencia->horas_periodo($fecha_inicio, $fecha_fin, $codigo_persona);
		echo $rspta["Total"];
	break;	

	case 'listar_asistencia':
		$fecha_inicio = $_REQUEST["fecha_inicio"];
		$fecha_fin = $_REQUEST["fecha_fin"];
		$codigo_persona = $_REQUEST["codigo_persona"];
		$rspta = $asistencia->listar_asistencia($fecha_inicio, $fecha_fin, $codigo_persona);
		//$horas = $asistencia->horasbtwdate($fecha_inicio, $fecha_fin, $codigo_persona);
		//declaramos un array
		$data = array();
		
		while($reg = $rspta->fetch_object()){
			$data[] = array(
				"0" => $reg->entrada,
				"1" => $reg->salida,
				"2" => $reg->horas . ' <button class="btn btn-warning btn-xs" onclick="editar_hora('.$reg->idasistencia.')"><i class="fa fa-pencil"></i></button>',
				"3" => '<button class="btn btn-danger btn-xs" onclick="borrar_asistencia('.$reg->idasistencia.')"><i class="fa fa-close"></i></button>',
			);
		}
		$results = array(
			"sEcho" => 1, //info para datatables
			"iTotalRecords" => count($data), //enviamos el total de registros al datatable
			"iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);
		break;
	case 'listar_asistenciau':
		$fecha_inicio = $_REQUEST["fecha_inicio"];
		$fecha_fin = $_REQUEST["fecha_fin"];
		$codigo_persona = $_SESSION["codigo_persona"];
		$rspta = $asistencia->listar_asistencia($fecha_inicio, $fecha_fin, $codigo_persona);
		//declaramos un array
		//$horas = $asistencia->horasbtwdate($fecha_inicio, $fecha_fin, $codigo_persona);
		//declaramos un array
		$data = array();
		while ($reg = $rspta->fetch_object()) {
			
			//$fecha = ($reg->fecha_hora)."";
			$data[] = array(
				"0" => $reg->entrada,
				"1" => $reg->salida,
				"2" => $reg->horas,
			);
			
		}

		$results = array(
			"sEcho" => 1, //info para datatables
			"iTotalRecords" => count($data), //enviamos el total de registros al datatable
			"iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);

		break;

	case 'selectPersona':
		require_once "../modelos/Usuario.php";
		$usuario = new Usuario();

		$rspta = $usuario->listar();

		while ($reg = $rspta->fetch_assoc()) {
			echo '<option value=' . $reg['codigo_persona'] . '>' . $reg['apellidos'] . ' ' . $reg['nombre'] . '</option>';
		}
		break;

	case 'nueva_hora':
		$idasistencia = $_REQUEST["idasistencia"];
		$nueva_hora = $_REQUEST["nueva_hora"];
		$rspta = $asistencia->nueva_hora($idasistencia,$nueva_hora);
	break;

	case 'nueva_asistencia':
		$codigo_persona = $_REQUEST["codigo_persona"];
		$fecha = $_REQUEST["fecha"];
		$rspta = $asistencia->existe_asistencia($codigo_persona,$fecha);
		if($rspta>0){
			echo "El usuario tiene una asistencia registrada el ". $fecha . ". Edite las horas o seleccione otra fecha.";
		} else {
			$nueva_hora = $_REQUEST["nueva_hora"];
			$rspta = $asistencia->nueva_asistencia($codigo_persona,$fecha,$nueva_hora);
			echo "Se ha creado exitosamente la nueva asistencia.";
		}
	break;	

	case 'borrar_asistencia':
		$idasistencia = $_REQUEST["idasistencia"];
		$asistencia->borrar_asistencia($idasistencia);
	break;
}
