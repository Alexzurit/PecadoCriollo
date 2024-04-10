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
<!-- ========= Main content section of dashboard ======= -->
<h1 class="text-center mt-3" >Añade Nuevos Platos</h1>
<div class="modal-body mx-3">
    
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
        <!--
        <div class="modal-footer mt-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-cerrar">Cerrar</button>
            <button type="submit" class="btn btn-primary">Grabar</button>
        </div>
        -->
        <div class="d-grid  mt-3">
            <button class="btn btn-success" type="submit">Registrar Producto</button>
        </div>
    </form>



</div>

<?php require_once 'vistas/parte-inferior.php'; ?>

<!--Scrip para mensajes TOastr-->
    <script>
    $(document).ready(function () {
            <?php
            if (isset($_SESSION['EXITO'])) {
                echo "Swal.fire({
                icon: 'success',
                title: '" . $_SESSION['EXITO'] . "',
                showConfirmButton: false,
                timer: 2000
            });";
                unset($_SESSION['EXITO']);
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