<?php
require_once 'RegistroLog.php';
require_once 'SeguridadPassword.php';
class Usuario extends Conectar {

public function login() {
    if (isset($_POST["enviar"])) {
        $name = $_POST["usu_name"];
        $pass = $_POST["usu_pass"];

        if (empty($name) || empty($pass)) {
            header("Location:".Conectar::ruta()."index.php?m=2");
            exit();
        }

        $hashedPass = md5($pass);
        $sql = "SELECT * FROM tm_usuario WHERE usu_name = :usu_name AND usu_pass = :usu_pass AND estado = 1";
        $params = [
            ':usu_name' => $name,
            ':usu_pass' => $hashedPass
        ];

        $resultado = $this->ejecutarConsulta($sql, $params, false); // No fetchAll, solo fetch

        if ($resultado) {
            $_SESSION["usu_id"] = $resultado["usu_id"];
            $_SESSION["usu_nom"] = $resultado["usu_nom"];
            $_SESSION["usu_ape"] = $resultado["usu_ape"];
            $_SESSION["usu_tipo"] = $resultado["usu_tipo"];
            $_SESSION["usu_correo"] = $resultado["usu_correo"];
            $_SESSION["usu_telefono"] = $resultado["usu_telefono"];

            $log = new RegistroLog();
            $ipCliente = $this->GetIpCliente();
            $mensaje = "El usuario {$_SESSION['usu_nom']} {$_SESSION['usu_ape']} inició sesión desde la IP: $ipCliente";
            $log->add_log_registro($_SESSION["usu_id"], 'Inicio sesion', $mensaje);

            header("Location:".Conectar::ruta()."view/Home/");
            exit();
        } else {
            $log = new RegistroLog();
            $ipCliente = $this->GetIpCliente();
            $mensaje = "El usuario $name intentó iniciar sesión, IP: $ipCliente";
            $log->add_log_registro(0, 'Inicio sesion', $mensaje);

            header("Location:".Conectar::ruta()."index.php?m=1");
            exit();
        }
    }
}
private function GetIpCliente() {
/**
* Obtener la dirección IP del cliente.
*
* Esta función verifica varias fuentes posibles para obtener la dirección IP del cliente,
* incluyendo $_SERVER['HTTP_CLIENT_IP'], $_SERVER['HTTP_X_FORWARDED_FOR'] y $_SERVER['REMOTE_ADDR'].
*
* @return string La dirección IP del cliente.
*/ 
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
$ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
$ip = $_SERVER['REMOTE_ADDR'];
}
return $ip;
}

public function get_tipo($usu_id) {
    $sql = "SELECT * FROM tm_usuario WHERE usu_id = :usu_id";
    $params = [':usu_id' => $usu_id];
    $resultado = $this->ejecutarConsulta($sql, $params);

    if (is_array($resultado) && count($resultado) > 0) {
        return $resultado;
    } else {
        return false;
    }
}



public function get_datos_contacto($usu_id) {
    try {
        $sql = "SELECT usu_nom, usu_ape, usu_telefono, usu_correo FROM tm_usuario WHERE usu_id = :usu_id";
        $params = [':usu_id' => $usu_id];
        $resultado = $this->ejecutarConsulta($sql, $params);

        if (is_array($resultado) && count($resultado) > 0) {
            return $resultado;
        } else {
            // No se encontraron datos
            return null;
        }
    } catch (PDOException $e) {
        // Error al ejecutar la consulta
        error_log('Error en get_datos_contacto(): ' . $e->getMessage());
        return null;
    }
}

public function get_todos_usuarios() {
    $sql = "SELECT * FROM tm_udu_tipo";
    $resultado = $this->ejecutarConsulta($sql);

    if (is_array($resultado) && count($resultado) > 0) {
        return $resultado;
    } else {
        echo '<script>console.log("No se encontraron Eventos")</script>';
        return 0;
    }
}

