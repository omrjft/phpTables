<?php
include_once 'conexion.php';

$token = $_GET['token'] ?? '';
$verificado = false;
$mensaje = "El enlace de verificación no es válido o ya ha sido utilizado.";

if (!empty($token)) {
    $sql = "SELECT * FROM usuarios WHERE token_verificacion = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        $sql = "UPDATE usuarios SET email_verificado = 1, token_verificacion = NULL WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $usuario['id']);
        $stmt->execute();

        $verificado = true;
        $mensaje = "¡Tu correo electrónico ha sido verificado con éxito!";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Correo — Portal Escolar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-wrapper">
        <a href="index.php" class="brand-badge">
            <span class="brand-title">Portal Escolar</span>
        </a>

        <div class="card" style="text-align: center;">
            <div style="margin-bottom: 24px;">
                <?php if ($verificado): ?>
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--color-mint); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--color-mint-text)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <h1 class="card-title">¡Email verificado!</h1>
                    <p class="card-subtitle"><?php echo htmlspecialchars($mensaje); ?></p>
                <?php else: ?>
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--color-danger-bg); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--color-danger-text)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </div>
                    <h1 class="card-title">Enlace no válido</h1>
                    <p class="card-subtitle"><?php echo htmlspecialchars($mensaje); ?></p>
                <?php endif; ?>
            </div>

            <a href="login.php" class="btn btn-primary" style="margin-top: 16px;">
                Iniciar sesión
            </a>
        </div>
    </div>
</body>
</html>