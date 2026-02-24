<?php
// CONEXIÓN INTERNA (DE ALTA VELOCIDAD) - CHANGO v4
$host = "mysql.railway.internal"; // ESTE ES EL HOST INTERNO
$user = "root"; 
$pass = "rJwwdlNlDAZRnKiYJUyYUxsJMZOIlySV"; // TU CLAVE LARGA
$db   = "railway"; 
$port = 3306; // PUERTO INTERNO ESTÁNDAR

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Error de conexión satelital: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
