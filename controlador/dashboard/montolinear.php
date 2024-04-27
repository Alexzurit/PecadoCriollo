<?php
include '../fordash.php';

$metDash = new fordash();

$graficolineal = $metDash->montoRecaudado();
header('Content-Type: application/json');
echo json_encode($graficolineal);
?>