<?php
require_once '../../vendor/autoload.php';
require_once '../../ventasPlantilla.php';
include '../methods.php';

// Verificar si se proporcionaron los datos del ticket en la solicitud POST
if (!isset($_GET['ticket'])) {
    // Manejar el caso en que no se proporcionaron los datos del ticket
    echo 'Error: No se proporcionaron los datos del ticket.';
    exit;
}

// Decodificar los datos del ticket de la solicitud POST
$ticket = json_decode($_GET['ticket'], true);

// Verificar si los datos del ticket son válidos
if ($ticket === null ) {
    // Manejar el caso en que los datos del ticket no son válidos
    echo 'Error: Los datos del ticket no son válidos.'.$ticket;
    exit;
}
$met = new methods();
//$detalles_venta = $met->obtenerDetallesVenta($id_venta);
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
$css= file_get_contents("../../css/stylecmpte.css");//Para agregar estilos
//$plantilla = getPlantilla($detalles_venta);
$plantilla = getPlantilla($ticket);
$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);

// Descargar o mostrar el PDF
$mpdf->Output("ticket_compra.pdf", "I"); // Mostrar el PDF en el navegador
//header('Content-Type: application/json');
//echo json_encode($carrito);

?>