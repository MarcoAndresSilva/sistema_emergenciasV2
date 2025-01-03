<?php
class SeguridadPassword extends Conectar {
    // Insertar datos de contraseña robusta (Insert tm_rb_pass)
    
public function add_password_info($email, $usu_name, $pass) {
            $conectar = parent::conexion();
            parent::set_names();
            
            // Verificar la seguridad de la contraseña
            $seguridad = $this->PasswordSegura($pass);
            
            // Obtener el ID del usuario basado en el correo electrónico y el nombre de usuario
            $sql_get_id = "SELECT usu_id FROM tm_usuario WHERE usu_correo = :email AND usu_name = :usu_name";
            $consulta_get_id = $conectar->prepare($sql_get_id);
            $consulta_get_id->bindParam(':email', $email);
            $consulta_get_id->bindParam(':usu_name', $usu_name);
            $consulta_get_id->execute();
            
            // Obtener el ID del usuario
            $fila = $consulta_get_id->fetch(PDO::FETCH_ASSOC);
            $usu_id = $fila['usu_id'];

            $sql_insert_pass = "INSERT INTO tm_rob_pass(usu_id, mayuscula, minuscula, especiales, numeros, largo) VALUES (:usu_id, :mayuscula, :minuscula, :especiales, :numeros, :largo)";
            
            $consulta_insert_pass = $conectar->prepare($sql_insert_pass);

            // Bind de los parámetros
            $consulta_insert_pass->bindParam(':usu_id', $usu_id);
            $consulta_insert_pass->bindParam(':mayuscula', $seguridad['mayuscula'], PDO::PARAM_BOOL);
            $consulta_insert_pass->bindParam(':minuscula', $seguridad['minuscula'], PDO::PARAM_BOOL);
            $consulta_insert_pass->bindParam(':especiales', $seguridad['especiales'], PDO::PARAM_BOOL);
            $consulta_insert_pass->bindParam(':numeros', $seguridad['numero'], PDO::PARAM_BOOL);
            $consulta_insert_pass->bindParam(':largo', $seguridad['largo'], PDO::PARAM_BOOL);

            $consulta_insert_pass->execute();
            
            if ($consulta_insert_pass->rowCount() > 0) {
                return true;
            } else {
                return 0;
            }

    }
    
