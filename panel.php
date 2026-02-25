<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
$mi_id = $_SESSION['user_id'];
$mi_rol = strtoupper($_SESSION['rol']);

// --- LÓGICA DE ACCIONES (ADMINISTRATIVAS) ---

if (isset($_POST['alta'])) {
    $u = $conn->real_escape_string($_POST['u']);
    $p = $conn->real_escape_string($_POST['p']);
    $r = $_POST['r']; 
    if($conn->query("INSERT INTO `usuarios` (`usuario`, `password`, `rol`, `saldo`) VALUES ('$u', '$p', '$r', 0)")){
        $new_id = $conn->insert_id;
        $conn->query("INSERT INTO movimientos (id_usuario, monto, tipo, saldo_anterior, saldo_nuevo) VALUES ($new_id, 0, 'ALTA', 0, 0)");
    }
    header("Location: panel.php"); exit;
}

if (isset($_POST['cargar_btn'])) {
    $id_u = (int)$_POST['id_u'];
    $monto = (float)$_POST['monto'];
    if($monto > 0) {
        $res_yo = $conn->query("SELECT saldo FROM usuarios WHERE id = $mi_id")->fetch_assoc();
        $res_u = $conn->query("SELECT saldo FROM usuarios WHERE id = $id_u")->fetch_assoc();
        if ($res_yo['saldo'] >= $monto || $mi_rol == 'GERENTE') {
            $conn->begin_transaction();
            try {
                if ($mi_rol != 'GERENTE') {
                    $conn->query("UPDATE usuarios SET saldo = saldo - $monto WHERE id = $mi_id");
                }
                $conn->query("UPDATE usuarios SET saldo = saldo + $monto WHERE id = $id_u");
                $conn->query("INSERT INTO movimientos (id_usuario, monto, tipo, saldo_anterior, saldo_nuevo) VALUES ($id_u, $monto, 'CARGA', {$res_u['saldo']}, {$res_u['saldo']} + $monto)");
                $conn->commit();
            } catch (Exception $e) { $conn->rollback(); }
        }
    }
    header("Location: panel.php"); exit;
}

if (isset($_POST['vaciar_btn'])) {
    $id_u = (int)$_POST['id_u'];
    $res_u = $conn->query("SELECT saldo FROM usuarios WHERE id = $id_u")->fetch_assoc();
    $ant = $res_u['saldo'];
    if ($ant > 0) {
        $conn->begin_transaction();
        try {
            $conn->query("UPDATE usuarios SET saldo = 0 WHERE id = $id_u");
            if ($mi_rol != 'GERENTE') {
                $conn->query("UPDATE usuarios SET saldo = saldo + $ant WHERE id = $mi_id");
            }
            $conn->query("INSERT INTO movimientos (id_usuario, monto, tipo, saldo_anterior, saldo_nuevo) VALUES ($id_u, $ant, 'VACIADO', $ant, 0)");
            $conn->commit();
        } catch (Exception $e) { $conn->rollback(); }
    }
    header("Location: panel.php"); exit;
}

if (isset($_POST['borrar_btn'])) {
    $id_b = (int)$_POST['id_u'];
    if ($mi_id != $id_b) {
        $res_u = $conn->query("SELECT saldo FROM usuarios WHERE id = $id_b")->fetch_assoc();
        $conn->query("INSERT INTO movimientos (id_usuario, monto, tipo, saldo_anterior, saldo_nuevo) VALUES ($id_b, 0, 'BAJA', {$res_u['saldo']}, 0)");
        $conn->query("DELETE FROM usuarios WHERE id = $id_b");
    }
    header("Location: panel.php"); exit;
}

if (isset($_GET['logout'])) { session_destroy(); header("Location: index.php"); exit; }

