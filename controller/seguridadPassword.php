<?php
require_once("../config/conexion.php");
require_once("../models/SeguridadPassword.php");
require_once("../models/RegistroLog.php");
if (isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2)) {

$RegistroLog= new RegistroLog();

if (isset($_POST["op"])) {
    $passSeg = new SeguridadPassword;
    switch ($_POST["op"]) {
        case "password_status":
            $response = $passSeg->get_usuarios_status_passwords(); 
            echo json_encode($response);
        break; 
    case "get_seguridad_unidad":
    try {
        $result = $passSeg->get_robuste_seguridad_unidad();
        if ($result !== false) {
            echo json_encode($result);
        } else {
            throw new Exception("Error al obtener la seguridad de la unidad.");
        }
    } catch (Exception $e) {
        echo json_encode(array("error" => $e->getMessage()));
    }
    break;
case 'update_unidad_robusta':
    // Validar los datos POST
    $required_fields = [
      'rob_id',
      'usu_unidad',
      'mayuscula',
      'minuscula',
      'especiales',
      'numeros',
      'largo',
      'camb_dias',
    ];
    $missing_fields = [];

    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            $missing_fields[] = $field;
        } elseif (trim($_POST[$field]) === '') {
            $invalid_fields[] = $field;
        }
    }

    // Verificar si hay campos faltantes
    if (!empty($missing_fields)) {
        // Respuesta de advertencia por datos faltantes
        $response = [
            'status' => 'warning',
            'message' => 'Faltan datos: ' . implode(', ', $missing_fields)
            ];
        }
        // Capturar los datos POST
        $rob_id = $_POST['rob_id'];
        $usu_unidad = $_POST['usu_unidad'];
        $mayuscula = $_POST['mayuscula'];
        $minuscula = $_POST['minuscula'];
        $especiales = $_POST['especiales'];
        $numeros = $_POST['numeros'];
        $largo = $_POST['largo'];
        $camb_dias = $_POST['camb_dias'];
        // Llamar al método para actualizar la unidad robusta
        $resputa = $passSeg->update_robuste_unidad($rob_id, $usu_unidad, $mayuscula, $minuscula, $especiales, $numeros, $largo, $camb_dias);

        if ($resputa) {
            $response = [
                'status' => 'success',
                'message' => 'Datos actualizados'
            ];

        }else{
          $response = [
           'status' => 'error',
           'message' => 'Error al actualizar los datos'
          ];
        }             // Respuesta de error genérico
    echo json_encode($response);
    break;
        }
    }
}