public function add_usuario($usu_nom, $usu_ape, $usu_correo, $usu_name, $usu_pass, $fecha_crea, $estado, $usu_tipo, $usu_telefono, $usu_unidad) {
    try {
        // Verificar si el nombre de usuario ya existe
        $sql_check = "SELECT usu_name FROM tm_usuario WHERE usu_name = :usu_name";
        $params_check = [':usu_name' => $usu_name];
        $usuario_existente = $this->ejecutarConsulta($sql_check, $params_check, false);

        if ($usuario_existente) {
            return array('status' => 'warning', 'message' => 'El usuario ya existe con ese nombre de usuario');
        }

        // Verificar seguridad de la contraseña
        $seguridad = new SeguridadPassword();
        $cumpleCriterios = $seguridad->cumpleCriteriosSeguridad($usu_unidad, $usu_pass);

        if (!$cumpleCriterios) {
            return ["status" => "warning", "message" => "La contraseña no cumple con todos los requisitos de seguridad para esta unidad."];
        }

        // Insertar nuevo usuario
        $sql = "INSERT INTO tm_usuario (usu_nom, usu_ape, usu_correo, usu_name, usu_pass, fecha_crea, estado, usu_tipo, usu_telefono, usu_unidad) VALUES (:usu_nom, :usu_ape, :usu_correo, :usu_name, :usu_pass, :fecha_crea, :estado, :usu_tipo, :usu_telefono, :usu_unidad)";
        $pass_cifrado = md5($usu_pass);
        $params = [
            ':usu_nom' => $usu_nom,
            ':usu_ape' => $usu_ape,
            ':usu_correo' => $usu_correo,
            ':usu_name' => $usu_name,
            ':usu_pass' => $pass_cifrado,
            ':fecha_crea' => $fecha_crea,
            ':estado' => $estado,
            ':usu_tipo' => $usu_tipo,
            ':usu_telefono' => $usu_telefono,
            ':usu_unidad' => $usu_unidad
        ];

        $resultado = $this->ejecutarAccion($sql, $params);

        if ($resultado) {
            return array('status' => 'success', 'message' => 'Usuario agregado correctamente');
        } else {
            return array('status' => 'error', 'message' => 'No se pudo agregar el usuario');
        }
    } catch (Exception $e) {
        return array('status' => 'error', 'message' => 'Error al agregar el usuario');
    }
}



