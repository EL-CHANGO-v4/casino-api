<?php
include 'db.php';

// 1. Borramos las tablas intrusas desde el propio PHP
$conn->query("DROP TABLE IF EXISTS jugadores");
$conn->query("DROP TABLE IF EXISTS vendedores");
$conn->query("DROP TABLE IF EXISTS configuracion");

// 2. Limpiamos la tabla usuarios y dejamos solo a los Jefes
$conn->query("TRUNCATE TABLE usuarios");
$conn->query("INSERT INTO usuarios (usuario, password, saldo, rol) VALUES ('EL CHANGO v4', '1234', 10000000, 'GERENTE')");
$conn->query("INSERT INTO usuarios (usuario, password, saldo, rol) VALUES ('Alejandro', '12345', 10000000, 'GERENTE')");

// 3. Mostramos qué quedó
$resultado = $conn->query("SHOW TABLES");
echo "<h3>Tablas que REALMENTE ve PHP:</h3>";
while($fila = $resultado->fetch_array()) {
    echo "- " . $fila[0] . "<br>";
}
?>
