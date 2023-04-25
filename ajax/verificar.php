<?php
session_start();
require_once "../modelos/Asistencia.php";

$asistencia = new Asistencia();

switch ($_GET["op"]){
    case 'verificar':
        $clavep = $_POST['clavepolicia'];
        $clavehash=hash("SHA256", $clavep);
        $rspsta = $asistencia->validarPolicia($clavehash);
        $fetch = $rspsta->fetch_object();
        if(isset($fetch)){
            $_SESSION['token'] = $fetch->token;
        }
        echo json_encode($fetch);
    break;
    case 'salir':
        session_unset();
        session_destroy();
        header("Location: ../index.php");
    break;
}
