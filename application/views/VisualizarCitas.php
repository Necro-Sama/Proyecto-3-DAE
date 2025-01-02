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
        <h1 class="mb-4">Listado de Citas</h1>

        <!-- Formulario de Búsqueda -->
        <form method="get" action="<?= site_url('usuarios/visualizar-citas'); ?>" class="mb-4">
            <div class="input-group">
                <input type="text" name="filtro" class="form-control" placeholder="Buscar por RUN o Nombre" value="<?= $this->input->get('filtro'); ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </div>
        </form>


        <div class="row">
            <?php if (isset($citas) && !empty($citas)): ?>
                <?php foreach ($citas as $cita): ?>
                    <?php
                    $esPasada = strtotime($cita['FechaInicio']) < time(); // Verificar si la cita es pasada
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <?php if ($esPasada): ?>
                                    <h5 class="card-title text-danger">Cita Pasada</h5>
                                <?php endif; ?>
                                <h5 class="card-title"><?= htmlspecialchars($cita['NombreEstudiante'] . ' ' . $cita['ApellidoEstudiante']); ?></h5>
                                <p class="card-text"><strong>Teléfono:</strong> <?= htmlspecialchars($cita['Telefono']); ?></p>
                                <p class="card-text"><strong>Correo:</strong> <?= htmlspecialchars($cita['Correo']); ?></p>
                                <p class="card-text"><strong>Trabajador Social:</strong> <?= htmlspecialchars($cita['NombreTS'] . ' ' . $cita['ApellidoTS']); ?></p>
                                <p class="card-text"><strong>Fecha Inicio:</strong> <?= htmlspecialchars($cita['FechaInicio']); ?></p>
                                <p class="card-text"><strong>Fecha Término:</strong> <?= htmlspecialchars($cita['FechaTermino']); ?></p>
                                <p class="card-text"><strong>Motivo:</strong> <?= htmlspecialchars($cita['Motivo']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        No se encontraron citas.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#buscarForm').on('submit', function(e) {
                var filtro = $('input[name="filtro"]').val().trim();
                if (filtro === '') {
                    e.preventDefault();
                    alert('Por favor, ingrese un texto para buscar.');
                }
            });
        });
    </script>

</body>
</html>
