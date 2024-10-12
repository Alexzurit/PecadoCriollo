<?php 
    session_start();
    
    if(!isset($_SESSION['usuario'])){
        header("location: login.php");
        exit(); // Es importante salir del script después de redirigir
    }
    
    // Evitar que el navegador almacene en caché la página
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="sp" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- iconoPestaña -->
    <link rel="icon" href="image/platorste.png" type="image/png">
    <!-- Charts.js para graficos en el dashboard -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!--lo necesario para validaciones y mensajes del CRUD-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- para mensajes de confirmacion -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- para eliminar -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bulma/bulma.css" rel="stylesheet">
    <!-- nose -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!--iconos-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"> 
    <!--fin-->
    <title>Restaurante</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- ======== Main wrapper for dashboard =========== -->

    <div class="wrapper">
        <!-- =========== Sidebar for admin dashboard =========== -->

        <aside id="sidebar" class="js-sidebar">

            <!-- ======== Content For Sidebar ========-->
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="#">Sistema de Ventas</a>
                </div>

                <!-- ======= Navigation links for sidebar ======== -->
                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Admin Elements
                    </li>
                    <li class="sidebar-item">
                        <a href="dash.php" class="sidebar-link">
                            <i class="fa-solid fa-list pe-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#pages" data-bs-toggle="collapse"
                        aria-expanded="false"><i class="fa-solid fa-file-lines pe-2"></i>
                        Producto
                        </a>
                        <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="addprod.php" class="sidebar-link">Registrar</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="listprod.php" class="sidebar-link">Ver Lista</a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#posts" data-bs-toggle="collapse"
                        aria-expanded="false"><i class="bi bi-cash-coin"></i>
                        Ventas
                        </a>
                        <ul id="posts" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="sales.php" class="sidebar-link">Realizar Venta</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="saleshist.php" class="sidebar-link">Historial Venta</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="report.php" class="sidebar-link">Reporte</a>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="sidebar-header">
                        Opciones Adminitrador
                    </li>
                    <!-- 
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#multi" data-bs-toggle="collapse"
                        aria-expanded="false"><i class="fa-solid fa-share-nodes pe-2"></i>
                        Multi Dropdown
                        </a>
                        <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link collapsed" data-bs-target="#level-1" 
                                data-bs-toggle="collapse" aria-expanded="false">Level 1</a>
                                <ul id="level-1" class="sidebar-dropdown list-unstyled collapse">
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">Level 1.1</a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="#" class="sidebar-link">Level 1.2</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>comment -->
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed" data-bs-target="#auth" data-bs-toggle="collapse"
                        aria-expanded="false"><i class="fa-regular fa-user pe-2"></i>
                        Auth
                        </a>
                        <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
<!--                                <a href="#" class="sidebar-link">Nuevo Usuario</a>-->
                            </li>
                            <li class="sidebar-item">
                                <a href="changepass.php" class="sidebar-link">Cambiar contraseña</a>
                            </li>
                            <li class="sidebar-item">
                                <a href="cancelsale.php" class="sidebar-link">Cancelar Venta</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- ========= Main section of dashboard ======= -->

        <div class="main">

            <!-- ========= Main navbar section of dashboard ======= -->

            <nav class="navbar navbar-expand px-3 border-bottom">
                <button class="btn" id="sidebar-toggle" type="button">
                    <span class="navbar-toggler-icon"></span> 
                </button>
                <div class="navbar-collapse navbar">
                    <ul class="navbar-nav">
                        <input name="user" type="hidden" class="nav-link dropdown-toggle" value="<?php echo $_SESSION['id_usuario']; ?>" readonly>
                           
                        <span style="color: orange" class="nav-link dropdown-toggle">
                            <?php echo $_SESSION['nombres'].' '.$_SESSION['apellidos']; ?>
                        </span>
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="image/pc.png" class="avatar img-fluid rounded" alt="">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="#" class="dropdown-item disabled">Profile</a>
                                <a href="#" class="dropdown-item disabled">Setting</a>
                                <a href="controlador/class/logout.php" id="logout-button" class="dropdown-item">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>