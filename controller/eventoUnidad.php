<?php
require_once("../config/conexion.php");
require_once("../models/Evento.php");
require_once("../models/Categoria.php");
require_once("../models/Unidad.php");
require_once("../models/Estado.php");
require_once("../models/EventoUnidad.php");
require_once("../models/RegistroLog.php");

$evento = new Evento();
$categoria = new Categoria();
$unidad = new Unidad();
$estado = new Estado();
$eventoUnidad = new EventoUnidad();
$registroLog = new RegistroLog();
if (isset($_GET["op"])) {
    switch ($_GET["op"]) {

        case "insert_asignacion_unidades":
            $datos = $eventoUnidad->add_eventoUnidad(
                $_POST['ev_id'],
                $_POST['unid_id']
            );
            if ($datos == true) {
             $usu_id = $_SESSION["usu_id"];
            $unidad_data = $unidad->get_datos_unidad($_POST['unid_id']);
            $unidad_nom = $unidad_data[0]['unid_nom'];
            $ev_desc = "Se deriva a unidad: " . $unidad_nom;
            $evento->insert_emergencia_detalle($_POST['ev_id'], $usu_id, $ev_desc);
                echo 1;
            } else {
                echo 0;
            }
            $registroLog->add_log_registro($_SESSION['usu_id'],$_GET['op'],"evento id:{$_POST['ev_id']} unid:{unid_id}");
            break;
            
        case "update_asignacion_evento":
            $datos = $eventoUnidad->update_asignacion_evento($_POST['ev_id'],$_POST['unid_id']);
            
            if ($datos == true) {

            $usu_id = $_SESSION["usu_id"];
            $unidad_data = $unidad->get_datos_unidad($_POST['unid_id']);
            $unidad_nom = $unidad_data[0]['unid_nom'];
            $ev_desc = "Se actualiza la unidad: " . $unidad_nom;
            $evento->insert_emergencia_detalle($_POST['ev_id'], $usu_id, $ev_desc);
                echo 1;
            } else {
                echo 0;
            }
            $registroLog->add_log_registro($_SESSION['usu_id'],$_GET['op'],"evento id:{$_POST['ev_id']} unid:{$_POST['unid_id']}");
            break;

        case "get_datos_eventoUnidad":
            $datos = $eventoUnidad->get_datos_eventoUnidad($_POST['ev_id']);
            if (is_array($datos) == true and count($datos) > 0){
                
                echo json_encode($datos);
 
             } else {
                echo json_encode(['error' => 'No hay unidades para este evento']);
             }
            break;

        case "reporte_actualizacion" :
            $datos = $eventoUnidad->add_reporte_cambio_unidad(
                $_POST['ev_id'],
                $_POST['str_antiguo'],
                $_POST['str_nuevo'],
                $_POST['fec_cambio']
            );
            if ($datos == true) {
                echo 1;
            } else {
                echo 0;
            }
            $registroLog->add_log_registro($_SESSION['usu_id'],$_GET['op'],"Actualizar evento id:{$_POST['ev_id']} Nombre:{$_POST['str_antiguo']} a {$_POST['str_nuevo']}");
            break;
        
        case "delete_unidad":
            $datos = $eventoUnidad->delete_unidad($_POST['ev_id'],$_POST['unid_id']);
            if ($datos == true){
            $usu_id = $_SESSION["usu_id"];
            $unidad_data = $unidad->get_datos_unidad($_POST['unid_id']);
            $unidad_nom = $unidad_data[0]['unid_nom'];
            $ev_desc = "Se ha eliminado la unidad: " . $unidad_nom;
            $evento->insert_emergencia_detalle($_POST['ev_id'], $usu_id, $ev_desc);
                echo 1;
            } else {
                echo 0;
            }
            $registroLog->add_log_registro($_SESSION['usu_id'],$_GET['op'],"Eliminar unidad:{$_POST['unid_id']} de evento id {$_POST['ev_id']}");
            break;


    }
    
}
