<?php
/* Credenciales de la base de datos. */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'login');
 
/* Conectarse a la base de datos MySQL */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Conexiòn correcta
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>