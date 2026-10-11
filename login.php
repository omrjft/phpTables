<?php
session_start();
include_once 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (!$usuario["email_verificado"]) {
            $mensaje = "Primero confirma tu email.";
        } elseif (password_verify($password, $usuario["password"])) {
            session_regenerate_id(true);
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["nombre"] = $usuario["nombre"];
            header("Location: dashboard.php");
            exit;
        } else {
            $mensaje = "Contraseña incorrecta.";
        }
    } else {             
        $mensaje = "Usuario no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Portal Escolar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-wrapper">
        <a href="index.php" class="brand-badge">
            <span class="brand-title">Portal Escolar</span>
        </a>

        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Iniciar sesión</h1>
                <p class="card-subtitle">Ingresa tus credenciales para acceder a tu cuenta</p>
            </div>

            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span><?php echo htmlspecialchars($mensaje); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            </form>

            <div class="card-footer">
                ¿No tienes una cuenta? <a href="registro.php" class="text-link">Crear cuenta</a>
            </div>
        </div>
    </div>
</body>
</html>
