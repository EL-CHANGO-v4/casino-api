<?php
// CONEXIÓN INTERNA - CHANGO v4
$host = "mysql.railway.internal"; 
$user = "root"; 
$pass = "rJwwdlNlDAZRnKiYJUyYUxsJMZOIlySV"; 
$db   = "railway"; 

// Sin puerto, Railway lo rutea solo por el nombre
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error satelital: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
