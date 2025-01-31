<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Estadísticas</title>
        <?php  $this->load->view('navbar', $tipo); ?>
        <link rel="stylesheet" href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <style>
            /* Estilos generales */
            body {
                background-color: #fbf1d0;
            }

            /* Contenedor principal más compacto */
            .container {
                max-width: 900px;
                padding: 20px;
            }

            /* Estilos de la tarjeta */
            .card {
                background-color: #fddeaa;
                border: none;
                border-radius: 15px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .card-body {
                padding: 1.5rem;
            }

            /* Título más compacto */
            .card-title {
                font-size: 1.5rem;
                margin-bottom: 1rem;
                color: #060eae;
            }

            /* Selector de fechas más compacto */
            .input-group {
                max-width: 400px;
                margin: 0 auto;
            }

            .input-group input {
                border: 1px solid #ddd;
                font-size: 0.9rem;
            }

            .btn-primary {
                background-color: #060eae;
                border: none;
            }

            .btn-primary:hover {
                background-color: #0509a8;
            }

            /* Pestañas más compactas */
            .nav-tabs {
                border-bottom: 2px solid #060eae;
                margin-top: 1rem;
            }

            .nav-tabs .nav-link {
                color: #060eae;
                border: none;
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .nav-tabs .nav-link.active {
                color: #fff;
                background-color: #060eae;
                border-radius: 5px 5px 0 0;
            }

            .nav-tabs .nav-link:hover:not(.active) {
                background-color: #f0f0f0;
                border-radius: 5px 5px 0 0;
            }

            /* Contenedor de gráficos más compacto */
            .tab-content {
                padding: 1rem 0;
            }

            .tab-pane {
                height: 400px; /* Altura fija para todos los gráficos */
                position: relative;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .container {
                    padding: 10px;
                }

                .card-body {
                    padding: 1rem;
                }

                .tab-pane {
                    height: 300px;
                }

                .nav-tabs .nav-link {
                    padding: 0.4rem 0.8rem;
                    font-size: 0.8rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="container mt-4">
            <div class="card rounded-4 shadow">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Estadísticas de Citas</h2>
                    
                    <!-- Seccion de Filtro por fecha-->
                    <div class="row mb-4">
                        <div class="col-md-6 offset-md-3">
                            <div class="input-group">
                                <input type="date" class="form-control" id="fecha_inicio">
                                <input type="date" class="form-control" id="fecha_fin">
                                <button class="btn btn-primary" onclick="actualizarEstadisticas()">Filtrar</button>
                            </div>
                        </div>
                    </div>

                    <!-- Seccion para los diferentes gráficos -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#porCarrera">Por Carrera</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#porMotivo">Por Motivo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#porEstado">Por Estado</a>
                        </li>
                    </ul>

                    <!-- Contenido de las pestañas -->
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="porCarrera">
                            <canvas id="carreraChart"></canvas>
                        </div>
                        <div class="tab-pane fade" id="porMotivo">
                            <canvas id="motivoChart"></canvas>
                        </div>
                        <div class="tab-pane fade" id="porEstado">
                            <canvas id="estadoChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Variables globales para los gráficos
            let carreraChart = null;
            let motivoChart = null;
            let estadoChart = null;

            function actualizarEstadisticas() {
                const fecha_inicio = document.getElementById('fecha_inicio').value;
                const fecha_fin = document.getElementById('fecha_fin').value;

                if (!fecha_inicio || !fecha_fin) {
                    alert('Por favor seleccione ambas fechas');
                    return;
                }

                fetch('<?php echo site_url('usuarios/estadisticas/obtener_datos'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `fecha_inicio=${fecha_inicio}&fecha_fin=${fecha_fin}`
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Datos recibidos:', data);
                    actualizarGraficos(data);
                })
                .catch(error => console.error('Error:', error));
            }

            // Configuración común para todos los gráficos
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 10,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            };

            function actualizarGraficos(data) {
                // Destruir gráficos existentes si existen
                if (carreraChart instanceof Chart) {
                    carreraChart.destroy();
                }
                if (motivoChart instanceof Chart) {
                    motivoChart.destroy();
                }
                if (estadoChart instanceof Chart) {
                    estadoChart.destroy();
                }

                // Gráfico por carrera
                const ctxCarrera = document.getElementById('carreraChart').getContext('2d');
                carreraChart = new Chart(ctxCarrera, {
                    type: 'bar',
                    data: {
                        labels: data.por_carrera.map(item => item.nombre_carrera || 'Sin Carrera'),
                        datasets: [{
                            label: 'Citas por Carrera',
                            data: data.por_carrera.map(item => item.total),
                            backgroundColor: 'rgba(54, 162, 235, 0.5)'
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });

                // Gráfico por motivo
                const ctxMotivo = document.getElementById('motivoChart').getContext('2d');
                motivoChart = new Chart(ctxMotivo, {
                    type: 'pie',
                    data: {
                        labels: data.por_motivo.map(item => item.Motivo),
                        datasets: [{
                            data: data.por_motivo.map(item => item.total),
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.5)',
                                'rgba(54, 162, 235, 0.5)',
                                'rgba(255, 206, 86, 0.5)',
                                'rgba(75, 192, 192, 0.5)',
                                'rgba(153, 102, 255, 0.5)'
                            ]
                        }]
                    },
                    options: commonOptions
                });

                // Gráfico por estado
                const ctxEstado = document.getElementById('estadoChart').getContext('2d');
                estadoChart = new Chart(ctxEstado, {
                    type: 'doughnut',
                    data: {
                        labels: data.estados.map(item => item.Estado),
                        datasets: [{
                            data: data.estados.map(item => item.total),
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.5)',
                                'rgba(255, 99, 132, 0.5)',
                                'rgba(255, 206, 86, 0.5)'
                            ]
                        }]
                    },
                    options: commonOptions
                });
            }

            // Cargar estadísticas iniciales
            document.addEventListener('DOMContentLoaded', function() {
                // Establecer fechas por defecto (último mes)
                const hoy = new Date();
                const haceMes = new Date();
                haceMes.setMonth(haceMes.getMonth() - 1);
                
                document.getElementById('fecha_inicio').value = haceMes.toISOString().split('T')[0];
                document.getElementById('fecha_fin').value = hoy.toISOString().split('T')[0];
                
                actualizarEstadisticas();
            });
        </script>

        <script src="<?php echo base_url('public/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    </body>
</html>