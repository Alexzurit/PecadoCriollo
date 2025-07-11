<style>
.modal-header{
        color:#fff;
        background: #634B7E;
        display: flex;
        justify-content: center;
    }
    .help-block {
        color: red;
    }
    .form-group.has-error .form-control-label {
        color: red;
    }
    .form-group.has-error .form-control {
        border: 1px solid red;
        box-shadow: 0 0 0 0.2rem rgba(250, 16, 0, 0.18);
    }
    .btn-fact{
        background: linear-gradient(180deg, #65bce8 0%, #306485 100%);
        transition: all .3s;
        color: white !important;
    }
    .btn-fact:hover{
        background: linear-gradient(180deg, #fbde74 0%, #ff9900 100%);
        transition: all .3s;
    }
    .btn-bol{
        background: linear-gradient(180deg, #65bce8 0%, #306485 100%);
        transition: all .3s;
        color: white !important;
    }
    .btn-bol:hover{
        background: linear-gradient(180deg, #fbde74 0%, #ff9900 100%);
        transition: all .3s;
    }
</style>
<?php require_once 'vistas/parte-superior.php'; ?>

<main class="content px-3 py-2">
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Detalle de Venta</h1>
          </div>
          <div class="modal-body">
              <div class="form-group" hidden><!--hidden es para ocultar, readonly es bloquear el campo-->
                    <label class="form-label">ID Venta</label>
                    <input type="text" class="form-control" name="venta" id="id-venta" value="0" readonly>
                </div>
            <div class="form-floating mb-3">
                <input type="number" class="form-control" id="id-ruc_dni" placeholder="username">
                <label>RUC/DNI</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="id-razon_social" placeholder="username" oninput="this.value = this.value.toUpperCase()">
                <label >Razón Social/Nombres</label>
            </div>
              <hr>
              <div id="detalle-venta">
                
              </div>
            <!--TABLA-->
            <div class="row">
                <div class="col">
                    <label class="form-label">Mesa Escogida: <b id="msa">Mesa 4</b></label>
                </div>
                <div class="col">
                    <label class="form-label">Cod: <b>#</b><b id="id-sale">1234</b></label>
                </div>
            </div>
            <div class="table-responsive">
                <table id="tablaventa" class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nro</th>
                            <th scope="col">Producto</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Aquí es donde se agregarán las filas dinámicamente -->
                    </tbody>
                </table>
            </div>
            <!--TABLA-->
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-fact btn-xs" id="btn-fact">
                  Fact
              </button>
              <button type="button" class="btn btn-bol btn-xs" id="btn-bol">
                  Bol
              </button>
              <button type="button" class="btn btn-success btn-lg" id="btn-print">
                  <i class="bi bi-printer-fill bi-lg"></i>
              </button>
              <button type="button" class="btn btn-secondary" id="btn-cerrar" data-bs-dismiss="modal">Cerrar</button>
              <!--<button type="button" class="btn btn-danger" id="id-cancelar">Cancelar Venta</button>-->
              <div id="contenidoticket"></div>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal -->
    <div class="container-fluid">
        <div class="mb-3">
            <h3 class="text-center">Historial de ventas</h3>
        </div>
        <!--Table Elements-->
        <div class="card border-0">
            <div class="card-header">
                <!-- Agregar un formulario para filtrar por fechas -->
                <div class="container mt-4">
                    <h4>Filtrar por rango de fechas:</h4>
                    <form id="formFiltrarFechas">
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
                        <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-search"></i></button>
                    </form>
                </div>
                <!-- Agregar un formulario para filtrar por fechas -->
            </div>
            <div class="card-body">
                <div class="table-responsive" id="filtroTabla">
                    <table id="historial" class="table">
                        <thead>
                            <tr>
                                <th scope="col"># venta</th>
                                <th scope="col">nro mesa</th>
                                <th scope="col">Fecha Venta</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Total</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'vistas/parte-inferior.php'; ?>

<script>
$(document).ready(function () {
    //var table; // Variable global para la tabla DataTables
    var table = $('#historial').DataTable({ // Inicializar la tabla DataTables al cargar la página
        columns: [
            { data: 'id_venta' },
            { data: 'id_mesa' },
            { data: 'fecha_venta' },
            //{ data: 'estado_venta' },
            { 
            data: 'estado_venta',
            createdCell: function (cell, cellData) {
                // Crea un span con la clase badge y el color según el valor de estado_venta
                var badgeClass = cellData === 'CANCELADO' ? 'bg-danger' : (cellData === 'APROBADO' ? 'bg-success' : '');
                $(cell).html('<span class="badge ' + badgeClass + '">' + cellData + '</span>');
            }
        },
            { data: 'total_venta' },
            { data: null, defaultContent: '<button type="button" class="btn btn-warning btn-detalle"\n\
            data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-clipboard2-check-fill"></i>\n\
            ver</button>' }
        ],
        order: [[2, 'desc']]
    });
    var tiempoLimiteMinutos = 1;
    // Función para cargar las ventas al cargar la página

    // Evento de submit del formulario para filtrar por fechas
    $('#formFiltrarFechas').submit(function (event) {
        event.preventDefault(); // Evitar el envío del formulario

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
        // Realizar la solicitud AJAX para obtener las ventas filtradas por fechas
        $.ajax({
            url: 'controlador/class/salesrange.php',
            type: 'POST',
            dataType: 'json',
            data: {
                fechaInicio: fechaInicio,
                fechaFin: fechaFin
            },
            success: function (data) {
                // Mostrar la tabla y cargar los datos si hay resultados
                table.clear().draw();
                
                if (data.length > 0) {
                    $('#filtroTabla').show(); // Mostrar la tabla
                    table.rows.add(data).draw();
                } else {
                    // Mostrar un mensaje indicando que no hay ventas en ese rango de fechas
                    Swal.fire('No hay ventas en el rango de fechas seleccionado');
                    //$('#historial').empty();
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                Swal.fire('Error al filtrar las ventas por fechas');
            }
        });
        //Evento Click Boton Detalle
        $('#historial').on('click', '.btn-detalle', function () {
            var data = table.row($(this).parents('tr')).data(); // Obtener los datos de la fila clicada
            $('#id-venta').val(data.id_venta);
            /*Creo variable para obtener la fecha de la fila seleccionada*/
            var fechaVenta = new Date(data.fecha_venta); // Obtener la fecha de venta desde los datos
            var fechaLimite = new Date(fechaVenta.getTime() + tiempoLimiteMinutos * 60000); // Calcular la fecha límite
            // Deshabilitar el botón si ha pasado el tiempo límite desde la fecha de venta
        //var botonCancelar = document.getElementById("id-cancelar");
        var fechaActual = new Date();
        if (fechaActual > fechaLimite) {
            //botonCancelar.disabled = true; // Deshabilitar el botón
        }
            // Realizar una solicitud AJAX para obtener el detalle de la venta según el ID de la venta
            $.ajax({
                url: 'controlador/class/saledetail.php?id_venta=' + data.id_venta, // Ajusta la ruta y envía el ID de la venta
                type: 'GET',
                dataType: 'json',
                success: function (detalleData) {
                    var tbody = $('#tablaventa tbody'); // Referencia al cuerpo de la tabla
                    // Limpiar el contenido anterior
                    tbody.empty();
                    var totalVenta = 0; // Inicializar el total de la venta
                    
                    // Recorrer los detalles de la venta y agregarlos a la tabla
                    detalleData.forEach(function (detalle, index) {
                        var subtotal = detalle.cantidad_vendida * detalle.precio_venta;
                        totalVenta += subtotal; // Sumar el subtotal al total
                        document.getElementById('msa').innerText = detalle.id_mesa;
                        document.getElementById('id-sale').innerText = detalle.id_venta;

                        var filaHTML = 
                            '<tr>' +
                                '<th scope="row">' + (index + 1) + '</th>' +
                                '<td>' + detalle.nombre_prod + '</td>' +
                                '<td>' + detalle.cantidad_vendida + '</td>' +
                                '<td>S/ ' + subtotal.toFixed(2) + '</td>' +
                            '</tr>';
                        
                        tbody.append(filaHTML); // Agregar la fila a la tabla
                    });
                    // Agregar la fila del total al final de la tabla
                    var totalHTML = 
                        '<tr>' +
                            '<td colspan="3" class="text-end"><b>Total</b></td>' +
                            '<td><b>S/ ' + totalVenta.toFixed(2) + '</b></td>' +
                        '</tr>';
                    
                    tbody.append(totalHTML); // Agregar el total a la tabla

                    detalleData.forEach(function (detalle) {
                        var descripcionHTML = 
                            '<tr>' +
                                '<td colspan="4" class="text-center"><b>Descripción: </b>' + detalle.descripcion + '</td>' +
                            '</tr>';
                        
                        tbody.append(descripcionHTML); // Agregar la descripción a la tabla
                    });
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    console.error(xhr, status, error);
                    alert('Error al cargar el detalle de la venta');
                }
            });
        });
        //Fin evento click Boton DEtalle
        
        /*Inicio evento Click Boton CANCELAR*/
        
        /*Fin evento Click Boton CANCELAR*/
    });
});

</script>
<script>
    document.getElementById("btn-print").addEventListener("click", function(){
        const dato = document.getElementById("id-venta").value;
        //console.log(dato);
        // Construir la URL con el parámetro del ticket
        var url = 'controlador/class/generar_ticket.php?dato=' + dato;
        // Crear un iframe para mostrar el PDF del ticket
        var iframe = document.createElement('iframe');
        iframe.id = 'frmticket';
        iframe.src = url;
        iframe.style.width = '100%';
        iframe.style.height = '600px';
        iframe.style.border = 'none';

        // Agregar el iframe al contenedor en la página
        document.getElementById('contenidoticket').innerHTML = ''; // Limpiar contenido existente
        document.getElementById('contenidoticket').appendChild(iframe);
        document.getElementById('contenidoticket').style.display = 'none'; //ocultar el contenido
        // Enfocar en el iframe y luego imprimir su contenido
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    });
</script>
<script>
document.getElementById("btn-bol").addEventListener("click", function(){
    const dato = document.getElementById("id-venta").value;
    var url = 'controlador/class/Boleta.php?dato=' + dato;
    generarDocumento(url);
});

document.getElementById("btn-fact").addEventListener("click", function(){
    const dato = document.getElementById("id-venta").value;
    const ruc_dni = document.getElementById("id-ruc_dni").value;
    const razon_social = document.getElementById("id-razon_social").value;
    
    // Validación simple de campos requeridos
    if (!ruc_dni || !razon_social) {
        Swal.fire({
            title: "Error",
            text: "Por favor, complete los campos RUC/DNI y Razón Social",
            icon: "error"
        });
        return; // Detener la ejecución si los campos están vacíos
    }
    
    //var url = 'controlador/class/Factura.php?dato=' + dato;
    // Crear la URL con los parámetros adicionales
    var url = 'controlador/class/Factura.php?dato=' + dato + '&ruc_dni=' + ruc_dni + '&razon_social=' + razon_social;
    generarDocumento(url);
    document.getElementById('id-ruc_dni').value = '';
    document.getElementById('id-razon_social').value = '';
});

function generarDocumento(url) {
    var iframe = document.createElement('iframe');
    iframe.id = 'frmticket';
    iframe.src = url;
    iframe.style.width = '100%';
    iframe.style.height = '600px';
    iframe.style.border = 'none';

    document.getElementById('contenidoticket').innerHTML = ''; // Limpiar contenido existente
    document.getElementById('contenidoticket').appendChild(iframe);
    document.getElementById('contenidoticket').style.display = 'none'; // Ocultar el contenido
    iframe.contentWindow.focus();
    iframe.contentWindow.print();
}
</script>