<?php
include 'conexion.php';

class methods extends Conexion{
    
    public function login($usuario, $password) {
            $conexion = parent::conectar();
            $sql = "SELECT * FROM tb_usuarios WHERE usuario = '$usuario'";
            $respuesta = mysqli_query($conexion, $sql);

            if ($respuesta && mysqli_num_rows($respuesta) > 0) {
                $datosUsuario = mysqli_fetch_assoc($respuesta);
                $passwordExistente = $datosUsuario['clave'];

                if (password_verify($password, $passwordExistente)) {
                    // Almacena información adicional en la sesión si es necesario
                    $_SESSION['id_usuario'] = $datosUsuario['id_usuario'];
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['nombres'] = $datosUsuario['nombres']; // Reemplaza 'nombre' con el nombre de tu columna
                    // Puedes agregar más información aquí siguiendo el mismo patrón
                    $_SESSION['apellidos'] = $datosUsuario['apellidos'];
                    $_SESSION['rol'] = $datosUsuario['rol'];
                    return true;
                } else {
                    return false; // Contraseña incorrecta
                }
            } else {
                return false; // Usuario no encontrado en la base de datos
            }
        }
    
    public function listarTipos(){
        $conexion = parent::conectar();
        
        // Consulta SQL para seleccionar todos los registros de la tabla 'categoria'
        $sql = "SELECT * FROM tb_platos";
        
        // Ejecutar la consulta
        $resultado = $conexion->query($sql);
        
        if ($resultado) {
            // Comprobar si hay al menos un registro
            if ($resultado->num_rows > 0) {
                // Crear un array para almacenar los resultados
                $tipos = array();

                // Recorrer los resultados y almacenarlos en el array
                while ($fila = $resultado->fetch_assoc()) {
                    $tipos[] = $fila;
                }

                // Devolver el array de categorías
                return $tipos;
            } else {
                // No se encontraron registros
                return array();
            }
        } else {
            // Si ocurre un error en la consulta
            return false;
        }
    }
    
    public function listarMesas(){
        $conexion = parent::conectar();
        // Consulta SQL para seleccionar todos los registros de la tabla 'categoria'
        $sql = "SELECT * FROM tb_mesas";
        // Ejecutar la consulta
        $resultado = $conexion->query($sql);
        if ($resultado) {
            // Comprobar si hay al menos un registro
            if ($resultado->num_rows > 0) {
                // Crear un array para almacenar los resultados
                $tipos = array();
                // Recorrer los resultados y almacenarlos en el array
                while ($fila = $resultado->fetch_assoc()) {
                    $tipos[] = $fila;
                }
                // Devolver el array de categorías
                return $tipos;
            } else {
                // No se encontraron registros
                return array();
            }
        } else {
            // Si ocurre un error en la consulta
            return false;
        }
    }
    
    public function registrarProducto($nombre_prod,$tipo_plato,$precio_prod){
        $conexion = parent::conectar();
        $sql = "INSERT INTO tb_producto VALUES (NULL,'$nombre_prod','$tipo_plato','$precio_prod')";
        if ($conexion->query($sql) === TRUE) {
            //echo 'El registro se guardó correctamente.';
            return true;
        } else {
            //echo 'Error al guardar el registro: ' . $conexion->error;
            return false;
        }
        $conexion->close();
    }
    
    public function actualizarPro($nombre_prod,$tipo_plato,$precio_prod,$codigo){
        $conexion = parent::conectar();
        $sql = "UPDATE tb_producto SET nombre_prod='$nombre_prod', tipo_plato='$tipo_plato', precio_prod='$precio_prod' WHERE id_producto='$codigo' ";
        if ($conexion->query($sql) === TRUE) {
            //echo 'El registro se guardó correctamente.';
            return true;
        } else {
            //echo 'Error al guardar el registro: ' . $conexion->error;
            return false;
        }
        $conexion->close();
    }
    
