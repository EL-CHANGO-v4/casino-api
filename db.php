<?php
// Railway inyecta estas variables de entorno automáticamente
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Acá seguí con tus CREATE TABLE IF NOT EXISTS...
$conn->query("CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario` VARCHAR(50) UNIQUE,
  `password` VARCHAR(255),
  `saldo` DECIMAL(10,2) DEFAULT 0,
  `rol` VARCHAR(20) DEFAULT 'JUGADOR',
  `juego` VARCHAR(50) DEFAULT 'Ninguno'
)");
// ... resto del código
?>
