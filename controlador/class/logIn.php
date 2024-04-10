<?php
session_start();
include '../methods.php';

$usuario = $_POST['usuario'];
$password = $_POST['clave'];

$met = new methods();

if($met->login($usuario, $password)){
    $_SESSION['ISE'] = "Inicio de Sesion EXITOSO";
    header('Location: ../../dash.php');
    exit();
}else{
    $_SESSION['error_message'] = "¡Contraseña/Usuario Incorrectos!";
    header('Location: ../../login.php');
    exit();
}


?>