<?php
require_once './vendor/autoload.php';
require_once './ventasPlantilla.php';
//configurar Ticket para impresion
$mpdfConfig=array(
    'format'=>[80,350],
    'default_font_size'=>0,
    'default_font'=>'',
    'margin_left'=>1,
    'margin_right'=>1,
    'margin_top'=>1,
    'orientation'=>'P'
    
);
//FIN

$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$css= file_get_contents("css/stylecmpte.css");//Para agregar estilos
$plantilla = getPlantilla($carrito);
$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);

//$mpdf->output("miarchivopdf","D(descarga de manera local) o I(se visualiza)")
$mpdf->output("miarchivopdf","I");
?>

