<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$mi_id = $_SESSION['user_id'];

// Obtener info fresca del usuario
$user_q = $conn->query("SELECT * FROM usuarios WHERE id = $mi_id");
$user = $user_q->fetch_assoc();

// Obtener los últimos 20 movimientos del usuario
$movs_q = "SELECT * FROM movimientos WHERE id_usuario = $mi_id ORDER BY id DESC LIMIT 20";
$movs = $conn->query($movs_q);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil | TuCasino Elite</title>
    <link href="https://fonts.googleapis.com" rel="stylesheet">
    <style>
        :root { --primary: #10b981; --bg: #030712; --card: #111827; --border: #1f2937; --text-muted: #9ca3af; }
        body { background: var(--bg); color: #f3f4f6; font-family: 'Inter', sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; }
        .card { background: var(--card); border-radius: 20px; border: 1px solid var(--border); padding: 25px; margin-bottom: 20px; }
        .header-perfil { text-align: center; margin-bottom: 30px; }
        .avatar { width: 80px; height: 80px; background: #1f2937; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 30px; border: 2px solid var(--primary); }
        .saldo-grande { font-size: 35px; font-weight: 800; color: var(--primary); margin: 10px 0; }
        
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { text-align: left; color: var(--text-muted); padding: 10px; border-bottom: 1px solid var(--border); font-size: 11px; }
        td { padding: 12px 10px; border-bottom: 1px solid var(--border); }
        
        .badge-tipo { padding: 3px 8px; border-radius: 5px; font-size: 10px; font-weight: 700; }
        .tipo-CARGA { background: rgba(16, 185, 129, 0.1); color: #10b981; }
        .tipo-VACIADO { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        .tipo-JUEGO_GANO { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .tipo-JUEGO_PERDIO { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
        
        .btn-volver { display: inline-block; margin-top: 20px; color: var(--text-muted); text-decoration: none; font-size: 13px; }
        .btn-volver:hover { color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-perfil">
        <div class="avatar">👤</div>
        <h2 style="margin:0;"><?= $user['usuario'] ?></h2>
        <p style="color: var(--text-muted); font-size: 12px; margin: 5px 0;">Rol: <?= $user['rol'] ?></p>
        <div class="saldo-grande">$<?= number_format($user['saldo'], 2, ',', '.') ?></div>
    </div>

    <div class="card">
        <h3 style="margin-top:0; font-size: 16px;">Historial de Movimientos</h3>
        <table>
            <thead>
                <tr>
                    <th>FECHA</th>
                    <th>TIPO</th>
                    <th>MONTO</th>
                </tr>
            </thead>
            <tbody>
                <?php while($m = $movs->fetch_assoc()): ?>
                <tr>
                    <td style="color: var(--text-muted); font-size: 11px;"><?= date("d/m H:i", strtotime($m['fecha'])) ?></td>
                    <td><span class="badge-tipo tipo-<?= $m['tipo'] ?>"><?= str_replace('_', ' ', $m['tipo']) ?></span></td>
                    <td style="font-weight: 700;">$<?= number_format($m['monto'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
                <?php if($movs->num_rows == 0): ?>
                    <tr><td colspan="3" style="text-align:center; padding:20px; color:var(--text-muted);">Sin movimientos registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="text-align:center;">
        <a href="index.php" class="btn-volver">⬅ VOLVER AL INICIO</a>
    </div>
</div>

</body>
</html>
