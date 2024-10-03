<?php
include '../methods.php';
session_start(); // Asegúrate de iniciar la sesión si vas a usar variables de sesión

$methods = new Methods(); // Crea una instancia de la clase Methods

// Verifica que la solicitud sea de tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén el usuario de la sesión y los datos del formulario
    $usuario = $_SESSION['usuario'];
    $passwordActual = $_POST['password_actual'];
    $nuevaPassword = $_POST['nueva_password'];
    $repetirPassword = $_POST['repetir_password'];

    // Llama al método cambiarPassword
    $resultado = $methods->cambiarPassword($usuario, $passwordActual, $nuevaPassword, $repetirPassword);

    // Devuelve la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($resultado);
}

?>