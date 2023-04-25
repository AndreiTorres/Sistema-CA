<?php 
session_start();
require_once "../modelos/Entrada.php";
$entrada=new Entrada();
$clavec=isset($_POST["inputPassword"])? limpiarCadena($_POST["inputPassword"]):"";
switch ($_GET["op"]) {
	case 'editar_clave':
        $clavehash=hash("SHA256", $clavec);
		$rspta=$entrada->editar_clave($clavehash);
		echo $rspta ? "Password actualizado correctamente" : "No se pudo actualizar el password";
        break;
    }
?>