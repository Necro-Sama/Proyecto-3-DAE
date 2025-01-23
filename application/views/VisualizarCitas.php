    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Visualizar Citas</title>
        <?php

    use Google\Service\CloudSearch\OnClick;

    $this->load->view("navbar", $tipo); ?>
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
                justify-content: space-between;
                background-color: #FDDEAA;
                border: 2px solid #FDD188;
                border-radius: 15px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                width: 300px;
                height: 450px;
            }

            .card-body {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                flex-grow: 1;
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
                margin-top: auto;
            }

            @media (max-width: 768px) {
                .card {
                    flex: 1 1 100%;
                    height: auto;
                }
            }
        </style>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <script src="<?= base_url('assets/js/scripts.js') ?>"></script>

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
                    <?php
                        foreach ($citas as $cita):
                            // Crear objetos DateTime para la cita y la fecha actual
                            $fechaCita = new DateTime($cita['FechaInicio']);
                            $fechaActual = new DateTime();

                            // Comparar la fecha y hora de la cita con la fecha y hora actuales
                            $esPasada = $fechaCita < $fechaActual;

                            // Calcular la diferencia en segundos y convertirla a minutos
                            $diferenciaSegundos = $fechaCita->getTimestamp() - $fechaActual->getTimestamp();
                            $diferenciaMinutos = $diferenciaSegundos / 60;

                            // Permitir cancelar si faltan más de 10 minutos
                            $puedeCancelar = $diferenciaMinutos > 10;
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
                                    
                                    <?php if ($tipo === 'estudiante' || $tipo === 'noestudiante'): ?>
                                        <!-- Botón de reagendar -->
                                        <form method="POST" action="<?= site_url('usuarios/vistaReagendar'); ?>" style="display:inline;">
                                            <input type="hidden" name="idCita" value="<?= $cita['ID']; ?>">
                                            <button class="btn btn-primary mt-2" <?= $esPasada ? 'disabled' : ''; ?> type="submit">
                                                Reagendar
                                            </button>
                                        </form>
                                        
                                        <!-- Botón de cancelar -->
                                        <form method="POST" action="<?= site_url('usuarios/eliminarcita'); ?>" style="display:inline;">
                                            <input type="hidden" name="idCita" value="<?= $cita['ID']; ?>">
                                            <input type="hidden" name="runCliente" value="<?= $cita['RUNCliente']; ?>">
                                            <button class="btn btn-danger mt-2" <?= (!$puedeCancelar || $esPasada) ? 'disabled' : ''; ?> type="submit">
                                                Cancelar
                                            </button>
                                        </form>
                                    <?php endif; ?>
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
                        if (response.success){
                            alert(response.message);
                            location.reload(); // Recarga la página para actualizar el listado
                        }
                        else {
                            alert("Error: " + response.message);
                        }
                    }, "json").fail(function() {
                        alert("Ocurrió un error al intentar cancelar la cita.");
                    });
                }
            }
        </script>
        <script>
            function VistaReagendar(idCita) {
                if (confirm("¿Estás seguro de que deseas reagendar esta cita?")) {
                    console.log("pasa la pregunta");
                    // Envía la solicitud de reagendar al servidor
                    $.post("<?= site_url('usuarios/vistaReagendar'); ?>", {
                        idCita: idCita
                    }, function(response) {
                        if (response.success) {
                            alert(response.message);
                            window.location.href = "<?= site_url('usuarios/visualizar-cita'); ?>"; // Redirige a la página deseada
                        } else {
                            alert("Error: " + response.message);
                        }
                    }, "json").fail(function() {
                        alert("Ocurrió un error al intentar reagendar la cita.");
                    });
                }
            }
        </script>
    </body>
    </html>
