<?php
date_default_timezone_set('America/Lima'); // Configura la zona horaria a Lima, Perú

function getPlantilla2($ticket){
    // Obtener la fecha de venta del primer detalle de venta (suponiendo que la fecha es la misma para todos los detalles)
    $fecha_venta_str = $ticket[0]['fecha_venta'];
    
    // Convertir la cadena de fecha a un objeto DateTime
    $fecha_venta = new DateTime($fecha_venta_str);

    // Formatear la fecha en el formato deseado (dd/mm/yyyy)
    $fecha_formateada = $fecha_venta->format('d/m/Y H:i');
    
    $contenido = '
    <body>
        <header>
            <div id="logo">
                <img src="../../image/logorst.png" width="100" height="100">
            </div>
            <div id="company" class="clearfix">
                <div><h2>Restaurante-Cevicheria</h2></div>
                <div><h2>Pecado Criollo</h2></div>
            </div>
            <br>
            <div id="company">
                <div><span>FECHA:</span>' . $fecha_formateada . '</div> <!-- Ajustado para mostrar la fecha actual -->
                <br>
                <div>Encuéntranos en</div>
                <div>Av. Pacasmayo Mz B lote 11-A. Urb. Los Robles de Sta. Rosa - SMP - Lima</div>
                <div>915 057 162</div>
            </div>
        </header>
        <main>
            <p>Platos escogidos <strong>MESA ' . $ticket[0]['id_mesa'] . '</strong></p>
            <table>
                <thead>
                    <tr>
                        <th class="qty">#</th>
                        <th class="qty">Producto</th>
                        <th class="desc">Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>';

    $total = 0; // Variable para calcular el total de la venta

    // Iterar sobre los detalles de la venta
    foreach ($ticket as $indice => $producto){
        $subtotal = $producto['precio_venta'] * $producto['cantidad_vendida']; // Calcular subtotal por producto
        $total += $subtotal; // Sumar al total de la venta
        $contenido .= '
                    <tr>
                        <td class="qty">' . ($indice + 1) . '</td>
                        <td class="desc">' . $producto['nombre_prod'] . '</td>
                        <td class="qty">' . $producto['cantidad_vendida'] . '</td>
                        <td class="total">' . $subtotal . '</td>
                    </tr>';
    }

    $contenido .= '
                    <tr>
                        <td class="qty" colspan="3"><strong>TOTAL</strong></td>
                        <td class="total"><strong>S/' . $total . '</strong></td>
                    </tr>
                </tbody> 
            </table>
            <div id="company">
            <p>Gracias por elegir nuestro restaurante para tu comida ¡Esperamos verte pronto!</p>
            </div>
            <div id="company">
            <b>¡Este no es un comprobante de pago!</b>
            </div>
        </main>
        <footer>
        </footer>
    </body>';

    return $contenido;
}

?>
