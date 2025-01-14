<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        <!-- Seccion logo -->
                        <div class="text-center d-flex flex-column">
                            <h4>Sistema de Agenda de Hora</h4>
                            <h3>Direccion De Asuntos Estudiantiles</h3>
                            
                            <span class="text-center fs-1">
                                <img class="logo" src="https://portal.uta.cl/assets/images/logo/logo-uta.svg">
                            </span>
                            <h5>Oficina de Asistentes Sociales</h5>
                            <h1> </h1>
                            <?= $this->session->form_error ?>
                            <?= $this->session->error ?>
                        </div>
                        <!-- Sección inicio de sesion -->
                        <div id="loginForm" class="form-container active">
                            <?= form_open("usuarios/auth"); ?>
                                <div class="form-data">
                                    <div class="forms-inputs mb-4">
                                        <span>Correo</span>
                                        <input class="form-control" type="email" name="correo" value="<?= set_value('correo') ?>">
                                    </div>
                                    <div class="forms-inputs mb-4">
                                        <span>Contraseña</span>
                                        <input class="form-control" type="password" name="contraseña"
                                            value="<?= set_value('contraseña') ?>">
                                    </div>
                                    <div class="inicio mb-3">
                                        <?= form_submit("Ingresar", "Iniciar sesión", 'class="btn btn-login w-100"'); ?>
                                    </div>
                            <!-- Botón para cambiar entre formularios -->
                            <button type = "button"class="btn btn-switch btn-link w-100" onclick="toggleForms()">¿Nuevo usuario? Regístrate aquí</button>               
                            <?= form_close(); ?>
                        </div>
                        <div class="inicio"></div>
                        <div class="separator mx-2 text-secondary">o iniciar sesión con</div>
                        <div class="text-center">
                            <div class="g_id_signin" data-type="standard" data-shape="rectangular" data-theme="outline"
                                data-text="signin_with" data-size="large" data-locale="es-419" data-logo_alignment="left">
                            </div>
                        </div>
                    </div>
                            <!-- Mensajes de error o éxito -->
                        <?php if ($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= $this->session->flashdata('error'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->session->flashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= $this->session->flashdata('success'); ?>
                            </div>
                        <?php endif; ?>
                        <!-- Formulario para Registro -->
                        <div id="registerForm" class="form-container">
                            <?= form_open("usuarios/registrar"); ?>
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" name="nombre" placeholder="Ingrese su nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="apellidos">Apellidos</label>
                                <input type="text" class="form-control" name="apellidos" placeholder="Ingrese sus apellidos" required>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="tel" class="form-control" name="telefono" placeholder="Ingrese su teléfono" required>
                            </div>
                            <div class="form-group">
                                <label for="run">RUN</label>
                                <input type="text" class="form-control" name="run" placeholder="Ingrese su RUN" required>
                            </div>
                            <div class="form-group">
                                <label for="correo">Correo</label>
                                <input type="email" class="form-control" name="correo" placeholder="Ingrese su correo" required>
                            </div>
                            <div class="form-group">
                                <label for="contraseña">Contraseña</label>
                                <input type="password" class="form-control" name="contraseña" placeholder="Cree una contraseña" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" site_url>Registrar</button>
                            <!-- Botón para volver al inicio de sesión -->
                            <button type="button" class="btn btn-switch btn-link w-100" onclick="toggleForms()">¿Ya tienes una cuenta? Inicia sesión</button>
                            <?= form_close(); ?>
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
    min-height: 100vh;  /* Asegura que cubra toda la altura de la ventana */
    margin: 0;
    color: #333;
    font-family: Arial, sans-serif;
    }

    h3 {
        font-size: 1.2rem;
    }
    .forms-inputs span {
    display: block; /* Asegura que el texto esté en una línea independiente */
    margin-bottom: 5px; /* Ajusta el espacio entre el texto y el campo de entrada */
    font-size: 0.9rem; /* Opcional: ajusta el tamaño del texto */
    color: #333; /* Opcional: color del texto */
    }

    .container {
        max-width: 100%; /* Permite que la tarjeta ocupe el ancho máximo disponible */
        padding: 15px; /* Asegura que haya espacio en los bordes */
    }

    .card {
        max-width: 400px; /* Ancho máximo de la tarjeta */
        margin: auto; /* Centra la tarjeta horizontalmente */
        padding: 20px;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-login {
        background-color: #060EAE;
        border-color: #060EAE;
        color: white;
        width: 100%; /* Asegura que los botones se adapten al ancho de la tarjeta */
    }

    .btn-login:hover {
        background-color: #003D8E;
        border-color: #003D8E;
    }

    .form-container {
        display: none;
    }

    .form-container.active {
        display: block;
    }

    .logo {
        max-width: 100%; /* Asegura que el logo no exceda los límites de la tarjeta */
        height: auto; /* Mantiene la proporción del logo */
    }

    .separator {
        text-align: center;
        margin: 10px 0;
        font-size: 0.9rem;
        color: #666;
    }

    /* Medias Queries */
    @media (max-width: 768px) {
        .card {
            width: 90%; /* Hace que la tarjeta ocupe casi todo el ancho en pantallas pequeñas */
        }

        h3 {
            font-size: 1rem; /* Reduce el tamaño de los textos en pantallas pequeñas */
        }
    }

    @media (max-width: 480px) {
        h3 {
            font-size: 0.9rem;
        }

        .btn-login {
            font-size: 0.9rem; /* Ajusta el tamaño del texto del botón */
        }
    }


</style> 
<script>    
    window.addEventListener('resize', function() {
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;

        console.log(`Ancho: ${viewportWidth}, Alto: ${viewportHeight}`);
    });
    function toggleForms() {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const switchButton = document.querySelector('.btn-switch');
            
            loginForm.classList.toggle('active');
            registerForm.classList.toggle('active');
            
            if (loginForm.classList.contains('active')) {
                switchButton.textContent = '¿Nuevo usuario? Regístrate aquí';
            } else {
                switchButton.textContent = '¿Ya tienes una cuenta? Inicia sesión';
            }
        }
</script>