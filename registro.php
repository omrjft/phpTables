<?php 
include_once 'conexion.php';

$mensaje = "";
$tipo_mensaje = "";
$token_verificacion = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) trim($_POST["id"]);
    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(32));

    $sql = "INSERT INTO usuarios (id, nombre, email, password, token_verificacion) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("issss", $id, $nombre, $email, $passwordHash, $token);

    if ($stmt->execute()) {
        $mensaje = "Usuario registrado correctamente.";
        $tipo_mensaje = "success";
        $token_verificacion = $token;
    } else {
        $mensaje = "No se pudo registrar el usuario. Es posible que el correo o número de control ya existan.";
        $tipo_mensaje = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro — Portal Escolar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-wrapper">
        <a href="index.php" class="brand-badge">
            <span class="brand-title">Portal Escolar</span>
        </a>

        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Registro de alumno</h1>
                <p class="card-subtitle">Crea tu cuenta para consultar tus calificaciones</p>
            </div>

            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php if ($tipo_mensaje === 'success'): ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <?php else: ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <?php endif; ?>
                    <div>
                        <span><?php echo htmlspecialchars($mensaje); ?></span>
                        <?php if ($token_verificacion): ?>
                            <div style="margin-top: 6px;">
                                <a href="verificar.php?token=<?php echo urlencode($token_verificacion); ?>" class="text-link" style="font-weight: 600;">
                                    Haz clic aquí para confirmar tu correo &rarr;
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="id">Número de control</label>
                    <input type="number" id="id" name="id" min="1" placeholder="Ej. 101" required>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej. Juan Pérez" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary">Registrarme</button>
            </form>

            <div class="card-footer">
                ¿Ya tienes una cuenta? <a href="login.php" class="text-link">Iniciar sesión</a>
            </div>
        </div>
    </div>
</body>
</html>