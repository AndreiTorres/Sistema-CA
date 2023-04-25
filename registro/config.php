<?php 
//ip de la pc servidor base de datos
//define("DB_HOST", "localhost");
define("DB_HOST", "localhost");

// nombre de la base de datos
//define("DB_NAME", "control_asistencia");
define("DB_NAME", "control_asistencia");

//nombre de usuario de base de datos
//define("DB_USERNAME", "root");
define("DB_USERNAME", "root");

//conraseña del usuario de base de datos
//define("DB_PASSWORD", "");
define("DB_PASSWORD", "");

//codificacion de caracteres
define("DB_ENCODE", "utf8");

//nombre del proyecto
define("PRO_NOMBRE", "CONTROLACCESO");
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
mysqli_query($link, 'SET NAMES "'.DB_ENCODE.'"');
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>