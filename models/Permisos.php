<?php
class Permisos{
    public static function verificarPermisos() {
        try {
            return isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2);
        } catch (Exception $e) {
            // Puedes registrar el error si es necesario
            error_log($e->getMessage());
            return false;
        }
    }    public static function redirigirSiNoAutorizado() {
        if (!self::verificarPermisos()) {
            header("Location: " . Conectar::ruta() . "index.php");
            exit();
        }
    }
}
?>
