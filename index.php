<?php
session_start();
include 'db.php';

// Si no hay sesión, mandamos al login (puedes renombrar el login anterior como login.php)
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

$mi_rol = strtoupper($_SESSION['rol']);
$mi_nombre = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TuCasino Elite | Menú Principal</title>
    <link href="https://fonts.googleapis.com" rel="stylesheet">
    <style>
        :root { --primary: #10b981; --bg: #030712; --card: #111827; --border: #1f2937; }
        body { background: var(--bg); color: white; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
        .hub-container { width: 100%; max-width: 600px; text-align: center; padding: 20px; }
        h1 { font-weight: 800; font-size: 32px; margin-bottom: 10px; }
        h1 span { font-weight: 300; color: var(--primary); }
        .welcome { color: #9ca3af; margin-bottom: 40px; }
        
        .menu-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        
        .menu-item { 
            background: var(--card); border: 1px solid var(--border); padding: 30px; border-radius: 20px; 
            text-decoration: none; color: white; transition: 0.3s; display: flex; flex-direction: column; align-items: center;
        }
        .menu-item:hover { border-color: var(--primary); transform: translateY(-5px); background: #1f2937; }
        .menu-item i { font-size: 40px; margin-bottom: 15px; }
        .menu-item span { font-weight: 800; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
        .menu-item p { font-size: 11px; color: #9ca3af; margin: 5px 0 0; }

        .disabled { opacity: 0.3; pointer-events: none; filter: grayscale(1); }
        .logout { margin-top: 40px; display: inline-block; color: #ef4444; text-decoration: none; font-size: 12px; font-weight: 700; }
    </style>
</head>
<body>

<div class="hub-container">
    <h1>TuCasino <span>Elite</span></h1>
    <p class="welcome">Bienvenido, <strong><?= $mi_nombre ?></strong> (Acceso: <?= $mi_rol ?>)</p>

    <div class="menu-grid">
        <!-- PANEL DE CONTROL: Para Admin y Gerente -->
        <a href="panel.php" class="menu-item <?= ($mi_rol != 'ADMIN' && $mi_rol != 'GERENTE') ? 'disabled' : '' ?>">
            <i>⚙️</i>
            <span>Panel Admin</span>
            <p>Usuarios y Cargas</p>
        </a>

        <!-- MONITOR EN VIVO: Para Admin, Gerente y Vendedor -->
        <a href="monitor.php" class="menu-item <?= ($mi_rol == 'JUGADOR') ? 'disabled' : '' ?>">
            <i>📊</i>
            <span>Monitor Vivo</span>
            <p>Ver Jugadas 5s</p>
        </a>

        <!-- ÁREA DE JUEGO: Para todos, pero principalmente Jugadores -->
        <a href="game_lobby.php" class="menu-item">
            <i>🎮</i>
            <span>Lobby Juegos</span>
            <p>Entrar al Casino</p>
        </a>

        <!-- PERFIL / SALDO -->
        <a href="perfil.php" class="menu-item">
            <i>👤</i>
            <span>Mi Cuenta</span>
            <p>Saldo y Movimientos</p>
        </a>
    </div>

    <a href="login.php?logout=1" class="logout">CERRAR SESIÓN SEGURA</a>
</div>

</body>
</html>
