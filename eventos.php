<?php
  // if (isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2)) {
  require_once("db_chartJS/parametros.php");

  function permisos() {  
    if (isset($_SERVER['HTTP_ORIGIN'])){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Origin, Authorization, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Credentials: true');      
    }  
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))          
          header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
      if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
          header("Access-Control-Allow-Headers: Origin, Authorization, X-Requested-With, Content-Type, Accept");
      exit(0);
    }
  }
  permisos();

  $conexion =  Conectar($db);
  if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $fecha_inicio = date('Y-m-d', strtotime('-30 days')); // Fecha de inicio hace 30 días
    
    $sql = "SELECT tm_categoria.cat_nom, COUNT(tm_evento.ev_id) as cantidad_eventos 
            FROM tm_categoria 
            LEFT JOIN tm_evento ON tm_categoria.cat_id = tm_evento.cat_id AND tm_evento.ev_inicio >= :fecha_inicio
            GROUP BY tm_categoria.cat_id";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':fecha_inicio', $fecha_inicio);
    $stmt->execute();
    
    header("HTTP/1.1 200 OK");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit();
  }
// }
?>