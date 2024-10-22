<?php
require_once("../config/conexion.php");
require_once("../models/Usuario.php");
require_once("../models/Seccion.php");
require_once("../models/Permisos.php");
Permisos::redirigirSiNoAutorizado();

$usuario = new Usuario();
$RegistroLog= new RegistroLog();
$seccion = new Seccion();

header("Content-Type: application/json");

if (isset($_GET["op"])) {
    switch ($_GET["op"]) {
        case "get_secciones":
            $unidad = $_POST['unidad'];
            $datos = $seccion->get_secciones($unidad);
                $resultado = $datos;
                echo json_encode($resultado);
        break;
        case "lista_secciones_con_unidad":
            $datos = $seccion->lista_secciones_con_unidad();
                $resultado = $datos;
                echo json_encode($resultado);
        break;
       }
}
