<?php 
//incluir la conexion de base de datos
require "../../config/Conexion.php";
class Usuario{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$email,$clavehash,$imagen,$usuariocreado,$codigo_persona){
	date_default_timezone_set('America/Mexico_City');
	$fechacreado=date('Y-m-d H:i:s');
	$sql="INSERT INTO usuarios (nombre,apellidos,login,iddepartamento,idtipousuario,email,password,imagen,estado,fechacreado,usuariocreado,codigo_persona) VALUES ('$nombre','$apellidos','$login','$iddepartamento','$idtipousuario','$email','$clavehash','$imagen','1','$fechacreado','$usuariocreado','$codigo_persona')";
	return ejecutarConsulta($sql);

}

public function editar($idusuario,$nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$email,$imagen,$usuariocreado,$codigo_persona,$estado){
	$sql="UPDATE usuarios SET nombre='$nombre',apellidos='$apellidos',login='$login',iddepartamento='$iddepartamento',idtipousuario='$idtipousuario',email='$email',imagen='$imagen' ,usuariocreado='$usuariocreado',codigo_persona='$codigo_persona', estado='$estado'    
	WHERE idusuario='$idusuario'";
	 return ejecutarConsulta($sql);

}
public function editar_clave($idusuario,$clavehash){
	$sql="UPDATE usuarios SET password='$clavehash' WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}
public function mostrar_clave($idusuario){
	$sql="SELECT idusuario, password FROM usuarios WHERE idusuario='$idusuario'";
	return ejecutarConsultaSimpleFila($sql);
}
public function desactivar($codigo_persona){
	$sql="DELETE FROM usuarios WHERE codigo_persona = '$codigo_persona'; ";
	$sql.="DELETE FROM asistencia WHERE codigo_persona = '$codigo_persona'; ";
	return ejecutarMultiConsulta($sql);
}

public function get_foto($codigo_persona){
	$sql="SELECT imagen FROM usuarios WHERE codigo_persona='$codigo_persona'";
	return ejecutarConsultaSimpleFila($sql);
}

//metodo para mostrar registros
public function mostrar($idusuario){
	$sql="SELECT * FROM usuarios WHERE idusuario='$idusuario'";
	return ejecutarConsultaSimpleFila($sql);
}

public function horas_totales($idusuario){
	$sql="SELECT u.idusuario, u.codigo_persona, (SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas))) FROM asistencia_becarios a WHERE a.codigo_persona = u.codigo_persona) as horas FROM usuarios u WHERE u.idusuario='$idusuario'";
	return ejecutarConsultaSimpleFila($sql);
}

public function reiniciar_horas($codigo_persona){
	$sql="DELETE FROM asistencia WHERE '$codigo_persona' = codigo_persona";
	return ejecutarConsulta($sql);
}

//listar registros
public function listar(){
	$sql="SELECT DISTINCT u.idusuario,u.apellidos,u.nombre,t.nombre as idtipousuario,u.login,u.email,u.imagen,u.fechacreado,d.nombre as iddepartamento,u.codigo_persona, (SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(horas))) FROM asistencia_becarios a WHERE a.codigo_persona = u.codigo_persona) as horas FROM usuarios u JOIN tipousuario t JOIN departamento d WHERE (u.idtipousuario = 2 OR u.idtipousuario = 3) AND (u.idtipousuario = t.idtipousuario) AND (u.iddepartamento = d.iddepartamento) ORDER BY `u`.`apellidos` ASC";
	return ejecutarConsulta($sql);
}

public function cantidad_usuario(){
	$sql="SELECT count(*) nombre FROM usuarios";
	return ejecutarConsulta($sql);
}

//Función para verificar el acceso al sistema
public function verificar($login,$clave)
{
    $sql="SELECT u.codigo_persona,u.idusuario,u.nombre,u.apellidos,u.login,u.idtipousuario,u.iddepartamento,u.email,u.imagen,u.login, tu.nombre as tipousuario FROM usuarios u INNER JOIN tipousuario tu ON u.idtipousuario=tu.idtipousuario WHERE login='$login' AND password='$clave' AND estado='1'"; 
    return ejecutarConsulta($sql);  
}

public function registrar_token($token){
	$sql =  "UPDATE tokens SET token = '$token'";
	return ejecutarConsulta($sql); 
}

public function validar_policia($clave){
	$sql = "SELECT token FROM tokens WHERE token = '$clave'";
	return ejecutarConsultaSimpleFila($sql);
}
}

 ?>
