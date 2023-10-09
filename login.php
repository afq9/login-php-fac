<?php
// Iniciar sesión
session_start();
 
// Compruebe si el usuario ya ha iniciado sesión; en caso afirmativo, rediríjalo a la página de bienvenida.
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: welcome.php");
  exit;
}
 
// Incluir archivo de configuración
require_once "config.php";
 
// Define variables
$username = $password = "";
$username_err = $password_err = "";
 
// Procesamiento de datos del formulario cuando se envía el formulario
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Compruebe nombre de usuario 
    if(empty(trim($_POST["username"]))){
        $username_err = "Ingrese su usuario.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Compruebe constraseña
    if(empty(trim($_POST["password"]))){
        $password_err = "Ingrese su contraseña.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Valide credenciales
    if(empty($username_err) && empty($password_err)){
        // Prepare una declaración
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Vincular variables a la declaración
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Establecer parámetros
            $param_username = $username;
            
            // Intenta ejecutar la declaración preparada.
            if(mysqli_stmt_execute($stmt)){
                // Guardar resultado
                mysqli_stmt_store_result($stmt);
                
                // Verifique si el nombre de usuario existe; en caso afirmativo, verifique la contraseña
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Vincular variables de resultados
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // La contraseña es correcta, así que inicie una nueva sesión.
                            session_start();
                            
                            // Almacenar datos en variables de sesión.
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            
                            header("location: welcome.php");
                        } else{
                            // Muestra un mensaje de error si la contraseña no es válida
                            $password_err = "La contraseña que ha ingresado no es válida.";
                        }
                    }
                } else{
                    // Mostrar un mensaje de error si el nombre de usuario no existe
                    $username_err = "No existe cuenta registrada con ese nombre de usuario.";
                }
            } else{
                echo "Algo salió mal, por favor vuelve a intentarlo.";
            }
        }
        
        // Cerrar declaración
        mysqli_stmt_close($stmt);
    }
    
    // Cerrar conexión
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    
   
</head>
<body>
    <div class="wrapper">
        <h2>Inicia sesión</h2>
        <p>Por favor, complete sus credenciales para iniciar sesión.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Usuario</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Ingresar">
            </div>
            <p>¿No tienes una cuenta? <a href="register.php">Regístrate ahora</a>.</p>
        </form>
    </div>    
</body>
</html>