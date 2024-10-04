<?php
require_once '../../vendor/autoload.php';
require_once '../../billing/Bill.php';
include '../methods.php';

//recibir el id
$dato = $_GET['dato'];
$rucDni = $_GET['ruc_dni'];
$razonSocial = $_GET['razon_social'];

//llamar a la clase methods (instanciar)

$met = new methods();

$detalle= $met->obtenerDetallesVenta($dato);

if (empty($detalle)) {
    // Manejar el caso en que no se encuentren detalles de la venta
    echo 'No se encontraron detalles de la venta para el ID especificado.';
    exit;
}
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
$css= file_get_contents("../../css/stylecmpte.css");//Para agregar estilos
$plantilla = getFactura($detalle,$rucDni, $razonSocial);
$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);

// Descargar o mostrar el PDF
$mpdf->Output("ticket_compra.pdf", "I"); // Mostrar el PDF en el navegador
?>