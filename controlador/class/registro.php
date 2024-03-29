<?php
session_start();
include '../methods.php';
$codigo = $_POST['codigo'];
$nombre_prod = $_POST['nombre'];
$tipo_plato = $_POST['tipo'];
$precio_prod = $_POST['precio'];

$met = new methods();

if($codigo==0){
    if($met->registrarProducto($nombre_prod, $tipo_plato, $precio_prod)){
        $_SESSION['EXITO'] = "¡Registro del Producto Exitoso!";
        header("Location:../../addprod.php");
    }else{
        //echo 'Registro erroneo';
        $_SESSION['ERROR'] = "¡Error al Registrar!";
        header("Location:../../addprod.php");
    }
}else{
    if($met->actualizarPro($nombre_prod, $tipo_plato, $precio_prod, $codigo)){
        $_SESSION['MENSAJE'] = "¡Modificacion Exitosa!";
        header("Location:../../listprod.php");
    }else{
        //echo 'Registro erroneo';
        $_SESSION['ERROR'] = "¡Error al Modificar!";
        header("Location:../../listprod.php");
    }
}

?>
