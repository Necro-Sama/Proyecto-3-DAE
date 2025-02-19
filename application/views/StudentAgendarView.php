<?php
defined("BASEPATH") or exit("No direct script access allowed");

// Al inicio del archivo, verificar que tenemos el tipo
if (!isset($tipo)) {
    die('Error: Tipo de usuario no definido');
}

// Obtener la fecha actual
$fecha_actual = new DateTime();
$dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
$fechas_dias = [];

// Calcular las fechas para cada día de la semana
for ($i = 0; $i < 5; $i++) {
    $fecha_dia = clone $fecha_actual;
    $fecha_dia->modify("+$i days");
    $fechas_dias[] = $fecha_dia->format('d-m-Y'); // Formato: 10-febrero-2025
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    
    <meta charset="UTF-8">
    <title>Agenda</title>
    <?php 
    if($this->session->reagendar = 0)
    {
        $this->session->unset_userdata(array(
            'id_cita_anterior',
        ));
    }
    ?>
    <?php $this->load->view('navbar', $tipo); ?>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/agendar.css") ?>"/>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css">

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Inicialización de variables globales -->
    <script>
        // Función para limpiar el estado de agendar.js
        function limpiarEstadoAgenda() {
            if (typeof jQuery !== 'undefined') {
                $('.checkbox-dia').off();
                $('#semana-select').off();
                $('.dia-checkbox').off();
                $('#btn-bloquear').off();
                $('#tabla-horario').empty();
                $('.bloque-hora').removeData();
                $('.selected').removeClass('selected');
                $('.disponible').removeClass('disponible');
                $('.ocupado').removeClass('ocupado');
            }
            
            window.agendarConfig = null;
            window.cargar_calendario = null;
            window.seleccion_semana = null;
            window.marcarTodos = null;
        }

        // Asegurarse de que agendarConfig esté definido antes de usarlo
        window.agendarConfig = {
            tipoUsuario: '<?= isset($tipo) ? $tipo : "" ?>',
            site_url: '<?= site_url() ?>',
            base_url: '<?= base_url() ?>',
            run: '<?= isset($run) ? $run : "" ?>',
            reagenda: <?= isset($reagenda) && $reagenda ? 'true' : 'false' ?>,
            id_cita_anterior: '<?= $this->session->userdata("id_cita_anterior") ?? "" ?>',
            runTS: '<?= isset($runTS) ? $runTS : "" ?>'
        };

        // Verificar en consola
        console.log('agendarConfig inicializado:', window.agendarConfig);

        <?php if(isset($reagenda) && $reagenda): ?>
            console.log('Modo reagendamiento - ID cita:', window.agendarConfig.id_cita_anterior);
        <?php endif; ?>

        window.BOTON_TEXTO = '<?php echo isset($reagenda) && $reagenda ? "Reagendar" : "Agendar"; ?>';
    </script>

    <!-- Cargar agendar.js después de la inicialización -->
    <script src="<?= base_url('js/agendar.js') ?>"></script>
</head>

<body>

   
    <h1> </h1>
    <div class="container">
        <!-- <h6>Bloques disponibles</h6> -->
        <?php if ($this->session->agendar_error): ?>
            <p class="error font-weight-bold alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->agendar_error ?>
            </p>
        <?php endif; ?>

        <?php if ($this->session->agendar_exito): ?>
            <p class="alert-success font-weight-bold alert alert-dismissible fade show" role="alert">
                <?= $this->session->agendar_exito ?>
            </p>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('debug_info') && ENVIRONMENT === 'development'): ?>
            <div class="alert alert-info">
                <pre><?= $this->session->flashdata('debug_info') ?></pre>
            </div>
        <?php endif; ?>
            <!-- Botón para abrir el modal (justo arriba del calendario) -->
        <div id="tiempo-servidor" hidden><?= $this->BloqueModel->get_tiempo_bd() ?></div>
        
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="semana">Semana: </label>
                    <?php  $semanas = $this->BloqueModel->get_semanas(3); ?>
                    <select class="form-select" name="semana" id="semana-select" onchange="seleccion_semana(event)">
                        <?php foreach ($semanas as $semana) { 
                            $fecha = new DateTime($semana);
                            $fechaFin = clone $fecha;
                            $fechaFin->modify('+4 days');
                        ?>
                            <option value="<?= $semana ?>">
                                <?= $fecha->format('d/m/Y') ?> - <?= $fechaFin->format('d/m/Y') ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            
            <?php if($tipo === 'administrador' || $tipo === 'trabajadorsocial'): ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ts-select">Trabajador Social:</label>
                        <select class="form-control" id="ts-select" name="ts-select" required >
                            <option value="">Seleccione un Trabajador Social</option>
                            <?php if(isset($trabajadores_sociales) && !empty($trabajadores_sociales)): ?>
                                <?php foreach ($trabajadores_sociales as $ts): ?>
                                    <option value="<?= $ts['RUN'] ?>">
                                        <?= $ts['Nombre'] . ' ' . $ts['Apellido'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>

                    </div>
                </div>

                <div class="col-12 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Seleccionar días a bloquear para la semana seleccionada:</h6>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dia-checkbox" type="checkbox" id="check-lunes" value="lunes">
                                <label class="form-check-label" for="check-lunes">Lunes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dia-checkbox" type="checkbox" id="check-martes" value="martes">
                                <label class="form-check-label" for="check-martes">Martes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dia-checkbox" type="checkbox" id="check-miercoles" value="miercoles">
                                <label class="form-check-label" for="check-miercoles">Miércoles</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dia-checkbox" type="checkbox" id="check-jueves" value="jueves">
                                <label class="form-check-label" for="check-jueves">Jueves</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dia-checkbox" type="checkbox" id="check-viernes" value="viernes">
                                <label class="form-check-label" for="check-viernes">Viernes</label>
                            </div>
                            <button type="button" class="btn btn-warning mt-2" id="btn-bloquear">
                                Bloquear días seleccionados
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        

        <table class="text-center">
        <thead>
            <tr>
                <th><div class="p-2 display-7 dia">Hora</div></th>
                <?php foreach ($dias as $index => $dia): ?>
                    <th>
                        <div class="p-2 display-7 dia">
                            <?php 
                            $fecha = new DateTime($fechas_dias[$index]);
                            echo $dia . " " . $fecha->format('d-F-Y'); 
                            ?>
                        </div>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
            <tbody id="tabla-horario">
                <!-- Contenido generado dinamicamente -->
            </tbody>
        </table>

        

        <div id='calendar'></div>
    </div>

    <!-- Modal para agendar -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <?php echo isset($reagenda) && $reagenda ? 'Reagendar Cita' : 'Agendar Cita'; ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="<?= site_url('usuarios/accion_agendar') ?>">
                    <div class="modal-body">
                        <!-- Campos ocultos -->
                        <input type="hidden" id="fecha_ini" name="fecha_ini">
                        <input type="hidden" id="fecha_ter" name="fecha_ter">
                        <input type="hidden" id="runTS" name="RUN">
                        <input type="hidden" id="run_usuario" name="run_usuario">
                        <input type="hidden" name="reagenda" value="<?php echo isset($reagenda) && $reagenda ? 'true' : 'false'; ?>">
                        
                        <!-- Campos visibles -->
                        <div class="form-group">
                            <label>Día:</label>
                            <span id="dia" class="ml-2"></span>
                        </div>
                        <div class="form-group">
                            <label>Horario:</label>
                            <span id="bloque_horario" class="ml-2"></span>
                        </div>
                        <div class="form-group">
                            <label for="motivo">Motivo de la cita:</label>
                            <select class="form-control" name="motivo" required>
                                <option value="">Seleccione un motivo...</option>
                                <?php
                                $motivos = [
                                    "Gratuidad Mineduc",
                                    "Becas de arancel Mineduc",
                                    "Fondo Solidario de Crédito Universitario",
                                    "Beneficios Junaeb (BAES y Becas de mantención)",
                                    "Beca Fotocopia UTA",
                                    "Beca Alimentación UTA",
                                    "Beca Residencia UTA",
                                    "Beca Internado UTA",
                                    "Beca Ayuda Estudiantil UTA",
                                    "Beca PSU-PDT-PAES UTA",
                                    "Otro"
                                ];
                                foreach ($motivos as $m): ?>
                                    <option value="<?= $m ?>"><?= $m ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <?php echo isset($reagenda) && $reagenda ? 'Reagendar Cita' : 'Agendar Cita'; ?>
                        </button>
                        <?php if (isset($reagenda) && $reagenda && isset($id_cita_anterior)): ?>
                            <input type="hidden" name="id_cita_anterior" value="<?php echo $id_cita_anterior; ?>">
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Función para formatear fecha en español
        function formatearFecha(fecha) {
            const opciones = { 
                day: 'numeric', 
                month: 'long', 
                year: 'numeric' 
            };
            return fecha.toLocaleDateString('es-ES', opciones);
        }

        // Función para generar las opciones de semanas
        function generarOpcionesSemanas() {
            const selectSemana = document.getElementById('semana_inicio');
            const hoy = new Date();
            const primerDia = new Date(hoy);
            
            // Ajustar al próximo lunes si no es lunes
            const diaSemana = hoy.getDay();
            const diasHastaLunes = diaSemana === 0 ? 1 : 8 - diaSemana;
            primerDia.setDate(hoy.getDate() + diasHastaLunes);

            // Generar opciones para las próximas 12 semanas
            for (let i = 0; i < 12; i++) {
                const inicioSemana = new Date(primerDia);
                inicioSemana.setDate(primerDia.getDate() + (i * 7));
                
                const finSemana = new Date(inicioSemana);
                finSemana.setDate(inicioSemana.getDate() + 4); // Hasta el viernes

                const option = document.createElement('option');
                option.value = inicioSemana.toISOString().split('T')[0];
                option.text = `Semana ${i + 1}: ${formatearFecha(inicioSemana)} - ${formatearFecha(finSemana)}`;
                selectSemana.appendChild(option);
            }
        }

        // Inicializar selectores
        generarOpcionesSemanas();

        // Event listeners
        document.getElementById('semana_inicio').addEventListener('change', actualizarFechaTermino);
        document.getElementById('cantidad_semanas').addEventListener('change', actualizarFechaTermino);

        // Actualizar fecha de término inicial
        actualizarFechaTermino();
    });

    function seleccion_semana(event) {
        const fechaInicio = new Date(event.target.value);
        const dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
        const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        
        const encabezados = document.querySelectorAll('.dia');
        
        // Saltamos el primer encabezado que es "Hora"
        for(let i = 1; i < encabezados.length; i++) {
            const fecha = new Date(fechaInicio);
            fecha.setDate(fechaInicio.getDate() + (i-1));
            
            const dia = dias[i-1];
            const formatoFecha = `${dia} ${fecha.getDate()}-${meses[fecha.getMonth()]}-${fecha.getFullYear()}`;
            encabezados[i].textContent = formatoFecha;
        }
        
        // Resto de la lógica de selección de semana...
        cargar_calendario();
    }
    </script>
