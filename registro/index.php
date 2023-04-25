<?php
// Include config file
require_once "config.php";
$DB = $link;
 
// Define variables and initialize with empty values
$username = $username_valid = $username_err = "";
$password = $password_err = $confirm_password = $confirm_password_err = "";
$email = $valid_email = $email_err = "";
$nombre = $valid_nombre = $nombre_err = "";
$apellidos = $valid_apellidos = $apellidos_err = "";
$universidad = $valid_uni = $universidad_err = "";
$us="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor ingrese un usuario.";
    } else{
        // Prepare a select statement
        $sql = "SELECT idusuario FROM usuarios WHERE login = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt)>= 1){
                    echo '<script language="javascript">alert("Usuario/Correo electronico ya en uso");</script>';
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Al parecer algo salió mal.";
            }
        }
        $us=$_POST['username'];

         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor ingresa una contraseña.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "La contraseña al menos debe tener 6 caracteres.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Confirma tu contraseña.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "No coincide la contraseña.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO usuarios (nombre, apellidos, email, login, password, iddepartamento, idtipousuario) VALUES (?, ?, ?, ?, ?, ?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssss", $param_nombre, $param_apellidos, $param_email, $param_username, $param_password, $param_iddepartamento, $param_tipousuario);
            
            // Set parameters
            $param_nombre = $_POST["nombre"];
            $param_apellidos = $_POST["apellidos"];
            $param_email = $_POST["email"];
            $param_username = $username;
            $param_password = hash("SHA256",$password); // Creates a password hash
            $param_iddepartamento = $_POST["universidad"];
            $param_tipousuario = $_POST["tipousuario"];

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                session_start();
                    //Coreo para el administrador
                    $to = "jcastillob@sre.gob.mx";
                    $subject = "Registro de nuevo becario";
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    
                    $message = "
                    <html>
                    <head>
                    <title>HTML</title>
                    </head>
                    <body>
                    <h1>Se ha registrado un nuevo becario.</h1>
                    <h2>Nombre: ".$param_nombre."</h2>
                    <h2>Apellidos: ". $param_apellidos."</h2>
                    <h2>Favor de validar los datos proporcionados y cargar la fotografía correspondiente para terminar el perfil</h2>
                    </body>
                    </html>";
                    
                    mail($to, $subject, $message, $headers);
                    
                    
                    //Correo para el usuario
                        $to = $_POST["email"];
                        $subject = "Registro al sistema de acceso SRE";
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                                        
                        $message = "
                    <!DOCTYPE html>
                    <html lang='en' xmlns='http://www.w3.org/1999/xhtml' xmlns:o='urn:schemas-microsoft-com:office:office'>
                    <head>
                    	<meta charset='UTF-8'>
                    	<meta name='viewport' content='width=device-width,initial-scale=1'>
                    	<meta name='x-apple-disable-message-reformatting'>
                    	<title></title>
                    	<!--[if mso]>
                    	<noscript>
                    		<xml>
                    			<o:OfficeDocumentSettings>
                    				<o:PixelsPerInch>96</o:PixelsPerInch>
                    			</o:OfficeDocumentSettings>
                    		</xml>
                    	</noscript>
                    	<![endif]-->
                    	<style>
                    		table, td, div, h1, p {font-family: Arial, sans-serif;}
                    	</style>
                    </head>
                    <body style='margin:0;padding:0;'>
                    	<table role='presentation' style='width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;'>
                    		<tr>
                    			<td align='center' style='padding:0;'>
                    				<table role='presentation' style='width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;'>
                    					<tr>
                    						<td align='center' style='padding:40px 0 30px 0;background:#70bbd9;'>
                    							<img src='../admin/public/images/SRE Nuevo Logo.png' alt='' width='300' style='height:auto;display:block;' />
                    						</td>
                    					</tr>
                    					<tr>
                    						<td style='padding:36px 30px 42px 30px;'>
                    							<table role='presentation' style='width:100%;border-collapse:collapse;border:0;border-spacing:0;'>
                    								<tr>
                    									<td style='padding:0 0 36px 0;color:#153643;'>
                    										<h1 style='font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;'>¡Bienvenido!</h1>
                    										<p style='margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;'>Tu pre-registro se ha realizado con éxito. Para poder terminar de configurar tu perfil, es necesario que envies una fotografía al correo: </p>
                    										<p style='margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;'><a href='http://www.example.com' style='color:#ee4c50;text-decoration:underline;'>jcastillob@sre.gob.mx</a></p>
                    										<br>
                    										<p style='margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;'>Usuario: ".$_POST["username"]."</a></p>
                    										<p style='margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;'><a href='https://www.sremid.mx' style='color:#ee4c50;text-decoration:underline;'>Puedes acceder a tu perfil aquí: www.sremid.mx</a></p>
                    									</td>
                    								</tr>
                    							</table>
                    						</td>
                    					</tr>
                    					<tr>
                    						<td style='padding:30px;background:#ee4c50;'>
                    							<table role='presentation' style='width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;'>
                    								<tr>
                    									<td style='padding:0;width:50%;' align='left'>
                    										<p style='margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;'>
                    											&reg; SRE Yucatán<br/><a style='color:#ffffff;text-decoration:underline;'></a>
                    										</p>
                    									</td>
                    								</tr>
                    							</table>
                    						</td>
                    					</tr>
                    				</table>
                    			</td>
                    		</tr>
                    	</table>
                    </body>
                    </html>";
                    
                    mail($to, $subject, $message, $headers);
                    //Fin correo al usuario

                header("location: welcome.php");
                    // Close connection
                mysqli_close($link);
            } else{
                echo "Algo salió mal, por favor inténtalo de nuevo.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 0px; }
        p {
          background-image: url('../admin/public/images/register.jpg');
        }
    </style>
</head>
<body class="p">
    <center>
    <div class="wrapper">
        <h2>Registro</h2>
        <p>Por favor complete este formulario para crear una cuenta.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>" autocomplete="no" onkeyup="mayus(this)" required>
                <span class="help-block"><?php echo $nombre_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($apellidos_err)) ? 'has-error' : ''; ?>">
                <label>Apellidos</label>
                <input type="text" name="apellidos" class="form-control" value="<?php echo $apellidos; ?>" autocomplete="no" onkeyup="mayus(this)" required>
                <span class="help-block"><?php echo $apellidos_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>" autocomplete="no" required>
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($universidad_err)) ? 'has-error' : ''; ?>">
                <label for="text"> <b>Dependencia:</b> </label>
                <select class="form-select" name="universidad" id="dependencia" required onchange="mostrar(this.value)">
                    <option value="">Selecciona tu dependencia</option>
                    <?php 
                        $sql="SELECT * FROM departamento order by nombre ASC";               
                            if($resultado=mysqli_query($DB, $sql)){
                                while($datos=mysqli_fetch_array($resultado)){
                                    ?>
                                <option value="<?php echo $datos['iddepartamento']; ?>"><?php echo $datos['nombre'] ?>
                                <?php 
                                }
                            }               
                        ?>
                </select>
                <span class="help-block"><?php echo $universidad_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($tipo_usuario_err)) ? 'has-error' : ''; ?>">
                <label>Tipo de estancia</label>
                <br>
                <select class="form-select" name="tipousuario" id="idtipousuario" required>
                    <option value="2">Practicas profesionales</option>
                    <option value="3">Servicio Social</option>
                    <option value="4">Grupo Tecno</option>
                </select>
            </div>
            <div id="becarios">    
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label>Usuario</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" autocomplete="no">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>  
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <label>Confirmar Contraseña</label>
                    <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Ingresar">
                <input type="reset" class="btn btn-default" value="Borrar">
            </div>
            <p>¿Ya tienes una cuenta? <a href="https:www.sremid.mx/">Ingresa aquí</a>.</p>
        </form>
    </div>
    </center>
</body>
</html>

<script>
  function mayus(e) {
    e.value = e.value.toUpperCase();
}

function mostrar(id) {
 
    if(id == 9){
        $("#becarios").hide();
    }    
     if (id == "11") {
        $("#becarios").hide();
    }
    if(id=="1" || id=="2" ||id=="3" ||id=="4" ||id=="5" ||id=="6" ||id=="7" ||id=="8" ||id=="10"){
        $("#becarios").show();
    }
}
</script>