<?php
session_start();
include 'db.php';

// Seguridad: Si no hay sesión o no es JUGADOR/VENDEDOR/ADMIN, verificamos
if (!isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit; 
}

$mi_id = $_SESSION['user_id'];
$query = $conn->query("SELECT * FROM usuarios WHERE id = $mi_id");
$mi_info = $query->fetch_assoc();

// Si por alguna razón no encuentra al usuario (borrado), lo sacamos
if (!$mi_info) { session_destroy(); header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TuCasino Elite | Salta - Billetera Virtual</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com" rel="stylesheet">
    <style>
        :root { --primary: #10b981; --bg: #030712; --card: #111827; --border: #1f2937; }
        body { 
            background: var(--bg); 
            color: white; 
            font-family: 'Inter', sans-serif; 
            margin: 0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh;
            background-image: radial-gradient(circle at top right, rgba(16, 185, 129, 0.05), transparent);
        }
        .wallet-card { 
            background: var(--card); 
            padding: 40px 30px; 
            border-radius: 35px; 
            border: 1px solid var(--border); 
            width: 90%; 
            max-width: 380px; 
            text-align: center; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
        }
        .status-badge { 
            font-size: 10px; 
            font-weight: 800; 
            color: var(--primary); 
            letter-spacing: 2px; 
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .user-welcome { font-size: 20px; margin-bottom: 35px; color: #f3f4f6; }
        .balance-box { margin-bottom: 40px; }
        .balance-label { color: #6b7280; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .balance-amount { 
            font-size: 56px; 
            font-weight: 800; 
            color: var(--primary); 
            margin: 10px 0; 
            letter-spacing: -2px;
            text-shadow: 0 0 20px rgba(16, 185, 129, 0.2);
        }
        .btn { 
            display: block; 
            padding: 18px; 
            border-radius: 18px; 
            text-decoration: none; 
            font-weight: 800; 
            font-size: 14px; 
            margin-bottom: 15px; 
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .btn-play { 
            background: var(--primary); 
            color: #064e3b; 
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
        }
        .btn-whatsapp { 
            background: #25d366; 
            color: white; 
            box-shadow: 0 10px 20px rgba(37, 211, 102, 0.15);
        }
        .btn:hover { transform: translateY(-4px); opacity: 0.95; filter: brightness(1.1); }
        .logout-link { 
            display: inline-block; 
            margin-top: 30px; 
            color: #4b5563; 
            font-size: 11px; 
            text-decoration: none; 
            font-weight: 700;
            border-bottom: 1px solid transparent;
        }
        .logout-link:hover { color: #ef4444; border-color: #ef4444; }
    </style>
</head>
<body>
    <div class="wallet-card">
        <div class="status-badge">● SISTEMA ELITE ACTIVO</div>
        <div class="user-welcome">¡Hola, <span style="color: var(--primary); font-weight: 800;"><?= $mi_info['usuario'] ?></span>!</div>
        
        <div class="balance-box">
            <div class="balance-label">Créditos Disponibles</div>
            <div class="balance-amount">$<?= number_format($mi_info['saldo'], 2, ',', '.') ?></div>
            <div style="font-size: 11px; color: #4b5563;">Actualizado en tiempo real</div>
        </div>
        
        <!-- Botón para ir al Juego -->
        <a href="#" class="btn btn-play">INGRESAR A LA SALA</a>
        
        <!-- Botón de Carga vía WhatsApp - Tu número de Salta -->
        <a href="https://wa.me" target="_blank" class="btn btn-whatsapp">
            CARGAR FICHAS (WhatsApp)
        </a>
        
        <a href="panel.php?logout=1" class="logout-link">DESCONECTAR CUENTA SEGURA</a>
        
        <div style="margin-top: 20px; font-size: 10px; color: #1f2937;">
            TuCasino Elite | Quijano, Salta
        </div>
    </div>
</body>
</html>
