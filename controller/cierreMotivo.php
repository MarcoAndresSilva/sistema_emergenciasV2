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
            if ($result === true){
                $response = array("status" => "success", "message" => "El motivo de cierre se agregó correctamente.");
            } else {
                $response = array("status" => "error", "message" => $result);
            }
            echo json_encode($response);
            break;


        case 'update_cierre_motivo':
            $motivo = $_POST['motivo'];
            $id_mov = $_POST['id_mov'];
            $result = $model->update_motivo_cierre($motivo, $id_mov);
            echo json_encode($result);
            break;

        case 'delete_cierre_motivo':
            $id_mov = $_POST['id_mov'];
            $result = $model->delete_motivo_cierre($id_mov);
            echo json_encode($result);
            break;

        case 'get_cierre_motivo':
            $result = $model->get_motivo_cierre();
            echo json_encode($result);
            break;

        default:
            echo json_encode(['error' => 'Operación no reconocida']);
            break;
    }
}
}
