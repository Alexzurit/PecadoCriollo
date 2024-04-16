<?php
require_once './vendor/autoload.php';
require_once './ventasPlantilla.php';

$mpdf = new \Mpdf\Mpdf();
$css= file_get_contents("css/stylecmpte.css");//Para agregar estilos
$plantilla = getPlantilla();
$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);

//$mpdf->output("miarchivopdf","D(descarga de manera local) o I(se visualiza)")
$mpdf->output("miarchivopdf","I");
?>