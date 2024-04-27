<?php
include '../fordash.php';

$metDash = new fordash();

$graficoDiaMes = $metDash->dayMont();
header('Content-Type: application/json');

// Verificar si hay datos en $graficoDiaMes
echo empty($graficoDiaMes) ? json_encode("no hay ventas") : json_encode($graficoDiaMes);
?>