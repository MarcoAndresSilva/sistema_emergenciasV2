<?php
require_once("../config/conexion.php");
require_once("../models/NivelPeligro.php");
$nivelPeligro = new NivelPeligro();

if (isset($_GET["op"])) {
    switch ($_GET["op"]) {
        case "get_nivel_por_id":
            $datos = $nivelPeligro->get_nivel_por_id($ev_niv_id);

            if (is_array($datos) == true and count($datos) > 0) {
                $html = "";
                foreach ($datos as $row) {
                    $html .= "<option value=" . $row['ev_niv_id'] . ">" . $row['ev_niv_nom'] . "</option>";
                }
                echo $html;
            }
            break;
            
        case "get_nivel_peligro":
            $peligros = $nivelPeligro->get_nivel_peligro();

            if (is_array($peligros) == true and count($peligros) > 0) {
                $html = "";
                foreach ($peligros as $row) {
                    $html .= "<option value=" . $row['ev_niv_id'] . ">" . $row['ev_niv_nom'] . "</option>";
                }
                echo $html;
            }
            break;
        case "get_nivel_peligro_json":
            $peligros = $nivelPeligro->get_nivel_peligro();

            if (is_array($peligros) == true and count($peligros) > 0) {
                $peligros = json_encode($peligros);
                echo $peligros;
            }
        break;
    }
}