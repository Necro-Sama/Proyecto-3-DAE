<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body>

    <h1>Inicio de Sesión</h1>

    <?php if (isset($error)) { ?>
        <div style="color:red;"><?php echo $error; ?></div>
    <?php } ?>

    <?php echo form_open('login/authenticate'); ?>

    <label for="email">Email:</label>
    <input type="text" name="email" value="<?php echo set_value('email'); ?>">
    <?php echo form_error('email'); ?>

    <label for="password">Contraseña:</label>
    <input type="password" name="password">
    <?php echo form_error('password'); ?>

    <button type="submit">Iniciar Sesión</button>

    <?php echo form_close(); ?>

    <br>

    <?php echo form_open('login/no_student'); ?>
    <button type="submit">No Estudiante</button>
    <?php echo form_close(); ?>

</body>
</html>
