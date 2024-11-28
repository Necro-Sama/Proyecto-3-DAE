<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
    <title>Gestor de Trabajadores Sociales</title>
    <style>
        /* Fondo general */
        body {
            background-color: white; /* Naranja 3 */
        }
        /* Diseño de la tarjeta */
        .card {
            background-color: #fddeaa; /* Naranja 2 */
            border: 2px solid #fdd188; /* Naranja 1 */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            color: #060eae; /* Azul 1 */
        }
        /* Encabezado de tabla */
        .table-dark {
            background-color: #060eae; /* Azul 1 */
            color: white;
        }
        /* Filas de la tabla */
        .table tbody tr {
            background-color: #fddeaa; /* Naranja 2 */
        }
        /* Fila al pasar el ratón */
        .table-hover tbody tr:hover {
            background-color: #fbf1d0; /* Naranja 3 */
        }
        /* Botones */
        .btn-primary {
            background-color: #060eae; /* Azul 1 */
            border-color: #060eae;
        }
        .btn-primary:hover {
            background-color: #fdd188; /* Naranja 1 */
            border-color: #fdd188;
            color: #060eae;
        }
        .btn-warning {
            background-color: #fdd188; /* Naranja 1 */
            border-color: #fdd188;
            color: #060eae; /* Azul 1 */
        }
        .btn-warning:hover {
            background-color: #fddeaa; /* Naranja 2 */
            border-color: #fddeaa;
        }
        .btn-danger {
            background-color: #fdd188; /* Naranja 1 */
            border-color: #fdd188;
            color: #060eae; /* Azul 1 */
        }
        .btn-danger:hover {
            background-color: #fddeaa; /* Naranja 2 */
            border-color: #fddeaa;
        }
        /* Modal */
        .modal-header {
            background-color: #060eae; /* Azul 1 */
            color: #060eae;
        }
        .modal-content {
            background-color: #fddeaa; /* Naranja 2 */
        }
        .btn-close {
            color: white;
        }
    </style>
</head>
<body>
    <?php $this->load->view('navbar'); ?>
    <div class="container mt-5">
        <!-- Botón para añadir trabajador social -->
        <div class="text-center mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#trabajadorModal" onclick="nuevoTrabajador()">Añadir Trabajador Social</button>
        </div>

        <!-- Tabla de trabajadores sociales -->
        <div class="card">
            <div class="card-body">
                <h3 class="card-title text-center">Lista de Trabajadores Sociales</h3>
                <table class="table table-striped table-hover" >
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
                            <td><?php echo $trabajador['Nombre']; ?></td>
                            <td><?php echo $trabajador['Apellido']; ?></td>
                            <td>
                                <!-- Botón para editar -->
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#trabajadorModal"
                                    onclick="editarTrabajador('<?=$trabajador['RUN']?>', '<?=$trabajador['Nombre']?>', '<?=$trabajador['Apellido']?>', '<?=$trabajador['Correo']?>', '<?=$trabajador['Telefono']?>')">
                                    Editar
                                </button>
                                <!-- Botón para eliminar -->
                                <a href="<?php echo site_url('TrabajadorSocialController/eliminar/' . $trabajador['RUN']); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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
                        <!-- Campos del formulario -->
                        <div class="mb-3">
                            <label for="Nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="Nombre" id="Nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="Apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="Apellido" id="Apellido" required>
                        </div>
                        <div class="mb-3">
                            <label for="Correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" name="Correo" id="Correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="Telefono" class="form-label">Teléfono</label>
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
    </div>

    <script>
        function nuevoTrabajador() {
            document.getElementById('trabajadorForm').action = '<?php echo site_url('TrabajadorSocialController/agregar'); ?>';
        }

        function editarTrabajador(run, nombre, apellido, correo, telefono) {
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
