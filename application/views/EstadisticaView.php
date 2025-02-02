<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
    <?php $this->load->view('navbar', $tipo); ?>
    <link rel="stylesheet" href="<?php echo base_url('public/bootstrap/css/bootstrap.min.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        body { background-color: #fbf1d0; }
        .card {
            background-color: #fddeaa;
            border: 2px solid #fdd188;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #060eae;
            color: white;
            border-radius: 13px 13px 0 0 !important;
        }
    </style>
</head>
<body>

        <div class="container mt-4">
            <!-- Título y botón de exportar en la misma fila -->
            <div class="row mb-4 align-items-center">
                <div class="col-6">
                    <h2 class="mb-0">Estadísticas Generales</h2>
                </div>
                <div class="col-6 text-end">
                    <a href="<?= site_url('usuarios/estadisticas/exportarPDF') ?>" 
                       class="btn btn-primary">
                        <i class="fas fa-file-pdf me-2"></i>
                        Exportar a PDF
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Estadísticas por Carrera</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="graficoCarrera"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Estadísticas por Motivo</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="graficoMotivo"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Estadísticas por Estado</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="graficoEstado"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let graficos = {};

        function actualizarGraficos() {
            const url = '<?= site_url('usuarios/estadisticas/obtenerDatos') ?>';
            console.log('Intentando obtener datos de:', url);

            fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Estado de la respuesta:', response.status);
                return response.text().then(text => {
                    console.log('Respuesta cruda:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Error al parsear JSON:', e);
                        throw new Error('Error al parsear la respuesta');
                    }
                });
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                if (data.data) {
                    if (data.data.por_carrera) crearGrafico(data.data.por_carrera, 'carrera');
                    if (data.data.por_motivo) crearGrafico(data.data.por_motivo, 'motivo');
                    if (data.data.por_estado) crearGrafico(data.data.por_estado, 'estado');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar los datos: ' + error.message);
            });
        }

        function crearGrafico(datos, tipo) {
            if (graficos[tipo]) {
                graficos[tipo].destroy();
            }

            const ctx = document.getElementById('grafico' + tipo.charAt(0).toUpperCase() + tipo.slice(1)).getContext('2d');
            graficos[tipo] = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: datos.map(item => item.nombre),
                    datasets: [{
                        data: datos.map(item => parseInt(item.total)),
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Cargar datos al iniciar la página
        window.onload = actualizarGraficos;
    </script>
</body>
</html>