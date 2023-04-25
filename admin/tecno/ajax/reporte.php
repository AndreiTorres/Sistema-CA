<?php
require_once "../modelos/Reporte.php";
if (strlen(session_id()) < 1)
	session_start();
$reporte = new Reporte();
$codigo_persona = isset($_POST["codigo_persona"]) ? limpiarCadena($_POST["codigo_persona"]) : "";
$iddepartamento = isset($_POST["iddepartamento"]) ? limpiarCadena($_POST["iddepartamento"]) : "";

switch ($_GET["op"]) {
	case 'listar':
		$fecha_inicio = $_REQUEST["fecha_inicio"];
		$fecha_fin = $_REQUEST["fecha_fin"];
		$rspta = $reporte->listar($fecha_inicio, $fecha_fin);
		//declaramos un array
		$data = array();
		while ($reg = $rspta->fetch_object()) {
			$turno = $reporte->turno_usuario($reg->codigo_persona);
			$data[] = array(
				0 => "",
				1 => '<div id="cellhover" onclick="mostrarUsuario(' . "'" . $reg->idusuario . "'" . ')"><p>' . $reg->apellidos . " " . $reg->nombre . '</p></div>',
				2 => $turno["tipo"],
				3 => '<div id="cellhover"  onclick="mostrarES(' . "'" . $reg->codigo_persona . "'" . ',' . "'" . $fecha_inicio . "'" . ',' . "'" . $fecha_fin . "'" . ',' . "'Asistencia'" . ')"><p>' . $reg->asistencias . '</p></div>',
				4 => '<div id="cellhover"  onclick="mostrarES(' . "'" . $reg->codigo_persona . "'" . ',' . "'" . $fecha_inicio . "'" . ',' . "'" . $fecha_fin . "'" . ',' . "'Retardo'" . ')"><p>' . $reg->retardos . '</p></div>',
				5 => '<div id="cellhover"  onclick="mostrarES(' . "'" . $reg->codigo_persona . "'" . ',' . "'" . $fecha_inicio . "'" . ',' . "'" . $fecha_fin . "'" . ',' . "'Incidencia'" . ')"><p>' . $reg->incidencias . '</p></div>',
				6 => '<div id="cellhover"  onclick="mostrarES(' . "'" . $reg->codigo_persona . "'" . ',' . "'" . $fecha_inicio . "'" . ',' . "'" . $fecha_fin . "'" . ',' ."''".')"><p>' . $reg->total . '</p></div>',
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

	case 'listar_dia':
		$fecha = $_REQUEST["fecha"];
		$rspta = $reporte->listar_dia($fecha);
		//declaramos un array
		$data = array();
		while ($reg = $rspta->fetch_object()) {
			$turno = $reporte->turno_usuario($reg->codigo_persona);
			$data[] = array(
				0 => "",
				1 => '<div id="cellhover" onclick="mostrarUsuario(' . "'" . $reg->idusuario . "'" . ')"><p>' . $reg->apellidos . " " . $reg->nombre . '</p></div>',
				2 => $reg->entrada,
				3 => $reg->salida,
				4 => $turno["tipo"],
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
}
