<?php
// CONEXIÓN INTERNA PURA - CHANGO v4
$host = "mysql.railway.internal"; 
$user = "root"; 
$pass = "rJwwdlNlDAZRnKiYJUyYUxsJMZOIlySV"; 
$db   = "railway"; 

// Conectamos sin puerto manual para que Railway lo asigne solo
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error en el satélite: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
