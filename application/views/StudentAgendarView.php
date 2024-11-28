<?php
defined("BASEPATH") or exit("No direct script access allowed"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
    table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        max-height: calc(100vh - 150px); /* Ajusta la altura máxima a la ventana */
        overflow-y: auto; /* Permite scroll si es necesario */
        border: 2px solid #000; /* Añade un borde a toda la tabla */
    }

    th, td {
        padding: 5px;
        text-align: center;
        font-size: 12px;
        border: 1px solid #000; /* Añade bordes a cada celda */
    }

    thead th {
        background-color: #FFD700;
        color: #000;
        border: 2px solid #000; /* Bordes más gruesos en el encabezado */
    }

    tbody tr:nth-child(even) {
        background-color: #FFF7CC;
    }

    tbody tr:nth-child(odd) {
        background-color: #FFF2B3;
    }

    button {
        font-size: 10px;
        padding: 5px;
        cursor: pointer;
        border: 1px solid #000; /* Bordes a los botones */
    }


    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/style.css"/>
    <link rel="stylesheet" href="<?php echo base_url("public/bootstrap/css/bootstrap.min.css"); ?>">
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
    <h1> </h1>
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
                    <?php foreach ($motivos as $key => $value): ?>
                        <option value="<?= $key ?>"><?= $value ?></option>
                    <?php endforeach; ?>
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

    <script>
        const horarios = [
            { id: 1, rango: "08:00-08:45" },
            { id: 2, rango: "08:45-09:30" },
            { id: 3, rango: "09:40-10:25" },
            { id: 4, rango: "10:25-11:10" },
            { id: 5, rango: "11:20-12:05" },
            { id: 6, rango: "12:05-12:50" },
            { id: "almuerzo1", rango: "13:00-13:45", esAlmuerzo: true },
            { id: "almuerzo2", rango: "13:45-14:30", esAlmuerzo: true },
            { id: 7, rango: "14:45-15:30" },
            { id: 8, rango: "15:30-16:15" },
            { id: 9, rango: "16:20-17:05" },
            { id: 10, rango: "17:05-17:50" },
            { id: 11, rango: "17:55-18:40" },
            { id: 12, rango: "18:40-19:25" },
        ];

        const tablaHorario = document.getElementById("tabla-horario");

        horarios.forEach((horario, index) => {
            const fila = document.createElement("tr");
            fila.className = index % 2 === 0 ? "fila1" : "fila2";

            const celdaHora = document.createElement("td");
            celdaHora.innerHTML = `<div class="p-2 display-8">${horario.id}<br>${horario.rango}</div>`;
            fila.appendChild(celdaHora);

            for (let dia = 1; dia <= 5; dia++) {
                const celda = document.createElement("td");
                if (horario.esAlmuerzo) {
                    celda.innerHTML = `<div></div>`;
                } else {
                    celda.innerHTML = `
                        <div>
                            <button class="btn" onClick="agendar(${dia}, ${horario.id})">Agendar</button>
                        </div>
                    `;
                }
                fila.appendChild(celda);
            }
            tablaHorario.appendChild(fila);
        });

        function agendar(dia, hora) {
            alert(`Has seleccionado día ${dia} en el horario ${hora}`);
        }
    </script>
</body>
</html>
