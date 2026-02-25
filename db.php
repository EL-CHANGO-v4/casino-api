<?php
// Railway inyecta estas variables automáticamente si las vinculaste en el panel
$host = getenv('MYSQLHOST') ?: "localhost";
$user = getenv('MYSQLUSER') ?: "root";
$pass = getenv('MYSQLPASSWORD') ?: "";
$db   = getenv('MYSQLDATABASE') ?: "railway";
$port = getenv('MYSQLPORT') ?: "3306";

// Conexión usando las variables de entorno de la nube
$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) { 
    die("Error de conexión: " . $conn->connect_error); 
}

// 1. Crear tabla de usuarios
$conn->query("CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario` VARCHAR(50) UNIQUE,
  `password` VARCHAR(255),
  `saldo` DECIMAL(10,2) DEFAULT 0,
  `rol` VARCHAR(20) DEFAULT 'JUGADOR',
  `juego` VARCHAR(50) DEFAULT 'Ninguno'
)");

// 2. Crear tabla de movimientos
$conn->query("CREATE TABLE IF NOT EXISTS `movimientos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_usuario` INT,
  `monto` DECIMAL(10,2),
  `tipo` VARCHAR(50),
  `saldo_anterior` DECIMAL(10,2),
  `saldo_nuevo` DECIMAL(10,2),
  `fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// 3. Insertar usuarios base (Solo si no existen)
$conn->query("INSERT IGNORE INTO `usuarios` (id, usuario, password, rol) VALUES (2, 'Alejandro', '12345', 'GERENTE')");
$conn->query("INSERT IGNORE INTO `usuarios` (id, usuario, password, rol) VALUES (1, 'Oscar', '1234', 'JUGADOR')");

$conn->set_charset("utf8mb4");
?>
