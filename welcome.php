<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    

</head>
<body>
    <div class="page-header">
        <h1>Hola, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Ha iniciado sesión correctamente</h1>
        
    </div>
    <p>
        
        <a href="logout.php" class="btn btn-danger">Cierra la sesión</a>
        
    </p>


</body>
</html>