    //Actualizar Estado de VENTA
    public function modificarEstado($id_venta){
        $conexion = parent::conectar();
        //Verificamos el estado actual de venta
        $sqlVerified = "SELECT estado_venta FROM tb_ventas WHERE id_venta = ?;";
        $sqlVerified = $conexion->prepare($sqlVerified);
        $sqlVerified->bind_param("i", $id_venta);
        $sqlVerified->execute();
        $sqlVerified->bind_result($estadoActual);
        $sqlVerified->fetch();
        $sqlVerified->close();

        if ($estadoActual === "CANCELADO") {
            $response = array("status" => "CANCELADO", "message" => "Esta Venta ya está anulada.");
        } else {
            $estado = "CANCELADO";
            $sql = "UPDATE tb_ventas SET estado_venta = ? WHERE id_venta=?;";
            // Preparar la consulta
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("si", $estado,$id_venta);
            // // Ejecutar la consulta
            if ($stmt->execute()){
                $response = array("status"=>"success","message"=>"Anulado Correctamente.");
            } else {
                $response = array("status"=>"error","message"=>"No se pudo Anular.");
            }
            $stmt->close(); //Cerramos la declaración
        }
        $conexion->close(); //Cerramos la conexion
        header('Content-Type: application/json');
        echo json_encode($response);
        
        // $conexion = parent::conectar();
        // $estado = "CANCELADO";
        // $sql = "UPDATE tb_ventas SET estado_venta = ? WHERE id_venta=?;";
        // // Preparar la consulta
        // $stmt = $conexion->prepare($sql);

        // // Bind de parámetros
        // $stmt->bind_param("si", $estado,$id_venta);

        // // Ejecutar la consulta
        // if($stmt->execute()){
        //     $response = array("status"=>"success","message"=>"Anulado Correctamente.");
        // }else{
        //     $response = array("status"=>"error","message"=>"No se pudo Anular.");
        // }

        // $stmt->close(); //Cerramos la declaración
        // $conexion->close(); //Cerramos la conexion
        // header('Content-Type: application/json');
        // echo json_encode($response);
    }
    //FIN 

    public function eliminarConCodigo($id) {
        $conexion = parent::conectar();
        $sql = "DELETE FROM tb_producto WHERE id_producto =?";
        $query = $conexion->prepare($sql);
        $query->bind_param('i', $id);
        
        $resultado =$query->execute();
        // Cierra la conexión
        $conexion->close();
        return $resultado;
    }
    