public function update_password($old_pass, $new_pass, $usu_id){
    $hashed_old_pass = md5($old_pass); 

    $sql = "SELECT usu_pass, usu_unidad FROM tm_usuario WHERE usu_id = :usu_id AND usu_pass = :old_pass";
    $params = [
        ':usu_id' => $usu_id,
        ':old_pass' => $hashed_old_pass
    ];
    $user = $this->ejecutarConsulta($sql, $params, false);

    if (!$user) {
        return array('status' => 'warning', 'message' => 'La contraseña antigua no coincide');
    }

    // Verificar si la nueva contraseña es igual a la antigua
    if ($user['usu_pass'] == md5($new_pass)) {
        return array('status' => 'info', 'message' => 'La nueva contraseña debe ser distinta a la antigua');
    }

    // Verificar seguridad de la nueva contraseña
    $seguridad = new SeguridadPassword();
    $cumpleCriterios = $seguridad->cumpleCriteriosSeguridad($user['usu_unidad'], $new_pass);

    if (!$cumpleCriterios) {
        return ["status" => "warning", "message" => "La contraseña no cumple con todos los requisitos de seguridad para esta unidad."];
    }

    // Actualizar la contraseña
    $hashed_new_pass = md5($new_pass);
    $sql = "UPDATE tm_usuario SET usu_pass = :new_pass WHERE usu_id = :usu_id";
    $params = [
        ':new_pass' => $hashed_new_pass,
        ':usu_id' => $usu_id
    ];
    $resultado = $this->ejecutarAccion($sql, $params);

    // Verificar si la contraseña se actualizó correctamente
    if ($resultado) {
        return array('status' => 'success', 'message' => 'Contraseña actualizada con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}


public function update_password_force($new_pass, $usu_id){
    $user = $this->get_info_usuario($usu_id);
    $usu_unidad = $user['result']['Unidad'];
    // Verificar seguridad de la nueva contraseña
    $seguridad = new SeguridadPassword();
    $cumpleCriterios = $seguridad->cumpleCriteriosSeguridad($usu_unidad, $new_pass);

    if (!$cumpleCriterios) {
        return ["status" => "warning", "message" => "La contraseña no cumple con todos los requisitos de seguridad para esta unidad."];
    }

    // Actualizar la contraseña
    $hashed_new_pass = md5($new_pass);
    $sql = "UPDATE tm_usuario SET usu_pass = :new_pass WHERE usu_id = :usu_id";
    $params = [
        ':new_pass' => $hashed_new_pass,
        ':usu_id' => $usu_id
    ];

    $resultado = $this->ejecutarAccion($sql, $params);

    // Verificar si la contraseña se actualizó correctamente
    if ($resultado) {
        return array('status' => 'success', 'message' => 'Contraseña actualizada con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}




public function update_phone($new_phone, $usu_id){
    // Limpiar el número de teléfono y dejar solo los números
    $clean_phone = preg_replace('/\D/', '', $new_phone);

    // Verificar si la longitud del número de teléfono es correcta
    if (strlen($clean_phone) != 9) {
        return array('status' => 'warning', 'message' => 'La longitud del número de teléfono no es correcta');
    }

    $sql = "UPDATE tm_usuario SET usu_telefono = :new_phone WHERE usu_id = :usu_id";
    $params = [
        ':new_phone' => $clean_phone,
        ':usu_id' => $usu_id
    ];

    $resultado = $this->ejecutarAccion($sql, $params);

    // Verificar si el número de teléfono se actualizó correctamente
    if ($resultado) {
        return array('status' => 'success', 'message' => 'Número de teléfono actualizado con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}


public function get_info_usuario($usu_id){
    $sql = 'SELECT 
                CONCAT(usu.usu_nom, " ", usu.usu_ape) AS "Nombre Completo",
                tp.usu_tipo_nom as "Tipo",
                usu.usu_telefono as "Telefono",
                usu.usu_correo as "Correo",
                unid.unid_nom as "Unidad",
                usu.usu_name as "Usuario"
            FROM `tm_usuario` as usu
            JOIN tm_udu_tipo as tp
            ON(tp.usu_tipo_id=usu.usu_tipo)
            JOIN tm_unidad as unid
            ON (usu.usu_unidad=unid.unid_id)
            WHERE usu.usu_id = :usu_id';

    $params = [':usu_id' => $usu_id];

    $resultado = $this->ejecutarConsulta($sql, $params, false);

    if (!$resultado) {
        return array('status' => 'error', 'message' => 'No se puede obtener los datos');
    }

    return array('status' => 'success', 'message' => 'Se obtienen los datos', 'result' => $resultado);
}

public function get_full_usuarios(){
    $sql = 'SELECT 
                usu.usu_id as "usu_id",
                usu.estado as "estado",
                usu.usu_nom as "Nombre",
                usu.usu_ape as "Apellido",
                tp.usu_tipo_nom as "Tipo",
                tp.usu_tipo_id as "id_tipo",
                usu.usu_telefono as "Telefono",
                usu.usu_correo as "Correo",
                unid.unid_nom as "Unidad",
                usu.usu_name as "Usuario"
            FROM `tm_usuario` as usu
            JOIN tm_udu_tipo as tp
            ON(tp.usu_tipo_id=usu.usu_tipo)
            JOIN tm_unidad as unid
            ON (usu.usu_unidad=unid.unid_id);';

    $resultado = $this->ejecutarConsulta($sql);
    if (empty($resultado)) {
        return array('status' => 'error', 'message' => 'No se puede obtener los datos');
    }
    return array('status' => 'success', 'message' => 'Se obtienen los datos', 'result' => $resultado);
}

public function disable_usuario($usu_id){
    $fecha_elim = date('Y-m-d H:i:s'); // Get current date and time
    $estado = 0; // Assuming 0 is the value for 'disabled'

    $sql = "UPDATE tm_usuario SET estado = :estado, fecha_elim = :fecha_elim WHERE usu_id = :usu_id";
    $params = [
        ':estado' => $estado,
        ':fecha_elim' => $fecha_elim,
        ':usu_id' => $usu_id
    ];

    $resultado = $this->ejecutarAccion($sql, $params);

    if ($resultado) {
        return array('status' => 'success', 'message' => 'Usuario desactivado con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}

public function enable_usuario($usu_id){
    $estado = 1; // Assuming 1 is the value for 'enabled'
    $fecha_elim = null; // Set 'fecha_elim' to null

    $sql = "UPDATE tm_usuario SET estado = :estado, fecha_elim = :fecha_elim WHERE usu_id = :usu_id";
    $params = [
        ':estado' => $estado,
        ':fecha_elim' => $fecha_elim,
        ':usu_id' => $usu_id
    ];

    $resultado = $this->ejecutarAccion($sql, $params);

    if ($resultado) {
        return array('status' => 'success', 'message' => 'Usuario activado con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}
 

public function update_usuario($usu_id, $usu_nom, $usu_ape, $usu_correo, $usu_telefono, $usu_name, $usu_tipo, $usu_unidad){
    if (empty($usu_nom) || empty($usu_ape) || empty($usu_correo) || empty($usu_telefono) || empty($usu_name) || empty($usu_tipo) || empty($usu_unidad)) {
        return array('status' => 'warning', 'message' => 'Todos los campos son obligatorios');
    }

    if (empty($usu_nom)) {
        return array('status' => 'warning', 'message' => 'El campo nombre no puede estar vacío');
    }

    // Check if the username is being used by another user
    $sql_check = "SELECT * FROM tm_usuario WHERE usu_name = :usu_name AND usu_id != :usu_id";
    $params_check = [
        ':usu_name' => $usu_name,
        ':usu_id' => $usu_id
    ];

    $resultado_check = $this->ejecutarConsulta($sql_check, $params_check, false);

    if (!empty($resultado_check)) {
        return array('status' => 'warning', 'message' => 'El nombre de usuario ya está siendo utilizado por otro usuario');
    }

    // Proceed with the update if username is not being used by another user
    $sql = "UPDATE tm_usuario SET usu_nom = :usu_nom, usu_ape = :usu_ape, usu_correo = :usu_correo, usu_telefono = :usu_telefono, usu_name = :usu_name, usu_tipo = :usu_tipo, usu_unidad = :usu_unidad WHERE usu_id = :usu_id";
    $params = [
        ':usu_nom' => $usu_nom,
        ':usu_ape' => $usu_ape,
        ':usu_correo' => $usu_correo,
        ':usu_telefono' => $usu_telefono,
        ':usu_name' => $usu_name,
        ':usu_tipo' => $usu_tipo,
        ':usu_unidad' => $usu_unidad,
        ':usu_id' => $usu_id
    ];

    $resultado = $this->ejecutarAccion($sql, $params);

    if ($resultado) {
        return array('status' => 'success', 'message' => 'Usuario actualizado con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}


public function update_usuario_tipo($usu_id, $usu_tipo){
    if (empty($usu_tipo)) {
        return array('status' => 'warning', 'message' => 'El tipo de usuario es obligatorio');
    }

    $sql = "UPDATE tm_usuario SET usu_tipo = :usu_tipo WHERE usu_id = :usu_id";
    $params = [
        ':usu_tipo' => $usu_tipo,
        ':usu_id' => $usu_id
    ];

    $resultado = $this->ejecutarAccion($sql, $params);

    if ($resultado) {
        return array('status' => 'success', 'message' => 'Tipo de usuario actualizado con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}

}
?>
