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
    <?php $this->load->view("navbar"); ?> <!-- Incluir el navbar -->
    <h1> </h1>
    <div class="container">
        <h3>Bloques disponibles</h3>
        <p class="error font-weight-bold">
            <?= $this->session->agendar_error ?>
        </p>
        <table class="text-center">
            <thead>
                <tr>
                    <th><div class="p-2 display-7 dia">Hora</div></th>
                    <th><div class="p-2 display-7 dia">Lunes</div></th>
                    <th><div class="p-2 display-7 dia">Martes</div></th>
                    <th><div class="p-2 display-7 dia">Miércoles</div></th>
                    <th><div class="p-2 display-7 dia">Jueves</div></th>
                    <th><div class="p-2 display-7 dia">Viernes</div></th>
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
                <label for="dia">Día: </label>
                <input type="text" name="dia" id="dia" readonly>
                <br>
                <label for="bloque_horario">Bloque Horario: </label>
                <input type="text" name="bloque_horario" id="bloque_horario" readonly>
                <br>
                <label for="motivo">Motivo: </label>
                <select class="form-select" aria-label="Default select example" name="motivo">
                    <option selected>Seleccionar Motivo...</option>
                    <?php
                    $motivos = [
                        "Gratuidad Mineduc" => "Gratuidad Mineduc",
                        "Becas de arancel Mineduc" =>
                            "Becas de arancel Mineduc",
                        "Fondo Solidario de Crédito Universitario" =>
                            "Fondo Solidario de Crédito Universitario",
                        "Beneficios Junaeb (BAES y Becas de mantención)" =>
                            "Beneficios Junaeb (BAES y Becas de mantención)",
                        "Beca Fotocopia UTA" => "Beca Fotocopia UTA",
                        "Beca Alimentación UTA" => "Beca Alimentación UTA",
                        "Beca Residencia UTA" => "Beca Residencia UTA",
                        "Beca Internado UTA" => "Beca Internado UTA",
                        "Beca Ayuda Estudiantil UTA" =>
                            "Beca Ayuda Estudiantil UTA",
                        "Beca PSU-PDT-PAES UTA" => "Beca PSU-PDT-PAES UTA",
                        "Otro" => "Otro",
                    ];
                    foreach ($motivos as $key => $value): ?>
                        <option value="<?= $key ?>"><?= $value ?></option>
                    <?php endforeach;
                    ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <input type="submit" class="btn btn-primary" name="agendar" value="Agendar" />
            </div>
            </form>
            </div>
        </div>
    </div>
</body>
</html>