    public function listPro(){
        $resultados = array();
        try {
            $conexion = parent::conectar();
            $sql = "SELECT *FROM tb_producto";
            
            $stmt = $conexion->prepare($sql);
            if ($stmt->execute()) {
                $stmt->bind_result($id_producto, $nombre_prod, $tipo_plato, $precio_prod);

                while ($stmt->fetch()) {
                    
                    $resultados[] = array(
                        'id_producto' => $id_producto,
                        'nombre_prod' => $nombre_prod,
                        'tipo_plato' => $tipo_plato,
                        'precio_pro' => $precio_prod,
                    );
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        } finally {
            $stmt->close();
            $conexion->close();
        }
        // Devolver los resultados en formato JSON
        header('Content-Type: application/json');
        echo json_encode($resultados);
    }
    
    public function listFiltro($tipo) {
    try {
        $conexion = parent::conectar();
        // Consulta SQL para obtener los productos filtrados por tipo de plato
        $sql = "SELECT * FROM tb_producto WHERE tipo_plato = ?";
        
        // Preparar la consulta
        $pstm = $conexion->prepare($sql);
        
        // Vincular parámetros
        $pstm->bind_param('s', $tipo);
        
        // Ejecutar la consulta
        $pstm->execute();
        
        // Obtener resultados
        $resultado = $pstm->get_result();
        
        if ($resultado->num_rows > 0) {
            $productosFiltrados = array();
            // Recorre los resultados y los agrega al array de productos filtrados
            while ($fila = $resultado->fetch_assoc()) {
                $productosFiltrados[] = $fila;
            }
            
            // Devuelve los productos filtrados en formato JSON
            echo json_encode($productosFiltrados);
        } else {
            // Si no se encontraron resultados, devuelve un mensaje de error en formato JSON
            echo json_encode(array('error' => 'No se encontraron productos para el tipo de plato especificado.'));
        }
    } catch (Exception $ex) {
        // Manejar la excepción
        echo json_encode(array('error' => $ex->getMessage()));
    } finally {
        // Cierra la conexión a la base de datos
        $conexion->close();
    }
}

public function transaction($carrito, $id_usuario) {
    // Obtener conexión desde la clase padre
    $conexion = parent::conectar();
    
    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    
    // Iniciar transacción
    $conexion->begin_transaction();
    
    try {
        // Verificar si $carrito es un array y si tiene al menos un elemento
        if (!is_array($carrito) || empty($carrito)) {
            $carrito_info = json_encode($carrito);
            throw new Exception("El carrito está vacío o no tiene el formato esperado. datos del carro".$carrito_info);
        }
        $id_mesa = $carrito[0]['mesa'];
        // Crear la venta en la tabla tb_ventas
        $sql_venta = "INSERT INTO tb_ventas (id_usuario, id_mesa, fecha_venta, total_venta)
                      VALUES (?, ?, DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), 0)";
        
        $stmt_venta = $conexion->prepare($sql_venta);
        
        // Recuperar el ID de mesa del primer producto en el carrito
        //$id_mesa = $carrito[0]['mesa']; // Suponiendo que todos los productos en el carrito comparten la misma mesa

        $stmt_venta->bind_param("ii", $id_usuario, $id_mesa);
        
        
        if ($stmt_venta->execute()) {
            // Obtener el ID de la venta recién insertada
            $id_venta = $stmt_venta->insert_id;

            // Calcular el total de la venta sumando los subtotales de los productos
            $total_venta = 0;

            // Preparar la consulta para insertar detalles de la venta
            $sql_detalle = "INSERT INTO tb_detalle_venta (id_venta, id_producto, cantidad_vendida, precio_venta, subtotal, descripcion)
                            VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt_detalle = $conexion->prepare($sql_detalle);
            $stmt_detalle->bind_param("iiidds", $id_venta, $id_producto, $cantidad_vendida, $precio_venta, $subtotal, $descripcion);

            // Recorrer el carrito y agregar los detalles de la venta a la tabla tb_detalle_venta
            foreach ($carrito as $producto) {
                // Verificar si el producto tiene las claves necesarias
                if (!isset($producto['codigo'], $producto['cantidad'], $producto['precio'])) {
                    throw new Exception("El producto en el carrito no tiene los datos necesarios.");
                }
                
                $id_producto = $producto['codigo'];
                $cantidad_vendida = $producto['cantidad'];
                $precio_venta = $producto['precio'];
                $subtotal = $cantidad_vendida * $precio_venta;

                //Estableceer descripcion
                //Si descripcion existe y es diferente de null entonces agregar el valor que hay en descripcion sino agrega sin detalles
                //isset para verificar la existencia en este caso sí existe cuando no hay nada y es una cadena vacia(''), por ello usaremos empty
                $descripcion = !empty($producto['descripcion']) && $producto['descripcion'] !== null ? $producto['descripcion'] : "sin detalles";
                // Ejecutar la consulta preparada para insertar detalles de la venta
                $stmt_detalle->execute();

                // Actualizar el total de la venta
                $total_venta += $subtotal;
            }

            // Actualizar el total de la venta en la tabla tb_ventas
            $sql_actualizar_total = "UPDATE tb_ventas SET total_venta = ? WHERE id_venta = ?";
            
            $stmt_actualizar_total = $conexion->prepare($sql_actualizar_total);
            $stmt_actualizar_total->bind_param("di", $total_venta, $id_venta);
            $stmt_actualizar_total->execute();

            // Confirmar la transacción
            $conexion->commit();
            //obtener ID venta recien insertada
            $id_venta = $stmt_venta->insert_id;
            $ticket = array(
            "id_venta" => $id_venta,
            "detalles_venta" => $this->obtenerDetallesVenta($id_venta)
            );
            // La compra se realizó con éxito
            $response = array("success" => true, "ticket" => $ticket);
        } else {
            // Error al insertar la venta en la tabla tb_ventas
            throw new Exception("Error al procesar la compra.");
        }
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conexion->rollback();
        $response = array("success" => false, "error" => $e->getMessage());
    }

    // Devolver la respuesta al cliente
    header('Content-Type: application/json');
    echo json_encode($response);

    // Cerrar conexión
    $conexion->close();
}

public function obtenerIdVenta($id_usuario) {
    // Obtener conexión desde la clase padre
    $conexion = parent::conectar();
    
    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Consulta SQL para obtener el último ID de venta realizada por el usuario
    $sql = "SELECT id_venta FROM tb_ventas WHERE id_usuario = ? ORDER BY id_venta DESC LIMIT 1";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);
    
    // Verificar la preparación de la consulta
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conexion->error);
    }

    // Bind de parámetros
    $stmt->bind_param("i", $id_usuario);
    
    // Ejecutar la consulta
    if (!$stmt->execute()) {
        die("Error al ejecutar la consulta: " . $stmt->error);
    }

    // Ejecutar la consulta
    //$stmt->execute();

    // Vincular el resultado
    $stmt->bind_result($id_venta);

    // Obtener el resultado
    if (!$stmt->fetch()) {
        // No se encontraron resultados
        return null;
    }

    // Cerrar la conexión y liberar recursos
    $stmt->close();
    $conexion->close();

    // Retornar el ID de la última venta realizada por el usuario
    echo $id_venta;
    return $id_venta;
}

