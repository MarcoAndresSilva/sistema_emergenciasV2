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
                ?> <script>console.log("No se agregó la información de contraseña para el usuario con correo electrónico <?php echo $email; ?> y nombre de usuario <?php echo $usu_name; ?>")</script><?php
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
    function update_password_info($usu_id, $pass) : bool {
        $conectar = parent::conexion();
        parent::set_names();
        $seguridad = $this->PasswordSegura($pass);
        $sql = "UPDATE tm_rob_pass SET mayuscula=:mayuscula, minuscula=:minuscula, especiales=:especiales, numeros=:numeros, largo=:largo,fecha_modi=:fecha_modi WHERE usu_id = :usu_id";
        $consulta = $conectar->prepare($sql);
        $consulta->bindParam(':usu_id', $usu_id);
        $consulta->bindParam(':mayuscula', $seguridad['mayuscula'], PDO::PARAM_BOOL);
        $consulta->bindParam(':minuscula', $seguridad['minuscula'], PDO::PARAM_BOOL);
        $consulta->bindParam(':especiales', $seguridad['especiales'], PDO::PARAM_BOOL);
        $consulta->bindParam(':numeros', $seguridad['numero'], PDO::PARAM_BOOL);
        $consulta->bindParam(':largo', $seguridad['largo'], PDO::PARAM_BOOL);
        $consulta->bindParam(':fecha_modi', date('Y-m-d H:i:s'));

        $consulta->execute();

        if ($consulta->rowCount() > 0) {
            return true;
        } else {
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
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT 
                        usu.usu_nom as 'nombre', 
                        usu.usu_ape as 'apellido',
                        usu.usu_correo as 'correo',
                        DATEDIFF(NOW(), fecha_crea) DIV 30 AS 'fecha',
                        rb.mayuscula as 'mayuscula',
                        rb.minuscula as 'minuscula',
                        rb.numeros as 'numero',
                        rb.especiales as 'especiales',
                        rb.largo as 'largo'
                    FROM tm_rob_pass as rb
                    JOIN tm_usuario as usu
                    ON(usu.usu_id=rb.usu_id)
                    WHERE usu.fecha_elim IS NULL";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $userAll = $sql ->fetchAll(PDO::FETCH_ASSOC);
       
            if(is_array($userAll) && count($userAll) > 0){
                return $userAll;
            } else {
                ?> <script>console.log("No se encontraron usuarios con contraseñas")</script><?php
                return array(); // Devuelve un array vacío si no se encuentran usuarios con contraseñas
            }
        } catch (Exception $e) {
            ?> <script>console.log("Error al obtener usuarios con contraseñas")</script><?php
            throw $e;
        }
    }
}


?>
