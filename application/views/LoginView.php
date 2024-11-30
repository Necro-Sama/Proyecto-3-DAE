<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://accounts.google.com/gsi/client" async></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url(array('css', 'style.css')) ?>" />
</head>

<body>
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
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <div class="card px-5 py-5">
                    <div class="text-center d-flex flex-column">
                        <h4>Sistema de Agenda de Hora</h4>
                        <h3>Direccion De Asuntos Estudiantiles</h3>
                        
                        <span class="text-center fs-1">
                            <img class="logo" src="https://portal.uta.cl/assets/images/logo/logo-uta.svg">
                        </span>
                        <?= $this->session->form_error ?>
                        <?= $this->session->error ?>
                    </div>
                    <?= form_open("usuarios/auth"); ?>
                    <div class="form-data">
                        <div class="forms-inputs mb-4">
                            <span>Correo</span>
                            <input class="form-control" type="email" name="correo" value="<?= set_value('correo') ?>">
                            <!-- <div class="invalid-feedback">A valid email is required!</div> -->
                        </div>
                        <div class="forms-inputs mb-4">
                            <span>Contraseña</span>
                            <input class="form-control" type="password" name="contraseña"
                                value="<?= set_value('contraseña') ?>">
                            <!-- <div class="invalid-feedback">Password must be 8 character!</div> -->
                        </div>
                        <div class="inicio mb-3">
                            <?= form_submit("Ingresar", "Iniciar sesión", 'class="btn btn-login w-100"'); ?>
                            <!-- <button class="btn btn-login w-100">
                                <span class="font-bold">Iniciar sesión</span>
                            </button> -->
                        </div>
                    </div>
                    <?= form_close(); ?>
                    <!-- <div class="success-data" v-else>
                        <div class="text-center d-flex flex-column">
                            <span class="text-center fs-1">You have been logged in <br> Successfully</span>
                        </div>
                    </div> -->
                    <div class="inicio"></div>
                    <div class="separator mx-2 text-secondary">o iniciar sesión con</div>
                    <!-- <a href="<?php //echo base_url('home'); ?> " class="btn btn-insti w-100">Gmail institucional</a> -->
                    <!-- <button class="btn btn-insti w-100">Gmail institucional</button> -->

                    <div class="text-center">
                        <div class="g_id_signin" data-type="standard" data-shape="rectangular" data-theme="outline"
                            data-text="signin_with" data-size="large" data-locale="es-419" data-logo_alignment="left">
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>
<style>
    body {
    background: linear-gradient(to bottom, #FDD188 0%, #FDDEAA 25%, #FBF1D0 75%, #060EAE 100%);
    height: 100vh;  /* Asegura que el fondo cubra toda la altura de la pantalla */
    margin: 0;  /* Elimina los márgenes predeterminados */
    color: #333;  /* Color de texto oscuro para contraste */
    }
    h3 {
    font-size: 1.2rem;  /* Aumenta el espacio debajo del título */
    }

    .form-data .forms-inputs:first-child {
        margin-top: 20px; /* Aumenta el espacio entre el título y el primer campo de entrada */
    }
    .card {
        background-color: white; /* Fondo del recuadro (card) */
        border-radius: 15px; /* Bordes redondeados */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra ligera para la card */
    }
    .btn-login {
        background-color: #060EAE;  /* Azul de la paleta */
        border-color: #060EAE;  /* Asegura que los bordes también sean del mismo color */
        color: white;
    }

    .btn-login:hover {
        background-color: #003D8E;  /* Un tono más oscuro del azul para el hover */
        border-color: #003D8E;
        color: white;
    }


</style> 