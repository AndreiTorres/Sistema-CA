<?php 
//incluir la conexion de base de datos
require "../admin/config/Conexion.php";
class Asistencia{


	//implementamos nuestro constructor
public function __construct(){

}

public function verificar_usuario($codigo_persona){
	$sql = "SELECT u.nombre,u.apellidos,u.imagen, u.idtipousuario, u.estado, (select t.nombre FROM tipousuario t where t.idtipousuario = u.idtipousuario) as tipo_usuario FROM usuarios u WHERE u.codigo_persona='$codigo_persona' AND u.estado ='1';";
	return ejecutarConsultaSimpleFila($sql);
}

public function tipo_usuario($codigo_persona){
	$sql = "SELECT (select t.nombre FROM tipousuario t where t.idtipousuario = u.idtipousuario) as tipo_usuario FROM usuarios u WHERE u.codigo_persona='$codigo_persona' AND u.estado ='1';";
	return ejecutarConsultaSimpleFila($sql);
}

public function verificarcodigo_persona($codigo_persona){
 	$sql = "SELECT * FROM usuarios WHERE codigo_persona='$codigo_persona' AND estado ='1'";
	return ejecutarConsultaSimpleFila($sql);
}

//Obtener asistencias
public function seleccionarcodigo_persona($codigo_persona){
    $sql = "SELECT * FROM asistencia_becarios WHERE codigo_persona = '$codigo_persona'";
	return ejecutarConsulta($sql);
}

public function ultima_asistencia_becarios($codigo_persona){
	$fecha = date("Y-m-d");
	$sql = "SELECT * FROM asistencia_becarios WHERE codigo_persona = '$codigo_persona' AND fecha = '$fecha' ORDER BY entrada DESC LIMIT 1;";
	return ejecutarConsultaSimpleFila($sql);
}
public function ultima_asistencia_cancilleria($codigo_persona){
	$fecha = date("Y-m-d");
	$sql = "SELECT * FROM asistencia_cancilleria WHERE codigo_persona = '$codigo_persona' AND fecha = '$fecha' ORDER BY entrada DESC LIMIT 1;";
	return ejecutarConsultaSimpleFila($sql);
}
public function ultima_asistencia_tecno($codigo_persona){
	$fecha = date("Y-m-d");
	$sql = "SELECT * FROM asistencia_tecno WHERE codigo_persona = '$codigo_persona' AND fecha = '$fecha' ORDER BY entrada DESC LIMIT 1;";
	return ejecutarConsultaSimpleFila($sql);
}

public function total_asistencia_cancilleria($codigo_persona){
	$sql='select count(*) as total from asistencia_cancilleria WHERE codigo_persona = "'.$codigo_persona.'" and fecha = CURRENT_DATE;	';	return ejecutarConsultaSimpleFila($sql);
	return ejecutarConsultaSimpleFila($sql);
}

//Obtener numero de asistencias del dia
public function get_asistencia_becarios($codigo_persona){
	$sql='select count(*) as total from asistencia_becarios WHERE codigo_persona = "'.$codigo_persona.'" and fecha = CURRENT_DATE;	';
	return ejecutarConsultaSimpleFila($sql);
}

public function get_asistencia_total_becarios($codigo_persona){
	$sql='select count(*) as total from asistencia_becarios WHERE codigo_persona = "'.$codigo_persona.'" ';
	return ejecutarConsultaSimpleFila($sql);
}

public function get_asistencia($codigo_persona){
	$sql='select count(*) as total from asistencia_tecno WHERE codigo_persona = "'.$codigo_persona.'" and fecha = CURRENT_DATE;	';
	return ejecutarConsultaSimpleFila($sql);
}

public function primera_asitencia($codigo_persona){
 	$sql = "UPDATE usuarios SET fechacreado = CURRENT_TIMESTAMP WHERE codigo_persona = '$codigo_persona'";
    return ejecutarConsulta($sql);
}

//Registrar
public function horario_dia($codigo_persona,$dia){
	$sql="SELECT $dia FROM horario WHERE codigo_persona = '$codigo_persona'";
	return ejecutarConsultaSimpleFila($sql);
}

public function get_turno($codigo_persona){
	$sql="SELECT turno FROM turno where codigo_persona = '$codigo_persona'";
	return ejecutarConsultaSimpleFila($sql);
}

public function registrar_entrada_cancilleria($codigo_persona,$tipo){
	date_default_timezone_set('America/Mexico_City');
	$fecha = date("Y-m-d");
	$sql = "INSERT INTO asistencia_cancilleria (codigo_persona,  tipo, fecha) VALUES ('$codigo_persona', '$tipo', '$fecha')";
	return ejecutarConsulta($sql);
}

public function registrar_entrada_tecno($codigo_persona,$tipo){
	date_default_timezone_set('America/Mexico_City');
	$fecha = date("Y-m-d");
	$sql = "INSERT INTO asistencia_tecno (codigo_persona,  tipo, fecha) VALUES ('$codigo_persona', '$tipo', '$fecha')";
	return ejecutarConsulta($sql);
}

public function registrar_salida_cancilleria($idasistencia){
	$sql = "UPDATE asistencia_cancilleria SET salida = CURRENT_TIMESTAMP WHERE idasistencia = $idasistencia";
	return ejecutarConsulta($sql);
}

public function registrar_salida_tecno($idasistencia){
	$sql = "UPDATE asistencia_tecno SET salida = CURRENT_TIMESTAMP WHERE idasistencia = $idasistencia";
	return ejecutarConsulta($sql);
}

public function registrar_entrada($codigo_persona){
	date_default_timezone_set('America/Mexico_City');
	$fecha = date("Y-m-d");
    $sql = "INSERT INTO asistencia_becarios (codigo_persona, entrada, fecha) VALUES ('$codigo_persona', CURRENT_TIMESTAMP, '$fecha')";
	return ejecutarConsulta($sql);
}


public function registrar_salida($idasistencia){
	date_default_timezone_set('America/Mexico_City');
 	$sql = "UPDATE asistencia_becarios SET salida = CURRENT_TIMESTAMP,  horas= (SELECT TIMEDIFF (salida,entrada)) WHERE idasistencia = $idasistencia";
    return ejecutarConsulta($sql);
}

public function buscarimg($id_usuario){
	$sql = "SELECT imagen FROM usuarios WHERE codigo_persona = '$id_usuario'";
	return consultaimg($sql);
}

public function horasacumuladas($codigoper){
	$sql = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas))) AS Horas_acumuladas FROM asistencia WHERE codigo_persona = '$codigoper'";
	return ejecutarConsultaSimpleFila($sql);
}

public function ultimoregistro($codigo_persona){
	$sql = "SELECT MAX(fecha_hora) AS hora FROM asistencia WHERE codigo_persona = '$codigo_persona'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM asistencia";
	return ejecutarConsulta($sql);
}

public function validarPolicia($clavep){
	$sql = "SELECT token FROM tokens WHERE token = '$clavep'";
	return ejecutarConsulta($sql);
}

}

 ?>
