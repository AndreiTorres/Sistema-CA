<?php
require_once "../modelos/Asistencia.php";
require_once "../modelos/FaltaAutomatica.php";
require_once "../admin/config/Conexion.php";

$asistencia = new Asistencia();
$falta_auto = new FaltaAutomatica();

$codigo_persona = isset($_POST["codigo_persona"]) ? limpiarCadena($_POST["codigo_persona"]) : "";
$iddepartamento = isset($_POST["iddepartamento"]) ? limpiarCadena($_POST["iddepartamento"]) : "";

//$usuario = new Usuario();

class Mensaje
{
	public function entrada($imagen, $tipo, $nombre, $hora)
	{
		$styleimg = "";
		$styletipo = "";
		switch ($tipo) {
			case 'Administrador':
			case 'Administrador Cancillería':
			case 'Cancillería':
				$styleimg = "image-cancilleria";
				$styletipo = "tipo-cancilleria";
				break;
			case 'Administrador Tecno':
			case 'Tecno':
				$styleimg = "image-tecno";
				$styletipo = "tipo-tecno";
				break;
			default:
				$styleimg = "image-becarios";
				$styletipo = "tipo-becarios";
				break;
		};
		echo "<div align='center'><br><img class='" . $styleimg . "' src='../admin/files/usuarios/" . $imagen . "' height='240px' width='200px'></div>";
		echo "<div align='center'><h3><strong class='" . $styletipo . "'>" . $tipo . "</strong> <br>" . $nombre . '</h3><div class="alert alert-success"> <strong>Ingreso</strong> registrado ' . $hora . '</div></div>';
	}

	public function salida($imagen, $tipo, $nombre, $hora)
	{
		$styleimg = "";
		$styletipo = "";
		switch ($tipo) {
			case 'Administrador':
			case 'Administrador Cancillería':
			case 'Cancillería':
				$styleimg = "image-cancilleria";
				$styletipo = "tipo-cancilleria";
				break;
			case 'Administrador Tecno':
			case 'Tecno':
				$styleimg = "image-tecno";
				$styletipo = "tipo-tecno";
				break;
			default:
				$styleimg = "image-becarios";
				$styletipo = "tipo-becarios";
				break;
		};
		echo "<div align='center'><br><img class='" . $styleimg . "' src='../admin/files/usuarios/" . $imagen . "' height='240px' width='200px'></div>";
		echo "<div align='center'><h3><strong class='" . $styletipo . "'>" . $tipo . "</strong> <br>" . $nombre . '</h3><div class="alert alert-danger"> <strong>Salida</strong> registrada ' . $hora . '</div></div>';
	}
}

