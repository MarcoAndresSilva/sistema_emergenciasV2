<?php

require_once("../config/conexion.php");
require_once '../models/CierreMotivo.php';

$model = new CierreMotivo();

if (isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2)) {

if (isset($_GET["op"])) {
    switch ($_GET["op"]) {
        case 'add_cierre_motivo':
            $motivo = $_POST['motivo'];
            $result = $model->add_motivo_cierre($motivo);
            echo json_encode($result);
            break;

        case 'update_cierre_motivo':
            $motivo = $_POST['motivo_original'];
            $motivo_rename = $_POST['motivo_edit'];
            $mov_id= $_POST['mov_id'];
            $result = $model->update_motivo_cierre($motivo_rename,$mov_id );
            echo json_encode($result);
            break;

        case 'delete_cierre_motivo':
            $mov_id = $_POST['mov_id'];
            $result = $model->delete_motivo_cierre($mov_id);
            echo json_encode($result);
            break;

        case 'get_cierre_motivo':
            $result = $model->get_motivo_cierre();
            echo json_encode($result);
            break;

        case 'asociar_motivos_categoria':
            $data = json_decode(file_get_contents('php://input'), true);
        
            if (isset($data['categorias']) && is_array($data['categorias'])) {
                $mov_id = $data['mov_id'];
                $categorias = $data['categorias'];
                $result = $model->update_motivo_cate($mov_id, $categorias);
            } else {
                $result = array(
                    "error" => "El parámetro 'categorias' no es un array",
                    "datos_recibidos" => $data
                );
            }
            echo json_encode($result);
            break;
        case 'get_cierre_motivo_categoria':
            $result = $model->get_motivo_categoria();
            echo json_encode($result);
            break;
        default:
            echo json_encode(['error' => 'Operación no reconocida']);
            break;
    }
}
}
