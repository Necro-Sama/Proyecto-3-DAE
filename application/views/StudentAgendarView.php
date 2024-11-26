<?php
defined("BASEPATH") or exit("No direct script access allowed"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/style.css"/>
    <link rel="stylesheet" href="<?php echo base_url(
        "public/bootstrap/css/bootstrap.min.css"
    ); ?>">
    <script src="<?= base_url("js/agendar.js") ?>"></script>
</head>
<body>
<?php $this->load->view("navbar"); ?> <!-- Incluir el navbar -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?php echo base_url(
        "public/bootstrap/css/bootstrap.min.css"
    ); ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/style.css"/>
    <!-- <script type="text/javascript">
        function toggle(id) {
            button = document.getElementById("B"+id);
            div = document.getElementById("D"+id)
            if (button.innerHTML == "Mostrar") {
                button.innerHTML = "Ocultar";
                div.style.display = "block";
            } else {
                button.innerHTML = "Mostrar";
                div.style.display = "none";
            }
        }
    </script> -->
    <h1>Agendar para estudiantes</h1>
    <div class="error">
    <?= $this->session->agendar_error ?>
    </div>
    <?php
    $motivos = [
        "Gratuidad Mineduc" => "Gratuidad Mineduc",
        "Becas de arancel Mineduc" => "Becas de arancel Mineduc",
        "Fondo Solidario de Crédito Universitario" =>
            "Fondo Solidario de Crédito Universitario",
        "Beneficios Junaeb (BAES y Becas de mantención)" =>
            "Beneficios Junaeb (BAES y Becas de mantención)",
        "Beca Fotocopia UTA" => "Beca Fotocopia UTA",
        "Beca Alimentación UTA" => "Beca Alimentación UTA",
        "Beca Residencia UTA" => "Beca Residencia UTA",
        "Beca Internado UTA" => "Beca Internado UTA",
        "Beca Ayuda Estudiantil UTA" => "Beca Ayuda Estudiantil UTA",
        "Beca PSU-PDT-PAES UTA" => "Beca PSU-PDT-PAES UTA",
        "Otro" => "Otro",
    ];
    $dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];
    $estudiante = $this->UserModel->get_estudiante($RUN_ESTUDIANTE);
    $bloques = $this->BloqueModel->get_bloques_carrera(
        $estudiante->COD_CARRERA
    );
    // print_r($bloques);
    ?>
    <p>RUN estudiante: <?= $RUN_ESTUDIANTE ?></p>
    
    <div class="container">
        <h3>Bloques disponibles</h3>
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
            <tbody>
                <tr class="fila1">
                    <td><div class="p-2 display-8">1<br>08:00-08:45</div></td>
                    <td><div><button class="btn" onClick="agendar(1, 1)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(2, 1)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(3, 1)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(4, 1)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(5, 1)">Agendar</button></div></td>
                </tr>
                <tr class="fila2">
                    <td><div class="p-2 display-8">2<br>08:45-09:30</div></td>
                    <td><div><button class="btn" onClick="agendar(1, 2)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(2, 2)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(3, 2)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(4, 2)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(5, 2)">Agendar</button></div></td>
                </tr>
                <tr class="fila1">
                    <td><div class="p-2 display-8">3<br>09:40-10:25</div></td>
                    <td><div><button class="btn" onClick="agendar(1, 3)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(2, 3)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(3, 3)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(4, 3)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(5, 3)">Agendar</button></div></td>
                </tr>
                <tr class="fila2">
                    <td><div class="p-2 display-8">4<br>10:25-11:10</div></td>
                    <td><div><button class="btn" onClick="agendar(1, 4)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(2, 4)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(3, 4)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(4, 4)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(5, 4)">Agendar</button></div></td>
                </tr>
                <tr class="fila1">
                    <td><div class="p-2 display-8">5<br>11:20-12:05</div></td>
                    <td><div><button class="btn" onClick="agendar(1, 5)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(2, 5)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(3, 5)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(4, 5)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(5, 5)">Agendar</button></div></td>
                </tr>
                <tr class="fila2">
                    <td><div class="p-2 display-8">6<br>12:05-12:50</div></td>
                    <td><div><button class="btn" onClick="agendar(1, 6)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(2, 6)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(3, 6)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(4, 6)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(5, 6)">Agendar</button></div></td>
                </tr>
                <tr class="fila1">
                    <td><div class="p-2 display-8">Almuerzo<br>13:00-13:45</div></td>
                    <td></td><td></td><td></td><td></td><td></td>
                </tr>
                <tr class="fila2">
                    <td><div class="p-2 display-8">Almuerzo<br>13:45-14:30</div></td>
                    <td></td><td></td><td></td><td></td><td></td>
                </tr>
                <tr class="fila1">
                    <td><div class="p-2 display-8">7<br>14:45-15:30</div></td>
                    <td><div><button class="btn" onClick="agendar(1, 7)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(2, 7)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(3, 7)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(4, 7)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(5, 7)">Agendar</button></div></td>
                </tr>
                <tr class="fila2">
                    <td><div class="p-2 display-8">8<br>15:30-16:15</div></td>
                    <td><div><button class="btn" onClick="agendar(1, 8)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(2, 8)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(3, 8)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(4, 8)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(5, 8)">Agendar</button></div></td>
                </tr>
                <tr class="fila1">
                    <td><div class="p-2 display-8">9<br>16:20-17:05</div></td>
                    <td><div><button class="btn" onClick="agendar(1, 9)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(2, 9)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(3, 9)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(4, 9)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(5, 9)">Agendar</button></div></td>
                </tr>
                <tr class="fila2">
                    <td><div class="p-2 display-8">10<br>17:05-17:50</div></td>
                    <td><div><button class="btn" onClick="agendar(1, 10)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(2, 10)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(3, 10)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(4, 10)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(5, 10)">Agendar</button></div></td>
                </tr>
                <tr class="fila1">
                    <td><div class="p-2 display-8">11<br>17:55-18:40</div></td>
                    <td><div><button class="btn" onClick="agendar(1, 11)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(2, 11)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(3, 11)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(4, 11)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(5, 11)">Agendar</button></div></td>
                </tr>
                <tr class="fila2">
                    <td><div class="p-2 display-8">12<br>18:40-19:25</div></td>
                    <td><div><button class="btn" onClick="agendar(1, 12)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(2, 12)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(3, 12)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(4, 12)">Agendar</button></div></td>
                    <td><div><button class="btn" onClick="agendar(5, 12)">Agendar</button></div></td>
                </tr>
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
                    <option value="1">Gratuidad Mineduc</option>
                    <option value="2">Becas de arancel Mineduc</option>
                    <option value="3">Fondo Solidario de Crédito Universitario</option>
                    <option value="4">Beneficios Junaeb (BAES y Becas de mantención)</option>
                    <option value="5">Beca Fotocopia UTA</option>
                    <option value="6">Beca Alimentación UTA</option>
                    <option value="7">Beca Residencia UTA</option>
                    <option value="8">Beca Internado UTA</option>
                    <option value="9">Beca Ayuda Estudiantil UTA</option>
                    <option value="10">Beca PSU-PDT-PAES UTA</option>
                    <option value="11">Otro</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <!-- <button type="button" class="btn btn-primary">Agendar</button> -->
                <input type="submit" class="btn btn-primary" name="agendar" value="Agendar" />
            </div>
            </form>
            </div>
        </div>
    </div>
</body>
</html>

