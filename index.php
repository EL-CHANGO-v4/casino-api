<?php
session_start();
include 'db.php'; 

if (isset($_POST['ingresar'])) {
    $u = $conn->real_escape_string($_POST['user']);
    $p = $conn->real_escape_string($_POST['pass']);

    // CONSULTA LIMPIA: Buscamos en la tabla 'usuarios'
    $res = $conn->query("SELECT * FROM usuarios WHERE usuario='$u' AND password='$p'");
    
    if ($res && $res->num_rows > 0) {
        $user = $res->fetch_assoc();
        
        // Guardamos los datos en la sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['rol']     = strtoupper($user['rol']); // Lo pasamos a mayúsculas para comparar mejor
        $_SESSION['nombre']  = $user['usuario'];

        // --- DIRECCIONAMIENTO INTELIGENTE ---
        if ($_SESSION['rol'] === 'JUGADOR') {
            header("Location: jugador.php");
        } else {
            header("Location: panel.php"); // Gerentes y otros van aquí
        }
        exit;
        
    } else {
        $error = "Acceso denegado. Verifique sus credenciales.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TuCasino | Acceso al Núcleo</title>
    <link href="https://fonts.googleapis.com" rel="stylesheet">
    <style>
        :root { --primary: #10b981; --bg: #030712; --card: #111827; --border: #1f2937; }
        body { background: var(--bg); color: white; font-family: 'Inter', sans-serif; margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; overflow: hidden; }
        .blob { position: absolute; width: 400px; height: 400px; background: rgba(16, 185, 129, 0.1); filter: blur(80px); border-radius: 50%; z-index: -1; }
        .login-card { background: rgba(17, 24, 39, 0.8); padding: 50px 40px; border-radius: 32px; border: 1px solid var(--border); backdrop-filter: blur(20px); width: 100%; max-width: 400px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7); text-align: center; }
        .logo-area h1 { font-size: 28px; font-weight: 800; margin: 0; letter-spacing: -1px; }
        .logo-area span { color: var(--primary); font-weight: 300; }
        .subtitle { color: #9ca3af; font-size: 11px; margin-top: 10px; margin-bottom: 30px; letter-spacing: 1px; }
        .input-group { text-align: left; margin-bottom: 20px; }
        label { font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-left: 5px; }
        input { width: 100%; background: #030712; border: 1px solid var(--border); padding: 14px; border-radius: 12px; color: white; font-size: 15px; margin-top: 8px; box-sizing: border-box; transition: all 0.3s; }
        input:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 15px rgba(16, 185, 129, 0.1); }
        .btn-login { width: 100%; background: var(--primary); color: #064e3b; padding: 16px; border-radius: 14px; border: none; font-weight: 800; font-size: 14px; cursor: pointer; transition: all 0.3s; margin-top: 10px; text-transform: uppercase; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3); background: #34d399; }
        .error-msg { background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 10px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; border: 1px solid rgba(239, 68, 68, 0.2); }
        .footer-text { margin-top: 30px; font-size: 11px; color: #4b5563; }
    </style>
</head>
<body>
    <div class="blob" style="top: -100px; left: -100px;"></div>
    <div class="login-card">
        <div class="logo-area">
            <h1>TuCasino <span>Elite</span></h1>
            <div class="subtitle">AUTENTICACIÓN DE SEGURIDAD</div>
        </div>
        <?php if(isset($error)): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="input-group">
                <label>Identificador</label>
                <input type="text" name="user" placeholder="Nombre de usuario" required autofocus>
            </div>
            <div class="input-group">
                <label>Clave de Acceso</label>
                <input type="password" name="pass" placeholder="••••••••" required>
            </div>
            <button type="submit" name="ingresar" class="btn-login">Verificar Credenciales</button>
        </form>
        <div class="footer-text">
            &copy; 2026 TuCasino | Yerba Buena, Tucumán.<br>
            Acceso restringido a personal autorizado.
        </div>
    </div>
</body>
</html>
