<?php
require_once 'RegistroLog.php';
require_once 'SeguridadPassword.php';
class Usuario extends Conectar {

  public function login($name, $pass) {
    $log = new RegistroLog();
    $ipCliente = $this->GetIpCliente();

    if (is_null($name) || is_null($pass) || empty($name) || empty($pass)) {
      return 'camposvacios';
    }

    $hashedPass = md5($pass);
    $info_usuario = $this->get_login_usuario($name, $hashedPass);

    if (!$info_usuario) {
        $mensaje = "El usuario $name intentó iniciar sesión, IP: $ipCliente";
        $log->add_log_registro(0, 'Inicio sesión', $mensaje);
        return 'datoincorecto';
    }

    $this->crearSesionUsuario($info_usuario);
    $mensaje = "El usuario {$_SESSION['usu_nom']} {$_SESSION['usu_ape']} inició sesión desde la IP: $ipCliente";
    $log->add_log_registro($_SESSION["usu_id"], 'Inicio sesión', $mensaje);

    return 'home';
}

private function crearSesionUsuario($usuario) {
    $_SESSION["usu_id"] = $usuario["usu_id"];
    $_SESSION["usu_nom"] = $usuario["usu_nom"];
    $_SESSION["usu_ape"] = $usuario["usu_ape"];
    $_SESSION["usu_tipo"] = $usuario["usu_tipo"];
    $_SESSION["usu_correo"] = $usuario["usu_correo"];
    $_SESSION["usu_telefono"] = $usuario["usu_telefono"];
    $_SESSION["usu_unidad"] = $usuario["usu_unidad"];
    $_SESSION["usu_seccion"] = $usuario["usu_seccion"];
}

private function get_login_usuario($name, $hashedPass) {
    $sql = "SELECT * FROM tm_usuario WHERE usu_name = :usu_name AND usu_pass = :usu_pass AND estado = 1";
    $params = [
        ':usu_name' => $name,
        ':usu_pass' => $hashedPass
    ];
    return $this->ejecutarConsulta($sql, $params, false);
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
    $sql = "SELECT * FROM tm_usu_tipo";
    $resultado = $this->ejecutarConsulta($sql);

    if (is_array($resultado) && count($resultado) > 0) {
        return $resultado;
    } else {
        echo '<script>console.log("No se encontraron Eventos")</script>';
        return 0;
    }
}

public function add_usuario($usu_nom, $usu_ape, $usu_correo, $usu_name, $usu_pass, $fecha_crea, $estado, $usu_tipo, $usu_telefono, $usu_unidad,$usu_seccion) {
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
      $sql = "INSERT INTO tm_usuario (usu_nom, usu_ape, usu_correo, usu_name, usu_pass, fecha_crea, estado, usu_tipo, usu_telefono, usu_unidad, usu_seccion)
      VALUES (:usu_nom, :usu_ape, :usu_correo, :usu_name, :usu_pass, :fecha_crea, :estado, :usu_tipo, :usu_telefono, :usu_unidad,:usu_seccion)";
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
            ':usu_seccion' => $usu_seccion,
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
        $seguridad->update_password_info( $usu_id,$new_pass);
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
                unid.unid_nom as "Unidad",
                secc.sec_nombre as "Seccion",
                usu.usu_name as "Usuario"
            FROM `tm_usuario` as usu
            JOIN tm_usu_tipo as tp
            ON(tp.usu_tipo_id=usu.usu_tipo)
            JOIN tm_unidad as unid
            ON (usu.usu_unidad=unid.unid_id)
            JOIN tm_seccion as secc
            ON (secc.sec_id=usu.usu_seccion)
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
                secc.sec_nombre as "Seccion",
                unid.unid_nom as "Unidad",
                usu.usu_name as "Usuario"
            FROM `tm_usuario` as usu
            JOIN tm_usu_tipo as tp
            ON(tp.usu_tipo_id=usu.usu_tipo)
            JOIN tm_seccion as secc
            ON (secc.sec_id=usu.usu_seccion)
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
 

public function update_usuario($usu_id, $usu_nom, $usu_ape, $usu_correo, $usu_telefono, $usu_name, $usu_tipo, $usu_unidad,$usu_seccion){
    if (empty($usu_nom) || empty($usu_ape) || empty($usu_correo) || empty($usu_telefono) || empty($usu_name) || empty($usu_tipo)|| empty($usu_unidad) || empty($usu_seccion)) {
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
    $sql = "UPDATE tm_usuario SET usu_nom = :usu_nom, usu_ape = :usu_ape, usu_correo = :usu_correo,
    usu_telefono = :usu_telefono, usu_name = :usu_name, usu_tipo = :usu_tipo, usu_unidad = :usu_unidad, usu_seccion = :usu_seccion WHERE usu_id = :usu_id";
    $params = [
        ':usu_nom' => $usu_nom,
        ':usu_ape' => $usu_ape,
        ':usu_correo' => $usu_correo,
        ':usu_telefono' => $usu_telefono,
        ':usu_name' => $usu_name,
        ':usu_tipo' => $usu_tipo,
        ':usu_unidad' => $usu_unidad,
        ':usu_seccion' => $usu_seccion,
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
  public function get_full_usuarios_tipo($tipo_usuario){
    //! FIX: falta caso en que los datos sean 0
    $sql = "SELECT * FROM tm_usuario WHERE usu_tipo=:tipo_usuario and estado = 1";
    $params=[":tipo_usuario"=>$tipo_usuario];
    $result = $this->ejecutarConsulta($sql,$params);
    return $result;
  }
  public function get_usuario_derivados_por_evento($evento_id){
    $sql = "SELECT usr.usu_correo as 'usu_correo' from tm_usuario as usr JOIN tm_asignado as asig ON (asig.sec_id = usr.usu_seccion) WHERE asig.ev_id = :evento_id;";
    $params=[":evento_id"=>$evento_id];
    $result = $this->ejecutarConsulta($sql,$params);
    return $result;
  }
  public function get_eventos_derivado_por_usuario(int $usuario){
    $sql = "SELECT
              u.usu_nom AS usuario_nombre,
              un.unid_nom AS unidad_nombre,
              s.sec_nombre AS seccion_nombre,
              e.ev_id AS evento_id,
              e.ev_desc AS evento_descripcion,
              e.ev_direc AS evento_direccion,
              e.ev_latitud AS evento_latitud,
              e.ev_longitud AS evento_longitud,
              c.cat_nom AS categoria_nombre,
              n.ev_niv_id AS nivel_id,
              n.ev_niv_nom AS nivel_nombre,
              creador.usu_nom AS creador_nombre,
              creador.usu_ape AS creador_apellido,
              creador.usu_correo AS creador_correo
            FROM tm_usuario AS u
            JOIN tm_unidad AS un
              ON un.unid_id = u.usu_unidad
            JOIN tm_seccion AS s
              ON s.sec_id = u.usu_seccion
            JOIN tm_asignado AS a
              ON a.sec_id = s.sec_id
            JOIN tm_evento AS e
              ON e.ev_id = a.ev_id
            LEFT JOIN tm_usuario AS creador
              ON creador.usu_id = e.usu_id
            JOIN tm_categoria AS c
              ON c.cat_id = e.cat_id
            JOIN tm_ev_niv AS n
              ON n.ev_niv_id = e.ev_niv
            WHERE u.usu_id = :usuario
            ORDER BY `evento_id` ASC";
    $params = [":usuario" => $usuario];
    $resultado = $this->ejecutarConsulta($sql, $params);
    return $resultado;
}

}
?>
