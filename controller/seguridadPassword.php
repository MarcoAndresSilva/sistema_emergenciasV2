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
        }
    }
}
