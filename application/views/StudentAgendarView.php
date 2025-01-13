<?php
defined("BASEPATH") or exit("No direct script access allowed"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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

    <?php $this->load->view('navbar',$tipo); ?>
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

        <div id="tiempo-servidor" hidden><?= $this->BloqueModel->get_tiempo_bd() ?></div>
        
        <label for="semana">Semana: </label>
        <?php  $semanas = $this->BloqueModel->get_semanas(3); ?>
        <select class="form-select" name="semana" id="semana-select" onchange="seleccion_semana(event)">
            <?php foreach ($semanas as $semana) { ?>
                <option value="<?= $semana ?>">
                    <?= trim($semana, "00:00:00") ?>
                </option>
            <?php } ?>
        </select>

        <!-- Agregar el botón "Bloquear" debajo del select -->
        <button type="button" class="btn btn-danger" id="btn-bloquear">Bloquear</button>

        <table class="text-center">
        <thead>
            <tr>
                <th><div class="p-2 display-7 dia">Hora</div></th>
                <th><div class="p-2 display-7 dia">
                Lunes
                <input type="checkbox" class="checkbox-dia" id="checkbox-lunes" onchange="marcarTodos('lunes')" />
                    </div>
                </th>
                <th><div class="p-2 display-7 dia">
                Martes
                <input type="checkbox" class="checkbox-dia" id="checkbox-martes" onchange="marcarTodos('martes')" />
                    </div>
                </th>
                <th><div class="p-2 display-7 dia">
                Miércoles
                <input type="checkbox" class="checkbox-dia" id="checkbox-miercoles" onchange="marcarTodos('miercoles')" />
                    </div>
                </th>
                <th><div class="p-2 display-7 dia">
                Jueves
                <input type="checkbox" class="checkbox-dia" id="checkbox-jueves" onchange="marcarTodos('jueves')" />
                    </div>
                </th>
                <th><div class="p-2 display-7 dia">
                Viernes
                <input type="checkbox" class="checkbox-dia" id="checkbox-viernes" onchange="marcarTodos('viernes')" />
                    </div>
                </th>
            </tr>
        </thead>
            <tbody id="tabla-horario">
                <!-- Contenido generado dinámicamente -->
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agendar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form method="post" accept-charset="utf-8" action="<?= site_url() ?>/usuarios/accion_agendar">
                    <div class="modal-body">
                        <input type="text" id="fecha_ini" name="fecha_ini" hidden>
                        <input type="text" id="fecha_ter" name="fecha_ter" hidden>
                        <div class="form-group">
                            <label for="dia">Día: </label>
                            <span id="dia"></span>
                        </div>
                        <div class="form-group">
                            <label for="bloque_horario">Bloque Horario: </label>
                            <span id="bloque_horario"></span>
                        </div>
                        <div class="form-group">
                            <label for="motivo">Motivo: </label>
                            <select class="form-select" aria-label="Default select example" name="motivo">
                                <option value="" selected>Seleccionar Motivo...</option>
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
                                    "Otro",
                                ];
                                foreach ($motivos as $m): ?>
                                    <option value="<?= $m ?>"><?= $m ?></option>
                                <?php endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" name="agendar" value="Agendar" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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


</style>