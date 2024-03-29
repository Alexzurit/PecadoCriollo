<?php
include 'conexion.php';

class methods extends Conexion{
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
}

?>