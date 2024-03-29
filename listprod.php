<?php session_start(); ?>

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
            <h4>Lista de Productos</h4>
        </div>
        <!-- para colocar el modal -->
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modificar Producto</h1>

                    </div>
                    <div class="modal-body">
                        <form id="formProducto" method="POST" action="controlador/class/registro.php" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="id-codigo" class="form-label">Código</label>
                                <input type="text" class="form-control" name="codigo" id="id-codigo" value="0" readonly>
                            </div>

                            <div class="form-group">
                                <label for="id-nombre" class="form-label">Nombre Producto</label>
                                <input type="text" class="form-control" name="nombre" id="id-nombre">
                            </div>

                            <div class="form-group">
                                <label for="id-categoria" class="form-label">Elija tipo de plato</label>
                                <select class="form-control" name="tipo" id="id-tipo">
                                    <option value="">Seleccione tipo</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="id-precio" class="form-label">Precio</label>
                                <input type="text" class="form-control" name="precio" id="id-precio">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-cerrar">Cerrar</button>
                                <button type="submit" class="btn btn-warning">Modificar</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- para colocar el modal -->
        
        <!--Table Elements-->
        <div class="card border-0">
            <div class="card-header">
                <h5 class="card-title">
                    Tabla Básica
                </h5>
                <h6 class="card-subtitle text-muted">
                    Esta tabla contiene todos los productos que se han agregado y existen actualmente, si deseas Modificar el nombre, tipo o precio del plato dale click al botón
                    botón verde y luego de hacer el cambio dale clik en el boton "Modificar", si deseas eliminar un producto dale click al botón rojo o del tachito.
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table id="tableProducto" class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Tipo de Plato</th>
                            <th scope="col">Precio</th>
                            <th scope="col"></th>
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

<!--Scrip para mensajes TOastr-->
    <script>
    $(document).ready(function () {
            <?php
            if (isset($_SESSION['MENSAJE'])) {
                echo "Swal.fire({
                icon: 'success',
                title: '" . $_SESSION['MENSAJE'] . "',
                showConfirmButton: false,
                timer: 2000
            });";
                unset($_SESSION['MENSAJE']);
            }else if(isset($_SESSION['ERROR'])){
                echo "Swal.fire({
                icon: 'error',
                title: '" . $_SESSION['ERROR'] . "',
                showConfirmButton: false,
                timer: 2000
            });";
                unset($_SESSION['ERROR']);
            }
            ?>
        });
    </script>

<!--scrip para leer el JSON -->
    <script>

        // Realiza una solicitud AJAX para obtener las categorías
        $.ajax({
            url: 'controlador/class/listarTipoJSON.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                console.log(data); // Muestra el JSON en la consola

                var tipoSelect = $('#id-tipo');
                //tipoSelect.empty(); // Limpia las opciones actuales

                // Agrega una opción por cada categoría en los datos
                data.forEach(function (tipos) {
                    tipoSelect.append($('<option>', {
                        value: tipos.id_platos,
                        text: tipos.nombre_platos
                    }));
                });
            },
            error: function () {
                toastr.error('Error al cargar categorías.');
            }
        });

    </script>

