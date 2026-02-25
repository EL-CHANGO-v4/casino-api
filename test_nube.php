<?php
echo "¡Hola desde el satélite! Si ves esto, el PHP funciona. Ahora probamos la base...<br>";
include 'db.php';
$res = $conn->query("SELECT 'CONECTADO' as estado");
if($res) {
    $row = $res->fetch_assoc();
    echo "Estado de la DB: " . $row['estado'];
} else {
    echo "Error de DB: " . $conn->error;
}
?>
