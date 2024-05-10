<?php
require_once("../../config/conexion.php");
session_destroy();
// Host
header("Location:". "http://emergencias.melipilla.cl/");
// Local
header("Location:". "http://localhost/sistema_emergencia/");
exit();
?>