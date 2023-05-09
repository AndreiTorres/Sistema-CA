<?php 
session_start();
require_once "../modelos/Usuario.php";

$usuario=new Usuario();

$idusuarioc=isset($_POST["idusuarioc"])? limpiarCadena($_POST["idusuarioc"]):"";
$clavec=isset($_POST["clavec"])? limpiarCadena($_POST["clavec"]):"";
$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$apellidos=isset($_POST["apellidos"])? limpiarCadena($_POST["apellidos"]):"";
$login=isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
$iddepartamento=isset($_POST["iddepartamento"])? limpiarCadena($_POST["iddepartamento"]):"";
$idtipousuario=isset($_POST["idtipousuario"])? limpiarCadena($_POST["idtipousuario"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$codigo_persona=isset($_POST["codigo_persona"])? limpiarCadena($_POST["codigo_persona"]):"";
$password=isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
$estado=isset($_POST["estado"])? limpiarCadena($_POST["estado"]):"";
$porcentaje=isset($_POST["Porcentaje"])? limpiarCadena($_POST["Porcentaje"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':

		if (!file_exists($_FILES['imagen']['tmp_name'])|| !is_uploaded_file($_FILES['imagen']['tmp_name']))  
		{
			$imagen=$_POST["imagenactual"];
		}else
		{

			$ext=explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
			 {

			   $imagen = round(microtime(true)).'.'. end($ext);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../../files/usuarios/" . $imagen);
		 	}
		}

		//Hash SHA256 para la contraseña
		$clavehash=hash("SHA256", $password);

		if (empty($idusuario)) {
			$idusuario=$_SESSION["idusuario"];
			try {
				$rspta=$usuario->insertar($nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$email,$clavehash,$imagen,$codigo_persona);
				echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar todos los datos del usuario";
			} catch(exception $e){
				echo "Ya existe un usuario con el dato " . substr(
					$e->getMessage(),15
				);
			}
		}
		else {
			try {
				$rspta=$usuario->editar($idusuario,$nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$email,$imagen,$codigo_persona,$estado);
				echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
			} catch(exception $e){
				echo "Ya existe un usuario con el dato " . substr(
					$e->getMessage(),15
				);
			}
		}
	break;
	

	case 'desactivar':
		$rsptaFoto=$usuario->get_foto($codigo_persona);
		$rspta=$usuario->desactivar($codigo_persona);
		if($rspta == 1) { 
			$estado='Eliminado Correctamente'; 
			unlink("../../files/usuarios/".$rsptaFoto['imagen']);
		}
		echo $estado ;
	break;
	
	case 'mostrar':
		$rspta=$usuario->mostrar($idusuario);
		echo json_encode($rspta);
	break;

	case 'horas_totales':
		$rspta=$usuario->horas_totales($idusuario);
		echo json_encode($rspta);
	break;	

	case 'reiniciar_horas':
		$rspta=$usuario->reiniciar_horas($codigo_persona);
		echo $rspta ? "Se han reiniciado las horas correctamente" : "No se pudo reiniciar las horas";
	break;

	case 'editar_clave':
		$clavehash=hash("SHA256", $clavec);

		$rspta=$usuario->editar_clave($idusuarioc,$clavehash);
		echo $rspta ? "Password actualizado correctamente" : "No se pudo actualizar el password";
	break;

	case 'mostrar_clave':
		$rspta=$usuario->mostrar_clave($idusuario);
		echo json_encode($rspta);
	break;
	
	case 'listar':
		$rspta=$usuario->listar();
		//declaramos un array
		$data=Array();


		while ($reg=$rspta->fetch_object()) {

			$practicante= 400;
			$servicio= 480;

			switch($reg->idtipousuario){
				case 'Practicante': $porcentaje= round(intval($reg->horas)/$practicante *100);
				break;

				case 'Servicio social': $porcentaje=round(intval($reg->horas)/$servicio *100);
				
				break;
			}
				
			$data[]=array(
				"0"=>"",
				"1"=>'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-info btn-xs" onclick="mostrar_clave('.$reg->idusuario.')"><i class="fa fa-key"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="desactivar('."'".$reg->codigo_persona."'".')"><i class="fa fa-close"></i></button>',
				"2"=>$reg->apellidos,
				"3"=>$reg->nombre,
				"4"=>$reg->idtipousuario,
				"5"=>$reg->iddepartamento,
				"6"=>$reg->email,
				"7"=>"<img src='../../files/usuarios/".$reg->imagen."' height='50px' width='50px'>",
				"8"=>$reg->fechacreado, 
				"9"=>$reg->horas,
				"10"=>$porcentaje ."%"

				);
		}

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

	break;	

}
?>