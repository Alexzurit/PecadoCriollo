<?php
include '../methods.php';
$met = new methods();

$mesa = $met->listarMesas();

if($mesa !== false){
    header('Content-Type: application/json'); // Establece el encabezado como JSON
    echo json_encode($mesa);
}else{
    echo json_encode(['error' => 'Error al recuperar las categorías']);
}
?>


