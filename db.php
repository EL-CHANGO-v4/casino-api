<?php
$conn = new mysqli("127.0.0.1", "root", "", "club_jugadores");
if ($conn->connect_error) { die("Error: " . $conn->connect_error); }

// LA JUGADA MAESTRA: Creamos la tabla desde acá mismo
$conn->query("CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario` VARCHAR(50),
  `password` VARCHAR(255),
  `saldo` DECIMAL(10,2) DEFAULT 0,
  `rol` VARCHAR(20) DEFAULT 'JUGADOR'
)");

// Insertamos a Alejandro si no está
$conn->query("INSERT IGNORE INTO `usuarios` (id, usuario, password, rol) VALUES (2, 'Alejandro', '12345', 'GERENTE')");
$conn->query("INSERT IGNORE INTO `usuarios` (id, usuario, password, rol) VALUES (1, 'Oscar', '1234', 'JUGADOR')");

$conn->set_charset("utf8mb4");
?>
