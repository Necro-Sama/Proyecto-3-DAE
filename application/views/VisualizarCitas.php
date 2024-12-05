<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Citas</title>
    <?php $this->load->view("navbar", $tipo); ?>
    <link rel="stylesheet" href="<?= base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
    <style>
        body {
            background-color: #FBF1D0;
        }
        .card {
            background-color: #FDDEAA;
            border: 2px solid #FDD188;
            border-radius: 15px; /* Bordes redondeados */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            color: #060EAE;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #060EAE;
            border-color: #060EAE;
            color: white;
        }
        .btn-primary:hover {
            background-color: #FDD188;
            border-color: #FDD188;
            color: #060EAE;
        }
        .card-text strong {
            color: #060EAE;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Citas Programadas</h2>
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
</html>
