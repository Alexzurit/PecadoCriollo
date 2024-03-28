<?php
include '../methods.php';
$met = new methods();

$categ = $met->listarTipos();

if($categ !== false){
    header('Content-Type: application/json'); // Establece el encabezado como JSON
    echo json_encode($categ);
}else{
    echo json_encode(['error' => 'Error al recuperar las categorías']);
}
?>