<!--Script para listar y obtener la tabla-->
    <script>
        $(document).ready(function () {
            // Realiza una solicitud AJAX para obtener los datos de listarPro
            $.ajax({
                url: 'controlador/class/listaProductos.php', // Asegúrate de que esta sea la ruta correcta a tu archivo PHP
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    // Manipula los datos recibidos y crea una tabla
                    var table = $('#tableProducto').DataTable({
                        data: data,
                        columns: [
                            { data: 'id_producto' },
                            { data: 'nombre_prod' },
                            {
                                data: 'tipo_plato',
                                render: function(data, type, row) {
                                    // Si el tipo_plato es 1, mostrar "MENU", de lo contrario, mostrar "CARTA"
                                    return data === 1 ? 'MENU' : 'CARTAS';
                                }
                            },
                            { data: 'precio_pro' },
                            { data: null, defaultContent: '<button type="button" class="btn btn-success btn-editar" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="bi bi-pencil-square"></i></button>' },
                            { data: null, defaultContent: '<button type="button" class="btn btn-danger btn-eliminar"><i class="bi bi-trash3-fill"></i></button>' }
                        ],
                        // Configuración para hacer responsiva la tabla
                        responsive: true,
                        // Posicionamiento del buscador
                        dom: 'Blfrtip',
                        // Elementos de control
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ],
                        "paging": true,
                        "searching": true,
                        autoWidth: false // Desactiva el ajuste automático del ancho de la tabla
                    });
                    //Funcionalidad del Botón Editar
                    $(document).on("click",".btn-editar",function(){
                        var rowData = table.row($(this).parents('tr')).data();
                        var id = rowData.id_prod;
                        $('#id-codigo').val(rowData.id_producto);
                        $('#id-nombre').val(rowData.nombre_prod);
                        $('#id-tipo').val(rowData.tipo_plato);
                        $('#id-precio').val(rowData.precio_pro);

                    }); //Fin del boton Editar
                    //Asignamos eventos a cerrar para reiniciar formulario
                        $(document).on("click", "#btn-cerrar", function() {
                        // Resetear formulario
                        $("#formProducto").trigger("reset");
                        // Resetear Validación
                        $("#formProducto").data("bootstrapValidator").resetForm(true);
                        // Restablecer el valor del campo "codigo" a 0
                        $("#id-codigo").val("0");
                    });//Fin boton cerrar formulario
                    //Asignar evento al boton eliminar
                    $(document).on("click", ".btn-eliminar", function () {
                        var rowData = table.row($(this).parents("tr")).data();
                        var id_prod = rowData.id_producto;
                        var nombre_prod = rowData.nombre_prod;

                        Swal.fire({
                            title: '¿Seguro de eliminar?',
                            text: 'Producto con ID: ' + id_prod + '-' + nombre_prod,
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
                                    url: "controlador/class/deleteProd.php",
                                    type: "POST",
                                    data: { codigo: id_prod },
                                    success: function () {
                                            // Eliminación exitosa, redirige a la página deseada
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Eliminación exitosa',
                                                text: 'El producto se ha eliminado con éxito.'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    window.location = "listprod.php"; // Reemplaza con la ruta correcta
                                                }
                                            });//Falta agregarle una condicional por si no se elimina
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
                        });
                    });//Fin boton Eliminar
                },
                error: function () {
                // Si ocurre un error al obtener los productos, muestra un mensaje de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al obtener los productos de la base de datos. Por favor, inténtalo de nuevo más tarde.'
                    });
                }
            });
        });
        
    </script>
    
    <!--validaciones-->
    <script>
        $(document).ready(function () {
    $('#formProducto').bootstrapValidator({
        fields: {
            nombre: {
                validators: {
                    notEmpty: {
                        message: 'El campo Nombre es obligatorio'
                    },
                    stringLength: {
                        min: 2, // Longitud mínima del nombre
                        max: 50, // Longitud máxima del nombre
                        message: 'El nombre debe tener entre 2 y 50 caracteres'
                    }
                    // Puedes agregar más reglas de validación, como 'regexp' para expresiones regulares, 'emailAddress' para validar correos electrónicos, etc.
                }
            },
            tipo: {
                validators: {
                    notEmpty: {
                        message: 'El campo Tipo es obligatorio'
                    }
                    // Puedes agregar más reglas de validación, como 'regexp' para expresiones regulares, 'emailAddress' para validar correos electrónicos, etc.
                }
            },
            precio: {
                validators: {
                    notEmpty:{
                        message: 'El campo Precio es obligatorio'
                    },
                    regexp: {
                        regexp: /^[0-9]+(\.[0-9]{1,2})?$/, // Acepta números enteros o decimales con un máximo de dos decimales
                        message: 'El precio debe ser un número válido'
                    }
                }
            }
        }
    });
});

    </script>