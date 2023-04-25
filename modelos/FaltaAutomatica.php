<?php
require "../admin/config/Conexion.php";
class FaltaAutomatica{


	//implementamos nuestro constructor
public function __construct(){

}
public function usuarios_con_horario($dia){
    $sql='SELECT u.codigo_persona,h.'.$dia.' FROM usuarios u INNER JOIN horario h on u.codigo_persona = h.codigo_persona WHERE h.'.$dia.';';
	return ejecutarConsulta($sql);
}
public function get_asistencia($codigo_persona){
	$sql='select count(*) as total from asistencia_cancilleria WHERE codigo_persona = "'.$codigo_persona.'" and fecha = CURRENT_DATE;	';
	return ejecutarConsultaSimpleFila($sql);
}

public function falta_automatica($codigo_persona){
    $sql='INSERT INTO asistencia_cancilleria(codigo_persona,entrada,salida,fecha,tipo) 	VALUES ("'. $codigo_persona .'",CURRENT_DATE,CURRENT_DATE,CURRENT_DATE,"Falta");';
	return ejecutarConsulta($sql);
}
}
?>