    // Función para verificar la seguridad de la contraseña
    function PasswordSegura($pass) {
        // Objetivo es retornar un array con valores booleanos indicando si la contraseña cumple con los requisitos de seguridad
        $jsonPass = array(
            "mayuscula" => preg_match('@[A-Z]@', $pass) ? true : false,
            "minuscula" => preg_match('@[a-z]@', $pass) ? true : false,
            "numero" => preg_match('@[0-9]@', $pass) ? true : false,
            "especiales"  => preg_match('@[^\w]@', $pass) ? true : false,
            "largo" => strlen($pass) > 7 ? true : false
        );
        return $jsonPass;
    }

public function getCriteriosSeguridadPorUnidad($unidad = 0) {
    try {
        $sql = "SELECT mayuscula, minuscula, numeros, especiales, largo FROM tm_rob_unidad WHERE usu_unidad = :unidad";
        $params = [":unidad" => $unidad];

        $resultado = $this->ejecutarConsulta($sql, $params);

        if ($resultado) {
            $criterios = $resultado[0];
            return [
                "mayuscula" => (bool) $criterios['mayuscula'],
                "minuscula" => (bool) $criterios['minuscula'],
                "numero" => (bool) $criterios['numeros'],
                "especiales" => (bool) $criterios['especiales'],
                "largo" => (int) $criterios['largo']
            ];
        } else {
            return [
                "mayuscula" => True,
                "minuscula" => false,
                "numero" => false,
                "especiales" => false,
                "largo" => 8
            ];
        }
    } catch (PDOException $e) {
        error_log('Error en getCriteriosSeguridadPorUnidad(): ' . $e->getMessage());
        return [
            "mayuscula" => True,
            "minuscula" => false,
            "numero" => false,
            "especiales" => false,
            "largo" => 8
        ];
    }
}


public function cumpleCriteriosSeguridad($unidad, $passNoCifrado) {
    try {
        // Obtener los criterios de seguridad de la unidad desde la base de datos
        $criterios = $this->getCriteriosSeguridadPorUnidad($unidad);

        // Si no se obtuvieron criterios válidos, retornar false
        if (empty($criterios) || !isset($criterios['mayuscula']) || !isset($criterios['minuscula']) ||
            !isset($criterios['numero']) || !isset($criterios['especiales']) || !isset($criterios['largo'])) {
            return false;
        }

        // Evaluar la contraseña con los criterios activos
        $passSegura = $this->PasswordSegura($passNoCifrado);

        // Asegurar que el criterio 'largo' siempre se cumpla
        $largoCumple = strlen($passNoCifrado) >= $criterios['largo'];

        // Comparar los criterios activos con la contraseña proporcionada
        return (
            (!$criterios['mayuscula'] || $passSegura['mayuscula']) &&
            (!$criterios['minuscula'] || $passSegura['minuscula']) &&
            (!$criterios['numero'] || $passSegura['numero']) &&
            (!$criterios['especiales'] || $passSegura['especiales']) &&
            $largoCumple
        );
    } catch (PDOException $e) {
        // Manejo de errores
        error_log('Error en cumpleCriteriosSeguridad(): ' . $e->getMessage());
        return false;
    }
}
    public function update_password_info($usu_id, $pass) : bool {
    try {
        $seguridad = $this->PasswordSegura($pass);
        $sql = "UPDATE tm_rob_pass SET mayuscula=:mayuscula, minuscula=:minuscula, especiales=:especiales, numeros=:numeros, largo=:largo, fecha_modi=:fecha_modi WHERE usu_id = :usu_id";
        $fechaModi = date('Y-m-d H:i:s');
        
        // Parámetros para la consulta
        $params = [
            ':usu_id' => $usu_id,
            ':mayuscula' => $seguridad['mayuscula'],
            ':minuscula' => $seguridad['minuscula'],
            ':especiales' => $seguridad['especiales'],
            ':numeros' => $seguridad['numero'],
            ':largo' => $seguridad['largo'],
            ':fecha_modi' => $fechaModi
        ];

        // Ejecutar la acción
        $success = $this->ejecutarAccion($sql, $params);

        return $success;
    } catch (PDOException $e) {
        // Manejo de errores
        error_log('Error en update_password_info(): ' . $e->getMessage());
        return false;
    }
}
    public function get_usuarios_status_passwords(){
      /**
       * Retorna un array con la información de las password de los usuarios y los parámetros de robustez que cumplen
       * @autor: Nelson Navarro
       * @return array
       */
        
        try {
            $sql = "SELECT 
                        usu.usu_nom as 'nombre', 
                        usu.usu_ape as 'apellido',
                        usu.usu_correo as 'correo',
                        DATEDIFF(NOW(), rb.fecha_modi) AS 'fecha',
                        rb.mayuscula as 'mayuscula',
                        rb.minuscula as 'minuscula',
                        rb.numeros as 'numero',
                        rb.especiales as 'especiales',
                        rb.largo as 'largo',
                        runi.camb_dias AS 'dias_cambio',
                        uni.unid_nom as 'unidad'
                    FROM tm_rob_pass AS rb
                    JOIN tm_usuario AS usu
                    ON (usu.usu_id = rb.usu_id)
                    JOIN tm_rob_unidad AS runi
                    ON (runi.rob_id = usu.usu_unidad)
                    JOIN tm_unidad as uni
                    on (uni.unid_id = usu.usu_unidad)
                    WHERE  usu.fecha_elim IS NULL;";
            $userAll = $this->ejecutarConsulta($sql);

       
            if(is_array($userAll) && count($userAll) > 0){
                return $userAll;
            } else {
                return array(); // Devuelve un array vacío si no se encuentran usuarios con contraseñas
            }
        } catch (Exception $e) {
            ?> <script>console.log("Error al obtener usuarios con contraseñas")</script><?php
            throw $e;
        }
    }
    public function get_robuste_seguridad_unidad() {
      $query_rob_unidad = "select * from tm_rob_unidad;";
      $result = $this->ejecutarConsulta($query_rob_unidad);
      return $result;
    }
public function update_robuste_unidad($rob_id,$usu_unidad,$mayuscula,$minuscula,$especiales,$numeros,$largo,$camb_dias) {
    try {
        $sql = "UPDATE tm_rob_unidad SET
                  usu_unidad=:usu_unidad,
                  mayuscula=:mayuscula,
                  minuscula=:minuscula,
                  especiales=:especiales,
                  numeros=:numeros,
                  largo=:largo,
                  camb_dias=:camb_dias,
                  fecha_modi=:fecha_modi
              WHERE rob_id = :rob_id";

        $fechaModi = date('Y-m-d H:i:s');
        // Parámetros para la consulta
        $params = [
            ':rob_id' => $rob_id,
            ':usu_unidad' => $usu_unidad,
            ':mayuscula' => $mayuscula,
            ':minuscula' => $minuscula,
            ':especiales' => $especiales,
            ':numeros' => $numeros,
            ':largo' => $largo,
            ':camb_dias' => $camb_dias,
            ':fecha_modi' => $fechaModi
        ];

        // Ejecutar la acción
        $success = $this->ejecutarAccion($sql, $params);

        return $success;
    } catch (PDOException $e) {
        // Manejo de errores
        error_log('Error en update_unidad_info(): ' . $e->getMessage());
        return false;
    }

  }
 public function create_password_Configuracion_a_ultima_unidad() {
    $query_unidad = "SELECT * FROM tm_unidad ORDER BY unid_id DESC LIMIT 1";
    $unidad = $this->ejecutarConsulta($query_unidad);
    $unidad_id = $unidad[0]['unid_id'];

    $sql = "INSERT INTO tm_rob_unidad (usu_unidad, mayuscula, minuscula, especiales, numeros, largo, camb_dias, fecha_modi)
          VALUES (:usu_unidad, :mayuscula, :minuscula, :especiales, :numeros, :largo, :camb_dias, :fecha_modi)";

    $fecha_modi = date('Y-m-d H:i:s');

    $params = [
        ':usu_unidad' => $unidad_id,
        ':mayuscula' => 1,
        ':minuscula' => 0,
        ':especiales' => 0,
        ':numeros' => 0,
        ':largo' => 8,
        ':camb_dias' => 90,
        ':fecha_modi' => $fecha_modi
    ];
    $success = $this->ejecutarAccion($sql, $params);
    return $success;
 }
}

?>
