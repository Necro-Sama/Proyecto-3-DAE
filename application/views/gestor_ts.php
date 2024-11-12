
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
    <title>Document</title>
</head>
<body>
    <?php $this->load->view('navbar'); ?> <!-- Incluir el navbar -->
    <!-- Botón para añadir un trabajador social -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#trabajadorModal" onclick="nuevoTrabajador()">
        Añadir Trabajador Social
    </button>

    <!-- Tabla de trabajadores sociales -->
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>RUN</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($trabajadores as $trabajador): ?>
            <tr>
                <td><?php echo $trabajador['RUN']; ?></td>
                <td><?php echo $trabajador['NOMBRE_PER']; ?></td>
                <td><?php echo $trabajador['APELLID_PER']; ?></td>
                <td>
                    <!-- Botón para editar -->
                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#trabajadorModal"
                        onclick="editarTrabajador('<?php echo $trabajador['RUN']; ?>', '<?php echo $trabajador['NOMBRE_PER']; ?>', '<?php echo $trabajador['APELLID_PER']; ?>')">
                        Editar
                    </button>
                    <!-- Botón para eliminar -->
                    <a href="<?php echo site_url('TrabajadorSocialController/eliminar/' . $trabajador['RUN']); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal para añadir/editar -->
    <div class="modal fade" id="trabajadorModal" tabindex="-1" aria-labelledby="trabajadorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <form id="trabajadorForm" method="post" action="<?php echo site_url('TrabajadorSocialController/editar/' . $trabajador['ID_TS']); ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="trabajadorModalLabel">Editar Trabajador Social</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="Nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="Nombre" id="Nombre" value="<?php echo $trabajador['NOMBRE_PER']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="Apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" name="Apellido" id="Apellido" value="<?php echo $trabajador['APELLID_PER']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="Correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" name="Correo" id="Correo" value="<?php echo $trabajador['CORREO']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="Telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="Telefono" id="Telefono" value="<?php echo $trabajador['TELEFONO']; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>

            </div>
        </div>
    </div>
</body>
</html>





<script src="<?php echo base_url('public/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

<script>
    function nuevoTrabajador() {
        document.getElementById('RUN').value = '';
        document.getElementById('Nombre').value = '';
        document.getElementById('Apellido').value = '';
        document.getElementById('trabajadorForm').action = '<?php echo site_url('TrabajadorSocialController/agregar'); ?>';
        document.getElementById('trabajadorModalLabel').innerText = 'Añadir Trabajador Social';
    }

    function editarTrabajador(run, nombre, apellido) {
        document.getElementById('RUN').value = run;
        document.getElementById('Nombre').value = nombre;
        document.getElementById('Apellido').value = apellido;
        document.getElementById('trabajadorForm').action = '<?php echo site_url('TrabajadorSocialController/editar'); ?>/' + run;
        document.getElementById('trabajadorModalLabel').innerText = 'Editar Trabajador Social';
    }
</script>

