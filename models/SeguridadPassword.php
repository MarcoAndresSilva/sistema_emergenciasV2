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
    function update_password_info($usu_id, $pass) : Returntype {
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
}


?>