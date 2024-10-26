<style>
    .btn-bv{
        background: rgb(34,118,195);
        background: linear-gradient(0deg, rgba(34,118,195,1) 0%, rgba(45,206,253,1) 100%);
        color: white !important;
        border: none !important;
    }
    .btn-bv:hover{
        background: rgb(195,34,34);
        background: linear-gradient(0deg, rgba(195,34,34,1) 0%, rgba(253,111,45,1) 100%);
    }
</style>
<?php require_once 'vistas/parte-superior.php'; ?>
<!-- Modal -->
<div class="modal fade" id="ModalBuscaVenta" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">
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
            </div>
            <div class="modal-footer">
                <button id="anularBtn" type="button" class="btn btn-danger">Anular</button>
                <button id="cerrarBtn" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- fin modal-->
<main class="content px-3 py-2">
    <div class="container-fluid">
        <div class="card border-3">
            <div class="card-header">
                <div class="mb-3 text-center">
                    <h4 class="text-center mt-3">Cancelar Venta</h4>
                </div>
            </div>
            <div class="card-body">
                <form id="formSearchSale" method="POST">
                    <div class="form-floating">
                        <input type="number" class="form-control" id="id-venta" placeholder="Username">
                        <label>Ingresa Id de venta</label>
                    </div>

                    <div class="d-grid  mt-3">
                        <button class="btn btn-bv" type="submit">Buscar Venta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php require_once 'vistas/parte-inferior.php'; ?>

<script>
    // Capturar el evento submit del formulario
    $('#formSearchSale').on('submit', function (event) {
        event.preventDefault(); // Evitar el envío tradicional del formulario

        var idVenta = $('#id-venta').val(); // Obtener el ID de venta desde el input

        if (idVenta) {
            //limpiarModal();
            // Realizar una solicitud AJAX para obtener el detalle de la venta según el ID de la venta
            $.ajax({
                url: 'controlador/class/saledetail.php?id_venta=' + idVenta, // Ajusta la ruta y envía el ID de la venta
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    // Verificar si la respuesta fue exitosa
                    if (response.success === false) {
                        // Mostrar el mensaje de error en el modal o en otro lugar
                        Swal.fire({
                        icon: "error",
                        title: "No hay Registros",
                        text: response.message
                        });
                        //alert(response.message);
                    } else {
                        var detalleData = response; // Si la respuesta es exitosa, contiene los detalles de la venta
                        var tbody = $('#tablaventa tbody'); // Referencia al cuerpo de la tabla
                        tbody.empty(); // Limpiar el contenido anterior de la tabla

                        var totalVenta = 0; // Inicializar el total de la venta
                        var estadoVenta = detalleData[0].estado_venta;
                        var fechaCalculo = detalleData[0].fecha_venta;
                        // Convertir la cadena de fecha a un objeto Date
                        var partesFecha = fechaCalculo.split(' ');
                        var fechaYHora = partesFecha[0].split('-');
                        var horaYMinuto = partesFecha[1].split(':');

                        var fecha = new Date(
                            fechaYHora[0], // Año
                            fechaYHora[1] - 1, // Mes (0-11)
                            fechaYHora[2], // Día
                            horaYMinuto[0], // Hora
                            horaYMinuto[1], // Minutos
                            0 // Segundos
                        );
                        
                        var fechaActual = new Date();
                        
                        var diferencia = fechaActual - fecha;
                        
                        var horasPasadas = diferencia / (1000 * 60 * 60);
                        

                        // Verificar el estado de la venta (fuera del foreach)
                        if (estadoVenta === 'CANCELADO') {
                            $('#anularBtn').prop('disabled', true);
                            $('#tablaventa').before('<div class="alert alert-danger">Esta venta ya está anulada.</div>');
                        } else if (horasPasadas > 5){
                            // $('#anularBtn').prop('disabled', false);
                            $('#anularBtn').prop('disabled', true);
                            $('#tablaventa').before('<div class="alert alert-primary">No se puede anular, Límite tiempo excedido</div>');
                        } else {
                            $('#anularBtn').prop('disabled', false);
                        }

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

                        // Mostrar el modal una vez que los datos se hayan cargado
                        $('#ModalBuscaVenta').modal('show');
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                    console.error(xhr, status, error);
                    alert('Error al cargar el detalle de la venta');
                }
            });
        } else {
            alert('Por favor ingresa un ID de venta válido.');
        }
    });
    // Limpiar el contenido del modal
    function limpiarModal() {
        $('#tablaventa tbody').empty(); // Limpiar la tabla
        $('#id-sale').text(''); // Limpiar el ID de la venta
        $('#msa').text(''); // Limpiar la mesa
        $('#anularBtn').prop('disabled', false); // Habilitar el botón por defecto
        $('#tablaventa').prev('.alert').remove(); // Eliminar cualquier alerta previa
        $('.alert-danger').remove(); // Eliminar el mensaje de "venta anulada"
        $('.alert-success').remove(); // Eliminar el mensaje de "venta anulada"
        $('.alert-primary').remove(); // Eliminar el mensaje
    }
    $('#cerrarBtn').on('click', function(){
        limpiarModal();
    });
    // // Limpiar contenido al cerrar el modal
    // $('#ModalBuscaVenta').on('hidden.bs.modal', function () {
    //     limpiarModal();
    // });
</script>

<script>
    document.getElementById('anularBtn').addEventListener('click', function() {
        Swal.fire({
            title: "¿Seguro de ANULAR la venta?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Sí",
            denyButtonText: `NO`
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                //Swal.fire("Saved!", "", "success");
                let idVenta = document.getElementById('id-venta').value;
                if (!idVenta) {
                    alert("Por favor ingresa un ID de venta válido");
                    return;
                }

                fetch('controlador/class/changeSL.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id_venta=${idVenta}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('id-sale').innerText = `${idVenta}`;
                        //document.getElementById('tablaventa').insertAdjacentHTML('beforebegin', '<div class="alert alert-success">VENTA ANULADA</div>');
                        document.getElementById('tablaventa').insertAdjacentHTML('beforebegin', `<div class="alert alert-success">${data.message}</div>`);
                        document.getElementById('anularBtn').disabled = true;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
                //Podemos agregar mensaje opcional

            } else if (result.isDenied) {
                Swal.fire("La venta no ha sido anulada", "", "info");
            }
        });
    });
</script>