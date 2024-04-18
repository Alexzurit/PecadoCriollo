<?php
include '../conexion.php';

// Verificar si se enviaron las fechas por el formulario
if (isset($_POST['fechaInicio'], $_POST['fechaFin'])) {
    $fechaInicio = $_POST['fechaInicio']. " 00:00:00";
    $fechaFin = $_POST['fechaFin']. " 23:59:59";

    // Verificar si la fecha de fin es menor que la fecha de inicio
    if (strtotime($fechaFin) < strtotime($fechaInicio)) {
        echo json_encode(array('error' => 'La fecha de fin no puede ser menor que la fecha de inicio'));
        exit(); // Detener la ejecución del script
    }
    // Establecer la conexión a la base de datos usando MySQLi
    $conexion = new Conexion();
    $conn = $conexion->conectar();

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta para obtener las ventas dentro del rango de fechas
    $query = "SELECT * FROM tb_ventas WHERE fecha_venta BETWEEN ? AND ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $fechaInicio, $fechaFin);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        // Obtener los resultados de la consulta
        $ventasFiltradas = $result->fetch_all(MYSQLI_ASSOC);

        // Ahora puedes utilizar $ventasFiltradas como necesites
        echo json_encode($ventasFiltradas);
    } else {
        // Manejar errores en la consulta SQL
        echo json_encode(array('error' => 'Error en la consulta SQL'));
    }

    // Cerrar la conexión a la base de datos al finalizar
    $conn->close();
} else {
    // Manejar el caso en el que no se reciben las fechas por el formulario
    echo json_encode(array('error' => 'Fechas no proporcionadas'));
}

?>