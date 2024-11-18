<?php
require_once("../config/conexion.php");
require_once("../models/Unidad.php");
require_once("../models/Permisos.php");
Permisos::redirigirSiNoAutorizado();

$unidad = new Unidad();
if (isset($_GET["unidad"])) {
    switch ($_GET["unidad"]) {
        case "add_unidad":
            $datos = $unidad->add_unidad($_POST['unid_nom'],$_POST['unid_est']);
            
            if ($datos == true) {
                echo 1;
            } else {
                echo 0;
            }
            break;
        
        case "listar":
            $datos = $unidad->get_unidad();
            if (is_array($datos) == true and count($datos) > 0){
                echo json_encode($datos);
            } else {
                echo '<script> console.log(Error al obtener las unidades' . json_encode($datos) . ') </script>';
            }
            break;
        
        case "listar_disponible":
            $datos = $unidad->get_unidad_est($_POST['est_id']);
            if (is_array($datos) == true and count($datos) > 0){
                echo json_encode($datos);
            } else {
                echo '<script> console.log(Error al obtener las unidades'. json_encode($datos). ') </script>';
            }
            break;
        case "estado":
            $disponibilidad = $unidad->get_unidad_est($unid_est);
            if (is_array($datos) == true and count($datos) > 0) {
                $html = "";
                foreach ($datos as $row) {
                    $html .= "<option value=" . $row['unid_id'] . ">" . $row['unid_nom'] . "</option>";
                }
                echo $html;
            }
            break;
        case "estado-unidades":
            $unidades_disponibles = 0;
            $unidades_fuera = 0;
            $unidades_no_disponibles = 0;
            $unidades_ext = 0;
            $porcentaje_unidades_totales = 0;
            $porcentaje_unidades_disponibles = 0;
            $porcentaje_unidades_no_disponibles = 0;
            $datos = $unidad->get_unidad();
            if (is_array($datos) && count($datos) > 0){
                foreach ($datos as $row) {
                    if($row['unid_est'] === 1){
                        $unidades_disponibles ++;
                    }else if ($row['unid_est'] === 2){
                        $unidades_fuera ++;
                    }else if ($row['unid_est'] === 3) {
                        $unidades_no_disponibles ++;
                    }else {
                        $unidades_ext ++;
                    }
                }
            }
            $porcentaje_unidades_totales = $unidades_disponibles + $unidades_no_disponibles;
            if($porcentaje_unidades_totales != 0){
                if($unidades_disponibles != 0){
                    $porcentaje_unidades_disponibles +=  round(($unidades_disponibles / $porcentaje_unidades_totales )* 100);
                }
                if($unidades_no_disponibles != 0){
                    $porcentaje_unidades_no_disponibles += round(($unidades_no_disponibles / $porcentaje_unidades_totales )*100);
                }
            }else{
                $porcentaje_unidades_disponibles = 0;
                $porcentaje_unidades_no_disponibles = 0;
            }
            
            
            
            $respuesta = array(
                'unidades_disponibles' => $unidades_disponibles,
                'unidades_fuera' => $unidades_fuera,
                'unidades_no_disponibles' => $unidades_no_disponibles,
                'unidades_ext' => $unidades_ext,
                'porcentaje_unidades_disponibles' => $porcentaje_unidades_disponibles,
                'porcentaje_unidades_no_disponibles' => $porcentaje_unidades_no_disponibles
            );
            echo json_encode($respuesta);
            break;
        
        case "datos_unidad":
            $datos = $unidad->get_datos_unidad($_POST['unid_id']);
            if (is_array($datos) == true and count($datos) > 0){
                echo json_encode($datos);
            } else {
                echo '<script> console.log(Error al obtener las unidades'. json_encode($datos). ') </script>';
            }
            break;
    }
}

if (isset($_GET["op"])) { 
  switch ($_GET["op"]) {

   case 'add_unidad':
        $unid_nom = $_POST["unid_nom"];
        $unid_est = $_POST["unid_est"];

        $resultado = $unidad->add_unidad($unid_nom, $unid_est);

        // Verificar el resultado y construir la respuesta JSON en base al status y message
        if (!empty($resultado)) {
            $json = array(
                "status" => $resultado['status'],  // Utiliza el status devuelto por la función
                "message" => $resultado['message'] // Utiliza el message devuelto por la función
            );
        } else {
            $json = array(
                "status" => "error",
                "message" => "No se encontraron datos de unidades."
            );
        }
        header('Content-Type: application/json');
        echo json_encode($json);
    break;
        
    case 'get_unidad':
        $resultado = $unidad->get_unidad();
        if (!empty($resultado)) {
            $json = array(
            "status" => "success",
            "result" => $resultado
            );
        } else {
            $json = array(
            "status" => "error",
            "message" => "No se encontraron datos de unidades."
            );
        }
        header('Content-Type: application/json');
        echo json_encode($json);
    break;

    case 'delete_unidad':
        $unid_id = $_POST["unid_id"];
        $resultado = $unidad->delete_unidad($unid_id);
        
        // Verificar el resultado y construir la respuesta JSON en base al status y message
        if (!empty($resultado)) {
            $json = array(
            "status" => $resultado['status'],  // Utiliza el status devuelto por la función
            "message" => $resultado['message'] // Utiliza el message devuelto por la función
            );
        } else {
            $json = array(
            "status" => "error",
            "message" => "No se encontraron datos de unidades."
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($json);
    break;
    case 'update_unidad':
        $unid_id = $_POST["unid_id"];
        $unid_nom = $_POST["unid_nom"];
        $unid_est = $_POST["unid_est"];

        $resultado = $unidad->update_unidad($unid_id, $unid_nom, $unid_est);

        // Verificar el resultado y construir la respuesta JSON en base al status y message
        if (!empty($resultado)) {
        $json = array(
            "status" => $resultado['status'],  // Utiliza el status devuelto por la función
            "message" => $resultado['message'] // Utiliza el message devuelto por la función
        );
        } else {
        $json = array(
            "status" => "error",
            "message" => "No se encontraron datos de unidades."
        );
        }
        header('Content-Type: application/json');
        echo json_encode($json);
    break;
  }
}
