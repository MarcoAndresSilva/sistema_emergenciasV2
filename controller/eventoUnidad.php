<?php
require_once("../config/conexion.php");
require_once("../models/Evento.php");
require_once("../models/Categoria.php");
require_once("../models/Unidad.php");
require_once("../models/Estado.php");
require_once("../models/EventoUnidad.php");

$evento = new Evento();
$categoria = new Categoria();
$unidad = new Unidad();
$estado = new Estado();
$eventoUnidad = new EventoUnidad();

if (isset($_GET["op"])) {
    switch ($_GET["op"]) {

        case "insert_asignacion_unidades":
            $datos = $eventoUnidad->add_eventoUnidad(
                $_POST['ev_id'],
                $_POST['unid_id']
            );
            if ($datos == true) {
                echo 1;
            } else {
                echo 0;
            }
            break;
            
            
        case "update_asignacion_evento":
            $datos = $eventoUnidad->update_asignacion_evento($_POST['ev_id'],$_POST['unid_id']);
            
            if ($datos == true) {
                echo 1;
            } else {
                echo 0;
            }
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
            break;
        
        case "delete_unidad":
            $datos = $eventoUnidad->delete_unidad($_POST['ev_id'],$_POST['unid_id']);
            if ($datos == true){
                echo 1;
            } else {
                echo 0;
            }
            break;


    }
    
}