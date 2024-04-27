<?php
include '../fordash.php';

$metDash = new fordash();

$grafico = $metDash->cantidadMC();
header('Content-Type: application/json');
echo json_encode($grafico);
?>
