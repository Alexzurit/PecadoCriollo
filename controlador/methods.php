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
}

?>