$mi_info = $conn->query("SELECT `saldo` FROM `usuarios` WHERE `id` = $mi_id")->fetch_assoc();
$hijos_q = "SELECT * FROM `usuarios` WHERE `id` != $mi_id ORDER BY `id` DESC";
$movs_q = "SELECT m.*, u.usuario FROM movimientos m LEFT JOIN usuarios u ON m.id_usuario = u.id WHERE m.tipo IN ('CARGA','VACIADO','ALTA','BAJA') ORDER BY m.id DESC LIMIT 15";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TuCasino Elite | Monitor Administrativo</title>
    <link href="https://fonts.googleapis.com" rel="stylesheet">
    <style>
        :root { --primary: #10b981; --bg: #030712; --card: #111827; --border: #1f2937; --text-muted: #9ca3af; }
        body { background: var(--bg); color: #f3f4f6; font-family: 'Inter', sans-serif; margin: 0; padding: 20px; font-size: 13px; }
        .container { max-width: 1100px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; padding: 20px; background: rgba(17, 24, 39, 0.7); border-radius: 20px; border: 1px solid var(--border); margin-bottom: 20px; }
        .balance-value { font-size: 30px; font-weight: 800; color: var(--primary); }
        .card { background: var(--card); border-radius: 15px; border: 1px solid var(--border); padding: 20px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; color: var(--text-muted); font-size: 10px; text-transform: uppercase; padding: 10px; border-bottom: 1px solid var(--border); }
        td { padding: 10px; border-bottom: 1px solid var(--border); }
        .btn-primary { background: var(--primary); color: #064e3b; border: none; padding: 8px 15px; border-radius: 8px; font-weight: 700; cursor: pointer; }
        .btn-icon { background: #1f2937; color: white; border: none; padding: 5px 10px; border-radius: 6px; cursor: pointer; }
        .badge-juego { background: #1e293b; color: #38bdf8; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 700; }
        .tipo-CARGA { color: #10b981; } .tipo-VACIADO { color: #f59e0b; } .tipo-ALTA { color: #3b82f6; } .tipo-BAJA { color: #ef4444; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <h1 style="margin:0; font-size: 20px;">TuCasino <span style="font-weight:300;">Elite</span></h1>
            <p style="margin:5px 0; color: var(--text-muted);"><?= $_SESSION['nombre'] ?> | <a href="panel.php?logout=1" style="color:#ef4444; text-decoration:none;">SALIR</a></p>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 10px; color: var(--text-muted);">MI SALDO</div>
            <div class="balance-value">$<?= number_format($mi_info['saldo'], 2, ',', '.') ?></div>
            <button onclick="window.location.reload()" class="btn-icon" style="font-size: 10px; margin-top: 5px;">🔄 REFRESCAR</button>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-top:0;">Nuevo Usuario</h3>
        <form method="POST" style="display: flex; gap: 10px;">
            <input type="text" name="u" placeholder="Usuario" style="background:#030712; border:1px solid var(--border); color:white; padding:8px; border-radius:8px;" required>
            <input type="text" name="p" placeholder="Clave" style="background:#030712; border:1px solid var(--border); color:white; padding:8px; border-radius:8px;" required>
            <select name="r" style="background:#030712; border:1px solid var(--border); color:white; padding:8px; border-radius:8px;">
                <option value="JUGADOR">JUGADOR</option>
                <option value="VENDEDOR">VENDEDOR</option>
            </select>
            <button name="alta" class="btn-primary">REGISTRAR</button>
        </form>
    </div>

    <div class="card">
        <h3>Lista de Jugadores</h3>
        <table>
            <thead><tr><th>USUARIO</th><th>JUEGO</th><th>SALDO</th><th>ACCIONES</th></tr></thead>
            <tbody>
                <?php $res = $conn->query($hijos_q); while($row = $res->fetch_assoc()): ?>
                <tr>
                    <td><strong><?= $row['usuario'] ?></strong> (<?= $row['rol'] ?>)</td>
                    <td><span class="badge-juego"><?= (!empty($row['juego'])) ? strtoupper($row['juego']) : 'LOBBY'; ?></span></td>
                    <td style="color: var(--primary); font-weight: 800;">$<?= number_format($row['saldo'], 2) ?></td>
                    <td>
                        <form method="POST" style="display: flex; gap: 5px;">
                            <input type="hidden" name="id_u" value="<?= $row['id'] ?>">
                            <input type="number" name="monto" placeholder="0" step="0.01" style="width: 70px; background:#030712; border:1px solid var(--border); color:white; padding:5px; border-radius:6px;">
                            <button name="cargar_btn" class="btn-icon" title="Cargar">↑</button>
                            <button name="vaciar_btn" class="btn-icon" style="color: #f59e0b;" title="Vaciar">↓</button>
                            <button name="borrar_btn" class="btn-icon" style="color: #ef4444;" onclick="return confirm('¿Borrar?')" title="Eliminar">🗑</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3>Últimos Movimientos</h3>
        <table>
            <thead><tr><th>USUARIO</th><th>TIPO</th><th>MONTO</th><th>NUEVO SALDO</th></tr></thead>
            <tbody>
                <?php $rm = $conn->query($movs_q); while($m = $rm->fetch_assoc()): ?>
                <tr>
                    <td><?= $m['usuario'] ?></td>
                    <td class="tipo-<?= $m['tipo'] ?>"><?= $m['tipo'] ?></td>
                    <td>$<?= number_format($m['monto'], 2) ?></td>
                    <td>$<?= number_format($m['saldo_nuevo'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
