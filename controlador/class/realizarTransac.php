<?php
include '../methods.php';
// Datos del carrito obtenidos del cliente
$carrito = $_POST['carrito'];
// ID de usuario obtenido del cliente
$id_usuario = $_POST['id_usuario'];

// Depurar el contenido del carrito
//error_log('Contenido del carrito recibido:');
//error_log(print_r($carrito, true));


// Crear instancia de la clase Methods
$met = new Methods();

// Llamar al método transaction
$met->transaction($carrito, $id_usuario);

?>