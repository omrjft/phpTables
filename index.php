<?php
include_once 'conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablas</title>
</head>
<body>
<pre>
<?php
echo "=== TABLA DE CALIFICACIONES ===\n";
$sql = "SELECT * FROM calificaciones";
$resultado = $conexion->query($sql);
if ($resultado && $resultado->num_rows > 0) {
    printf("%-5s | %-20s | %-20s | %-12s\n", "ID", "Nombre", "Materia", "Calificación");
    echo str_repeat("-", 67) . "\n";
    while ($row = $resultado->fetch_assoc()) {
        printf("%-5s | %-20s | %-20s | %-12s\n", $row["id"], $row["nombre"], $row["materia"], $row["calificaciones"]);
    }
} else {
    echo "0 resultados\n";
}

echo "\n\n=== TABLA DE ALUMNOS ===\n";
$sql = "SELECT * FROM alumnos";
$resultado = $conexion->query($sql);
if ($resultado && $resultado->num_rows > 0) {
    printf("%-5s | %-20s | %-12s | %-20s | %-25s | %-19s\n", "ID", "Nombre", "Matrícula", "Carrera", "Correo", "Fecha Registro");
    echo str_repeat("-", 115) . "\n";
    while ($row = $resultado->fetch_assoc()) {
        printf("%-5s | %-20s | %-12s | %-20s | %-25s | %-19s\n", $row["id"], $row["nombre"], $row["matricula"], $row["carrera"], $row["correo"], $row["fecha_registro"]);
    }
} else {
    echo "0 resultados\n";
}
$conexion->close();
?>
</pre>
</body>
</html>
