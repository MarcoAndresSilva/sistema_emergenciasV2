<?php
require_once("../config/conexion.php");
require_once("../models/Usuario.php");
$usuario = new Usuario();

if (isset($_GET["op"])) {
    switch ($_GET["op"]) {
        case "add_usuario":
            $datos = $usuario->add_usuario($_POST['usu_nom'],
            $_POST['usu_ape'],
            $_POST['usu_correo'],
            $_POST['usu_name'],
            $_POST['usu_pass'],
            $_POST['fecha_crea'],
            $_POST['estado'],
            $_POST['usu_tipo']);
            if ($datos == true) {
                echo 1;
            } else {
                echo 0;
            }
            break;

        case "get_tipo":

            $usu_tipo = $_POST['#usu_tipo'];

            $datos = $usuario->get_tipo($usu_tipo);

            if (count($datos) > 0){
                $resultado = $datos["usu_tipo"];
                echo $resultado;
            }else{
                echo false;
            }
        break;
        
        case "info-personal":
            // Verificar si se recibió el ID del usuario en la solicitud
           
                // Obtener el ID del usuario desde la solicitud
                $usu_id = $_POST['usu_id'];

                // Obtener los datos de contacto del usuario
                $datos = $usuario->get_datos_contacto($usu_id);
        
                // Verificar si se encontraron datos de contacto
                if (count($datos) > 0) {
                    // Imprimir los datos antes de devolverlos
                    var_dump($datos);
                    // Devolver los datos como JSON
                    echo json_encode($datos);
                } else {
                    echo false;
                }
           
        break;
            
            
        
        case "get_todos_usuarios":
            $datos = $usuario->get_todos_usuarios();

            if (is_array($datos) == true and count($datos) > 0) {
                $html = "";
                foreach ($datos as $row) {
                    $html .= "<option value=" . $row['usu_tipo_id'] . ">" . $row['usu_tipo_nom'] . "</option>";
                }
                echo $html;
            }
        break;

  
    }
}