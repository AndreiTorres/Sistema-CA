<?php 
//incluir la conexion de base de datos
require "../../config/Conexion.php";
class Asistencia{

//implementamos nuestro constructor
public function __construct(){

}

//listar registros
public function listar($fecha_inicio,$fecha_fin){
	$sql="SELECT a.codigo_persona,a.entrada, a.salida,a.fecha,u.nombre,u.apellidos,u.idtipousuario,u.estado,u.idusuario, SEC_TO_TIME(SUM(TIME_TO_SEC(horas))) AS Total FROM asistencia_becarios a INNER JOIN usuarios u ON a.codigo_persona = u.codigo_persona WHERE DATE(a.fecha)>='$fecha_inicio' AND DATE(a.fecha)<='$fecha_fin'  GROUP BY codigo_persona ORDER BY apellidos ASC";
	//SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas))) AS Total FROM asistencia WHERE tipo = 'Salida' AND DATE(fecha)>= '2022-06-28' AND DATE(fecha)<='2022-06-30' GROUP by codigo_persona
	return ejecutarConsulta($sql);
}

public function listar_asistencia($fecha_inicio,$fecha_fin,$codigo_persona){
	$sql="SELECT a.idasistencia,a.codigo_persona,a.entrada, a.salida, a.horas,a.fecha,u.nombre,u.apellidos FROM asistencia_becarios a INNER JOIN usuarios u ON  a.codigo_persona=u.codigo_persona WHERE DATE(a.fecha)>='$fecha_inicio' AND DATE(a.fecha)<='$fecha_fin' AND a.codigo_persona='$codigo_persona' ORDER BY a.entrada ASC";
	return ejecutarConsulta($sql);
}

public function horasacumuladas($codigoper){
	$sql = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas))) AS Horas_acumuladas FROM asistencia_becarios WHERE codigo_persona = '$codigoper'";
	return ejecutarConsultaSimpleFila($sql);
}

public function listar_dias_asistidos($fecha_inicio,$fecha_fin,$codigo_persona){
	$sql="SELECT COUNT(DISTINCT fecha) AS DiasA FROM asistencia_becarios a WHERE a.codigo_persona = '$codigo_persona' AND DATE(a.fecha)>='$fecha_inicio' AND DATE(a.fecha)<='$fecha_fin'";
	return ejecutarConsultaSimpleFila($sql);
}

public function horas_periodo($fecha_inicio,$fecha_fin,$codigo_persona){
	$sql="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas))) AS Total FROM asistencia a  WHERE DATE(a.fecha)>='$fecha_inicio' AND DATE(a.fecha)<='$fecha_fin' AND a.codigo_persona = '$codigo_persona'";
	//SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas))) AS Total FROM asistencia WHERE tipo = 'Salida' AND DATE(fecha)>= '2022-06-28' AND DATE(fecha)<='2022-06-30' GROUP by codigo_persona
	return ejecutarConsultaSimpleFila($sql); 
}

public function horasbtwdate($fecha_inicio,$fecha_fin,$codigoper){
	$sql = "SELECT a.codigo_persona as becario, a.horas as Horas, a.fecha FROM asistencia_becarios a WHERE codigo_persona = '$codigoper' AND DATE(a.fecha)>='$fecha_inicio' AND DATE(a.fecha)<='$fecha_fin'";
	return ejecutarConsultaSimpleFila($sql);
}

public function tipo_usuario($idtipo){
	$sql = "SELECT a.nombre as tipo FROM tipousuario a WHERE idtipousuario = '$idtipo'";
	return ejecutarConsultaSimpleFila($sql);

}

public function nueva_hora($idasistencia,$nueva_hora){
	$sql = "UPDATE asistencia_becarios SET horas='$nueva_hora' WHERE asistencia_becarios.idasistencia = '$idasistencia';";
	return ejecutarConsulta($sql);
}

public function obtenerNombre($codigo_persona){
	$sql = "SELECT a.apellidos FROM usuarios a WHERE '$codigo_persona' = codigo_persona";
	return ejecutarConsultaSimpleFila($sql);
}

public function nueva_asistencia($codigo_persona,$fecha,$nueva_hora) {
	//Se guardan las horas pero no se muestran
	$sql = "INSERT INTO `asistencia_becarios` ( `codigo_persona`, `entrada`, `salida`, `horas`, `fecha`) VALUES ( '$codigo_persona', '$fecha 00:00:00', '$fecha 00:00:01', '$nueva_hora', '$fecha')";
	return ejecutarConsulta($sql);
}

public function borrar_asistencia($idasistencia){
	$sql = "DELETE FROM asistencia_becarios WHERE idasistencia = $idasistencia";
	return ejecutarConsulta($sql);
}


public function existe_asistencia($codigo_persona,$fecha) {
	//Se guardan las horas pero no se muestran
	$sql = "SELECT * FROM asistencia_becarios where codigo_persona = '$codigo_persona' AND fecha = '$fecha'";
	return ejecutarConsultaSimpleFila($sql);
}

}

 ?>