<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/style.css"/>
</head>
<body>
    <h1>Inicio de Sesión de Sistema de Atención DAE</h1>
    <?php echo $this->session->form_error; ?>
    <div class="error"><?php echo $this->session->error; ?></div>
    <?php echo form_open("usuarios/auth"); ?>
    <h2>Correo</h2>
    <input type="email" name="correo" value="<?php echo set_value('correo') ?>">
    <h2>Contraseña</h2>
    <input type="password" name="contraseña" value="<?php echo set_value('contraseña') ?>">
    <h2>Tipo de Usuario</h2>
    <?php
        echo form_radio('user_type', 'student', TRUE);
        echo form_label('Estudiante', 'student');
        echo form_radio('user_type', 'not_student');
        echo form_label('No Estudiante', 'not_student');
        echo form_radio('user_type', 'admin'); 
        echo form_label('Administrador', 'admin');
        echo form_radio('user_type', 'ts'); 
        echo form_label('Trabajadora Social', 'ts');
        echo "<br>";
        echo form_submit("Ingresar", "Ingresar");
        echo form_close();
    ?>
</body>
</html>
