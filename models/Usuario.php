<?php
    class Usuario extends Conectar {

        public function login() {
            $conectar = parent::conexion();
            parent::set_names();
            if (isset($_POST["enviar"])) {
                $name = $_POST["usu_name"];
                $pass = $_POST["usu_pass"];
                if (empty($name) and empty($pass) and empty($usu_tipo)) {
                    header("Location:".conectar::ruta()."index.php?m=2");
                    exit();
                }else{
                    $sql ="SELECT * FROM tm_usuario WHERE usu_name= ? and usu_pass= ? and estado=1 ";
                    $stmt=$conectar->prepare($sql);
                    $stmt->bindValue(1, $name);
                    $stmt->bindValue(2, $pass);
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
                        exit();
                    }else {
                        header("Location:".Conectar::ruta()."index.php?m=1");
                        exit();
                    }
                
                }
            }
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
                $sql = "INSERT INTO tm_usuario (usu_nom, usu_ape, usu_correo, usu_name,usu_pass, fecha_crea, estado,usu_tipo) VALUES (:usu_nom, :usu_ape, :usu_correo, :usu_name,:usu_pass, :fecha_crea, :estado,:usu_tipo)";

                $consulta = $conectar->prepare($sql);

                $consulta->bindParam(':usu_nom',$usu_nom);
                $consulta->bindParam(':usu_ape',$usu_ape);
                $consulta->bindParam(':usu_correo',$usu_correo);
                $consulta->bindParam(':usu_name',$usu_name);
                $consulta->bindParam(':usu_pass',$usu_pass);
                $consulta->bindParam(':fecha_crea',$fecha_crea);
                $consulta->bindParam(':estado',$estado);
                $consulta->bindParam(':usu_tipo',$usu_tipo);

                $consulta->execute();
                
                if ($consulta->rowCount() > 0) {
                    return true;
                } else {
                    ?> <script>console.log("No se agrego el usuario ". $usu_nom ." ")</script><?php
                    return 0;
                }
            } catch (Exception $e) {
                ?> <script> console.log("Error catch    add_usuario") </script>  <?php
                throw $e;
            }

        }
    }

    
?>