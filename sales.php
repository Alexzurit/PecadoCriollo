<style>
    .modal-header{
        color:#fff;
        background: #428bca;
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
    <div class="container-fluid">
        <div class="mb-3">
            <h4>Admin Dasboard</h4>
        </div>
        <!-- Modal producto seleccionado -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Producto Seleccionado</h1>

                    </div>
                    <div class="modal-body">
                        <form id="formProducto" method="POST" action="" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="id-codigo" class="form-label">Código</label>
                                <input type="text" class="form-control" name="codigo" id="id-codigo" value="0" readonly>
                            </div>

                            <div class="form-group">
                                <label for="id-nombre" class="form-label">Nombre Producto</label>
                                <input type="text" class="form-control" name="nombre" id="id-nombre" readonly>
                            </div>

                            <div class="form-group">
                                <label for="id-precio" class="form-label">Precio</label>
                                <input type="text" class="form-control" name="precio" id="id-precio" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="id-cantidad" class="form-label">Ingrese Cantidad</label>
                                <input type="number" class="form-control" name="cantidad" id="id-cantidad">
                            </div>

                            <div class="form-group">
                                <label for="id-mesa" class="form-label">Mesa de entrega</label>
                                <select class="form-control" name="mesa" id="id-mesa">
                                    <option value="">nro de mesa</option>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-cerrar">Cerrar</button>
                                <button type="button" class="btn btn-warning btn-agregar" id="id-agregar">Agregar al Carro</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- modal producto seleccionado -->
        
        <!-- modal para el carrito -->
        <!-- Modal para mostrar los productos seleccionados -->
<div class="modal fade" id="carritoModal" tabindex="-1" aria-labelledby="carritoModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="carritoModalLabel">Productos Seleccionados</h5>
            </div>
            <div class="card-body">
                <!-- tabla carrito -->
                <div class="table-responsive">
                <table id="tablaCarrito" class="table">
                    <thead>
                        <tr>
                            <th scope="col">Producto</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">subtotal</th>
                            <th scope="col">Mesa</th>
                            <th scope="col">accion</th>
                        </tr>
                    </thead>
                    <tbody><!-- Aquí es donde se agregarán las filas dinámicamente --></tbody>
                </table>
            </div>
                <!-- tabla carrito -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" id="finalizarCompraBtn">Finalizar Compra</button>
                <div id="contenidoticket"></div>
            </div>            
        </div>
    </div>
</div>
        <!-- modal para el carrito -->
        <!--Table Elements-->
        <div class="card border-0">
            <div class="card-header">
                <h5 class="card-title">
                    <button type="button" id="mostrarCarritoBtn" class="btn btn-danger mt-3"><i class="bi bi-cart4"></i> Carrito</button>
                </h5>
                <h6 class="card-subtitle text-muted">
                    
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="filtro-plato">Filtrar por Tipo de Plato:</label>
                    <select id="filtro-plato">
                        <option value="">Todos</option>
                        <option value="1">MENU</option>
                        <option value="2">CARTAS</option>
                    </select>
                </div>
                <div class="mb-3">
    <label for="buscador">Buscar producto:</label>
    <input type="text" id="buscador" class="form-control" placeholder="Ingrese el nombre del producto...">
</div>

                <div id="productos-container" class="d-grid gap-2"></div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'vistas/parte-inferior.php'; ?>

<!--scrip para leer el JSON -->
<script>

    // Realiza una solicitud AJAX para obtener las categorías
    $.ajax({
        url: 'controlador/class/listarMesaJSON.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data); // Muestra el JSON en la consola

            var tipoSelect = $('#id-mesa');
            //tipoSelect.empty(); // Limpia las opciones actuales

            // Agrega una opción por cada categoría en los datos
            data.forEach(function (tipos) {
                tipoSelect.append($('<option>', {
                    value: tipos.id_mesas,
                    text: tipos.nro_mesas
                }));
            });
        },
        error: function () {
            toastr.error('Error al cargar categorías.');
        }
    });

</script>

<!-- mostrar carrito -->
<!-- mostrar carrito -->

