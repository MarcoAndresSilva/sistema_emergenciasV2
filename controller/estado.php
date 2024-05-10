<?php
require_once("../config/conexion.php");
require_once("../models/Estado.php");
$estado = new Estado();

if (isset($_GET["solicitud"])) {
    switch ($_GET["solicitud"]) {
        case "get_estado":
            $respuesta = $estado->get_estados();
            if (is_array($respuesta) == true and count($respuesta) > 0) {
                $html = "";
                foreach ($respuesta as $row) {
                    $html.= "<option value=". $row['est_id']. ">". $row['est_nom']. "</option>";
                }
                echo $html;
            }
        break;

        // Obtiene el estado de la emergencia
        case "estado_emergencia":
            $respuesta = $estado->get_datos_estado($_POST["ev_est"]);
            if (is_array($respuesta) == true and count($respuesta) > 0) {
                echo json_encode($respuesta);
            }
            break;
    }
}