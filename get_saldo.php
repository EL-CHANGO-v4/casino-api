<?php
// Desactivamos cualquier error de PHP que pueda ensuciar la respuesta
error_reporting(0);
include 'db.php';

if (isset($_GET['u'])) {
    $usuario = $conn->real_escape_string($_GET['u']);
    
    // Buscamos el saldo del usuario
    $res = $conn->query("SELECT saldo FROM usuarios WHERE usuario = '$usuario'");
    
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        // Imprimimos el número puro (ej: 10500.50)
        echo $row['saldo'];
    } else {
        // Si el usuario no existe, devolvemos 0
        echo "0";
    }
} else {
    echo "0";
}

// Cerramos la conexión para que el Socket de C detecte el fin de la transmisión
$conn->close();
?>
