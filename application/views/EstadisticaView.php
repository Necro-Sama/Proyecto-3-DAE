<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Estadísticas</title>
        <?php  
        $this->load->view('navbar', $tipo); ?>
        <!-- Cargar Bootstrap desde CDN -->
        <link rel="stylesheet" href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
        <div class="container mt-4">
            <div class="card rounded-4 shadow">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Estadísticas de Citas</h2>
                    
                    <!-- Selector de fechas -->
                    <div class="row mb-4">
                        <div class="col-md-6 offset-md-3">
                            <div class="input-group">
                                <input type="date" class="form-control" id="fecha_inicio">
                                <input type="date" class="form-control" id="fecha_fin">
                                <button class="btn btn-primary" onclick="actualizarEstadisticas()">Filtrar</button>
                            </div>
                        </div>
                    </div>

                    <!-- Pestañas -->
                    <ul class="nav nav-tabs" id="statsTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#porCarrera">Por Carrera</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#canceladas">Cancelaciones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#temas">Temas Recurrentes</a>
                        </li>
                    </ul>

                    <!-- Contenido de las pestañas -->
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="porCarrera">
                            <canvas id="carrerasChart"></canvas>
                        </div>
                        <div class="tab-pane fade" id="canceladas">
                            <canvas id="canceladasChart"></canvas>
                        </div>
                        <div class="tab-pane fade" id="temas">
                            <canvas id="temasChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Inicializar fechas
            document.addEventListener('DOMContentLoaded', function() {
                const hoy = new Date();
                const inicioAño = new Date(hoy.getFullYear(), 0, 1);
                document.getElementById('fecha_inicio').value = inicioAño.toISOString().split('T')[0];
                document.getElementById('fecha_fin').value = hoy.toISOString().split('T')[0];
                actualizarEstadisticas();
            });

            function actualizarEstadisticas() {
                const fecha_inicio = document.getElementById('fecha_inicio').value;
                const fecha_fin = document.getElementById('fecha_fin').value;

                fetch('<?php echo site_url('usuarios/estadisticas/obtener_datos'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `fecha_inicio=${fecha_inicio}&fecha_fin=${fecha_fin}`
                })
                .then(response => response.json())
                .then(data => {
                    actualizarGraficos(data);
                });
            }

            function actualizarGraficos(data) {
                // Actualizar gráfico de carreras
                new Chart(document.getElementById('carrerasChart'), {
                    type: 'bar',
                    data: {
                        labels: data.por_carrera.map(item => item.nombre_carrera),
                        datasets: [{
                            label: 'Citas por Carrera',
                            data: data.por_carrera.map(item => item.total)
                        }]
                    }
                });

                // Actualizar otros gráficos similares...
            }
        </script>
    </body>
</html>