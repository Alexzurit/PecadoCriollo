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

</style>
<?php require_once 'vistas/parte-superior.php'; ?>

<main class="content px-3 py-2">
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Detalle de Venta</h1>
          </div>
          <div class="modal-body">
              <div class="form-group" hidden><!--hidden es para ocultar, readonly es bloquear el campo-->
                    <label class="form-label">ID Venta</label>
                    <input type="text" class="form-control" name="venta" id="id-venta" value="0" readonly>
                </div>
              <hr>
              <div id="detalle-venta">
                
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="btn-cerrar" data-bs-dismiss="modal">Cerrar</button>
              <!--<button type="button" class="btn btn-danger" id="id-cancelar">Cancelar Venta</button>-->
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
                    var detalleVenta = $('#detalle-venta');
                    // Limpiar el contenido anterior
                    detalleVenta.empty();
                    
                    // Construir el contenido del detalle de la venta en el modal
                    detalleData.forEach(function (detalle) {
                        var detalleHTML = 
                                          '<p>Nombre Producto: ' + detalle.nombre_prod + '</p>' +
                                          '<p>ID Producto: ' + detalle.id_producto + '</p>' +
                                          '<p>Cantidad: ' + detalle.cantidad_vendida + '</p>' +
                                          '<p>Precio Venta: ' + detalle.precio_venta + '</p>' +
                                          '<p>Subtotal: ' + detalle.subtotal + '</p>' +
                                          '<hr>';

                        detalleVenta.append(detalleHTML);//Agregar detalles al modal
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
        /*$('#exampleModal').on('click', '.btn-danger', function () {
            var idVenta = $('#id-venta').val(); // Obtener el ID de la venta del input
            //Inicio deshabitlitar boton CANCELAR
            
            //Fin deshabilitar boton CANCELAR
            Swal.fire({
                title: '¿Seguro de Cancelar?',
                text: 'Venta con ID: ' + idVenta ,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // El usuario ha confirmado la eliminación, realiza la solicitud Ajax para eliminar el producto
                    $.ajax({
                        url: "Controlador/class/cancelarVenta.php",
                        type: "POST",
                        dataType: 'json',
                        data: { id_venta: idVenta },
                        success: function (response) {
                                // Eliminación exitosa, redirige a la página deseada
                                if (response.success) {
                                    toastr.success(response.message);
                                    setTimeout(function (){
                                        window.location = "ventasHistorial.php";
                                    },2000); //redirecciona después de 2 segundos
                                    // Realizar alguna acción adicional después de cancelar la venta, como recargar la tabla, etc.

                                } else {
                                    toastr.error(response.message);
                                }
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error en la solicitud Ajax para eliminar el producto.'
                            });
                        }
                    });
                }
            });//Fin Swalfire
        });
        /*Fin evento Click Boton CANCELAR*/
    });
});

</script>