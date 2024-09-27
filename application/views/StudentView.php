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
    <?php
        $dias = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes");
        echo "<table>";
        echo "<tr>";
        foreach (range(0, 4) as $d) {
            echo "<th>".$dias[$d]."</th>";
        }
        echo "</tr>";
        foreach (range(1, 12) as $bh) {
            echo "<tr>";
            foreach (range(0, 4) as $d) {
                echo "<td>";
                $dia_check = date("Y-m-d", strtotime("last monday +".$d." days"));
                $estado = "Disponible";
                foreach ($bloques_no_disponibles->result() as $br) {
                    if ($br->fecha == $dia_check && $br->num_bloque == $bh) {
                        $estado = "Ocupado";
                        break;
                    }
                }
                echo $estado;
                echo "</td>";
            }
            echo "</tr>";
        }

        // foreach (range(1, 12) as $bh) {

        //     // foreach(range(0, 4) as $d) {
        //     //     echo $dias[$d]."  ";
        //     // }
        //     // echo "<br>";
        //     // echo $bloques_reservados;
        //     // echo "<div class='bloque bloque_libre'>";
        //     // echo "<div>Número de Bloque: ".$i."</div>";
        //     // echo "</div>";
        // }
        echo "</table>";
    ?>
</body>
</html>
