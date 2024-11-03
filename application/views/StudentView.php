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
    <script type="text/javascript">
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
    </script>
    <h1>Vista de Estudiante</h1>
    <?php echo anchor("usuarios/logout", "Cerrar sesión") ?>
    <div class="error"><?php echo $this->session->flashdata("agendar_error") ?></div>
    <h3>Bloques disponibles</h3>
    <p>Carrera: <?php echo $carrera; ?></p>
    <?php
        function crear_horarios_disponibles($bloques_no_disponibles, $semana) {
            $motivos = array("Gratuidad Mineduc" => "Gratuidad Mineduc",
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
            $minutos_por_bloque = 30;
            $hora_inicio_jornada = 8;
            $fecha_base = strtotime("last monday") + strtotime($semana." weeks", 0) + strtotime($hora_inicio_jornada." hours", 0);
            echo "<p>Disponibilidad para la semana del ".date("d-m-Y", $fecha_base).":</p>";
            echo "Hora actual: ".date("H:i:s d-m-Y", strtotime("now"));
            echo "<table>";
            echo "<tr><th class='celda-header'>Bloque</th>";
            foreach (range(0, 4) as $dia) {
                echo "<th class='celda-header'>".$dias[$dia]."</th>";
            }
            echo "</tr>";
            foreach (range(0, 15) as $bloque_horario) {
                echo "<tr>";
                $fecha_base_bloque_offset = $fecha_base + strtotime($bloque_horario*$minutos_por_bloque." minutes", 0);
                echo "<th class='celda-header'>".date("H:i", $fecha_base_bloque_offset)."</th>";
                foreach (range(0, 4) as $dia) {
                    $fecha_bloque = $fecha_base_bloque_offset + strtotime($dia." days", 0);
                    $js = '
                        id="B'.$fecha_bloque.'"
                        onClick="toggle('.$fecha_bloque.')"
                    ';
                    $estado = form_button("mostrar", "Mostrar", $js)
                             .'<div id="'."D".$fecha_bloque.'" style="display: none">'
                             .form_open("usuarios/agendar")
                             .form_dropdown("motivo", $motivos)
                             .form_hidden(array("fecha" => $fecha_bloque,
                                                "num_bloque" => $bloque_horario))
                             .form_submit("Agendar", "Agendar")
                             .form_close()
                             ."</div>";
                    $class = "bloque-libre";
                    if ($fecha_bloque < strtotime("now")) {
                        $estado = "No disponible";
                        $class = "bloque-reservado";
                    } else {
                        foreach ($bloques_no_disponibles->result() as $bloque) {
                            if (date("Y-m-d", $fecha_bloque) == $bloque->fecha && $bloque_horario == $bloque->num_bloque) {
                                $estado = "Reservado";
                                $class = "bloque-reservado";
                            }
                        }
                    }
                    echo "<td class='bloque ".$class."'>".$estado."</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
        crear_horarios_disponibles($bloques_no_disponibles, 0);
        crear_horarios_disponibles($bloques_no_disponibles, 1);
        crear_horarios_disponibles($bloques_no_disponibles, 2);
    ?>
</body>
</html>
