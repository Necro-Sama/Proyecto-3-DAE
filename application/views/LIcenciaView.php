<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Licencia</title>
    <script src="https://accounts.google.com/gsi/client" async></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fbf1d0; /* Naranja 3 */
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fddeaa; /* Naranja 2 */
            border: 2px solid #fdd188; /* Naranja 1 */
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #060eae; /* Azul 1 */
        }

        .form-container label {
            font-weight: bold;
            color: #060eae; /* Azul 1 */
        }

        .form-container select,
        .form-container input[type="date"],
        .form-container button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #fdd188; /* Naranja 1 */
        }

        .form-container select:focus,
        .form-container input[type="date"]:focus {
            border-color: #060eae; /* Azul 1 */
            outline: none;
            box-shadow: 0 0 5px #060eae;
        }

        .form-container button {
            background-color: #060eae; /* Azul 1 */
            color: #ffffff;
            font-weight: bold;
            border: none;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #4046d3; /* Un tono más claro del azul */
        }
    </style>
</head>

<body>
<?php $this->load->view('navbar'); ?> <!-- Incluir el navbar -->

<div class="form-container">
    <h1>Registrar Licencia</h1>
    <form action="<?= site_url('usuarios/guardar') ?>" method="post">
        <!-- Lista desplegable para seleccionar Trabajador Social -->
        <div class="form-group">
            <label for="trabajador_id">Seleccione Trabajador Social:</label>
            <select name="trabajador_id" id="trabajador_id" class="form-control" required>
                <option value="">Seleccione...</option>
                <?php if (!empty($trabajadores)): ?>
                    <?php foreach ($trabajadores as $trabajador): ?>
                        <option value="<?= $trabajador['RUN'] ?>">
                            <?= $trabajador['Nombre'] . ' ' . $trabajador['Apellido'] ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No hay Trabajadores Sociales disponibles</option>
                <?php endif; ?>
            </select>
        </div>

        <!-- Campos para las fechas -->
        <div class="form-group">
            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="fecha_termino">Fecha de Término:</label>
            <input type="date" id="fecha_termino" name="fecha_termino" class="form-control" required>
        </div>

        <!-- Botón para enviar el formulario -->
        <button type="submit" class="btn btn-block">Guardar Licencia</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
</body>

</html>
