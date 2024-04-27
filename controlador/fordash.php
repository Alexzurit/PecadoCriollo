<?php
include 'conexion.php';

class fordash extends Conexion{
    public function cantidadMC () {
        $conexion = parent::conectar();
        if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
        }
        
        // Consulta para obtener datos de ventas
        $sql = "SELECT 
                    DAYNAME(tv.fecha_venta) AS dia_semana,
                    SUM(CASE WHEN tpl.nombre_platos = 'CARTAS' THEN tdv.cantidad_vendida ELSE 0 END) AS cartas,
                    SUM(CASE WHEN tpl.nombre_platos = 'MENU' THEN tdv.cantidad_vendida ELSE 0 END) AS menu
                FROM
                    tb_ventas AS tv
                    INNER JOIN tb_detalle_venta AS tdv ON tv.id_venta = tdv.id_venta
                    INNER JOIN tb_producto AS tp ON tdv.id_producto = tp.id_producto
                    INNER JOIN tb_platos AS tpl ON tp.tipo_plato = tpl.id_platos
                WHERE
                    tv.estado_venta = 'APROBADO'
                    AND tv.fecha_venta >= CURDATE() - INTERVAL 7 DAY -- Fecha actual menos 7 días
                GROUP BY
                    dia_semana
                ORDER BY
                    FIELD(dia_semana, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";

        $resultado = $conexion->query($sql);
        
        $data = []; // Array para almacenar los datos
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $data[] = [
                    'dia_semana' => $row['dia_semana'],
                    'cartas' => $row['cartas'],
                    'menu' => $row['menu']
                ];
            }
        }
        $conexion->close();
        return $data;
    }
    
    public function montoRecaudado(){
        $conexion = parent::conectar();
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        // Establecer el idioma en español
        $conexion->query("SET lc_time_names = 'es_ES'");

        // Consulta para obtener el monto recaudado en los últimos 7 días
        $sql = "SELECT
                    DATE_FORMAT(fecha_venta, '%W') AS dia_semana,
                    SUM(total_venta) AS total_venta_recaudado
                FROM 
                    tb_ventas
                WHERE 
                    estado_venta = 'APROBADO'
                    AND fecha_venta >= CURDATE() - INTERVAL 7 DAY -- Fecha actual menos 7 días
                GROUP BY
                    dia_semana
                ORDER BY
                    FIELD(dia_semana, 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo')";

        $resultado = $conexion->query($sql);

        $data = [];
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $data[] = [
                    'dia_semana' => $row['dia_semana'],
                    'total_venta_recaudado' => $row['total_venta_recaudado']
                ];
            }
        }
        $conexion->close();
        return $data;
    }
    
    public function montoMeses() {
        $conexion = parent::conectar();
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }
        $conexion->query("SET lc_time_names = 'es_ES'");
        $sql = "SELECT
                DATE_FORMAT(fecha_venta, '%M') AS nombre_mes,
                SUM(total_venta) AS total_venta_recaudado
            FROM 
                tb_ventas
            WHERE 
                estado_venta = 'APROBADO'
                AND (
                    (MONTH(fecha_venta) >= MONTH(CURDATE()) AND YEAR(fecha_venta) = YEAR(CURDATE()))
                    OR 
                    (MONTH(fecha_venta) <= MONTH(CURDATE()) AND YEAR(fecha_venta) = YEAR(CURDATE()))
                )
            GROUP BY
                MONTH(fecha_venta)
            ORDER BY
                MONTH(fecha_venta) ASC;";
        
        $resultado = $conexion->query($sql);
        
        $data = [];
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $data[] = [
                    'nombre_mes' => $row['nombre_mes'],
                    'total_venta_recaudado' => $row['total_venta_recaudado']
                ];
            }
        }
        $conexion->close();
        return $data;
    }
    
    public function dayMont() {
        $conexion = parent::conectar();
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }
        
        $sql = "SELECT
                (SELECT SUM(total_venta) AS total_mes_actual
                FROM tb_ventas
                WHERE MONTH(fecha_venta) = MONTH(CURDATE()) 
                AND YEAR(fecha_venta) = YEAR(CURDATE())
                AND estado_venta = 'APROBADO') AS total_mes_actual,
                (SELECT SUM(total_venta) AS total_dia_actual
                FROM tb_ventas
                WHERE DATE(fecha_venta) = CURDATE() 
                AND estado_venta = 'APROBADO') AS total_dia_actual;";
        
        $resultado = $conexion->query($sql);
        
        $data = [];
        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                // Reemplazar valores nulos por "no hay ventas"
                $total_mes_actual = $row['total_mes_actual'] !== null ? $row['total_mes_actual'] : "00.00";
                $total_dia_actual = $row['total_dia_actual'] !== null ? $row['total_dia_actual'] : "00.00";
                $data[] = [
                    'total_mes_actual' => $total_mes_actual,
                    'total_dia_actual' => $total_dia_actual
                ];
            }
        }
        $conexion->close();
        return $data;
    }

}
?>
