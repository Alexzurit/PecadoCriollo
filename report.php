<style>
    /* Estilos generales para la carta */
    .card-body {
        font-family: Arial, sans-serif;
    }
    .row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
    }
    .border-bottom {
        border-bottom: 1px solid #ccc;
    }

    /* Estilos específicos para BALANCE TOTAL */
    .balanceTotalRow {
        background-color: #216E67; /* Fondo azul */
        color: white; /* Texto blanco */
        font-weight: bold; /* Texto en negrita */
        border-radius: 5px; /* Bordes redondeados */
        padding: 10px 0; /* Añade un poco más de espacio */
    }
</style>
<?php require_once './vistas/parte-superior.php'; ?>

<main class="content px-3 py-2">
    <div class="container-fluid">
        <div class="mb-3">
            <h3 class="text-center">Reporte</h3>
        </div>
        <!--Table Elements-->
        <div class="card border-0">
            <div class="card-header">
                <h4>Seleccione las fechas</h4>
                <form id="formFiltrarReporte">
                    <div class="row">
                        <div class="col">
                            <label for="fechaInicio">Fecha de inicio:</label>
                            <input type="date" class="form-control" id="fechaInicio" name="fechaInicio">
                        </div>
                        <div class="col">
                            <label for="fechaFin">Fecha de fin:</label>
                            <input type="date" class="form-control" id="fechaFin" name="fechaFin">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning mt-3"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="card-body">
                <!-- parte de reporte -->
                <div class="card border-dark mb-3 mx-auto w-75" id="cartaReporte">
                    <div class="card-header bg-dark">
                        <h2 class="text-light text-center">Pecado Criollo</h2>
                    </div>
                    <div class="card-body">
                        <div class="row border-bottom py-2">
                            <div class="col">Fecha Inicio/Fecha Fin</div>
                            <div class="col text-end" id="fechaReporte">DD/MM/YY DD/MM/YY</div>
                        </div>
                        <div class="row border-bottom py-2">
                            <div class="col">N° Ventas Realizadas</div>
                            <div class="col text-end" id="numVentasRealizadas">Valor</div>
                        </div>
                        <div class="row border-bottom py-2">
                            <div class="col">N° MENUS vendidos</div>
                            <div class="col text-end" id="numMenusVendidos">Valor</div>
                        </div>
                        <div class="row border-bottom py-2">
                            <div class="col">N° CARTAS vendidos</div>
                            <div class="col text-end" id="numCartasVendidos">Valor</div>
                        </div>
                        <div class="row border-bottom py-2">
                            <div class="col">N° BEBIDAS vendidas</div>
                            <div class="col text-end" id="numBebidasVendidos">Valor</div>
                        </div>
                        <div class="row border-bottom py-2">
                            <div class="col">Total APROBADAS</div>
                            <div class="col text-end" id="totalAprobadas">Valor</div>
                        </div>
                        <div class="row border-bottom py-2">
                            <div class="col">Total CANCELADAS</div>
                            <div class="col text-end" id="totalCanceladas">Valor</div>
                        </div>
                        <div class="row border-bottom py-2">
                            <div class="col">Monto APROBADO</div>
                            <div class="col text-end" id="montoAprobado">Valor</div>
                        </div>
                        <div class="row border-bottom py-2">
                            <div class="col">Monto CANCELADO</div>
                            <div class="col text-end" id="montoCancelado">Valor</div>
                        </div>
                        <div class="row py-2 balanceTotalRow">
                            <div class="col">BALANCE TOTAL</div>
                            <div class="col text-end" id="balanceTotal">Valor</div>
                        </div>
                    </div>
                </div>
                <!-- parte de reporte -->                
            </div>
        </div>
    </div>
</main>

<?php require_once './vistas/parte-inferior.php'; ?>
<script>
$(document).ready(function () {
    $('#formFiltrarReporte').submit(function (event) {
        event.preventDefault(); // Evitar el envío del formulario por defecto
        
        var fechaInicio = $('#fechaInicio').val();
        var fechaFin = $('#fechaFin').val();
        
        // Validar si la fecha de fin es menor que la fecha de inicio
        var fechaInicioObj = new Date(fechaInicio);
        var fechaFinObj = new Date(fechaFin);

        if (fechaFinObj < fechaInicioObj) {
            Swal.fire({
                title: "Cuidado!",
                text: "Fecha Fin es Menor a Fecha Inicio!",
                icon: "error"
              });
            return; // Detener la ejecución si las fechas son inválidas
        }
        // Realizar la solicitud AJAX para obtener los datos filtrados
        $.ajax({
            url: 'controlador/class/salesreportes.php', 
            type: 'POST',
            dataType: 'json',
            data: {
                fechaInicio: fechaInicio,
                fechaFin: fechaFin
            },
            success: function (data) {
                // Verificar si los datos devueltos están vacíos o indefinidos
                if ($.isEmptyObject(data)) {
                    Swal.fire({
                        title: "Sin datos",
                        text: "No se encontraron datos para las fechas especificadas",
                        icon: "warning"
                    });
                    return;
                }
                // Actualizar los valores de la tarjeta con los datos obtenidos
                $('#fechaReporte').text(fechaInicio + ' / ' + fechaFin);
                $('#numVentasRealizadas').text(data.num_ventas_realizadas);
                $('#numMenusVendidos').text(data.menu_vendidos);
                $('#numCartasVendidos').text(data.cartas_vendidas);
                $('#numBebidasVendidos').text(data.bebidas_vendidas);
                $('#totalAprobadas').text(data.num_ventas_aprobadas);
                $('#totalCanceladas').text(data.num_ventas_canceladas);
                $('#montoAprobado').text(data.total_ventas_aprobadas);
                $('#montoCancelado').text(data.total_ventas_canceladas);
                $('#balanceTotal').text('S/'+data.total_ventas_aprobadas);
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                Swal.fire('Error al filtrar el reporte por fechas');
            }
        });
    });
});
</script>


