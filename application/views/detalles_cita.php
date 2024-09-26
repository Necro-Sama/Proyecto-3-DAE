<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Cita</title>
</head>
<body>
    <h1>Detalle de la Cita</h1>

    <p><strong>Fecha:</strong> <?php echo $cita['fecha']; ?></p>
    <p><strong>Hora:</strong> <?php echo $cita['hora']; ?></p>
    <p><strong>ID Trabajador Social:</strong> <?php echo $cita['id_trabajador_social']; ?></p>

    <a href="<?php echo site_url('CitasController/citas_estudiante/' . $cita['id_alumno']); ?>">Volver a la lista de citas</a>
</body>
</html>

