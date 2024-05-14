<?php
require_once("../config/conexion.php");
require_once("../models/Categoria.php");
$categoria = new Categoria();

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
            echo json_encode($response);
            break;        

}}