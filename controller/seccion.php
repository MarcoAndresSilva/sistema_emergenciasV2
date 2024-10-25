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
        case "update_seccion":
            $id_seccion = $_POST['id'];
            $nombre = $_POST['nombre'];
            $id_unidad = $_POST['unidad'];
            $detalle = $_POST['detalle'];
            $datos = $seccion->update($id_seccion, $nombre, $detalle,$id_unidad);
            echo json_encode($datos);
        break;
        case "info_seccion":
            $id_seccion = $_POST['id'];
            $datos = $seccion->get_seccion($id_seccion);
            echo json_encode($datos);
        break;
        case "agregar_seccion":
            $unidad = $_POST['unidad'];
            $nombre = $_POST['nombre'];
            $detalle = $_POST['detalle'];
            $datos = $seccion->add_seccion($nombre, $detalle, $unidad);
            echo json_encode($datos);
        break;
        case "eliminar_seccion":
            $id_seccion = $_POST['id'];
            $result = $seccion->delete_seccion($id_seccion);
            echo json_encode($result);
        break;
        }
}
