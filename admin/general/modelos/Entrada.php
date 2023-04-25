<?php 
require "../../config/Conexion.php";
class Entrada
{
    public function editar_clave($clavehash)
    {
        $sql = "UPDATE tokens SET token='$clavehash'";
        return ejecutarConsulta($sql);
    }
}
?>