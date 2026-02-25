<?php
// 1. CAPTURAMOS LAS VARIABLES DE RAILWAY
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT') ?: "3306";

// Si no hay host, es que las variables no están conectadas en el panel de Railway
if (!$host) { 
    die("Error: No se detectaron las variables de entorno. Revisa la pestaña 'Variables' en Railway."); 
}

// 2. CONEXIÓN A LA BASE DE DATOS
$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) { 
    die("Fallo crítico de conexión: " . $conn->connect_error); 
}

$conn->set_charset("utf8mb4");

// 3. CREACIÓN AUTOMÁTICA DE TABLAS (La "Jugada Maestra")
$conn->query("CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario` VARCHAR(50) UNIQUE,
  `password` VARCHAR(255),
  `saldo` DECIMAL(10,2) DEFAULT 0,
  `rol` VARCHAR(20) DEFAULT 'JUGADOR',
  `juego` VARCHAR(50) DEFAULT 'LOBBY'
)");

$conn->query("CREATE TABLE IF NOT EXISTS `movimientos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_usuario` INT,
  `monto` DECIMAL(10,2),
  `tipo` VARCHAR(50),
  `saldo_anterior` DECIMAL(10,2),
  `saldo_nuevo` DECIMAL(10,2),
  `fecha` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// 4. INSERTAR USUARIOS INICIALES (Solo si no existen)
$conn->query("INSERT IGNORE INTO `usuarios` (id, usuario, password, rol, saldo) VALUES (1, 'Alejandro', '12345', 'GERENTE', 1000000)");
$conn->query("INSERT IGNORE INTO `usuarios` (id, usuario, password, rol, saldo) VALUES (2, 'Oscar', '1234', 'JUGADOR', 0)");

?>
