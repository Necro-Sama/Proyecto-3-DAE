<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/style.css"/>
    <link rel="stylesheet" href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
</head>
<body>
<?php $this->load->view('navbar'); ?> <!-- Incluir el navbar -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>css/style.css"/>
    <link rel="stylesheet" href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
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
    <!-- <?php echo anchor("usuarios/logout", "Cerrar sesión") ?> -->
    <div class="error"><?php echo $this->session->flashdata("agendar_error") ?></div>
    <h3>Bloques disponibles</h3>
    <!-- <p>Carrera: <?php echo $carrera; ?></p> -->
    <p>Carrera: nose</p>

    <?php
        $motivos = array(
            "Gratuidad Mineduc" => "Gratuidad Mineduc",
            "Becas de arancel Mineduc" => "Becas de arancel Mineduc",
            "Fondo Solidario de Crédito Universitario" => "Fondo Solidario de Crédito Universitario",
            "Beneficios Junaeb (BAES y Becas de mantención)" => "Beneficios Junaeb (BAES y Becas de mantención)",
            "Beca Fotocopia UTA" => "Beca Fotocopia UTA",
            "Beca Alimentación UTA" => "Beca Alimentación UTA",
            "Beca Residencia UTA" => "Beca Residencia UTA",
            "Beca Internado UTA" => "Beca Internado UTA",
            "Beca Ayuda Estudiantil UTA" => "Beca Ayuda Estudiantil UTA",
            "Beca PSU-PDT-PAES UTA" => "Beca PSU-PDT-PAES UTA",
            "Otro" => "Otro");
        $dias = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes");
        
    ?>
    <div class="d-flex border border-primary">
        <div class="p-2 flex-fill display-4 border border-secondary text-center"></div>
        <?php foreach ($dias as $dia) {
            echo '<div class="p-2 flex-fill display-4 border border-secondary text-center dia">'.$dia.'</div>';
        } ?>
    </div>
</body>
</html>

