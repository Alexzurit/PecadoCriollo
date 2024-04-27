<?php require_once 'vistas/parte-superior.php'; ?>

<main class="content px-3 py-2">
    <div class="container-fluid">
        <div class="mb-3 text-center">
            <h4>Admin Dasboard</h4>
        </div>
        <!-- fila para datos de ventas -->
        <div class="row justify-content-center">
            <!-- Ventas (Mes) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-primary py-2 border-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-primary fw-bold text-uppercase mb-1">
                                    Ventas (Mes)</div>
                                <div class="h5 mb-0" id="total-ventas-mes">
                                    AZJ
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Ventas (Día) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-success py-2 border-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-success fw-bold text-uppercase mb-1">
                                    Ventas (Día)</div>
                                <div class="h5 mb-0" id="total-ventas-dia">
                                    AZJ
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-3x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Ventas (EJEMPLO) Card Example-->
            
        </div>
        <!-- FIN -->
        <div class="row">
            <div class="col-12 col-md-6 d-flex">
                <div class="card flex-fill border-black border-2 illustration">
                    <div class="card-header bg-secondary">
                        <p class="text-dark text-uppercase fw-bold text-center mb-0">
                            Cantidad Vendidas en la semana
                        </p>
                        <p class="card-subtitle text-muted text-center">
                            (los ultimos 7 días)
                        </p>
                    </div>
                    <div class="card-body p-0 d-flex flex-fill">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 d-flex">
                <div class="card flex-fill border-black border-2">
                    <div class="card-header bg-secondary">
                        <p class="text-dark text-uppercase fw-bold text-center mb-0">
                            Monto Recaudado de cada día
                        </p>
                        <p class="card-subtitle text-muted text-center">
                            (los ultimos 7 días)
                        </p>
                    </div>
                    <div class="card-body py-4">
                        <canvas id="myChart2"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!--Table Elements-->
        <div class="card border-secondary border-2">
            <div class="card-header">
                <h5 class="text-center">
                    Balance recaudado de todos los meses
                </h5>
                <h6 class="card-subtitle text-muted">
                    
                </h6>
            </div>
            <div class="card-body">
                <!-- espacio para mas dash -->
                <canvas id="myChart3" width="800" height="400"></canvas>
            </div>
        </div>
    </div>
</main>


<?php require_once 'vistas/parte-inferior.php'; ?>
<script>
    // Cargar datos del servidor
    fetch('controlador/dashboard/scaledouble.php')
    .then(response => response.json())
    .then(data => {
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(item => item.dia_semana),
                datasets: [{
                    label: 'Cartas',
                    data: data.map(item => item.cartas),
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Menu',
                    data: data.map(item => item.menu),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
<script>
    // Cargar datos del servidor
    fetch('controlador/dashboard/montolinear.php') // Cambia esto por la ruta correcta de tu script PHP que obtiene los datos
    .then(response => response.json())
    .then(data => {
        var ctx = document.getElementById('myChart2').getContext('2d');
        var myChart2 = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => item.dia_semana),
                datasets: [{
                    label: 'Monto Recaudado',
                    data: data.map(item => item.total_venta_recaudado),
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
<script>
    // Cargar datos del servidor
    fetch('controlador/dashboard/montsales.php') // Cambia esto por la ruta correcta de tu script PHP que obtiene los datos
    .then(response => response.json())
    .then(data => {
        var ctx = document.getElementById('myChart3').getContext('2d');
        var myChart3 = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => item.nombre_mes),
                datasets: [{
                    label: 'Monto Recaudado',
                    data: data.map(item => item.total_venta_recaudado),
                    fill: false,
                    borderColor: 'rgba(0, 190, 22, 1)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
<script>
    const totalVentasDia = document.getElementById('total-ventas-dia');
    const totalVentasMes = document.getElementById('total-ventas-mes');
    // Hacer la solicitud AJAX para obtener los datos de ventas
    fetch('controlador/dashboard/montodm.php')
    .then(response => response.json())
    .then(data => {
        //console.log(data);
        const totalDiaActual = data[0].total_dia_actual;
        totalVentasDia.innerHTML = "S/"+totalDiaActual;
        const totalMesActual = data[0].total_mes_actual;
        totalVentasMes.innerHTML = "S/"+totalMesActual;
    })
    .catch(error => console.error('Error al obtener los datos de ventas:', error));
</script>
