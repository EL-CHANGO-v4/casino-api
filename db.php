<?php
// CONEXIÓN INTERNA - SIN PUERTO MANUAL
$host = "mysql.railway.internal"; 
$user = "root"; 
$pass = "rJwwdlNlDAZRnKiYJUyYUxsJMZOIlySV"; 
$db   = "railway"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
