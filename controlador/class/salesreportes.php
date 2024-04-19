<?php
include '../methods.php';

// Verificar si se han proporcionado fechaInicio y fechaFin en la solicitud POST
if (isset($_POST['fechaInicio'], $_POST['fechaFin'])) {
    // Definir las fechas de inicio y fin
    $fechaInicio = $_POST['fechaInicio'] . " 00:00:00";
    $fechaFin = $_POST['fechaFin'] . " 23:59:59";

    // Instanciar la clase methods
    $met = new methods();

    // Obtener el reporte
    $reporte = $met->salesReport($fechaInicio, $fechaFin);

    // Verificar si se obtuvieron los detalles de la venta correctamente
    if ($reporte) {
        // Los detalles de la venta se obtuvieron correctamente
        // Convertir los detalles de la venta a formato JSON
        $json_reporte = json_encode($reporte);

        // Devolver los detalles de la venta como una respuesta JSON
        header('Content-Type: application/json');
        echo $json_reporte;
    } else {
        // Enviar una respuesta de error si no se pudieron obtener los detalles de la venta
        $response = array('success' => false, 'message' => 'No se pudieron obtener los detalles de la venta');
        echo json_encode($response);
    }
} else {
    // Enviar una respuesta de error si no se proporcionaron fechaInicio y fechaFin
    $response = array('success' => false, 'message' => 'No se proporcionaron fechaInicio y/o fechaFin');
    echo json_encode($response);
}
?>
