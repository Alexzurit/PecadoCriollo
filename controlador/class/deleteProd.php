<?php
include '../methods.php';

$id = $_POST['codigo'];

$delete = new methods();

if($delete->eliminarConCodigo($id)){
    echo "success";
}
?>
