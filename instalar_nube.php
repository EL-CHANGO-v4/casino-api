<?php
include 'db.php'; // USARÁ LOS DATOS DE RAILWAY QUE YA PUSISTE ACÁ

$sql = "CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    saldo DECIMAL(10,2) DEFAULT 0.00,
    rol VARCHAR(20) DEFAULT 'JUGADOR',
    juego VARCHAR(100) DEFAULT 'LOBBY'
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS movimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    monto DECIMAL(10,2),
    tipo VARCHAR(20),
    saldo_anterior DECIMAL(10,2),
    saldo_nuevo DECIMAL(10,2),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO usuarios (usuario, password, saldo, rol) 
VALUES ('EL CHANGO v4', '1234', 10000000.00, 'GERENTE'), 
       ('Alejandro', '12345', 0.00, 'GERENTE');";

echo "<h2>🚀 Iniciando Ignición al Satélite...</h2>";

if ($conn->multi_query($sql)) {
    do {
        if ($res = $conn->store_result()) { $res->free(); }
    } while ($conn->more_results() && $conn->next_result());
    
    echo "<h1 style='color:green;'>¡ÉXITO TOTAL, INGENIERO! 🛰️</h1>";
    echo "<p>Las tablas ya están en la nube. Ya podés entrar al <a href='panel.php'>Panel Elite</a>.</p>";
} else {
    echo "<h1 style='color:red;'>FALLÓ EL DESPEGUE 💥</h1>";
    echo "Error técnico: " . $conn->error;
}
?>
