<?php
require_once(__DIR__."/../config/conexion.php");
class Permisos extends Conectar{
  public static function verificarLogin() {
        try {
            return !empty($_SESSION["usu_id"]) && !empty($_SESSION["usu_tipo"]);
        } catch (Exception $e) {
            // Puedes registrar el error si es necesario
            error_log($e->getMessage());
            return false;
        }
  }
  public static function redirigirSiNoAutorizado() {
        if (!self::verificarLogin()) {
            header("Location: " . Conectar::ruta() . "index.php");
            exit();
        }
    }
}
?>
