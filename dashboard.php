<?php
session_start();
include_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT id, nombre, email, created_at FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if (!$usuario) {
    session_destroy();
    header('Location: login.php');
    exit();
}

$sql = "SELECT materia, calificaciones FROM calificaciones WHERE nombre = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario['nombre']);
$stmt->execute();
$calificaciones = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Alumno — Portal Escolar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="navbar">
        <div class="nav-container">
            <a href="dashboard.php" class="brand-badge" style="margin-bottom: 0;">
                <span class="brand-icon"></span>
                <span class="brand-title">Portal Escolar</span>
            </a>
            <div class="nav-user">
                <span style="font-size: var(--text-body-sm); color: var(--color-slate); font-weight: 500;">
                    Hola, <strong style="color: var(--color-ink);"><?php echo htmlspecialchars($usuario['nombre']); ?></strong>
                </span>
                <a href="logout.php" class="btn-danger-outline">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    Cerrar sesión
                </a>
            </div>
        </div>
    </header>

    <main class="dashboard-main">
        <section class="welcome-banner">
            <h1 class="welcome-title">Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?> 👋</h1>
            <p class="welcome-subtitle">Consulta tu información académica y el registro de tus materias cursadas.</p>
        </section>

        <section>
            <div class="grid-cards">
                <div class="info-card accent-sky">
                    <div class="info-label">Número de Control</div>
                    <div class="info-value">#<?php echo htmlspecialchars($usuario['id']); ?></div>
                </div>

                <div class="info-card accent-lavender">
                    <div class="info-label">Correo Registrado</div>
                    <div class="info-value"><?php echo htmlspecialchars($usuario['email']); ?></div>
                </div>

                <div class="info-card accent-mint">
                    <div class="info-label">Fecha de Registro</div>
                    <div class="info-value" style="font-size: var(--text-body);">
                        <?php echo htmlspecialchars($usuario['created_at']); ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="table-card">
            <div class="section-header">
                <h2 class="section-title">Mis Materias y Calificaciones</h2>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50%;">Materia</th>
                            <th style="width: 50%; text-align: right;">Calificación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($calificaciones && $calificaciones->num_rows > 0): ?>
                            <?php while ($fila = $calificaciones->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fila['materia']); ?></td>
                                    <td style="text-align: right;"><?php echo htmlspecialchars($fila['calificaciones']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="empty-state">
                                    No se encontraron materias ni calificaciones registradas.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>