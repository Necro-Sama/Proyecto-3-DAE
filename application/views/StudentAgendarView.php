<?php
defined("BASEPATH") or exit("No direct script access allowed"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calendario</title>
    <?php $this->load->view("navbar", $tipo); ?>
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
    <script src="<?= base_url("js/agendar.js") ?>"></script>
</head>
<body>
    <div class="container">
        <!-- Dropdown para seleccionar semana -->
        <select id="semana-select" onchange="seleccion_semana()">
            <!-- Las opciones de semanas serán generadas por JS -->
        </select>

        <table id="tabla-horario" class="table">
            <thead>
                <tr>
                    <th>Horario</th>
                    <th>Lunes</th>
                    <th>Martes</th>
                    <th>Miércoles</th>
                    <th>Jueves</th>
                    <th>Viernes</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <!-- Modal Agendar -->
        <div id="exampleModal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agendar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="dia"></p>
                        <p id="bloque_horario"></p>
                        <input type="datetime-local" id="fecha_ini" />
                        <input type="datetime-local" id="fecha_ter" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="enviarAgendar()">Agendar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Bloquear -->
        <div  id="bloquearModal" class="modal fade" tabindex="-1" aria-labelledby="bloquearModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bloquearModalLabel">Bloquear Día</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="diaBloqueo"></p>
                        <p>Bloques seleccionados: <span id="bloquesSeleccionados"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-danger" onclick="enviarBloqueos()">Bloquear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/agendar.js') ?>"></script> <!-- Enlace al archivo JavaScript -->
</body>
</html>
<style>
   /* Reducir el tamaño del calendario */
table {
    width: 90%; /* Reducir al 90% del contenedor */
    margin: 20px auto; /* Centrado horizontal */
    table-layout: fixed;
    border-collapse: separate;
    border-spacing: 0;
    border: 2px solid #000; /* Borde del calendario */
    border-radius: 15px; /* Bordes redondeados */
    overflow: hidden;
    font-size: 12px; /* Tamaño de texto más pequeño */
}

/* Ajustar las celdas de la tabla */
th, td {
    padding: 8px; /* Aumentar el espaciado */
    text-align: center;
    font-size: 10px; /* Reducir tamaño de texto */
    border: 1px solid #000;
    height: 40px; /* Altura más pequeña para las celdas */
}

/* Mantener el estilo del encabezado */
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

/* Ajuste de los botones "Agendar" */
button {
    font-size: 10px;
    padding: 2px 5px;
    cursor: pointer;
    border: 1px solid #000;
    border-radius: 5px;
    background-color: #FBF1D0;
    transition: background-color 0.3s ease;
}

button:active, button.selected {
    background-color: #FDD188;
}


</style>