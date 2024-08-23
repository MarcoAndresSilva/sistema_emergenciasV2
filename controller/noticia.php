<?php
require_once '../config/conexion.php';
require_once '../models/Noticia.php';
if (isset($_GET["op"])) {
  $noticia = new Noticia();
    switch ($_GET["op"]) {
        case "add_noticia":
            $asunto = $_POST["asunto"];
            $mensaje= $_POST["mensaje"];
            $url = isset($_POST["enlace"]) ? $_POST["enlace"] : null;
            $datos = $noticia->add_noticia($asunto,$mensaje,$url);
            echo json_encode($datos);
        break;
        case "get_noticia":
            $usuario_id = $_SESSION["usu_id"];
            $result = $noticia->get_noticias_usuario($usuario_id);
            echo json_encode($result);
        break;
        case "read_noticia":
            $usuario_id = $_SESSION["usu_id"];
            $noticia_id = $_POST["noticia_id"];
            $result = $noticia->check_mensaje_leido($noticia_id, $usuario_id);
            echo json_encode($result);
        break;
  }
}
