<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/stylelog.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- toastr -->    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!--iconos-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"> 
    <title>Login</title>
    <style>
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
</head>
<body>
  <div class="wrapper">
    <div class="container main">
        <div class="row">
            <div class="col-md-6 side-image">
                       
                <!-------------      image     ------------->
                
                <img src="" alt="">
                <div class="text">
                    <p>Alex Eduardo Zurita Julca<i>- zarblex</i></p>
                </div>
                
            </div>

            <div class="col-md-6 right">
                
                <div class="input-box">
                   
                   <header>Pecado Criollo</header>
                   <form id="frmLogin" method="post" action="controlador/class/logIn.php">
                            <div class="form-group">
                                <div class="input-field">
                                    <input type="text" class="input" required="" autocomplete="off" name="usuario" id="id-usuario">
                                     <label>Email/User</label> 
                                 </div>
                            </div>
                            <div class="form-group">
                                <div class="input-field">
                                    <input type="password" class="input" required="" name="clave" id="id-clave">
                                     <label>Password</label>
                                 </div>
                            </div>
                        <div class="input-field">
                            <button type="submit" class="submit" value="Iniciar Sesion">Iniciar Sesion</button>
                        </div> 
                        </form>
                   <div class="signin">
                    <span>Already have an account? <a href="#">Log in here</a></span>
                   </div>
                </div>  
            </div>
        </div>
    </div>
</div>
</body>

<!-- libreria principal de JQUERY -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<!-- Libreria Jquery de boostrap y que funcione Modal-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<!-- Libreria para validar (boostrap validator) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.0/js/bootstrapValidator.js"></script>
<!-- toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function () {
        <?php
        if (isset($_SESSION['error_message'])) {
            echo "toastr.error('" . $_SESSION['error_message'] . "');";
            unset($_SESSION['error_message']);
        }
        ?>
    });
</script>

<!-- validaciones -->
<script>
$(document).ready(function(){
    $('#frmLogin').bootstrapValidator({
        fields:{
            usuario:{
                validators:{
                    notEmpty:{
                        message: 'Ingrese el Usuario'
                    }
                }
            },
            clave:{
                validators:{
                    notEmpty:{
                        message: 'Ingrese la contraseña'
                    }
                }
            }
            //aquí agregasria mas
            }
        });
    });  
</script>
</html>