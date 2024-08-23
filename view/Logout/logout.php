<?php
require_once("../../config/conexion.php");
session_destroy();
$conexion = new Conectar;
header("Location:". $conexion->ruta());
exit();
?>
