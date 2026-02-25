<?php
// 1. CAPTURAMOS LAS VARIABLES DE RAILWAY (CON RESPALDO DE $_ENV)
$host = getenv('MYSQLHOST') ?: ($_ENV['MYSQLHOST'] ?? null);
$user = getenv('MYSQLUSER') ?: ($_ENV['MYSQLUSER'] ?? null);
$pass = getenv('MYSQLPASSWORD') ?: ($_ENV['MYSQLPASSWORD'] ?? null);
$db   = getenv('MYSQLDATABASE') ?: ($_ENV['MYSQLDATABASE'] ?? null);
$port = getenv('MYSQLPORT') ?: ($_ENV['MYSQLPORT'] ?? "3306");

// Si no hay host, detenemos con un mensaje claro
if (!$host) { 
    die("Error crítico: El servidor PHP no recibe las variables de Railway. Verifica la pestaña 'Variables'."); 
}

// 2. ESTABLECER CONEXIÓN
$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) { 
    die("Fallo de conexión a la base de datos: " . $conn->connect_error); 
}

$conn->set_charset("utf8mb4");

// 3. CREACIÓN AUTOMÁTICA DE TABLAS (ESTRUCTURA DEL CASINO)
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

// 4. INSERTAR USUARIOS DE PRUEBA (Solo si la tabla está vacía)
$check = $conn->query("SELECT id FROM usuarios LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO `usuarios` (usuario, password, rol, saldo) VALUES ('Alejandro', '12345', 'GERENTE', 1000.00)");
    $conn->query("INSERT INTO `usuarios` (usuario, password, rol, saldo) VALUES ('Oscar', '1234', 'JUGADOR', 0.00)");
}

?>