switch ($_GET["op"]) {
	case 'verificar_usuario':
		$mensaje = new Mensaje();
		$result = $asistencia->verificar_usuario($codigo_persona);
		if ($result > 0) {
			date_default_timezone_set('America/Mexico_City');
			$hora = date("H:i:s");
			$fecha = date("Y-m-d");
			switch ($result['tipo_usuario']) {
				case 'Administrador':
				case 'Administrador Cancillería':
				case 'Cancillería':
					$ultimo = $asistencia->ultima_asistencia_cancilleria($codigo_persona);
					if ($ultimo == null || $ultimo['salida'] != '') {
						$mensaje->entrada($result['imagen'], $result['tipo_usuario'], $result['apellidos'] . " " . $result['nombre'], $hora);
					} else {
						$mensaje->salida($result['imagen'], $result['tipo_usuario'], $result['apellidos'] . " " . $result['nombre'], $hora);
					}
					break;
				case 'Administrador Tecno':
				case 'Tecno':
					$ultimo = $asistencia->ultima_asistencia_tecno($codigo_persona);
					if ($ultimo == null || $ultimo['salida'] != '') {
						$mensaje->entrada($result['imagen'], $result['tipo_usuario'], $result['apellidos'] . " " . $result['nombre'], $hora);
					} else {
						$mensaje->salida($result['imagen'], $result['tipo_usuario'], $result['apellidos'] . " " . $result['nombre'], $hora);
					}
					break;
				default:
					if (($asistencia->get_asistencia_becarios($codigo_persona))['total'] == 0) {
						$mensaje->entrada($result['imagen'], $result['tipo_usuario'], $result['apellidos'] . " " . $result['nombre'], $hora);
					} else {
						$ultimo = $asistencia->ultima_asistencia_becarios($codigo_persona);
						if ($ultimo['salida'] != "") {
							$mensaje->entrada($result['imagen'], $result['tipo_usuario'], $result['apellidos'] . " " . $result['nombre'], $hora);
						} else {
							$mensaje->salida($result['imagen'], $result['tipo_usuario'], $result['apellidos'] . " " . $result['nombre'], $hora);
						}
					}
			}
		}
		break;
	case 'registrar_asistencia':
		// !!!!!!!!determinar tipo de usuario y hace switch
		$result = $asistencia->tipo_usuario($codigo_persona);
		date_default_timezone_set('America/Mexico_City');
		$fecha = date("Y-m-d");
		$hora = date("H:i:s");
		switch ($result['tipo_usuario']) {
			case 'Administrador':
			case 'Administrador Cancillería':
			case 'Cancillería':
				if (($falta_auto->get_asistencia($codigo_persona))['total'] == 0) {
					//$ultimo = $asistencia->ultima_asistencia_cancilleria($codigo_persona);
					//Verificar el retardo
					$dia = date('l', strtotime($fecha));
					$horario = $asistencia->horario_dia($codigo_persona, $dia);
					if ($horario[$dia] == null) {
						$asistencia->registrar_entrada_cancilleria($codigo_persona, "Asistencia");
					} else {
						list($hour, $minute, $second) = explode(':', $horario[$dia]);
						$tiempo_limite = 0;
						$tiempo_limite += $hour * 3600;
						$tiempo_limite += $minute * 60;
						$tiempo_limite += $second;
						$tiempo_limite += 900;
						list($hour, $minute, $second) = explode(':', $hora);
						$registro = 0;
						$registro += $hour * 3600;
						$registro += $minute * 60;
						$registro += $second;
						if ($registro > $tiempo_limite) {
							$asistencia->registrar_entrada_cancilleria($codigo_persona, "Retardo");
						} else {
							$asistencia->registrar_entrada_cancilleria($codigo_persona, "Asistencia");
						}
					}
				} else {
					$ultimo = $asistencia->ultima_asistencia_cancilleria($codigo_persona);
					if ($ultimo['salida'] != "") {
						$asistencia->registrar_entrada_cancilleria($codigo_persona, "Asistencia");
					} else {
						$asistencia->registrar_salida_cancilleria($ultimo['idasistencia']);
					}
				}
				break;
			case 'Administrador Tecno':
			case 'Tecno':
				if (($asistencia->get_asistencia($codigo_persona))['total'] == 0) {
					$turno = $asistencia->get_turno($codigo_persona);
					list($hour, $minute, $second) = explode(':', $hora);
					$registro = 0;
					$registro += $hour * 3600;
					$registro += $minute * 60;
					$registro += $second;
					switch ($turno['turno']) {
						case 'Matutino':
							//antes de las 8:05
							if ($registro > 29100) {
								$asistencia->registrar_entrada_tecno($codigo_persona, "Retardo");
							} else {
								$asistencia->registrar_entrada_tecno($codigo_persona, "Asistencia");
							}
							break;
						case 'Vespertino':
							if ($registro > 50700) {
								$asistencia->registrar_entrada_tecno($codigo_persona, "Retardo");
							} else {
								$asistencia->registrar_entrada_tecno($codigo_persona, "Asistencia");
							}
							break;
					}
				} else {
					$ultimo = $asistencia->ultima_asistencia_tecno($codigo_persona);
					if ($ultimo['salida'] != "") {
						$asistencia->registrar_entrada_tecno($codigo_persona, "Reingreso");
					} else {
						$asistencia->registrar_salida_tecno($ultimo['idasistencia']);
					}
				}
				break;
			default:
			
			if (($asistencia->get_asistencia_total_becarios($codigo_persona))['total'] == 0) {
						$asistencia->primera_asitencia($codigo_persona);
					  echo 'entrada';
	
				  }
				if (($asistencia->get_asistencia_becarios($codigo_persona))['total'] == 0) {
					$asistencia->registrar_entrada($codigo_persona);
				} else {
					$ultimo = $asistencia->ultima_asistencia_becarios($codigo_persona);
					if ($ultimo['salida'] != "") {
						$asistencia->registrar_entrada($codigo_persona);
					} else {
						$salida = $asistencia->registrar_salida($ultimo['idasistencia']);
					}
				}
		}
		break;
	case 'falta_automatica':
		date_default_timezone_set('America/Mexico_City');
		$fecha = date("Y-m-d");
		$dia = date('l', strtotime($fecha));
		$usuarios = $falta_auto->usuarios_con_horario($dia);
		while ($reg = $usuarios->fetch_object()) {
			if (($falta_auto->get_asistencia($reg->codigo_persona))['total'] == 0) {
				$falta_auto->falta_automatica($reg->codigo_persona);
			}
		}
		break;
}
