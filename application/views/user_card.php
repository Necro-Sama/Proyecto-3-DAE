<?php if (isset($persona)) { ?>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">
                <?php echo $persona['Nombre'] . ' ' . $persona['Apellido']; ?>
            </h5>
            <p class="card-text">
                <strong>RUN:</strong> <?php echo $persona['RUN']; ?><br>
                <strong>Teléfono:</strong> <?php echo $persona['Telefono']; ?><br>
                <strong>Correo:</strong> <?php echo $persona['Correo']; ?>
            </p>
        </div>
    </div>
<?php } else { ?>
    <p>No hay datos disponibles para mostrar.</p>
<?php } ?>
