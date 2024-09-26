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
    <p>todo...</p>

    <?php
        foreach (range(1, 12) as $i) {
            echo "<div>";
            echo "<div>Número de Bloque: ".$i."</div>";
            echo "</div>";
        }
    ?>
    
</body>
</html>
