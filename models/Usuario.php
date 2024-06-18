<?php
require_once 'RegistroLog.php';
    class Usuario extends Conectar {

        public function login() {
            $conectar = parent::conexion();
            parent::set_names();
            if (isset($_POST["enviar"])) {
                $name = $_POST["usu_name"];
                $pass = $_POST["usu_pass"];
                $log= new RegistroLog;
                $ipCliente = $this->GetIpCliente();
                if (empty($name) and empty($pass) and empty($usu_tipo)) {
                    header("Location:".conectar::ruta()."index.php?m=2");
                    exit();
                }else{
                    $sql ="SELECT * FROM tm_usuario WHERE usu_name= ? and usu_pass= ? and estado=1 ";
                    $stmt=$conectar->prepare($sql);
                    $stmt->bindValue(1, $name);
                    $stmt->bindValue(2, md5($pass)); // cifrando a md5 la pass
                    $stmt->execute();
                    //se agrega variable para almacenar el usuario
                    $resultado = $stmt->fetch();
                    if (is_array($resultado) and count($resultado) > 0) {
                        $_SESSION["usu_id"] = $resultado["usu_id"];
                        $_SESSION["usu_nom"] = $resultado["usu_nom"];
                        $_SESSION["usu_ape"] = $resultado["usu_ape"];
                        $_SESSION["usu_tipo"] = $resultado["usu_tipo"];
                        $_SESSION["usu_correo"] = $resultado["usu_correo"];
                        $_SESSION["usu_telefono"] = $resultado["usu_telefono"];
                        header("Location:".Conectar::ruta()."view/Home/");
                        $mensaje="el usuario {$_SESSION['usu_nom']} {$_SESSION['usu_ape']} inició sesión desde la IP: $ipCliente";
                        $log->add_log_registro( $_SESSION["usu_id"],'Inicio sesion',$mensaje); 
                        exit();
                     }else{ 
                        $mensaje="el usuario {$_POST['usu_name']} intento iniciar sesion, ip: $ipCliente";
                        $log->add_log_registro( 0,'Inicio sesion',$mensaje); 
                        header("Location:".Conectar::ruta()."index.php?m=1");
                        exit();
                    }
                
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
        public function get_tipo($usu_id){
            $conectar = parent::conexion();
            parent::set_names();
            $sql ="SELECT * FROM tm_usuario where usu_id = ? ";
            $stmt=$conectar->prepare($sql);
            $stmt->bindValue(1, $usu_id);
            $stmt->execute();
            //se agrega variable para almacenar el usuario
            $resultado = $stmt->fetchAll();
            if (is_array($resultado) and count($resultado) > 0) {
                return $resultado;
            }else {
                return false;
            }
        }

        public function get_datos_contacto($usu_id){
            try {
                $conectar = parent::conexion();
                parent::set_names();
                $sql = "SELECT usu_nom, usu_ape, usu_telefono, usu_correo FROM tm_usuario WHERE usu_id = ?";
                $stmt = $conectar->prepare($sql);
                $stmt->bindValue(1, $usu_id);
                $stmt->execute();
                $resultado = $stmt->fetchAll();
                
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

        public function get_todos_usuarios()
        {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_udu_tipo";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql ->fetchAll();

            if(is_array($resultado) and count($resultado) > 0){
                return $resultado;
            }else {
                ?> <script>console.log("No se encontraron Eventos")</script><?php
                return 0;
            }
        }

        //add_categoria (Insert categoria)
        public function add_usuario($usu_nom,$usu_ape,$usu_correo,$usu_name,$usu_pass,$fecha_crea,$estado,$usu_tipo) {
            try {
                $conectar = parent::conexion();
                parent::set_names();
                $sql = "INSERT INTO tm_usuario (usu_nom, usu_ape, usu_correo, usu_name,usu_pass, fecha_crea, estado,usu_tipo,usu_telefono) VALUES (:usu_nom, :usu_ape, :usu_correo, :usu_name,:usu_pass, :fecha_crea, :estado,:usu_tipo,:usu_telefono)";

                $consulta = $conectar->prepare($sql);
                $numero = 12345678;
                $consulta->bindParam(':usu_nom',$usu_nom);
                $consulta->bindParam(':usu_ape',$usu_ape);
                $consulta->bindParam(':usu_correo',$usu_correo);
                $consulta->bindParam(':usu_name',$usu_name);
                $consulta->bindParam(':usu_pass',md5($usu_pass));
                $consulta->bindParam(':fecha_crea',$fecha_crea);
                $consulta->bindParam(':estado',$estado);
                $consulta->bindParam(':usu_tipo',$usu_tipo);
                $consulta->bindParam(':usu_telefono',$numero);

                $consulta->execute();
                
                if ($consulta->rowCount() > 0) {
                    return true;
                } else {
                    ?> <script>console.log("No se agrego el usuario ". $usu_nom ." ")</script><?php
                    return false;
                }
            } catch (Exception $e) {
                ?> <script> console.log("Error catch    add_usuario") </script>  <?php
                throw $e;
            }

        }


public function update_password($old_pass, $new_pass, $usu_id){
    $conectar = parent::conexion();
    parent::set_names();

    $hashed_old_pass = md5($old_pass);
    $sql = "SELECT usu_pass FROM tm_usuario WHERE usu_id = :usu_id AND usu_pass = :old_pass";
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':usu_id', $usu_id);
    $consulta->bindParam(':old_pass', $hashed_old_pass);
    $consulta->execute();
    $user = $consulta->fetch();

    // Verificar si la contraseña antigua es correcta
    if (!$user) {
        return array('status' => 'warning', 'message' => 'La contraseña antigua no coincide');
    }

    // Verificar si la nueva contraseña es igual a la antigua
    if ($user['usu_pass'] == md5($new_pass)) {
        return array('status' => 'info', 'message' => 'La nueva contraseña debe ser distinta a la antigua');
    }

    // Actualizar la contraseña
    $hashed_new_pass = md5($new_pass); // Almacenar el resultado de md5($new_pass) en una variable
    $sql = "UPDATE tm_usuario SET usu_pass = :new_pass WHERE usu_id = :usu_id";
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':new_pass', $hashed_new_pass); // Pasar la variable a bindParam
    $consulta->bindParam(':usu_id', $usu_id);
    $consulta->execute();

    // Verificar si la contraseña se actualizó correctamente
    if ($consulta->rowCount() == 1) {
        return array('status' => 'success', 'message' => 'Contraseña actualizada con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}

public function update_phone($new_phone, $usu_id){
    $conectar = parent::conexion();
    parent::set_names();

    // Limpiar el número de teléfono y dejar solo los números
    $clean_phone = preg_replace('/\D/', '', $new_phone);

    // Verificar si la longitud del número de teléfono es correcta
    if (strlen($clean_phone) != 9) {
        return array('status' => 'warning', 'message' => 'La longitud del número de teléfono no es correcta');
    }

    $sql = "UPDATE tm_usuario SET usu_telefono = :new_phone WHERE usu_id = :usu_id";
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':new_phone', $clean_phone);
    $consulta->bindParam(':usu_id', $usu_id);
    $consulta->execute();

    // Verificar si el número de teléfono se actualizó correctamente
    if ($consulta->rowCount() == 1) {
        return array('status' => 'success', 'message' => 'Número de teléfono actualizado con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}

public function get_info_usuario($usu_id){
    $conectar = parent::conexion();
    parent::set_names();
    $sql = 'SELECT 
            	usu.usu_nom as "Nombre",
                usu.usu_ape as "Apellido",
                tp.usu_tipo_nom as "Tipo",
                usu.usu_telefono as "Telefono",
                usu.usu_correo as "Correo",
                usu.usu_name as "Usuario"
            FROM `tm_usuario` as usu
            JOIN tm_udu_tipo as tp
            ON(tp.usu_tipo_id=usu.usu_tipo)
            WHERE usu.usu_id=:usu_id;';
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':usu_id',$usu_id);
    $consulta->execute();
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    if ($consulta->rowCount()!=1){
        return array('status' => 'error', 'message' => 'No se puede optener los datos');
    }
    return array('status'=> 'success', 'message' =>  'se optienen los datos', 'result'=> $resultado);
}

public function get_full_usuarios(){
    $conectar = parent::conexion();
    parent::set_names();
    $sql = 'SELECT 
            	usu.usu_id as "usu_id",
            	usu.estado as "estado",
            	usu.usu_nom as "Nombre",
                usu.usu_ape as "Apellido",
                tp.usu_tipo_nom as "Tipo",
                tp.usu_tipo_id as "id_tipo",
                usu.usu_telefono as "Telefono",
                usu.usu_correo as "Correo",
                usu.usu_name as "Usuario"
            FROM `tm_usuario` as usu
            JOIN tm_udu_tipo as tp
            ON(tp.usu_tipo_id=usu.usu_tipo);';
    $consulta = $conectar->prepare($sql);
    $consulta->execute();
    $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if ($consulta->rowCount()<=0){
        return array('status' => 'error', 'message' => 'No se puede optener los datos');
    }
    return array('status'=> 'success', 'message' =>  'se optienen los datos', 'result'=> $resultado);
    }
public function disable_usuario($usu_id){
    $conectar = parent::conexion();
    parent::set_names();

    $fecha_elim = date('Y-m-d H:i:s'); // Get current date and time
    $estado = 0; // Assuming 0 is the value for 'disabled'

    $sql = "UPDATE tm_usuario SET estado = :estado, fecha_elim = :fecha_elim WHERE usu_id = :usu_id";
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':estado', $estado);
    $consulta->bindParam(':fecha_elim', $fecha_elim);
    $consulta->bindParam(':usu_id', $usu_id);
    $consulta->execute();

    if ($consulta->rowCount() == 1) {
        return array('status' => 'success', 'message' => 'Usuario desactivado con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}
public function enable_usuario($usu_id){
    $conectar = parent::conexion();
    parent::set_names();

    $estado = 1; // Assuming 1 is the value for 'enabled'
    $fecha_elim = null; // Set 'fecha_elim' to null

    $sql = "UPDATE tm_usuario SET estado = :estado, fecha_elim = :fecha_elim WHERE usu_id = :usu_id";
    $consulta = $conectar->prepare($sql);
    $consulta->bindParam(':estado', $estado);
    $consulta->bindParam(':fecha_elim', $fecha_elim);
    $consulta->bindParam(':usu_id', $usu_id);
    $consulta->execute();

    if ($consulta->rowCount() == 1) {
        return array('status' => 'success', 'message' => 'Usuario activado con éxito');
    } else {
        return array('status' => 'info', 'message' => 'No se realizó ningún cambio');
    }
}
    
}
?>
