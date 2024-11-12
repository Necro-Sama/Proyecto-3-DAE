<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

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

