<?php if (isset($persona)) { ?>
    <div class="card" style="background-color: #fbf1d0; border: 1px solid #fdd188; margin: 10px; padding: 10px;">
        <div class="card-body">
            <h5 class="card-title" style="background-color: #060eae; color: white; padding: 10px; text-align: center;">
                <?php echo $persona['Nombre'] . ' ' . $persona['Apellido']; ?>
            </h5>
            <div class="card-text" style="padding: 10px; background-color: #fddeaa;">
                <p><strong>RUN:</strong> <?php echo $persona['rut']; ?></p>
                <p><strong>Teléfono:</strong> <?php echo $persona['Telefono']; ?></p>
                <p><strong>Correo:</strong> <?php echo $persona['Correo']; ?></p>

                <?php if ($tipo === 'Estudiante') { ?>
                    <p><strong>Carrera:</strong> <?php echo $detalle['OM_CARRERA']; ?></p>
                <?php } elseif ($tipo === 'Funcionario') { ?>
                    <p><strong>Cargo:</strong> <?php echo $detalle['cargo']; ?></p>
                <?php } elseif ($tipo === 'Administrador') { ?>
                    <p><strong>Cargo:</strong> <?php echo $detalle['cargo']; ?></p>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } else { ?>
    <p style="color: #060eae; text-align: center; font-weight: bold;">No hay datos disponibles para mostrar.</p>
<?php } ?>
