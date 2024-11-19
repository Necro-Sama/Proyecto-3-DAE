<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
    <title>Licencia</title>
    <script src="https://accounts.google.com/gsi/client" async></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?= base_url(array('css', 'style.css')) ?>" />
    <link rel="stylesheet" href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
</head>

<body>
<?php $this->load->view('navbar'); ?> <!-- Incluir el navbar -->
    <?php
    // $a = array('cvergarab0', 'jvarayav1', 'kcorrales2', 'mbernales3', 'ncornejoa4', 'palday5', 'petorob6', 'ebrizuela7', 'vvillalobos8', 'yarayar9');
    // foreach ($a as $v) {
    //     echo password_hash($v, PASSWORD_DEFAULT).'<br>';
    // }
    // echo password_hash("mauri", PASSWORD_DEFAULT);
    ?>
    <div id="g_id_onload" data-client_id="937712052910-utrla4pp1g3pnhcpfn00gi5j01eio5fj.apps.googleusercontent.com"
        data-context="use" data-ux_mode="popup" data-login_uri="<?= base_url(array('index.php', 'usuarios', 'auth')) ?>"
        data-auto_prompt="false">
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
        <div class="container mt-5">
        <h1>Registrar Licencia</h1>

    <form action="<?= base_url('Licencia/guardar') ?>" method="post">
        <!-- Lista desplegable para seleccionar Trabajador Social -->
        <label for="trabajador_id">Seleccione Trabajador Social:</label>
        <select name="trabajador_id" id="trabajador_id" required>
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

        <!-- Campos para las fechas -->
        <div>
            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required>
        </div>

        <div>
            <label for="fecha_termino">Fecha de Término:</label>
            <input type="date" id="fecha_termino" name="fecha_termino" required>
        </div>

        <!-- Botón para enviar el formulario -->
        <button type="submit">Guardar Licencia</button>
    </form>

</body>