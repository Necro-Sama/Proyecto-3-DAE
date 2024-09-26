<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Citas del Estudiante</title>
</head>
<body>
    <h1>Citas agendadas del Estudiante</h1>

    <?php if (!empty($citas)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>ID Trabajador Social</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($citas as $cita): ?>
                    <tr>
                        <td><?php echo $cita['fecha']; ?></td>
                        <td><?php echo $cita['hora']; ?></td>
                        <td><?php echo $cita['id_trabajador_social']; ?></td>
                        <td><a href="<?php echo site_url('CitasController/detalle_cita/' . $cita['id_cita']); ?>">Ver detalles</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay citas agendadas.</p>
    <?php endif; ?>

</body>
</html>