public function obtenerDetallesVenta($id_venta) {
    // Obtener conexión desde la clase padre
    $conexion = parent::conectar();
    
    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Consulta SQL para obtener los detalles de la venta
    $sql = "SELECT v.id_venta, v.id_usuario, v.id_mesa, v.fecha_venta, v.estado_venta, -- Agregar estado_venta
                   dv.id_detalle, dv.id_producto, dv.cantidad_vendida, dv.precio_venta, dv.subtotal, dv.descripcion,
                   p.nombre_prod
            FROM tb_ventas v
            INNER JOIN tb_detalle_venta dv ON v.id_venta = dv.id_venta
            INNER JOIN tb_producto p ON dv.id_producto = p.id_producto
            WHERE v.id_venta = ?";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);

    // Bind de parámetros
    $stmt->bind_param("i", $id_venta);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();

    // Obtener los detalles de la venta como un arreglo asociativo
    $detalles_venta = $result->fetch_all(MYSQLI_ASSOC);

    // Cerrar la conexión y liberar recursos
    $stmt->close();
    $conexion->close();

    // Retornar los detalles de la venta
    return $detalles_venta;
}

public function salesReport($fechaInicio,$fechaFin){
    // Obtener conexión desde la clase padre
    $conexion = parent::conectar();
    
    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }
    
    $sql = "SELECT 
                total_ventas_aprobadas,
                total_ventas_canceladas,
                num_ventas_aprobadas,
                num_ventas_canceladas,
                num_ventas_realizadas,
                cartas_vendidas,
                menu_vendidos,
                bebidas_vendidas,
                licores_vendidos,
                vinos_vendidos
            FROM
                (SELECT SUM(total_venta) AS total_ventas_aprobadas
                FROM tb_ventas
                WHERE estado_venta = 'APROBADO'
                and fecha_venta BETWEEN ? AND ?) AS t1
            INNER JOIN
                (SELECT SUM(total_venta) AS total_ventas_canceladas
                FROM tb_ventas
                WHERE estado_venta = 'CANCELADO'
                and fecha_venta BETWEEN ? AND ?) AS t2 ON 1=1
            INNER JOIN
                (SELECT COUNT(*) AS num_ventas_aprobadas
                FROM tb_ventas
                WHERE estado_venta = 'APROBADO'
                and fecha_venta BETWEEN ? AND ?) AS t3 ON 1=1
            INNER JOIN
                (SELECT 
                    COUNT(*) AS num_ventas_canceladas
                FROM 
                    tb_ventas
                WHERE 
                    estado_venta = 'CANCELADO' AND
                    fecha_venta BETWEEN ? AND ?) AS t5 ON 1=1
            INNER JOIN
                (SELECT 
                    COUNT(*) AS num_ventas_realizadas
                FROM 
                    tb_ventas
                WHERE 
                    fecha_venta BETWEEN ? AND ?) AS t6 ON 1=1
            INNER JOIN
                (SELECT
                    SUM(CASE WHEN tpl.nombre_platos = 'CARTAS' THEN tdv.cantidad_vendida ELSE 0 END) AS cartas_vendidas,
                    SUM(CASE WHEN tpl.nombre_platos = 'MENU' THEN tdv.cantidad_vendida ELSE 0 END) AS menu_vendidos,
                    SUM(CASE WHEN tpl.nombre_platos = 'BEBIDAS' THEN tdv.cantidad_vendida ELSE 0 END) AS bebidas_vendidas,
                    SUM(CASE WHEN tpl.nombre_platos = 'LICORES' THEN tdv.cantidad_vendida ELSE 0 END) AS licores_vendidos,
                    SUM(CASE WHEN tpl.nombre_platos = 'VINOS' THEN tdv.cantidad_vendida ELSE 0 END) AS vinos_vendidos
                FROM
                    tb_ventas AS tv
                    INNER JOIN tb_detalle_venta AS tdv ON tv.id_venta = tdv.id_venta
                    INNER JOIN tb_producto AS tp ON tdv.id_producto = tp.id_producto
                    INNER JOIN tb_platos AS tpl ON tp.tipo_plato = tpl.id_platos
                WHERE
                    tv.estado_venta = 'APROBADO'
                    AND tv.fecha_venta BETWEEN ? AND ?) AS t4 ON 1=1;
            ";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ssssssssssss', $fechaInicio, $fechaFin, $fechaInicio, $fechaFin, $fechaInicio, $fechaFin, $fechaInicio, $fechaFin, $fechaInicio, $fechaFin, $fechaInicio, $fechaFin);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result) {
        $reporte = $result->fetch_assoc();
        return $reporte;
    } else {
        echo 'Error en la consulta SQL';
    }

    $stmt->close();
}