</body>
</html>
<style>
    /* Reducir el tamaño vertical del calendario */
    table {
        width: 80%; /* Ancho reducido */
        margin: 20px auto; /* Centrado horizontal */
        table-layout: fixed;
        border-collapse: separate;
        border-spacing: 0;
        border: 2px solid #000; /* Borde del calendario */
        border-radius: 15px; /* Bordes redondeados */
        overflow: hidden;
        font-size: 12px; /* Tamaño de texto más pequeño */
    }

    th, td {
        padding: 4px; /* Menor espaciado interno */
        text-align: center;
        font-size: 11px; /* Texto más pequeño */
        border: 1px solid #000; /* Bordes internos */
        height: 30px; /* Altura fija para las celdas */
    }

    /* Estilo del encabezado */
    thead {
        background-color: #FBF1D0;
        color: #000;
        border-radius: 15px 15px 0 0;
    }

    /* Alternar colores en las filas del cuerpo */
    tbody tr:nth-child(even) {
        background-color: #FFF7CC;
    }

    tbody tr:nth-child(odd) {
        background-color: #FFF2B3;
    }

    /* Estilo para los botones "Agendar" */
    button {
        font-size: 10px;
        padding: 2px 5px; /* Más compacto */
        cursor: pointer;
        border: 1px solid #000;
        border-radius: 5px; /* Bordes redondeados */
        background-color: #FBF1D0; /* Color inicial */
        transition: background-color 0.3s ease; /* Animación al cambiar de color */
    }

    button:active, button.selected {
        background-color: #FDD188; /* Color cuando está seleccionado */
    }
    /* Estilo para el mensaje de error */
    .error {
        position: relative;
        padding: 15px;
        margin: 10px 0;
        border-radius: 5px;
        font-size: 16px;
        background-color: #f8d7da; /* Fondo de error */
        color: #721c24; /* Color del texto */
        border: 1px solid #f5c6cb; /* Borde sutil */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Botón de cierre */
    .error .btn-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #721c24;
        cursor: pointer;
    }

    /* Efecto de hover en el botón */
    .error .btn-close:hover {
        color: #f5c6cb;
    }

    /* Estilo para el texto */
    .error strong {
        font-weight: bold;
    }

    /* Estilo en caso de que el error tenga un mensaje largo o más de una línea */
    .error {
        white-space: normal;
        word-wrap: break-word;
    }
    /* Personalización del modal */
    .modal-body {
        font-size: 16px;
        padding: 20px;
    }

    .form-group label {
        font-weight: bold;
    }

    .form-control-plaintext {
    border: none;  /* Eliminar el borde */
    background-color: transparent;  /* Asegurarse de que el fondo sea transparente */
    padding: 0;  /* Eliminar el padding extra */
    font-size: 16px;  /* Tamaño de texto adecuado */
    }
    .form-select {
        font-size: 14px;
    }

    /* Actualizar estilos existentes */
    .form-select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #fff;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #333;
    }

    /* Mejorar estilos de la tabla */
    table {
        width: 100%;
        margin: 20px 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    th {
        background-color: #4a5568;
        color: white;
        padding: 12px;
        font-weight: 600;
    }

    td {
        padding: 8px;
        vertical-align: middle;
    }

    /* Agregar hover effect a las filas */
    tbody tr:hover {
        background-color: #f8f9fa;
        transition: background-color 0.2s ease;
    }

    /* Mejorar estilos de los botones */
    .btn {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    /* Estilos base para todos los botones */
    .btn-custom {
        padding: 8px 16px;
        margin: 5px;
        border-radius: 5px;
        transition: all 0.3s ease;
        font-weight: 500;
        border: 2px solid transparent;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 120px;
    }

    .btn-custom i {
        margin-right: 8px;
    }

    /* Botón principal (azul DAE) */
    .btn-primary-dae {
        background-color: #2B309E;
        color: white;
        border-color: #2B309E;
    }

    .btn-primary-dae:hover {
        background-color: white;
        color: #2B309E;
        border-color: #2B309E;
        transform: scale(1.05);
    }

    /* Botón de acción (verde) */
    .btn-action-dae {
        background-color: #28a745;
        color: white;
        border-color: #28a745;
    }

    .btn-action-dae:hover {
        background-color: white;
        color: #28a745;
        border-color: #28a745;
        transform: scale(1.05);
    }

    /* Botón de cancelar (rojo) */
    .btn-cancel-dae {
        background-color: #dc3545;
        color: white;
        border-color: #dc3545;
    }

    .btn-cancel-dae:hover {
        background-color: white;
        color: #dc3545;
        border-color: #dc3545;
        transform: scale(1.05);
    }

    /* Contenedor de botones */
    .button-container {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        margin: 10px 0;
    }

    /* Estilos responsivos */
    @media (max-width: 768px) {
        .btn-custom {
            width: 100%;
            margin: 5px 0;
        }
        
        .button-container {
            flex-direction: column;
        }
    }

    /* Estilos adicionales para el modal */
    .modal-content {
        border-radius: 10px;
    }

    .modal-header {
        background-color: #2B309E;
        color: white;
        border-radius: 10px 10px 0 0;
    }

    .modal-header .btn-close {
        color: white;
        background-color: white;
    }

    .form-label {
        font-weight: 500;
    }

    .form-control, .form-select {
        border-radius: 5px;
        border: 1px solid #ced4da;
        padding: 8px 12px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2B309E;
        box-shadow: 0 0 0 0.2rem rgba(43, 48, 158, 0.25);
    }

    .form-check-input:checked {
        background-color: #2B309E;
        border-color: #2B309E;
    }
</style>
