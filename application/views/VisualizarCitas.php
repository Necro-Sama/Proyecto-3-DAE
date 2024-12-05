<?php
defined("BASEPATH") or exit("No direct script access allowed"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Citas</title>
    <?php $this->load->view("navbar",$tipo);?>
    <link rel="stylesheet" type="text/css" href="<?= base_url(
        "css/agendar.css"
    ) ?>"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/style.css"/>
    <link rel="stylesheet" href="<?php echo base_url(
        "public/bootstrap/css/bootstrap.min.css"
    ); ?>">
    
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
</head>

<body>
        <div class="container mt-5">
    <h2 class="text-center">Visualizar Citas</h2>
        <div class="row mt-4">
            <?php if (!empty($citas)): ?>
                <?php foreach ($citas as $cita): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Estudiante: <?= $cita['NombreEstudiante'] . ' ' . $cita['ApellidoEstudiante']; ?></h5>
                                <p class="card-text"><strong>Teléfono:</strong> <?= $cita['Telefono']; ?></p>
                                <p class="card-text"><strong>Correo:</strong> <?= $cita['Correo']; ?></p>
                                <p class="card-text"><strong>Fecha Inicio:</strong> <?= $cita['FechaInicio']; ?></p>
                                <p class="card-text"><strong>Fecha Término:</strong> <?= $cita['FechaTermino']; ?></p>
                                
                                <?php if (isset($cita['NombreTS'])): ?>
                                    <h5 class="card-title mt-3">Trabajador Social:</h5>
                                    <p class="card-text"><strong>Nombre:</strong> <?= $cita['NombreTS'] . ' ' . $cita['ApellidoTS']; ?></p>
                                    <p class="card-text"><strong>Teléfono:</strong> <?= $cita['TelefonoTS']; ?></p>
                                    <p class="card-text"><strong>Correo:</strong> <?= $cita['CorreoTS']; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No hay citas disponibles.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
</body>