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

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Asegura que los elementos estén espaciados */
            background-color: #FDDEAA;
            border: 2px solid #FDD188;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px; /* Ancho fijo para uniformidad */
            height: 450px; /* Altura fija para todas las tarjetas */
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Espacia los elementos dentro del cuerpo */
            flex-grow: 1; /* Hace que el contenido ocupe el espacio disponible */
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

        .card-footer {
            text-align: center;
        }

        .btn-danger {
            margin-top: auto; /* Asegura que el botón quede al final */
        }

        @media (max-width: 768px) {
            .card {
                flex: 1 1 100%; /* Una tarjeta por fila en pantallas pequeñas */
                height: auto; /* Permite que las tarjetas sean más dinámicas en pantallas pequeñas */
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Listado de Citas</h1>
        
        <!-- Mostrar mensajes flash -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php elseif ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <!-- Formulario de Búsqueda -->
        <form method="get" action="<?= site_url('usuarios/visualizar-citas'); ?>" class="mb-4">
            <div class="input-group">
                <input type="text" name="filtro" class="form-control" placeholder="Buscar por RUN o Nombre" value="<?= $this->input->get('filtro'); ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </div>
        </form>
        <div class="card-container">
            <?php if (isset($citas) && !empty($citas)): ?>
                <?php foreach ($citas as $cita): ?>
                    <?php
                    $esPasada = strtotime($cita['FechaInicio']) < time(); // Verificar si la cita es pasada
                    ?>
                    <div class="card">
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
                        <div class="card-footer">
                            <!-- Botón de cancelar -->
                            <form method="POST" action="<?= site_url('usuarios/eliminarcita'); ?>" style="display:inline;">
                                <input type="hidden" name="idCita" value="<?= $cita['ID']; ?>">
                                <input type="hidden" name="runCliente" value="<?= $cita['RUNCliente']; ?>">
                                <button 
                                    class="btn btn-danger mt-2" 
                                    <?= $esPasada ? 'disabled' : ''; ?> 
                                    type="submit">
                                    Cancelar
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info text-center w-100">
                    No se encontraron citas.
                </div>
            <?php endif; ?>
        </div>
    </div>


    <script>
        function cancelarCita(idCita, runCliente) {
            if (confirm("¿Estás seguro de que deseas cancelar esta cita?")) {
                // Envía la solicitud de cancelación al servidor
                $.post("<?= site_url('usuarios/eliminarcita'); ?>", { 
                    idCita: idCita, 
                    runCliente: runCliente 
                }, function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload(); // Recarga la página para actualizar el listado
                    } else {
                        alert("Error: " + response.message);
                    }
                }, "json").fail(function() {
                    alert("Ocurrió un error al intentar cancelar la cita.");
                });
            }
        }
    </script>
</body>
</html>
