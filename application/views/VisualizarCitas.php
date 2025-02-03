    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Visualizar Citas</title>
        <?php  $this->load->view("navbar", $tipo); ?>
        <!-- <?php 
        // Limpiar cualquier dato de reagendamiento al cargar la vista
        $this->session->unset_userdata(array(
            'id_cita_anterior',
            'reagenda',
            'fecha_seleccionada',
            'horario_seleccionado'
        ));
        ?> -->
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
                padding: 20px;
            }

            .card {
                width: 300px;
                margin-bottom: 20px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }

            .card-body {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                flex-grow: 1;
            }

            .card-title {
                font-weight: bold;
                margin-bottom: 1rem;
                text-align: center;
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

            .text-warning {
                color: #ffc107 !important;
            }
            
            .text-success {
                color: #28a745 !important;
            }
            
            .text-danger {
                color: #dc3545 !important;
            }
            
            .text-primary {
                color: #007bff !important;
            }
            
            .btn {
                margin-top: 10px;
                width: 100%;
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
                    <?php foreach ($citas as $cita):
                        // Crear objetos DateTime para la cita y la fecha actual
                        $fechaCita = new DateTime($cita['FechaInicio']);
                        $fechaActual = new DateTime();
                        $esPasada = $fechaCita < $fechaActual;
                        
                        // Obtener el estado de la cita
                        $estado = isset($cita['Estado']) ? $cita['Estado'] : '';
                        
                        // Determinar el estilo según el estado
                        $estadoClass = '';
                        $estadoText = '';
                        
                        if ($esPasada && $estado !== 'Atendido' && $estado !== 'Cancelado') {
                            $estadoClass = 'text-danger';
                            $estadoText = 'Ausente';
                            $estado = 'Ausente'; // Actualizar el estado
                        } elseif ($estado === 'Cancelado') {
                            $estadoClass = 'text-warning';
                            $estadoText = 'Cita Cancelada';
                        } elseif ($estado === 'Atendido') {
                            $estadoClass = 'text-success';
                            $estadoText = 'Cita Atendida';
                        } elseif ($estado === 'Reservado') {
                            $estadoClass = 'text-primary';
                            $estadoText = 'Cita Reservada';
                        }
                    ?>
                        <div class="card">
                            <div class="card-body">
                                <?php if (!empty($estadoText)): ?>
                                    <h5 class="card-title <?= $estadoClass ?>"><?= $estadoText ?></h5>
                                <?php endif; ?>

                                <p class="card-text"><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($cita['FechaInicio'])) ?></p>
                                <p class="card-text"><strong>Estudiante:</strong> <?= $cita['NombreEstudiante'] . ' ' . $cita['ApellidoEstudiante'] ?></p>
                                <p class="card-text"><strong>Trabajador Social:</strong> <?= $cita['NombreTS'] . ' ' . $cita['ApellidoTS'] ?></p>
                                <p class="card-text"><strong>Motivo:</strong> <?= $cita['Motivo'] ?></p>
                                
                                <?php if ($tipo === 'trabajadorsocial' && $estado === 'Reservado'): ?>
                                    <button type="button" class="btn btn-success" onclick="marcarComoAtendida(<?= $cita['ID'] ?>)">
                                        Marcar como Atendida
                                    </button>
                                <?php endif; ?>

                                <?php if (($tipo === 'estudiante' || $tipo === 'noestudiante') && $estado === 'Reservado'): ?>
                                    <form method="POST" action="<?= site_url('usuarios/eliminarcita'); ?>" style="display:inline;">
                                        <input type="hidden" name="idCita" value="<?= $cita['ID'] ?>">
                                        <input type="hidden" name="runCliente" value="<?= $cita['RUNCliente'] ?>">
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas cancelar esta cita?')">
                                            Cancelar Cita
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <?php if ($cita['Estado'] === 'Reservado'): ?>
                                    <form method="POST" action="<?= site_url('usuarios/agendar'); ?>" style="display:inline;">
                                        <?php $this->session->set_userdata('id_cita_anterior', $cita['ID']); ?>
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="fas fa-calendar-alt"></i> Reagendar
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
        <script>
            function marcarComoAtendida(idCita) {
                if (confirm('¿Está seguro de marcar esta cita como atendida?')) {
                    fetch('<?= site_url('trabajadorsocial/marcarComoAtendida') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            idCita: idCita
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert('Cita marcada como atendida exitosamente');
                            window.location.reload();
                        } else {
                            alert(data.message || 'Error al marcar la cita como atendida');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al procesar la solicitud: ' + error.message);
                    });
                }
            }
        </script>
    </body>
    </html>
