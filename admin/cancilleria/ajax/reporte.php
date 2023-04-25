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
		switch ($_SESSION['tipousuario']){
			case 'Administrador':
				$rspta = $reporte->listar($fecha_inicio, $fecha_fin);
				//declaramos un array
				$data = array();
				while ($reg = $rspta->fetch_object()) {
					$tipo = $reporte->puesto_usuario($reg->iddepartamento);
					$data[] = array(
						0=>"",
						1=>'<div id="cellhover" onclick="mostrarUsuario('."'".$reg->idusuario."'".')"><p>'.$reg->apellidos ." ".$reg->nombre.'</p></div>' ,
						2=>$tipo["tipo"],
						3=> '<div id="cellhover"  onclick="mostrarES('."'".$reg->codigo_persona."'".','."'".$fecha_inicio."'".','."'".$fecha_fin."'".','."'Falta'".')"><p>'.$reg->faltas.'</p></div>',
						4=> '<div id="cellhover"  onclick="mostrarES('."'".$reg->codigo_persona."'".','."'".$fecha_inicio."'".','."'".$fecha_fin."'".','."'Retardo'".')"><p>'.$reg->retardos.'</p></div>',
						5=> '<div id="cellhover"  onclick="mostrarES('."'".$reg->codigo_persona."'".','."'".$fecha_inicio."'".','."'".$fecha_fin."'".','."'Incidencia'".')"><p>'.$reg->incidencias.'</p></div>',
						6=> '<button class="btn btn-info btn-xs" onclick="mostrarES(' . "'" . $reg->codigo_persona . "'" . ',' . "'" . $fecha_inicio . "'" . ',' . "'" . $fecha_fin . "'" . ',' ."''".')"><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i></button>'
					);
				}
				break;
			case 'Administrador Cancillería':
				$rspta = $reporte->listar($fecha_inicio, $fecha_fin);
				//declaramos un array
				$data = array();
				while ($reg = $rspta->fetch_object()) {
					$tipo = $reporte->puesto_usuario($reg->iddepartamento);
					$data[] = array(
						0=>"",
						1=>$reg->idtipousuario=='1'?$reg->apellidos." ".$reg->nombre:'<div id="cellhover" onclick="mostrarUsuario('."'".$reg->idusuario."'".')"><p>'.$reg->apellidos ." ".$reg->nombre.'</p></div>' ,
						2=>$tipo["tipo"],
						3=> '<div id="cellhover"  onclick="mostrarES('."'".$reg->codigo_persona."'".','."'".$fecha_inicio."'".','."'".$fecha_fin."'".','."'Falta'".')"><p>'.$reg->faltas.'</p></div>',
						4=> '<div id="cellhover"  onclick="mostrarES('."'".$reg->codigo_persona."'".','."'".$fecha_inicio."'".','."'".$fecha_fin."'".','."'Retardo'".')"><p>'.$reg->retardos.'</p></div>',
						5=> '<div id="cellhover"  onclick="mostrarES('."'".$reg->codigo_persona."'".','."'".$fecha_inicio."'".','."'".$fecha_fin."'".','."'Incidencia'".')"><p>'.$reg->incidencias.'</p></div>',
						6=> '<button class="btn btn-info btn-xs" onclick="mostrarES(' . "'" . $reg->codigo_persona . "'" . ',' . "'" . $fecha_inicio . "'" . ',' . "'" . $fecha_fin . "'" . ',' ."''".')"><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i></button>'
					);
				}
				break;
		}
		

		$results = array(
			"sEcho" => 1, //info para datatables
			"iTotalRecords" => count($data), //enviamos el total de registros al datatable
			"iTotalDisplayRecords" => count($data), //enviamos el total de registros a visualizar
			"aaData" => $data
		);
		echo json_encode($results);

		break;

	case 'listar_mes':
		$month = $_REQUEST["month"];
		$year = $_REQUEST["year"];
		$fecha_inicio = date($year.'-'.$month.'-01');
		$fecha_fin = date("Y-m-t",strtotime($year.'-'.$month.'-01'));
		$rspta = $reporte->listar_mes($month, $year);
		//declaramos un array
		switch ($_SESSION['tipousuario']){
			case 'Administrador':
				$rspta = $reporte->listar($fecha_inicio, $fecha_fin);
				//declaramos un array
				$data = array();
				while ($reg = $rspta->fetch_object()) {
					$tipo = $reporte->puesto_usuario($reg->iddepartamento);
					$data[] = array(
						0=>"",
						1=>'<div id="cellhover" onclick="mostrarUsuario('."'".$reg->idusuario."'".')"><p>'.$reg->apellidos ." ".$reg->nombre.'</p></div>' ,
						2=>$tipo["tipo"],
						3=> '<div id="cellhover"  onclick="mostrarES('."'".$reg->codigo_persona."'".','."'".$fecha_inicio."'".','."'".$fecha_fin."'".','."'Falta'".')"><p>'.$reg->faltas.'</p></div>',
						4=> '<div id="cellhover"  onclick="mostrarES('."'".$reg->codigo_persona."'".','."'".$fecha_inicio."'".','."'".$fecha_fin."'".','."'Retardo'".')"><p>'.$reg->retardos.'</p></div>',
						5=> '<div id="cellhover"  onclick="mostrarES('."'".$reg->codigo_persona."'".','."'".$fecha_inicio."'".','."'".$fecha_fin."'".','."'Incidencia'".')"><p>'.$reg->incidencias.'</p></div>',
						6=> '<button class="btn btn-info btn-xs" onclick="mostrarES(' . "'" . $reg->codigo_persona . "'" . ',' . "'" . $fecha_inicio . "'" . ',' . "'" . $fecha_fin . "'" . ',' ."''".')"><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i></button>'
					);
				}
				break;
			case 'Administrador Cancillería':
				$rspta = $reporte->listar($fecha_inicio, $fecha_fin);
				//declaramos un array
				$data = array();
				while ($reg = $rspta->fetch_object()) {
					$tipo = $reporte->puesto_usuario($reg->iddepartamento);
					$data[] = array(
						0=>"",
						1=>$reg->idtipousuario=='1'?$reg->apellidos." ".$reg->nombre:'<div id="cellhover" onclick="mostrarUsuario('."'".$reg->idusuario."'".')"><p>'.$reg->apellidos ." ".$reg->nombre.'</p></div>' ,
						2=>$tipo["tipo"],
						3=> '<div id="cellhover"  onclick="mostrarES('."'".$reg->codigo_persona."'".','."'".$fecha_inicio."'".','."'".$fecha_fin."'".','."'Falta'".')"><p>'.$reg->faltas.'</p></div>',
						4=> '<div id="cellhover"  onclick="mostrarES('."'".$reg->codigo_persona."'".','."'".$fecha_inicio."'".','."'".$fecha_fin."'".','."'Retardo'".')"><p>'.$reg->retardos.'</p></div>',
						5=> '<div id="cellhover"  onclick="mostrarES('."'".$reg->codigo_persona."'".','."'".$fecha_inicio."'".','."'".$fecha_fin."'".','."'Incidencia'".')"><p>'.$reg->incidencias.'</p></div>',
						6=> '<button class="btn btn-info btn-xs" onclick="mostrarES(' . "'" . $reg->codigo_persona . "'" . ',' . "'" . $fecha_inicio . "'" . ',' . "'" . $fecha_fin . "'" . ',' ."''".')"><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i></button>'
					);
				}
				break;
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
