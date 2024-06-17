<?php
require_once("../../config/conexion.php");
session_destroy();
// Host
header("Location:". "http://emergencias.melipilla.cl/");
// Local xammp
//header("Location:". "http://localhost/sistema_emergenciasV2/");
// Local docker
header("Location:". "http://localhost/");
exit();
?>
