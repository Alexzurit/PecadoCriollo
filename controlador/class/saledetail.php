<?php
include '../methods.php';
// Verificar si se recibe un ID de venta válido en la solicitud GET
if (isset($_GET['id_venta'])) {
    $idVenta = $_GET['id_venta'];

    // Crear una instancia de la clase Methods
    $met = new Methods();

    // Obtener los detalles de la venta utilizando el método obtenerDetallesVenta
    $detalles = $met->obtenerDetallesVenta($idVenta);

    // Verificar si se obtuvieron los detalles de la venta correctamente
    if ($detalles) {
        // Los detalles de la venta se obtuvieron correctamente
        // Convertir los detalles de la venta a formato JSON
        $json_detalles = json_encode($detalles);

        // Devolver los detalles de la venta como una respuesta JSON
        header('Content-Type: application/json');
        echo $json_detalles;
    } else {
        // Enviar una respuesta de error si no se pudieron obtener los detalles de la venta
        $response = array('success' => false, 'message' => 'No se pudieron obtener los detalles de la venta');
        echo json_encode($response);
    }
} else {
    // Enviar una respuesta de error si no se recibe un ID de venta válido
    $response = array('success' => false, 'message' => 'ID de venta no válido');
    echo json_encode($response);
}
?>