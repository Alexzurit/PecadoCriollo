<?php
include '../methods.php';

//$tipo = $_POST['tipo'];
$tipo = $_GET['tipo_plato'];
$met = new methods();

$met->listFiltro($tipo);

?>
