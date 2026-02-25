<?php
session_start();
include 'db.php';

$error = "";

// LÓGICA DE SALIDA (LOGOUT)
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

// SI YA ESTÁ LOGUEADO, MANDAR AL INDEX DIRECTO
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// LÓGICA DE ENTRADA (LOGIN)
if (isset($_POST['login'])) {
    $u = $conn->real_escape_string($_POST['u']);
    $p = $conn->real_escape_string($_POST['p']);

    $res = $conn->query("SELECT * FROM usuarios WHERE usuario = '$u' AND password = '$p'");
    $user = $res->fetch_assoc();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nombre']  = $user['usuario'];
        $_SESSION['rol']     = $user['rol'];
        
        header("Location: index.php");
        exit;
    } else {
        $error = "Usuario o clave incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>TuCasino Elite | Acceso</title>
    <link href="https://fonts.googleapis.com" rel="stylesheet">
    <style>
        :root { --primary: #10b981; --bg: #030712; --card: #111827; --border: #1f2937; }
        body { background: var(--bg); color: white; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-card { background: var(--card); padding: 40px; border-radius: 24px; border: 1px solid var(--border); width: 100%; max-width: 350px; text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
        h1 { font-weight: 800; font-size: 24px; margin-bottom: 5px; }
        h1 span { font-weight: 300; color: var(--primary); }
        p { color: #9ca3af; font-size: 14px; margin-bottom: 30px; }
        input { width: 100%; padding: 12px; margin-bottom: 15px; background: #030712; border: 1px solid var(--border); border-radius: 10px; color: white; box-sizing: border-box; }
        input:focus { border-color: var(--primary); outline: none; }
        .btn { width: 100%; padding: 12px; background: var(--primary); color: #064e3b; border: none; border-radius: 10px; font-weight: 800; cursor: pointer; transition: 0.3s; }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); }
        .error { color: #ef4444; font-size: 12px; margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>

<div class="login-card">
    <h1>TuCasino <span>Elite</span></h1>
    <p>Acceso restringido</p>

    <?php if($error): ?>
        <div class="error">❌ <?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="u" placeholder="Usuario" required autocomplete="off">
        <input type="password" name="p" placeholder="Contraseña" required>
        <button type="submit" name="login" class="btn">ACCEDER</button>
    </form>
    
    <div style="margin-top: 20px; font-size: 10px; color: #4b5563;">
        SISTEMA DE MONITOREO DE ACTIVIDAD
    </div>
</div>

</body>
</html>
