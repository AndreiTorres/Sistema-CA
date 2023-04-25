<?php 
//incluir la conexion de base de datos
require "../../config/Conexion.php";
class Reporte{

//implementamos nuestro constructor
public function __construct(){

}

//listar registros
public function listar($fecha_inicio,$fecha_fin){
	$sql="SELECT a.codigo_persona,a.entrada,a.salida,a.fecha, a.anotacion, sum(CASE WHEN tipo ='falta' THEN 1 ELSE 0 END) as faltas,sum(CASE WHEN tipo ='retardo' THEN 1 ELSE 0 END) as retardos,sum(CASE WHEN tipo ='incidencia' THEN 1 ELSE 0 END) as incidencias,u.idusuario,u.nombre,u.apellidos,u.iddepartamento,u.idtipousuario,u.estado FROM asistencia_cancilleria a INNER JOIN usuarios u ON a.codigo_persona = u.codigo_persona WHERE DATE(a.fecha)>='$fecha_inicio' AND DATE(a.fecha)<='$fecha_fin'  GROUP BY codigo_persona ORDER BY apellidos ASC";
	return ejecutarConsulta($sql);
}

public function listar_mes($month,$year){
	$sql="SELECT a.codigo_persona,a.entrada,a.salida,a.fecha, a.anotacion, sum(CASE WHEN tipo ='falta' THEN 1 ELSE 0 END) as faltas,sum(CASE WHEN tipo ='retardo' THEN 1 ELSE 0 END) as retardos,sum(CASE WHEN tipo ='incidencia' THEN 1 ELSE 0 END) as incidencias,u.idusuario,u.nombre,u.apellidos,u.iddepartamento,u.idtipousuario,u.estado FROM asistencia_cancilleria a INNER JOIN usuarios u ON a.codigo_persona = u.codigo_persona WHERE MONTH(a.fecha) = $month AND YEAR(a.fecha)=$year  GROUP BY codigo_persona ORDER BY apellidos ASC";
	return ejecutarConsulta($sql);
}

public function puesto_usuario($idtipo){
	$sql = "SELECT a.nombre as tipo FROM departamento a WHERE iddepartamento = '$idtipo'";
	return ejecutarConsultaSimpleFila($sql);

}

}

 ?>
