<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Trabajadores Sociales a Carreras</title>
    <?php  
    $this->load->view('navbar', $tipo); ?>
    <!-- Cargar Bootstrap desde CDN -->
    <link rel="stylesheet" href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
    <style>
        body {
            background-color: #fbf1d0; /* Naranja 3 */
        }
        .card {
            background-color: #fddeaa;
            border: 2px solid #fdd188;
            border-radius: 15px; /* Bordes redondeados */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header, .modal-header {
            background-color: #060eae;
            color: white;
        }
        .card-title {
            color: #fddeaa;
        }
        .btn-primary {
            background-color: #060eae;
            border-color: #060eae;
        }
        .btn-primary:hover {
            background-color: #fdd188;
            border-color: #fdd188;
            color: #060eae;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Asignar Trabajadores Sociales a Carreras</h3>
        </div>
        <div class="card-body">
            <form action="<?= base_url('index.php/usuarios/asignar-carrera-procesar') ?>" method="POST">
                <div class="mb-3">
                    <label for="COD_CARRERA" class="form-label">Carrera:</label>
                    <select name="COD_CARRERA" class="form-select" required>
                        <option value="">Seleccione una carrera</option>
                        <?php foreach ($carreras as $carrera): ?>
                            <option value="<?= $carrera['COD_CARRERA'] ?>">
                                <?= $carrera['Nombre'] ?> (<?= $carrera['Facultad'] ?>)
                                <?= empty($carrera['NombreTS']) ? ' (Sin TS asignada)' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="RUN_TS_PRINCIPAL" class="form-label">Trabajador Social Principal:</label>
                    <?php error_log("DEBUG - Trabajadores Sociales recibidos: " . print_r($trabajadores_sociales, true)); ?>
                    <select name="RUN_TS_PRINCIPAL" class="form-select" required>
                        <option value="">Seleccione un trabajador social principal</option>
                        <?php foreach ($trabajadores_sociales as $ts): ?>
                            <?php 
                            $disabled = false;
                            $label = "";
                            error_log("DEBUG - Procesando TS: " . print_r($ts, true));
                            
                            // Verificar si está asignado como TS principal
                            foreach ($carreras as $carrera) {
                                if ($carrera['RUNTS'] == $ts['RUN']) {
                                    $disabled = true;
                                    $label = " (TS activa asignada)";
                                    error_log("DEBUG - TS asignada: {$ts['RUN']}");
                                    break;
                                }
                            }
                            
                            // Verificar estado activo
                            error_log("DEBUG - Activo TS {$ts['RUN']}: {$ts['Activo']}");
                            if ($ts['Activo'] != 0) {
                                $disabled = true;
                                $label = " (Con ss)";
                                error_log("DEBUG - TS con licencia: {$ts['RUN']}");
                            }
                            ?>
                            <option value="<?= $ts['RUN'] ?>" 
                                    <?= $disabled ? 'disabled' : '' ?>>
                                <?= $ts['Nombre'] ?> <?= $ts['Apellido'] ?> 
                                (RUN: <?= $ts['RUN'] ?>) 
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="RUN_TS_REEMPLAZO" class="form-label">Trabajador Social de Reemplazo:</label>
                    <select name="RUN_TS_REEMPLAZO" class="form-select" required>
                        <option value="">Seleccione un trabajador social de reemplazo</option>
                        <?php foreach ($trabajadores_sociales as $ts): ?>
                            <?php 
                            $disabled = false;
                            $label = "";
                            // Verificar si está asignado como TS de reemplazo o con licencia
                            foreach ($carreras as $carrera) {
                                if ($carrera['ReemplazaRUNTS'] == $ts['RUN']) {
                                    $disabled = true;
                                    $label = " (TS activa asignada)";
                                    break;
                                }
                            }
                            // Verificar estado activo (1 es disponible, 0 es con licencia)
                            if ($ts['Activo'] != 0) {
                                $disabled = true;
                                $label = " (Con Licencia)";
                            }
                            ?>
                            <option value="<?= $ts['RUN'] ?>" 
                                    <?= $disabled ? 'disabled' : '' ?>>
                                <?= $ts['Nombre'] ?> <?= $ts['Apellido'] ?> 
                                (RUN: <?= $ts['RUN'] ?>)
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Asignar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo base_url('public/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>