<!-- cargar productos por tipo -->
<script>
$(document).ready(function () {
    // Función para cargar y mostrar productos
    function cargarProductos(tipoPlato) {
        var url = tipoPlato !== '' ? 'controlador/class/listProdFil.php' : 'controlador/class/listaProductos.php';
        $.ajax({
            url: url,
            type: 'GET',
            data: { tipo_plato: tipoPlato },
            dataType: 'json',
            success: function (data) {
                mostrarProductos(data);
            }
        });
    }
    // Función para mostrar productos filtrados
    function mostrarProductos(productos) {
        $('#productos-container').empty();
        productos.forEach(function (producto) {
            var $button = $('<button/>', {
                'class': 'btn btn-outline-success mb-2',
                'type': 'button',
                'text': producto.nombre_prod
            });
            //
            // Evento para llenar los input al seleccionar un producto
            $button.click(function () {
                $('#id-codigo').val(producto.id_producto);
                $('#id-nombre').val(producto.nombre_prod);
                $('#id-precio').val(producto.precio_pro || producto.precio_prod);
                $('#staticBackdrop').modal('show');
            });
            //
            $('#productos-container').append($button);
        });
        $('#productos-container').show();
    }

    // Aplicar el filtro cuando se selecciona una opción
    $('#filtro-plato').on('change', function () {
        var tipoPlato = $(this).val();
        cargarProductos(tipoPlato);
    });

    // Capturar el evento de teclado en el campo de búsqueda
    $('#buscador').on('keyup', function () {
        var textoBusqueda = $(this).val().toLowerCase();
        $('.btn-outline-success').each(function () {
            var nombreProducto = $(this).text().toLowerCase();
            if (nombreProducto.indexOf(textoBusqueda) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Cargar todos los productos al iniciar la página
    cargarProductos('');
});


</script>
<!-- script para ticket
<script>
   function imprimirTicket(){
        //$('#contenidoticket').html('<iframe id="frmticket" src="controlador/class/generarTicket.php"></iframe>');
        $('#contenidoticket').html('<iframe id="frmticket" src="controlador/class/generarTicket.php"></iframe>');
        $('#frmticket').get(0).contentWindow.focus();
        $('#frmticket').get(0).contentWindow.print();
        //console.log(response);
    }
</script>
<!-- script para el carrito -->
<script>
    // Arreglo para almacenar los elementos agregados al carrito
    var carrito = [];

    // Función para actualizar la tabla del carrito
    function actualizarTablaCarrito() {
        var tabla = $('#tablaCarrito tbody');
        tabla.empty(); // Limpiar tabla

        // Recorrer los elementos del carrito y agregarlos a la tabla
        var total = 0;
        carrito.forEach(function (item, index) {
            var subtotal = item.cantidad * item.precio;
            var botonEliminar = '<button type="button" class="btn btn-danger btn-eliminar" data-index="' + index + '"><i class="bi bi-trash3-fill"></i></button>';
            total += subtotal;

            // Crear una fila para el elemento del carrito
            var fila = '<tr>' +
                '<td>' + item.nombre + '</td>' +
                '<td>' + item.cantidad + '</td>' +
                '<td>' + subtotal.toFixed(2) + '</td>' +
                '<td>' + item.mesa + '</td>' +
                '<td>' + botonEliminar + '</td>' +
                '</tr>';

            tabla.append(fila);
        });

        // Mostrar el total al final de la tabla
        var filaTotal = '<tr>' +
            '<td colspan="2"><strong>Total:</strong></td>' +
            '<td colspan="3"><strong>' + total.toFixed(2) + '</strong></td>' +
            '</tr>';
        tabla.append(filaTotal);
    }

    $(document).ready(function () {
        // Manejar el clic en el botón de "Agregar al Carro"
        $('.btn-agregar').click(function () {
            //aquí coloco el boton de agregar al carro para que se desactive
            var botonAgregar = document.getElementById("id-agregar");
            // Obtener los datos del formulario
            var codigo = $('#id-codigo').val();
            var nombre = $('#id-nombre').val();
            var precio = parseFloat($('#id-precio').val());
            var cantidad = parseInt($('#id-cantidad').val());
            var mesa = $('#id-mesa').val();

            // Validar datos (puedes agregar más validaciones aquí si es necesario)
            //if (!codigo || !nombre || isNaN(precio) || isNaN(cantidad) || !mesa) {
            if (isNaN(cantidad) || cantidad<=0 || cantidad % 1 !== 0 || !mesa) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Por favor complete todos los campos obligatorios!"
                });
                return;
            }
            // Crear objeto de producto
            var producto = {
                codigo: codigo,
                nombre: nombre,
                precio: precio,
                cantidad: cantidad,
                mesa: mesa
            };

            // Agregar producto al carrito
            carrito.push(producto);
            console.log('Producto agregado al carrito:', producto);
            // Actualizar tabla del carrito
            actualizarTablaCarrito();

            // Cerrar modal
            $('#staticBackdrop').modal('hide');
            limpiarInputs();
        });

        // Manejar el clic en el botón de eliminar del carrito
        $('#tablaCarrito').on('click', '.btn-eliminar', function () {
            var index = $(this).data('index');
            carrito.splice(index, 1); // Eliminar elemento del carrito
            actualizarTablaCarrito(); // Actualizar tabla del carrito
        });

        // Manejar el clic en el botón de mostrar carrito
        $('#mostrarCarritoBtn').click(function () {
            actualizarTablaCarrito(); // Actualizar tabla del carrito antes de mostrarla
            $('#carritoModal').modal('show');
        });
    });
    
    function limpiarInputs(){
        // Resetear formulario
        //$("#formProducto").trigger("reset");
        // Resetear Validación
        //$("#formProducto").data("bootstrapValidator").resetForm(true);
        $("#id-cantidad").val("");
    }

</script>
<!-- script para el carrito -->

<!-- script para VENTA -->
<script>
    // Obtener el valor del campo user
    var id_usuario = $("input[name='user']").val(); // Suponiendo que el campo user es un input con el name "user"

    // Función para procesar la compra
    function procesarCompra() {
        // Aquí puedes enviar los datos del carrito al servidor para procesar la compra
        $.ajax({
            url: 'controlador/class/realizarTransac.php', // URL del script PHP que procesa la compra
            type: 'POST',
            data: {id_usuario: id_usuario,
                //carrito: JSON.stringify(carrito)
                carrito: carrito
            }, // Enviar el carrito como JSON al servidor
            dataType: 'json',
            success: function (response, textStatus, xhr) {
                // Manejar la respuesta del servidor
                if (response.success) {
                    //ticket
                    console.log('Datos del ticket:', response.ticket);
                    //ticket
                    // La compra se realizó con éxito, puedes mostrar un mensaje de éxito o redirigir a otra página
                    Swal.fire({
                        icon: 'success',
                        title: '¡Compra realizada!',
                        text: 'Su compra se ha realizado con éxito.',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(function () {
                        // Redirigir a otra página después de 2 segundos (opcional)
                        //window.location.href = 'sales.php';
                    });
                    mostrarTicket(response.ticket);
                } else {
                    console.log('Error al procesar la compra:', xhr.responseText);
                    // Hubo un error al procesar la compra, mostrar un mensaje de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al procesar la compra. Por favor, inténtelo de nuevo más tarde.'
                    });
                }
            },
            error: function (xhr, status, error) {
                //console.log(carrito);
                console.log("XHR:", xhr);
                console.log("Status:", status);
                console.log("Error:", error);
                // Error en la solicitud AJAX
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Se produjo un error al procesar la solicitud. Por favor, inténtelo de nuevo más tarde.'
                });
            }
        });
    }
    
    function mostrarTicket(ticket) {
        // Crear un iframe para mostrar el PDF del ticket
        var iframe = document.createElement('iframe');
        var url = 'controlador/class/generarTicket.php?ticket=' + JSON.stringify(ticket);
        console.log('ticket recibido: ',ticket);
        //iframe.src = 'generarTicket.php';
        iframe.src = url;
        iframe.width = '100%';
        iframe.height = '600px';
        iframe.style.border = 'none';

        // Agregar el iframe al contenedor en la página
        document.getElementById('contenidoticket').appendChild(iframe);
    }

    $(document).ready(function () {
        // Manejar el clic en el botón "Finalizar Compra"
        $('#finalizarCompraBtn').click(function () {
            // Verificar si hay elementos en el carrito antes de procesar la compra
            if (carrito.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Carrito vacío',
                    text: 'No hay productos en el carrito. Agregue productos antes de finalizar la compra.'
                });
                return;
            }

            // Procesar la compra
            procesarCompra();            
        });
    });
</script>

<!-- script para VENTA -->

<!--validaciones-->
<script>
$(document).ready(function () {
    $('#formProducto').bootstrapValidator({
        fields: {
            cantidad: {
                validators: {
                    notEmpty: {
                        message: 'Cantidad Obligatoria'
                    },
                    integer: {
                        message: 'Ingrese un número entero'
                    },
                    greaterThan: {
                        value: 0,
                        inclusive: false,
                        message: 'La cantidad debe ser un número entero positivo'
                    }
                }
            },
            mesa: {
                validators: {
                    notEmpty: {
                        message: 'Elija mesa de entrega'
                    }
                }
            }
        }
    });
});
</script>
