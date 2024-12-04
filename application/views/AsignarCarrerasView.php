<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Trabajadores Sociales a Carreras</title>

    <!-- Cargar Bootstrap desde CDN -->
    <link rel="stylesheet" href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
    <style>
        /* Fondo general */
        body {
            background-color: white; /* Naranja 3 */
        }
        /* Diseño de la tarjeta */
        .card {
            background-color: #fddeaa; /* Naranja 2 */
            border: 2px solid #fdd188; /* Naranja 1 */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            color: #060eae; /* Azul 1 */
        }
        /* Encabezado de tabla */
        .table-dark {
            background-color: #060eae !important; /* Azul 1 */
            color: white;
        }
        /* Filas de la tabla */
        .table tbody tr {
            background-color: #fddeaa; /* Naranja 2 */
        }
        /* Fila al pasar el ratón */
        .table-hover tbody tr:hover {
            background-color: #fbf1d0; /* Naranja 3 */
        }
        /* Botones */
        .btn-primary {
            background-color: #060eae; /* Azul 1 */
            border-color: #060eae;
        }
        .btn-primary:hover {
            background-color: #fdd188; /* Naranja 1 */
            border-color: #fdd188;
            color: #060eae;
        }
        .btn-warning {
            background-color: #fdd188; /* Naranja 1 */
            border-color: #fdd188;
            color: #060eae; /* Azul 1 */
        }
        .btn-warning:hover {
            background-color: #fddeaa; /* Naranja 2 */
            border-color: #fddeaa;
        }
        .btn-danger {
            background-color: #fdd188; /* Naranja 1 */
            border-color: #fdd188;
            color: #060eae; /* Azul 1 */
        }
        .btn-danger:hover {
            background-color: #fddeaa; /* Naranja 2 */
            border-color: #fddeaa;
        }
        /* Modal */
        .modal-header {
            background-color: #060eae; /* Azul 1 */
            color: #060eae;
        }
        .modal-content {
            background-color: #fddeaa; /* Naranja 2 */
        }
        .btn-close {
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h3>Asignar Trabajadores Sociales a Carreras</h3>

    <!-- Formulario de asignación -->
    <!-- Elimina la etiqueta de formulario duplicada dentro del modal -->
    <form action="<?= base_url('index.php/usuarios/asignar-carrera-procesar') ?>" method="POST">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="asignarModalLabel">Asignar Trabajadores Sociales a Carreras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario de asignación -->
                    <div class="mb-3">
                        <label for="COD_CARRERA" class="form-label">Carrera:</label>
                        <select name="COD_CARRERA" class="form-select" required>
                            <option value="">Seleccione una carrera</option>
                            <?php foreach ($carreras as $carrera): ?>
                                <option value="<?= $carrera['COD_CARRERA'] ?>"><?= $carrera['Nombre'] ?> (<?= $carrera['Facultad'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="RUN_TS_PRINCIPAL" class="form-label">Trabajador Social Principal:</label>
                        <select name="RUN_TS_PRINCIPAL" class="form-select" required>
                            <option value="">Seleccione un trabajador social principal</option>
                            <?php foreach ($trabajadores_sociales as $ts): ?>
                                <option value="<?= $ts['RUN'] ?>"><?= $ts['Nombre'] ?> <?= $ts['Apellido'] ?> (RUN: <?= $ts['RUN'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="RUN_TS_REEMPLAZO" class="form-label">Trabajador Social de Reemplazo:</label>
                        <select name="RUN_TS_REEMPLAZO" class="form-select" required>
                            <option value="">Seleccione un trabajador social de reemplazo</option>
                            <?php foreach ($trabajadores_sociales as $ts): ?>
                                <option value="<?= $ts['RUN'] ?>"><?= $ts['Nombre'] ?> <?= $ts['Apellido'] ?> (RUN: <?= $ts['RUN'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Asignar</button>
                </div>
            </div>
        </div>
    </form>

</div>

<script src="<?php echo base_url('public/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>
