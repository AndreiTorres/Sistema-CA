<?php 
//incluir la conexion de base de datos
require "../../config/Conexion.php";
class Reporte{

//implementamos nuestro constructor
public function __construct(){

}

//listar registros
public function listar($fecha_inicio,$fecha_fin){
	$sql="SELECT a.codigo_persona,a.entrada,a.salida,a.fecha, a.anotacion, sum(CASE WHEN tipo ='asistencia' THEN 1 ELSE 0 END) as asistencias,sum(CASE WHEN tipo ='retardo' THEN 1 ELSE 0 END) as retardos,sum(CASE WHEN tipo ='incidencia' THEN 1 ELSE 0 END) as incidencias, sum(CASE WHEN tipo <> 'Reingreso'  THEN 1 ELSE 0 END) as total,u.idusuario,u.nombre,u.apellidos,u.iddepartamento,u.idtipousuario,u.estado FROM asistencia_tecno a INNER JOIN usuarios u ON a.codigo_persona = u.codigo_persona WHERE DATE(a.fecha)>='$fecha_inicio' AND DATE(a.fecha)<='$fecha_fin'  GROUP BY codigo_persona ORDER BY apellidos ASC";
	return ejecutarConsulta($sql);
}

public function listar_dia($fecha){
	$sql="SELECT a.codigo_persona,a.entrada, (CASE WHEN MAX(CASE WHEN a.salida IS NULL THEN 1 ELSE 0 END) = 0 THEN MAX(a.salida) END) AS salida,a.fecha,u.idusuario,u.nombre,u.apellidos FROM asistencia_tecno a INNER JOIN usuarios u ON a.codigo_persona = u.codigo_persona WHERE a.fecha='$fecha' GROUP BY codigo_persona ORDER BY apellidos ASC;";
	return ejecutarConsulta($sql);
}

public function turno_usuario($codigo_persona){
	$sql = "SELECT t.turno as tipo FROM turno t WHERE codigo_persona = '$codigo_persona'";
	return ejecutarConsultaSimpleFila($sql);
}
}
 ?>
