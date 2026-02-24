<?php
// CONEXIÓN INTERNA DIRECTA - CHANGO v4
$host = "mysql.railway.internal"; 
$user = "root"; 
$pass = "rJwwdlNlDAZRnKiYJUyYUxsJMZOIlySV"; 
$db   = "railway"; 

// Intentamos conectar sin puerto explícito (Railway lo rutea solo)
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    // Si falla el nombre, probamos con localhost (algunas configs lo prefieren)
    $conn = new mysqli("127.0.0.1", $user, $pass, $db);
    if ($conn->connect_error) {
        die("Error satelital: " . $conn->connect_error);
    }
}
$conn->set_charset("utf8mb4");
?>
