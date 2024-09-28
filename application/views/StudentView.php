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
    <h1>Vista de Estudiante</h1>
    <h3>Bloques disponibles</h3>
    <p>Carrera: <?php echo $carrera; ?></p>
    <p>Disponibilidad para esta semana:</p>
    <?php
        function crear_horarios_disponibles($bloques_no_disponibles, $semana) {
            echo date("Y-m-d", strtotime("last monday + ".$semana." weeks"));
            $dias = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes");
            echo "<table>";
            echo "<tr>";
            echo "<th class='celda-header'>Bloque</th>";
            foreach (range(0, 4) as $d) {
                echo "<th class='celda-header'>".$dias[$d]."</th>";
            }
            echo "</tr>";
            foreach (range(1, 20) as $bh) {
                $hora_bloque = strtotime("8:00 am +".(($bh-1)*30)." minutes");
                echo "<th class='celda-header'>".date("H:i", $hora_bloque)."</th>";
                foreach (range(0, 4) as $d) {
                    $dia_check = date("Y-m-d", strtotime("last monday + ".$semana." weeks +".$d." days"));
                    $estado = "Disponible";
                    $class = "bloque-libre";
                    foreach ($bloques_no_disponibles->result() as $bnd) {
                        if (strtotime("now") > (strtotime("last monday + ".$semana." weeks +".$d." days +8 hours +".(($bh-2)*30)." minutes"))) {
                            $estado = "No disponible";
                            $class = "bloque-reservado";
                            break;
                        }
                        if ($bnd->fecha == $dia_check && $bnd->num_bloque == $bh) {
                            $estado = "Ocupado";
                            $class = "bloque-reservado";
                            break;
                        }
                    }
                    echo "<td class='bloque ".$class."'>";
                    echo $estado;
                    echo "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
        crear_horarios_disponibles($bloques_no_disponibles, 0)
    ?>
    
    <p>Disponibilidad para la próxima semana:</p>
    <?php
        crear_horarios_disponibles($bloques_no_disponibles, 1)
    ?>
</body>
</html>
