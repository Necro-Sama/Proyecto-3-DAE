<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/style.css"/>
    <link rel="stylesheet" href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
</head>
<body>
    <?php $this->load->view('navbar'); ?> <!-- Incluir el navbar -->
    <h1>Vista de Trabajadora Social</h1>
    <?php echo anchor("usuarios/logout", "Cerrar sesión") ?>
    <p>todo...</p>
</body>
</html>
