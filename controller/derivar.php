<?php
require_once("../config/conexion.php");
require_once("../models/Evento.php");
require_once("../models/Categoria.php");
require_once("../models/Unidad.php");
require_once("../models/Estado.php");
require_once("../models/EventoUnidad.php");
require_once("../models/RegistroLog.php");
require_once("../models/Noticia.php");
require_once("../models/Seccion.php");
require_once("../models/Permisos.php");

Permisos::redirigirSiNoAutorizado();


$evento = new Evento();
$categoria = new Categoria();
$unidad = new Unidad();
$estado = new Estado();
$derivado = new EventoUnidad();
$registroLog = new RegistroLog();
$noticia  = new Noticia();
$seccion = new Seccion();

header('Content-Type: application/json; charset=utf-8');

if (isset($_GET["op"])) {
    switch ($_GET["op"]) {

        case "agregar_derivado":
            $id_seccion = $_POST['sec_id'];

           $est =  $seccion->seccion_estado($id_seccion);
           if ($est == true){
                $datos = $derivado->add_eventoUnidad(
                $_POST['ev_id'],
                $_POST['sec_id']);

           }else {
                $datos = false;
                $resultado = ["status"=>"warning","message"=>"no esta diponible la seccion"];
            }
            if ($datos == true) {
             $seccion->seccion_ocupado($id_seccion);
             $usu_id = $_SESSION["usu_id"];
             $seccion_data = $seccion->get_seccion($id_seccion);
             $seccion_nombre_unidad = $seccion_data['sec_unidad_nom'] . "-" . $seccion_data['sec_nombre'];
             $ev_desc = "<span class='alert alert-success'>Se ha derivado la unidad: $seccion_nombre_unidad</span>";
            $evento->insert_emergencia_detalle($_POST["ev_id"], $usu_id, $ev_desc);
            $ags_noticia = [
              "asunto" => "Derivado",
              "mensaje" => $ev_desc,
              "id_evento"=>$_POST['ev_id'],
              "usuario"=>$_SESSION['usu_nom'],
              "unidad"=>$seccion_nombre_unidad,
            ];
             try {
               $correo_resultado = $noticia->crear_y_enviar_noticia_para_derivados($ags_noticia);
             } catch (Exception $e) {
               $correo_resultado = $e->getMessage();
             }
             $resultado = ["status"=>"success","message"=>"se agrego la seccion","correo"=>$correo_resultado];
            }
            echo json_encode($resultado);
            $registroLog->add_log_registro($_SESSION['usu_id'],$_GET['op'],"evento id:{$_POST['ev_id']} unid:{unid_id}");
            break;
        case "get_seccion_asignados_evento":
            if (!isset($_POST['ev_id']) || !is_numeric($_POST['ev_id'])) {
                echo json_encode(['status'=>'warning','message'=>'Falta el parametro ev_id']);
                break;
            }
            $evento_id = intval($_POST['ev_id']);
            $datos = $derivado->get_datos_eventoUnidad($evento_id);
            echo json_encode($datos);
            break;

        case "reporte_actualizacion" :
            $datos = $derivado->add_reporte_cambio_unidad(
                $_POST['ev_id'],
                $_POST['str_antiguo'],
                $_POST['str_nuevo'],
                $_POST['fec_cambio']
            );
            if ($datos == true) {
             $resutado = ["status"=>"success","message"=>"no se pudo hacer el cambio"];
            } else {
             $resutado = ["status"=>"warning","message"=>"no se pudo hacer el cambio"];
            }
            echo json_encode($resultado);
            $registroLog->add_log_registro($_SESSION['usu_id'],$_GET['op'],"Actualizar evento id:{$_POST['ev_id']} Nombre:{$_POST['str_antiguo']} a {$_POST['str_nuevo']}");
            break;
        case "delete_derivado":
            $datos = $derivado->delete_unidad($_POST['ev_id'],$_POST['sec_id']);
            if ($datos == true){
            $usu_id = $_SESSION["usu_id"];
            $id_seccion = $_POST['sec_id'];
            $seccion_data = $seccion->get_seccion($id_seccion);
            $seccion_nombre_unidad = $seccion_data['sec_nombre'] . " - " . $seccion_data['sec_unidad_nom'];
            $ev_desc = "<span class='alert alert-danger'>Se ha delegado la unidad: $seccion_nombre_unidad</span>";
            $seccion->seccion_disponible($id_seccion);
            $evento->insert_emergencia_detalle($_POST['ev_id'], $usu_id, $ev_desc);
            $ags_noticia = [
              "asunto" => "Eliminar Derivado",
              "mensaje" => $ev_desc,
              "id_evento"=>$_POST['ev_id'],
              "usuario"=>$_SESSION['usu_nom'],
              "unidad"=>$seccion_nombre_unidad,
            ];

            try {
                $correoResultado = $noticia->crear_y_enviar_noticia_para_derivados($ags_noticia);
            } catch (Exception $e) {
                $correoResultado = $e->getMessage();
            }
            $resultado = ["status"=>"success","message"=>"se eliminado","correo"=>$correoResultado];

            } else {
             $resultado = ["status"=>"warning","message"=>"no se pudo hacer el cambio"];
            }
            echo json_encode($resultado);
            $registroLog->add_log_registro($_SESSION['usu_id'],$_GET['op'],"Eliminar unidad:{$_POST['sec_id']} de evento id {$_POST['ev_id']}");
            break;


    }
}
