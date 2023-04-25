<?php 
//incluir la conexion de base de datos
require "../../config/Conexion.php";
class Asistencia{

//implementamos nuestro constructor
public function __construct(){

}

//listar registros
public function listar($fecha_inicio,$fecha_fin){
	$sql="SELECT a.codigo_persona,a.fecha_hora,a.tipo,a.fecha,u.nombre,u.apellidos,u.idtipousuario,u.estado, SEC_TO_TIME(SUM(TIME_TO_SEC(horas))) AS Total FROM asistencia a INNER JOIN usuarios u ON a.codigo_persona = u.codigo_persona WHERE DATE(a.fecha)>='$fecha_inicio' AND DATE(a.fecha)<='$fecha_fin'  GROUP BY codigo_persona ORDER BY apellidos ASC";
	return ejecutarConsulta($sql);
}

public function listar_asistencia($fecha_inicio,$fecha_fin,$codigo_persona){
	$sql="SELECT a.idasistencia,a.codigo_persona,a.entrada,a.salida,a.fecha,a.tipo,a.anotacion,u.nombre,u.apellidos FROM asistencia_cancilleria a INNER JOIN usuarios u ON  a.codigo_persona=u.codigo_persona WHERE DATE(a.fecha)>='$fecha_inicio' AND DATE(a.fecha)<='$fecha_fin' AND a.codigo_persona='$codigo_persona' ORDER BY a.entrada ASC";
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
	$sql = "UPDATE asistencia_cancilleria SET tipo='$tipoAsistencia',anotacion='$anotacion' WHERE asistencia_cancilleria.idasistencia = '$idasistencia';";
	return ejecutarConsulta($sql);
}

public function nueva_asistencia($codigo_persona, $fecha, $anotacion, $tipoAsistencia) {
	//Se guardan las horas pero no se muestran
	$sql = "INSERT INTO `asistencia_cancilleria` (`idasistencia`, `codigo_persona`, `entrada`,`salida`, `fecha`, `tipo`, `anotacion`) VALUES (NULL, '$codigo_persona', '$fecha 00:00:00', '$fecha 00:00:00', '$fecha', '$tipoAsistencia', '$anotacion')";
	return ejecutarConsulta($sql);
}

public function existe_asistencia($codigo_persona,$fecha) {
	//Se guardan las horas pero no se muestran
	$sql = "SELECT * FROM asistencia_cancilleria where codigo_persona = '$codigo_persona' AND fecha = '$fecha'";
	return ejecutarConsultaSimpleFila($sql);
}

public function borrar_asistencia($id_asistencia){
	$sql = "DELETE FROM asistencia_cancilleria WHERE idasistencia = $id_asistencia";
	return ejecutarConsulta($sql);
}
}

 ?>
