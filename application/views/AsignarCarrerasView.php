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
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Asignar Trabajadores Sociales a Carreras</h3>
        </div>
        <div class="card-body">
            <form action="<?= site_url('usuarios/asignar-carrera-procesar') ?>" method="POST">
                <div class="mb-3">
                    <label for="COD_CARRERA" class="form-label">Carrera:</label>
                    <select name="COD_CARRERA" class="form-select" required>
                        <option value="">Seleccione una carrera</option>
                        <?php foreach ($carreras as $carrera): 
                            $estado_ts = '';
                            
                            // Verificar TS Principal
                            if (!empty($carrera['RUNTS'])) {
                                if (isset($carrera['Activo_Principal']) && $carrera['Activo_Principal'] == 0) {
                                    $estado_ts .= ' (TS Principal con licencia)';
                                }
                            }
                            
                            // Verificar TS Reemplazo
                            if (!empty($carrera['ReemplazaRUNTS'])) {
                                if (isset($carrera['Activo_Reemplazo']) && $carrera['Activo_Reemplazo'] == 0) {
                                    $estado_ts .= ' (TS Reemplazo con licencia)';
                                }
                            }
                            
                            // Si no hay ningún TS asignado
                            if (empty($carrera['RUNTS']) && empty($carrera['ReemplazaRUNTS'])) {
                                $estado_ts = ' (Sin TS asignada)';
                            }
                            
                            // Debug
                            //echo "<!-- Carrera: " . $carrera['Nombre'] . " | Principal Activo: " . $carrera['Activo_Principal'] . " | Reemplazo Activo: " . $carrera['Activo_Reemplazo'] . " -->";
                        ?>
                            <option value="<?= $carrera['COD_CARRERA'] ?>">
                                <?= $carrera['Nombre'] . $estado_ts ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="RUN_TS_PRINCIPAL" class="form-label">Trabajador Social Principal:</label>
                    <?php error_log("DEBUG - Trabajadores Sociales recibidos: " . print_r($trabajadores_sociales, true)); ?>
                    <select name="RUN_TS_PRINCIPAL" class="form-control" required>
                        <option value="">Seleccione Trabajador Social Principal</option>
                        <?php foreach ($trabajadores_sociales as $ts): ?>
                            <option value="<?= $ts['RUN'] ?>" 
                                    <?= $ts['Activo'] == 0 ? 'disabled' : '' ?>>
                                <?= $ts['nombre_completo'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="RUN_TS_REEMPLAZO" class="form-label">Trabajador Social de Reemplazo:</label>
                    <select name="RUN_TS_REEMPLAZO" class="form-control">
                        <option value="">Seleccione Trabajador Social de Reemplazo (Opcional)</option>
                        <?php foreach ($trabajadores_sociales as $ts): ?>
                            <option value="<?= $ts['RUN'] ?>" 
                                    <?= $ts['Activo'] == 0 ? 'disabled' : '' ?>>
                                <?= $ts['nombre_completo'] ?>
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