//Método para cambiar contraseña
public function cambiarPassword($usuario, $passwordActual, $nuevaPassword, $repetirPassword) {
    $conexion = parent::conectar();

    // Preparamos la consulta para evitar inyecciones SQL
    $sql = "SELECT * FROM tb_usuarios WHERE usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('s', $usuario); // 's' indica que el parámetro es una cadena de texto
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $datosUsuario = $resultado->fetch_assoc();
        $passwordExistente = $datosUsuario['clave'];

        // Verificar que la contraseña actual es correcta
        if (password_verify($passwordActual, $passwordExistente)) {
            // Verificar que la nueva contraseña y su repetición coinciden
            if ($nuevaPassword === $repetirPassword) {
                // Encriptar la nueva contraseña
                $nuevaPasswordHash = password_hash($nuevaPassword, PASSWORD_DEFAULT);

                // Preparamos la consulta de actualización
                $updateSql = "UPDATE tb_usuarios SET clave = ? WHERE usuario = ?";
                $stmtUpdate = $conexion->prepare($updateSql);
                $stmtUpdate->bind_param('ss', $nuevaPasswordHash, $usuario); // 'ss' indica dos cadenas de texto

                if ($stmtUpdate->execute()) {
                    return [
                        'status' => 'success',
                        'message' => 'Contraseña actualizada correctamente.'
                    ];
                } else {
                    return [
                        'status' => 'error',
                        'message' => 'Error al actualizar la contraseña.'
                    ];
                }
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Las nuevas contraseñas no coinciden.'
                ];
            }
        } else {
            return [
                'status' => 'error',
                'message' => 'La contraseña actual es incorrecta.'
            ];
        }
    } else {
        return [
            'status' => 'error',
            'message' => 'Usuario no encontrado.'
        ];
    }
}

}

?>