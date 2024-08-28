<?php
require_once '../config/conexion.php';
require_once '../models/Noticia.php';
require_once '../models/Unidad.php';
require_once '../models/Usuario.php';

if (isset($_GET["op"])) {
  $noticia = new Noticia();
    switch ($_GET["op"]) {
        case "add_noticia":
            $asunto = $_POST["asunto"];
            $mensaje= $_POST["mensaje"];
            $url = isset($_POST["enlace"]) ? $_POST["enlace"] : null;
            $argumentos = ["asunto"=> $asunto, "mensaje"=>$mensaje, "url" =>$url];
            $datos = $noticia->crear_noticia_y_enviar_grupo_usuario($argumentos);
            echo json_encode($datos);
        break;
        case "get_noticia":
            $usuario_id = $_SESSION["usu_id"];
            $result = $noticia->get_noticias_usuario($usuario_id);
            echo json_encode($result);
        break;
        case "get_reglas":
            $usuario_id = $_SESSION["usu_id"];
            $result = $noticia->get_reglas();
            echo json_encode($result);
        break;
        case "get_reglas_relaciones":
              $unidad = new Unidad();
              $usuario = new Usuario();
              $result=[
                  "unidad" =>  $unidad->get_unidad(),
                  "tipo_usuario" =>  $usuario->get_todos_usuarios(),
                  "tipo_usuario" =>  $usuario->get_todos_usuarios(),
                  "usuarios" =>  $usuario->get_full_usuarios(),
                ];

            echo json_encode($result);
        break;
        case "update_reglas":
            if (isset($_POST['unidad'], $_POST['seccion'], $_POST['usuario'], $_POST['tipo_usuario'], $_POST['asunto'])) {
                $args = [
                    "unidad" => $_POST['unidad'],
                    "seccion" => $_POST['seccion'],
                    "usuario" => $_POST['usuario'],
                    "tipo_usuario" => $_POST['tipo_usuario'],
                    "id_regla" => $_POST['id_regla']
                ];

                $result = $noticia->update_regla_envio($args);

                if ($result) {
                    echo json_encode($result);
                } else {
                    echo json_encode(["status" => "error", "message" => "Ocurrió un error al actualizar la regla."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Datos incompletos para la actualización."]);
            }
        break;
        case "read_noticia":
            $usuario_id = $_SESSION["usu_id"];
            $noticia_id = $_POST["noticia_id"];
            $result = $noticia->check_mensaje_leido($noticia_id, $usuario_id);
            echo json_encode($result);
        break;
  }
}
