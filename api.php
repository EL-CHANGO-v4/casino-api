<?php
// --- BYPASS DE SEGURIDAD PARA EL C ---
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/plain; charset=utf-8');
// Evitamos que el hosting cachee resultados viejos
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include 'db.php';

// Capturamos parámetros (Sanitización de Ingeniería)
$u     = isset($_GET['u']) ? $conn->real_escape_string($_GET['u']) : '';
$p     = isset($_GET['p']) ? $conn->real_escape_string($_GET['p']) : '';
$op    = isset($_GET['op']) ? $_GET['op'] : '';
$cant  = isset($_GET['cant']) ? (float)$_GET['cant'] : 0;
$juego = isset($_GET['juego']) ? $conn->real_escape_string($_GET['juego']) : '';

// 1. Verificamos al Jugador
$res = $conn->query("SELECT id, saldo FROM usuarios WHERE usuario='$u' AND password='$p'");
$user = $res->fetch_assoc();

// Si el usuario no existe y no es solo un update de status, rebotamos con -1
if (!$user && $op != 'update_status') {
    die("-1"); 
}

switch ($op) {
    case 'leer':
        // Retorno limpio para el atof() de tu código en C
        echo $user['saldo'];
        break;

    case 'update':
        $id_u = $user['id'];
        $saldo_viejo = $user['saldo'];
        $tipo = ($cant >= $saldo_viejo) ? 'JUEGO_GANO' : 'JUEGO_PERDIO';
        $diferencia = abs($cant - $saldo_viejo);

        $conn->begin_transaction();
        try {
            // Actualizamos saldo y nombre del juego en una sola ráfaga
            $conn->query("UPDATE usuarios SET saldo = $cant, juego = '$juego' WHERE id = $id_u");
            
            // Log de auditoría para que no se pierda ni un peso
            $conn->query("INSERT INTO movimientos (id_usuario, monto, tipo, saldo_anterior, saldo_nuevo) 
                          VALUES ($id_u, $diferencia, '$tipo', $saldo_viejo, $cant)");

            $conn->commit();
            echo "OK";
        } catch (Exception $e) {
            $conn->rollback();
            echo "ERROR";
        }
        break;

    case 'update_status':
        // Solo actualiza en qué mesa está sentado el Chango
        if ($conn->query("UPDATE usuarios SET juego = '$juego' WHERE usuario = '$u'")) {
            echo "OK";
        } else {
            echo "ERROR";
        }
        break;

    default:
        echo "INVALID_OP";
        break;
}

$conn->close();
?>
