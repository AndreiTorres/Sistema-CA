<?php 
if (strlen(session_id())<1) 
	session_start();


switch ($_GET["op"]) {
		case 'selectEstado':
			echo '<option value="0">Desactivado</option>';
            echo '<option value="1">Activado</option>';
			break;
}
 ?>