<?php 
//incluir la conexion de base de datos
require "../../config/Conexion.php";
class Usuario{
	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
public function insertar($nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$clavehash,$imagen,$usuariocreado,$codigo_persona,$lunes,$martes,$miercoles,$jueves,$viernes,$sabado,$domingo){
	date_default_timezone_set('America/Mexico_City');
	$fechacreado=date('Y-m-d H:i:s');
	if ($login!==''){
		$sql="INSERT INTO usuarios (nombre,apellidos,login,iddepartamento,idtipousuario,password,imagen,estado,fechacreado,usuariocreado,codigo_persona) VALUES ('$nombre','$apellidos','$login','$iddepartamento','$idtipousuario','$clavehash','$imagen','1','$fechacreado','$usuariocreado','$codigo_persona');";
	} else {
		$sql="INSERT INTO usuarios (nombre,apellidos,iddepartamento,idtipousuario,imagen,estado,fechacreado,usuariocreado,codigo_persona) VALUES ('$nombre','$apellidos','$iddepartamento','$idtipousuario','$imagen','1','$fechacreado','$usuariocreado','$codigo_persona');";
	}
	$sql.="INSERT INTO horario (codigo_persona) VALUES ('$codigo_persona');";
	$sql.=$lunes!=null?"UPDATE horario SET Monday='$lunes' WHERE codigo_persona='$codigo_persona';":"";
	$sql.=$martes!=null?"UPDATE horario SET Tuesday='$martes' WHERE codigo_persona='$codigo_persona';":"";
	$sql.=$miercoles!=null?"UPDATE horario SET Wednesday='$miercoles' WHERE codigo_persona='$codigo_persona';":"";
	$sql.=$jueves!=null?"UPDATE horario SET Thursday='$jueves' WHERE codigo_persona='$codigo_persona';":"";
	$sql.=$viernes!=null?"UPDATE horario SET Friday='$viernes' WHERE codigo_persona='$codigo_persona';":"";
	$sql.=$sabado!=null?"UPDATE horario SET Saturday='$sabado' WHERE codigo_persona='$codigo_persona';":"";
	$sql.=$domingo!=null?"UPDATE horario SET Sunday='$domingo' WHERE codigo_persona='$codigo_persona';":"";
	return ejecutarMultiConsulta($sql);
}

public function editar($idusuario,$nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$imagen,$usuariocreado,$codigo_persona,$lunes,$martes,$miercoles,$jueves,$viernes,$sabado,$domingo){
	if ($login!==''){
		$sql="UPDATE usuarios SET nombre='$nombre',apellidos='$apellidos',login='$login',iddepartamento='$iddepartamento',idtipousuario='$idtipousuario',imagen='$imagen' ,usuariocreado='$usuariocreado',codigo_persona='$codigo_persona' WHERE idusuario='$idusuario';";
	} else {
		$sql="UPDATE usuarios SET nombre='$nombre',apellidos='$apellidos',iddepartamento='$iddepartamento',idtipousuario='$idtipousuario',imagen='$imagen' ,usuariocreado='$usuariocreado',codigo_persona='$codigo_persona' WHERE idusuario='$idusuario';";
	}
	$sql.=$lunes!=null?"UPDATE horario SET Monday='$lunes' WHERE codigo_persona='$codigo_persona';":"UPDATE horario SET Monday=NULL WHERE codigo_persona='$codigo_persona';";
	$sql.=$martes!=null?"UPDATE horario SET Tuesday='$martes' WHERE codigo_persona='$codigo_persona';":"UPDATE horario SET Tuesday=NULL WHERE codigo_persona='$codigo_persona';";
	$sql.=$miercoles!=null?"UPDATE horario SET Wednesday='$miercoles' WHERE codigo_persona='$codigo_persona';":"UPDATE horario SET Wednesday=NULL WHERE codigo_persona='$codigo_persona';";
	$sql.=$jueves!=null?"UPDATE horario SET Thursday='$jueves' WHERE codigo_persona='$codigo_persona';":"UPDATE horario SET Thursday=NULL WHERE codigo_persona='$codigo_persona';";
	$sql.=$viernes!=null?"UPDATE horario SET Friday='$viernes' WHERE codigo_persona='$codigo_persona';":"UPDATE horario SET Friday=NULL WHERE codigo_persona='$codigo_persona';";
	$sql.=$sabado!=null?"UPDATE horario SET Saturday='$sabado' WHERE codigo_persona='$codigo_persona';":"UPDATE horario SET Saturday=NULL WHERE codigo_persona='$codigo_persona';";
	$sql.=$domingo!=null?"UPDATE horario SET Sunday='$domingo' WHERE codigo_persona='$codigo_persona';":"UPDATE horario SET Sunday=NULL WHERE codigo_persona='$codigo_persona';";
	return ejecutarMultiConsulta($sql);
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
	$sql.="DELETE FROM asistencia_cancilleria WHERE codigo_persona = '$codigo_persona'; ";
	$sql.="DELETE FROM horario WHERE codigo_persona = '$codigo_persona'; ";
	return ejecutarMultiConsulta($sql);
}

public function get_foto($codigo_persona){
	$sql="SELECT imagen FROM usuarios WHERE codigo_persona='$codigo_persona'";
	return ejecutarConsultaSimpleFila($sql);
}

//metodo para mostrar registros
public function mostrar($idusuario){
	//$sql="SELECT * FROM usuarios WHERE idusuario='$idusuario'";
	$sql="SELECT * FROM usuarios inner join horario on usuarios.codigo_persona = horario.codigo_persona WHERE idusuario='$idusuario';";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT DISTINCT u.idusuario,u.apellidos,u.nombre,t.nombre as idtipousuario,u.imagen,d.iddepartamento,d.nombre as departamento,u.codigo_persona FROM usuarios u JOIN tipousuario t JOIN departamento d WHERE (u.idtipousuario = 1 OR u.idtipousuario = 6 OR u.idtipousuario = 7) AND (u.idtipousuario = t.idtipousuario) AND (u.iddepartamento = d.iddepartamento) ORDER BY iddepartamento ASC, apellidos ASC;";
	return ejecutarConsulta($sql);
}

public function selectPersona(){
	$sql="SELECT codigo_persona, nombre, apellidos FROM usuarios WHERE idtipousuario=1 OR idtipousuario=6 OR idtipousuario=7 ORDER BY apellidos ASC;";
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
