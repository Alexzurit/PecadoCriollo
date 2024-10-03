<?php require_once 'vistas/parte-superior.php'; ?>


<main class="content px-3 py-2">
    <div class="container-fluid">
        <div class="card border-0 fondograd">
            <div class="card-header">
                <div class="mb-3 text-center">
                    <h4 class="text-center mt-3">Cambiar contraseña</h4>
                </div>
            </div>
            <div class="card-body">
                <form id="formCambiarPassword" method="POST">
                    <div class="form-group">
                        <label class="form-label">Contraseña Actual</label>
                        <input type="password" class="form-control" name="password_actual" id="id-actual">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" name="nueva_password" id="id-nueva">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Repetir Nueva Contraseña</label>
                        <input type="password" class="form-control" name="repetir_password" id="id-repetir">
                    </div>

                    <div class="d-grid  mt-3">
                        <button class="btn btn-success" type="submit">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once 'vistas/parte-inferior.php'; ?>

<script>
    function limpiarInputs() {
        document.getElementById('id-actual').value = '';
        document.getElementById('id-nueva').value = '';
        document.getElementById('id-repetir').value = '';
    }
    
    document.getElementById('formCambiarPassword').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Obtener los valores del formulario
        const password_actual = document.getElementById('id-actual').value;
        const nueva_password = document.getElementById('id-nueva').value;
        const repetir_password = document.getElementById('id-repetir').value;
        
        if(password_actual=="" || nueva_password=="" || repetir_password==""){
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Los campos no deben estar vacios'
            });
            return;
        }
        try {
            // Enviar los datos al backend usando fetch
            const response = await fetch('controlador/class/changePw.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'password_actual': password_actual,
                    'nueva_password': nueva_password,
                    'repetir_password': repetir_password
                })
            });

            const resultado = await response.json();

            if (resultado.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: resultado.message
                }).then(() => {
                    // Limpiar los campos de entrada
                    limpiarInputs();
                    window.location.href = 'controlador/class/logout.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: resultado.message
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al procesar la solicitud. Inténtalo de nuevo más tarde.'
            });
            console.error('Error:', error);
        }
    });
</script>