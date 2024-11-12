<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
    <title>Gestor de Trabajadores Sociales</title>
</head>
<body>
    <?php $this->load->view('navbar'); ?>
    <div class="container mt-4">
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#trabajadorModal" onclick="nuevoTrabajador()">Añadir Trabajador Social</button>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>RUN</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trabajadores as $trabajador): ?>
                    <tr>
                        <td><?php echo $trabajador['RUN']; ?></td>
                        <td><?php echo $trabajador['Nombre']; ?></td>
                        <td><?php echo $trabajador['Apellido']; ?></td>
                        <td><?php echo $trabajador['Correo']; ?></td>
                        <td><?php echo $trabajador['Telefono']; ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#trabajadorModal"
                                onclick="editarTrabajador('<?php echo $trabajador['RUN']; ?>', '<?php echo $trabajador['Nombre']; ?>', '<?php echo $trabajador['Apellido']; ?>', '<?php echo $trabajador['Correo']; ?>', '<?php echo $trabajador['Telefono']; ?>')">Editar</button>
                            <a href="<?php echo site_url('TrabajadorSocialController/eliminar/' . $trabajador['RUN']); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para añadir/editar -->
    <div class="modal fade" id="trabajadorModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <form id="trabajadorForm" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="trabajadorModalLabel">Añadir Trabajador Social</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Campo para RUN -->
                    <div class="mb-3">
                        <label for="RUN">RUN</label>
                        <input type="text" class="form-control" name="RUN" id="RUN" required>
                    </div>
                    <div class="mb-3">
                        <label for="Nombre">Nombre</label>
                        <input type="text" class="form-control" name="Nombre" id="Nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="Apellido">Apellido</label>
                        <input type="text" class="form-control" name="Apellido" id="Apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="Correo">Correo</label>
                        <input type="email" class="form-control" name="Correo" id="Correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="Telefono">Teléfono</label>
                        <input type="text" class="form-control" name="Telefono" id="Telefono" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>

            </div>
        </div>
    </div>

    <script>
        function nuevoTrabajador() {
            document.getElementById('trabajadorForm').action = '<?php echo site_url('TrabajadorSocialController/agregar'); ?>';
        }

        function editarTrabajador(run, nombre, apellido, correo, telefono) {
            document.getElementById('RUN').value = run;
            document.getElementById('Nombre').value = nombre;
            document.getElementById('Apellido').value = apellido;
            document.getElementById('Correo').value = correo;
            document.getElementById('Telefono').value = telefono;
            document.getElementById('trabajadorForm').action = '<?php echo site_url('TrabajadorSocialController/editar'); ?>/' + run;
        }
    </script>

    <script src="<?php echo base_url('public/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>
