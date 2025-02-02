<?php
defined("BASEPATH") or exit("No direct script access allowed"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reagendar Cita</title>
    <?php $this->load->view('navbar', $tipo); ?>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/agendar.css") ?>"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <!-- Información de la cita actual -->
        <div class="card mb-4 mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Información de Cita Actual</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($citaOriginal['FechaInicio'])) ?></p>
                        <p><strong>Hora:</strong> <?= date('H:i', strtotime($citaOriginal['FechaInicio'])) ?> - 
                                                <?= date('H:i', strtotime($citaOriginal['FechaTermino'])) ?></p>
                        <p><strong>Motivo:</strong> <?= $citaOriginal['Motivo'] ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estructura exacta del calendario como en agendar -->
        <div id="contenedor-principal" class="mt-4">
            <div id="contenedor-calendario">
                <div class="controles-calendario mb-3">
                    <button type="button" id="btn-semana-anterior" class="btn btn-primary">Semana Anterior</button>
                    <button type="button" id="btn-semana-siguiente" class="btn btn-primary">Semana Siguiente</button>
                </div>
                <input type="hidden" id="tiempo-servidor" value="<?= date("Y-m-d") ?>">
                <div class="table-responsive">
                    <table id="tabla-horario" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <th>Lunes</th>
                                <th>Martes</th>
                                <th>Miércoles</th>
                                <th>Jueves</th>
                                <th>Viernes</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-horario-body">
                            <!-- Se llenará dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal de reagendamiento -->
        <div class="modal fade" id="modalReagendar" tabindex="-1" role="dialog" aria-labelledby="modalReagendarLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalReagendarLabel">Confirmar Reagendamiento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form-reagendar" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="idCitaAnterior" value="<?= $idCita ?>">
                            <input type="hidden" name="fecha_inicio" id="fecha_inicio">
                            <input type="hidden" name="fecha_fin" id="fecha_fin">
                            <input type="hidden" name="motivo" value="<?= $citaOriginal['Motivo'] ?>">
                            
                            <div class="info-cita">
                                <p><strong>Fecha seleccionada:</strong> <span id="fecha-seleccionada"></span></p>
                                <p><strong>Hora:</strong> <span id="hora-seleccionada"></span></p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Confirmar Reagendamiento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <script>
        // Configuración para el calendario
        window.agendarConfig = {
            tipoUsuario: '<?= $tipo ?>',
            site_url: '<?= site_url() ?>',
            base_url: '<?= base_url() ?>',
            run: '<?= $run ?>',
            reagenda: true,
            citaOriginal: <?= json_encode($citaOriginal) ?>,
            idCitaAnterior: '<?= $idCita ?>'
        };

        // Debugger para el calendario
        console.log('Configuración del calendario:', window.agendarConfig);
        
        // Verificar elementos del DOM
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado');
            console.log('Elemento tabla-horario:', document.getElementById('tabla-horario'));
            console.log('Elemento tiempo-servidor:', document.getElementById('tiempo-servidor'));
            
            // Verificar si el script de agendar.js se cargó
            console.log('Script agendar.js:', typeof cargar_calendario !== 'undefined' ? 'Cargado' : 'No cargado');
            
            // Intentar cargar el calendario
            try {
                if(typeof cargar_calendario === 'function') {
                    console.log('Intentando cargar calendario...');
                    cargar_calendario();
                }
            } catch(error) {
                console.error('Error al cargar calendario:', error);
            }
        });
    </script>

    <!-- Asegurarnos que agendar.js se carga después de jQuery -->
    <script>
        // Verificar que jQuery está disponible
        if(typeof jQuery === 'undefined') {
            console.error('jQuery no está cargado!');
        } else {
            console.log('jQuery versión:', jQuery.fn.jquery);
        }
    </script>
    <script src="<?= base_url('js/agendar.js') ?>"></script>

    <?php if(isset($debug) && $debug): ?>
        <script>
            console.log('Datos del controlador:', {
                tipo: '<?= $tipo ?>',
                run: '<?= $run ?>',
                idCita: '<?= $idCita ?>',
                citaOriginal: <?= json_encode($citaOriginal) ?>
            });
        </script>
    <?php endif; ?>
</body>
</html> 