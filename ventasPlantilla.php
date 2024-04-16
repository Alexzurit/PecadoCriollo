<?php
date_default_timezone_set('America/Lima'); // Configura la zona horaria a Lima, Perú

function getPlantilla($ticket){
    $detalles_venta = $ticket['detalles_venta'];
    
    if (empty($detalles_venta)) {
        return '<p>No se encontraron detalles de la venta.</p>';
    }
    // Obtener la fecha de venta del detalle de venta
    $fecha_venta_str = $detalles_venta['fecha_venta'];

    // Convertir la cadena de fecha a un objeto DateTime
    $fecha_venta = new DateTime($fecha_venta_str);

    // Formatear la fecha en el formato deseado (dd/mm/yyyy)
    $fecha_formateada = $fecha_venta->format('d/m/Y H:i');
    
    $contenido = '
    <body>
        <header>
            <div id="logo">
                <img src="image/logorst.jpg" width="100" height="90">
            </div>
            <div id="company" class="clearfix">
                <div><h2>Restaurant Cevicheria</h2></div>
                <div><h2>Pecado Criollo</h2></div>
                <div>HEH00000123</div>
                <div>Dirección</div>
                <div>Teléfono</div>
            </div>
            <br>
            <div id="project">
                <div><span>CLIENTE:XDXD</span></div>
            </div>
            <div id="project2">
                <div><span>FECHA:</span>' . $fecha_formateada . '</div> <!-- Ajustado para mostrar la fecha actual -->
            </div>
        </header>
        <main>
            <p>Platos escogidos</p>
            <table>
                <thead>
                    <tr>
                        <th class="qty">#</th>
                        <th class="qty">Producto</th>
                        <th class="desc">Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                        <th>Mesa</th>
                    </tr>
                </thead>
                <tbody>';

    $total = 0; // Variable para calcular el total de la venta
    foreach ($detalles_venta as $indice => $producto){
        $subtotal = $producto['precio_venta'] * $producto['cantidad_vendida']; // Calcular subtotal por producto
        $total += $subtotal; // Sumar al total de la venta
        $contenido .= '
                    <tr>
                        <td class="qty">' . ($indice + 1) . '</td>
                        <td class="qty">' . $producto['nombre_prod'] . '</td> <!-- Ajustado para coincidir con el nombre del campo en la consulta SQL -->
                        <td class="desc">' . $producto['cantidad_vendida'] . '</td>
                        <td class="total">' . $producto['precio_venta'] . '</td>
                        <td class="total">' . $subtotal . '</td>
                        <td class="total">' . $producto['id_mesa'] . '</td>
                    </tr>';
    }

    $contenido .= '
                    <tr>
                        <td class="qty" colspan="4"><strong>TOTAL</strong></td>
                        <td class="total"><strong>S/' . $total . '</strong></td>
                        <td class="total"></td>
                    </tr>
                </tbody> 
            </table>
            <p>Este no es un comprobante</p>
        </main>
        <footer>
        </footer>
    </body>';

    return $contenido;
}

?>
