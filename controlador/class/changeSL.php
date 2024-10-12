<?php
include'../methods.php';

//Verificar si se recibe el ID de manera correcta
if (isset($_POST['id_venta'])) {
    $idVenta = $_POST['id_venta'];

    // Crear una instancia de la clase Methods
    $met = new Methods();

    // Ejecutar y devolver la respuesta de modificarEstado
    echo $met->modificarEstado($idVenta);

} else {
    // Enviar una respuesta de error en JSON si no se recibe el ID
    $response = array("status" => "error", "message" => "ID de venta no recibido.");
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>