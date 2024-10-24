<?php
require_once '../config/conexion.php';
require_once '../models/Noticia.php';
require_once '../models/Unidad.php';
require_once '../models/Usuario.php';
require_once("../models/Permisos.php");
Permisos::redirigirSiNoAutorizado();


header("Content-Type: application/json");
if(isset($_GET["op"])){
  $permisos = new Permisos();
  switch ($_GET["op"]){
    case "get_permisos":
      $result = $permisos->get_permisos();
      echo json_encode($result);
    break;
    case "update_permisos":
        $result = $permisos->update_permisos($_POST["id"], $_POST["unidad"], $_POST["seccion"], $_POST["usuario"], $_POST["tipo_usuario"]);
        echo json_encode($result);
    break;
  }
}
?>
