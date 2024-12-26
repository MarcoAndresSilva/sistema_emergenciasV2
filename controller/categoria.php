<?php
require_once("../config/conexion.php");
require_once("../models/Categoria.php");
require_once("../models/Evento.php");
require_once("../models/RegistroLog.php");
require_once("../models/Permisos.php");

Permisos::redirigirSiNoAutorizado();

$categoria = new Categoria();
$evento = new Evento();
$RegistroLog= new RegistroLog();
if (isset($_GET["op"])) {
    switch ($_GET["op"]) {

        case "combo":

            $datos = $categoria->get_categoria();

            if (is_array($datos) == true and count($datos) > 0) {
                $html = "";
                foreach ($datos as $row) {
                    $html .= "<option value=" . $row['cat_id'] . ">" . $row['cat_nom'] . "</option>";
                }
                echo $html;
            }
        break;
    case "get_categoria":
            $datos = $categoria->get_categoria();
            if (is_array($datos) == true and count($datos) > 0) {
                echo json_encode($datos);
            }
        break;
        case "get_categoria_gestion_motivo":
            if(isset($_POST['mov_id'])) {
                $mov_id = $_POST['mov_id'];

                $relacion = $categoria->get_categoria_relacion_motivo($mov_id);
                $categorias = $categoria->get_categoria(); 

                // Agregar la propiedad 'activo' a cada categoría
                foreach ($categorias as $key => $cat) {
                    $categorias[$key]['activo'] = 0; // Por defecto es falso
                    foreach ($relacion as $rel) {
                        if ($cat['cat_nom'] == $rel['cat_nom'] and array_key_exists('activo',$rel)) {
                            $categorias[$key]['activo'] = $rel['activo']; // Si existe en la relación, se actualiza el valor
                            break;
                        }
                    }
                } 
                echo json_encode($categorias);
            } else {
                echo json_encode(array(
                    "error" => "No se recibió el parámetro 'mov_id'".$_POST['mov_id']
                ));
            }
            break;
         //Datos de categoría segun si ID
        case "datos_categoria":

            $cat_id = $_POST["cat_id"];

            $datos_categoria = $categoria->get_datos_categoria($cat_id);

            if (is_array($datos_categoria) == true and count($datos_categoria) > 0) {
                echo json_encode($datos_categoria);
            }
        break;

        //Datos del nombre de la categoría segun si ID
        case "get_cat_nom_by_ev_id":

            $ev_id = isset($_POST["ev_id"]) ? $_POST["ev_id"] : null;
            echo $categoria->get_cat_nom_by_ev_id($ev_id);
        break;
        case 'cateogia_nivel':

            $datos = $categoria->get_categoria_nivel();

            if (is_array($datos) == true and count($datos) > 0) {
                echo json_encode($datos);
            }
        break;            
    }
}
if (isset($_POST["op"])) {
    switch ($_POST["op"]) {
        case "update_categoria":
            $cat_id = $_POST["cat_id"];
            $cat_nom = $_POST["cat_nom"];
            $est = $_POST["ev_niv_id"];
            $resultado = $categoria->update_categoria($cat_id, $cat_nom, $est);
            if ($resultado === true) {
                $mensaje = "¡La operación se ha realizado exitosamente!";
                $status = "success";
            } else {
                $mensaje = "¡La operación ha fallado!";
                $status = "error";
            }
            $response = array(
                "status" => $status,
                "mensaje" => $mensaje
            );
            $RegistroLog->add_log_registro($_SESSION['usu_id'],$_POST['op'],"actualizar {$cat_nom}:{$cat_id} al estado {$est}:".$mensaje);
            echo json_encode($response);
            break;

        case "add_categoria":
            $cat_nom = $_POST["cat_nom"];
            $est = $_POST["ev_niv_id"];
            $resultado = $categoria->add_categoria($cat_nom, $est);
            if ($resultado === true) {
                $mensaje = "¡La operación se ha realizado exitosamente!";
                $status = "success";
            } else {
                $mensaje = "¡La operación ha fallado!";
                $status = "error";
            }
            $response = array(
                "status" => $status,
                "mensaje" => $mensaje
            );
            $RegistroLog->add_log_registro($_SESSION['usu_id'],$_POST['op'],"se agrega la categoria {$cat_nom} estado {$est}:".$mensaje);
            echo json_encode($response);
            break;
        case "delete_categoria":
            $cat_id = $_POST["cat_id"];
            $validar = $evento->get_evento_por_categoria($cat_id);
        
            if (is_array($validar) && count($validar) > 0) {
                $response = array(
                    "status" => "warning",
                    "mensaje" => "¡La categoría ya tiene datos existentes relacionados!"
                );
            } else {
                $resultado = $categoria->delete_categoria($cat_id);
                $response = array(
                    "status" => $resultado ? "success" : "error",
                    "mensaje" => $resultado ? "¡La operación se ha realizado exitosamente!" : "¡La operación ha fallado!"
                );
            }
            $RegistroLog->add_log_registro($_SESSION['usu_id'],$_POST['op'],"eliminar {$cat_id}".$response['mensaje']);
            echo json_encode($response);
        break;
}}
