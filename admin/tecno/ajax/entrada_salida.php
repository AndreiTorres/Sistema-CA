<?php
require_once "../modelos/Entrada_salida.php";
if (strlen(session_id()) < 1)
	session_start();
$asistencia = new Asistencia();
$codigo_persona = isset($_POST["codigo_persona"]) ? limpiarCadena($_POST["codigo_persona"]) : "";
$iddepartamento = isset($_POST["iddepartamento"]) ? limpiarCadena($_POST["iddepartamento"]) : "";
switch ($_GET["op"]) {
	case 'listar_asistencia':
		$fecha_inicio = $_REQUEST["fecha_inicio"];
		$fecha_fin = $_REQUEST["fecha_fin"];
		$codigo_persona = $_REQUEST["codigo_persona"];
		$rspta = $asistencia->listar_asistencia($fecha_inicio, $fecha_fin, $codigo_persona);
		//$horas = $asistencia->horasbtwdate($fecha_inicio, $fecha_fin, $codigo_persona);
		//declaramos un array
		$data = array();
		$i = 0;
		$inicio = "";
		while ($reg = $rspta->fetch_object()) {
				//$fecha = ($reg->fecha_hora)."";
				$data[] = array(
					"0" => $reg->entrada.' <button class="btn btn-warning btn-xs" onclick="editar_entrada('."'".$reg->entrada."'".','.$reg->idasistencia.')"><i class="fa fa-pencil"></i></button>',
					"1" => $reg->salida==""?"":$reg->salida. ' <button class="btn btn-warning btn-xs" onclick="editar_salida('."'".$reg->entrada."'".','.$reg->idasistencia.')"><i class="fa fa-pencil"></i></button>',
					"2" => $reg->tipo,
					"3" => $reg->anotacion,
					"4" => '<button class="btn btn-warning btn-xs" onclick="editartipo('.$reg->idasistencia.')"><i class="fa fa-pencil"></i></button> <button class="btn btn-danger btn-xs" onclick="borrar_asistencia('.$reg->idasistencia.')"><i class="fa fa-close"></i></button>'
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
		$rspta = $usuario->llenar_select();
		while ($reg = $rspta->fetch_assoc()) {
			echo '<option value=' . $reg['codigo_persona'] . '>' . $reg['apellidos'] . ' ' . $reg['nombre'] . '</option>';
		}
		break;

	case 'nuevo_tipo':
		$idasistencia = $_REQUEST["idasistencia"];
		$tipoAsistencia = $_REQUEST["tipoAsistencia"];
		$anotacion = $_REQUEST["anotacion"];
		$rspta = $asistencia->nuevo_tipo($idasistencia,$tipoAsistencia, $anotacion);
	break;

	case 'nueva_asistencia':
		$codigo_persona = $_REQUEST["codigo_persona"];
		$arrFechas = $_REQUEST["arrFechas"];
		$anotacion = $_REQUEST["anotacion"];
		$tipoAsistencia = $_REQUEST["tipoAsistencia"];

		$Agregados = array();
		$NoAgregados  = array();
		$i = 0;
		while($i < count($arrFechas)){
			$rspta = $asistencia->existe_asistencia($codigo_persona,$arrFechas[$i]);
			
			if ($rspta>0) {
				array_push($NoAgregados, $arrFechas[$i]);
			}else {
				$rspta = $asistencia->nueva_asistencia($codigo_persona, $arrFechas[$i], $anotacion, $tipoAsistencia);
				array_push($Agregados, $arrFechas[$i]);
			}
			$i++;
		}

		$arrDates = array (
			$Agregados,
			$NoAgregados
		);
		
		echo json_encode($arrDates);
	break;	

	case 'editar_entrada':
		$idasistencia = $_REQUEST["id_asistencia"];
		$hora = $_REQUEST["hora"];
		$dia = $_REQUEST["dia"];
		$asistencia->editar_entrada($idasistencia, $hora, $dia);
	break;	

	case 'editar_salida':
		$idasistencia = $_REQUEST["id_asistencia"];
		$hora = $_REQUEST["hora"];
		$dia = $_REQUEST["dia"];
		$asistencia->editar_salida($idasistencia, $hora, $dia);
	break;	


	case 'borrar_asistencia':
		$id_asistencia = $_REQUEST["id_asistencia"];
		$asistencia->borrar_asistencia($id_asistencia);
	break;
}
