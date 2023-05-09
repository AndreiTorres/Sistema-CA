<?php

//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
  exit;
}

require 'phpqrcode/qrlib.php';

$dir = './temp/';

if(!file_exists($dir))
    mkdir($dir);

$filename = $dir.'test.png';

$tamanio = 35;
$level = 'M';
$frameSize = 0;
$contenido = $_SESSION['codigo_persona'];

QRcode::png($contenido, $filename, $level, $tamanio, $frameSize);


// cargar las dos imágenes que deseas combinar
$fondo = imagecreatefromjpeg('./plantilla.jpg');
$superposicion = imagecreatefrompng($filename);

// Obtener las dimensiones de ambas imágenes
$ancho_fondo = imagesx($fondo);
$alto_fondo = imagesy($fondo);
$ancho_superposicion = imagesx($superposicion);
$alto_superposicion = imagesy($superposicion);

// Calcular las coordenadas para superponer la imagen
$x = ($ancho_fondo - $ancho_superposicion) / 2;
$y = ($alto_fondo - $alto_superposicion) / 6;

// Superponer la imagen en el fondo
imagecopymerge($fondo, $superposicion, $x, $y, 0, 0, $ancho_superposicion, $alto_superposicion, 100);

$bg_color = imagecolorallocate($fondo, 255, 255, 255); // blanco
$text_color = imagecolorallocate($fondo, 255, 255, 255); // negro
$text = $_SESSION['nombre'] . "\n" . $_SESSION['apellidos']; 
imagettftext($fondo, 50, 0, 100, 1100, $text_color, './arial.ttf', $text);

// Establecer los headers para indicar que se va a descargar una imagen
header('Content-Type: image/jpeg');
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="QR-SRE.jpg"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');

// Mostrar la imagen en el navegador
imagejpeg($fondo);

// Limpiar el buffer de salida y terminar la ejecución del script
imagedestroy($fondo);
imagedestroy($superposicion);
unlink($filename);
exit;

?>