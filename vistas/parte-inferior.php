<!-- ========= light and dark mode toggle button ======= -->

            <a href="#" class="theme-toggle">
                <i class="fa-regular fa-moon"></i>
                <i class="fa-regular fa-sun"></i>
            </a>

            <!-- ========= footer section of dashboard ======= -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a href="#" class="text-muted">
                                    <strong>Sistema de Ventas</strong>
                                </a>
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a href="" class="text-muted">Contact</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="" class="text-muted">About us</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="" class="text-muted">Terms</a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="" class="text-muted">Booking</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    <!-- libreria principal de JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <!--Script de boostrap5, toatr y sweet alert-->
    <!--Para validar y registrocdsscas-->
    <!-- Libreria Jquery de boostrap y que funcione Modal-->
    
    <!-- libreria JS de la tabla (buscador)-->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Libreria para validar (boostrap validator) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.0/js/bootstrapValidator.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- mesanje eliminar -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>

<script>
//document.getElementById('logout-button').addEventListener('click', async function(e) {
//    e.preventDefault(); // Evitar la acción por defecto del enlace
//
//    try {
//        // Realizamos la solicitud al servidor para cerrar la sesión
//        const response = await fetch('controlador/class/logout.php', {
//            method: 'POST',
//            headers: {
//                'Content-Type': 'application/x-www-form-urlencoded'
//            }
//        });
//
//        if (response.ok) {
//            // Redirigir al usuario al login
//            window.location.href = 'login.php';
//        } else {
//            console.error('Error al cerrar sesión.');
//        }
//    } catch (error) {
//        console.error('Error al procesar la solicitud de cierre de sesión:', error);
//    }
//});
</script>
//Scrip reload page for security ctmre gaa
<script>
  // Recargar la página si el usuario navega hacia atrás
  window.onpageshow = function(event) {
    if (event.persisted) {
      window.location.reload();
    }
  };
</script>

