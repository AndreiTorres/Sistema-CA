<?php 
//incluir la conexion de base de datos
require "../../config/Conexion.php";
class Asistencia{

//implementamos nuestro constructor
public function __construct(){

}

//listar registros
public function listar_asistencia($fecha_inicio,$fecha_fin,$codigo_persona){
	$sql="SELECT a.idasistencia,a.codigo_persona,a.entrada,a.salida,a.fecha,a.tipo,a.anotacion,u.nombre,u.apellidos FROM asistencia_tecno a INNER JOIN usuarios u ON  a.codigo_persona=u.codigo_persona WHERE DATE(a.fecha)>='$fecha_inicio' AND DATE(a.fecha)<='$fecha_fin' AND a.codigo_persona='$codigo_persona' ORDER BY a.entrada ASC";
	return ejecutarConsulta($sql);
}

public function listar_dias_asistidos($fecha_inicio,$fecha_fin,$codigo_persona){
	$sql="SELECT COUNT(DISTINCT fecha) AS DiasA FROM asistencia a WHERE a.codigo_persona = '$codigo_persona' AND DATE(a.fecha)>='$fecha_inicio' AND DATE(a.fecha)<='$fecha_fin'";
	return ejecutarConsultaSimpleFila($sql);
}

public function tipo_usuario($idtipo){
	$sql = "SELECT a.nombre as tipo FROM tipousuario a WHERE idtipousuario = '$idtipo'";
	return ejecutarConsultaSimpleFila($sql);
}

public function nuevo_tipo($idasistencia,$tipoAsistencia,$anotacion){
	$sql = "UPDATE asistencia_tecno SET tipo='$tipoAsistencia',anotacion='$anotacion' WHERE asistencia_tecno.idasistencia = '$idasistencia';";
	return ejecutarConsulta($sql);
}

public function nueva_asistencia($codigo_persona, $fecha, $anotacion, $tipoAsistencia) {
	//Se guardan las horas pero no se muestran
	$sql = "INSERT INTO `asistencia_tecno` (`idasistencia`, `codigo_persona`, `entrada`,`salida`, `fecha`, `tipo`, `anotacion`) VALUES (NULL, '$codigo_persona', '$fecha 00:00:00', '$fecha 00:00:00', '$fecha', '$tipoAsistencia', '$anotacion')";
	return ejecutarConsulta($sql);
}

public function existe_asistencia($codigo_persona,$fecha) {
	//Se guardan las horas pero no se muestran
	$sql = "SELECT * FROM asistencia_tecno where codigo_persona = '$codigo_persona' AND fecha = '$fecha'";
	return ejecutarConsultaSimpleFila($sql);
}

public function borrar_asistencia($id_asistencia){
	$sql = "DELETE FROM asistencia_tecno WHERE idasistencia = $id_asistencia";
	return ejecutarConsulta($sql);
}

public function editar_entrada($id_asistencia, $hora, $dia){
	$entrada = $dia . " " . $hora;
	$sql = "UPDATE asistencia_tecno SET entrada = '$entrada' WHERE asistencia_tecno.idasistencia = '$id_asistencia';";
	return ejecutarConsulta($sql);
}

public function editar_salida($id_asistencia, $hora, $dia){
	$salida = $dia . " " . $hora;
	$sql = "UPDATE asistencia_tecno SET salida = '$salida' WHERE asistencia_tecno.idasistencia = '$id_asistencia';";
	return ejecutarConsulta($sql);
}

}

 ?>
