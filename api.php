<?php
include 'db.php';

// Capturamos lo que manda el C
$u     = isset($_GET['u']) ? $conn->real_escape_string($_GET['u']) : '';
$p     = isset($_GET['p']) ? $conn->real_escape_string($_GET['p']) : '';
$op    = isset($_GET['op']) ? $_GET['op'] : '';
$cant  = isset($_GET['cant']) ? (float)$_GET['cant'] : 0;
$juego = isset($_GET['juego']) ? $conn->real_escape_string($_GET['juego']) : '';

switch ($op) {
    case 'update_status':
        // Esta es la que llama tu función nombre_del_juego_al_servidor
        if ($conn->query("UPDATE usuarios SET juego = '$juego' WHERE usuario = '$u'")) {
            echo "OK";
        } else {
            echo "ERROR";
        }
        break;

    case 'leer':
        $res = $conn->query("SELECT saldo FROM usuarios WHERE usuario='$u' AND password='$p'");
        $user = $res->fetch_assoc();
        echo ($user) ? $user['saldo'] : "-1";
        break;

    case 'update':
        // Esta es para actualizar las fichas y también el juego si viene el dato
        $res = $conn->query("SELECT id, saldo FROM usuarios WHERE usuario='$u' AND password='$p'");
        $user = $res->fetch_assoc();
        if ($user) {
            $saldo_viejo = $user['saldo'];
            $tipo = ($cant >= $saldo_viejo) ? 'JUEGO_GANO' : 'JUEGO_PERDIO';
            $diferencia = abs($cant - $saldo_viejo);
            
            $conn->begin_transaction();
            try {
                $conn->query("UPDATE usuarios SET saldo = $cant, juego = '$juego' WHERE id = " . $user['id']);
                $conn->query("INSERT INTO movimientos (id_usuario, monto, tipo, saldo_anterior, saldo_nuevo) 
                              VALUES ({$user['id']}, $diferencia, '$tipo', $saldo_viejo, $cant)");
                $conn->commit();
                echo "OK";
            } catch (Exception $e) { $conn->rollback(); echo "ERROR"; }
        } else { echo "-1"; }
        break;
}
$conn->close();
?>
