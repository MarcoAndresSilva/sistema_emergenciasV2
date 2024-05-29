# registroLog

el controlar nos permite solicitar la informacion de los registros log de la tabla `tm_reg_log`
esta funciones requiere de la configuracion de [conexion](../config/conexion.md) y el modelo [RegistroLog](../models/registroLog.md)

La entrada de usuarios eta limitada a los que tengal el tipo 1 y 2
``` php
<?php
require_once("../config/conexion.php");
require_once("../models/RegistroLog.php");
if (isset($_SESSION["usu_id"]) && ($_SESSION["usu_tipo"] == 1 || $_SESSION["usu_tipo"] == 2)) {

$RegistroLog= new RegistroLog();
if (isset($_POST["op"])) {
    switch ($_POST["op"]) {
        case 'registro_log':
            $log = $RegistroLog->get_registros_log();
            if (is_array($log) == true and count($log) > 0) {
                $html=json_encode($log);
                echo $html;
            }else{
                echo 'no era array';
            }
        break; 
    }
}
}
```
