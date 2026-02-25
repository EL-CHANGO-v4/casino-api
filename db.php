<?php
// Conexión Unificada InfinityFree - Chango v4
$host = "sql212.infinityfree.com"; 
$user = "if0_41237608"; 
// ATENCIÓN: Esta es la clave con la que entrás al panel de InfinityFree
$pass = "ze6RU0UUeKpu"; 
$db   = "if0_41237608_casino"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
