<?php
include '../fordash.php';

$metDash = new fordash();

$graficomes = $metDash->montoMeses();
header('Content-Type: application/json');
echo json_encode($graficomes);
?>