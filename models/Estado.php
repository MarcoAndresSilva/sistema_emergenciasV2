<?php
class Estado extends Conectar {
    
    public function get_estados(){

        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tm_estado";
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

    //get_datos_estado segun su id
    public function get_datos_estado($est_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_estado where est_id = ". $est_id ." ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
                ?> <script>console.log("No se encontraron Eventos")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script>console.log("Error catch     get_datos_estado")</script> <?php
            throw $e;
        }

    }

    //add_estado agrega un estado nuevo
    public function add_estado($est_nom) {
        try{
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_estado (est_nom) VALUES ( :est_nom)";
            
            $consulta  = $conectar->prepare($sql);
        
            $consulta->bindParam(':est_nom', $est_nom);
            
            $consulta->execute();

            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se encontraron Eventos")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script>console.log("Error catch     add_estado")</script> <?php
            throw $e;
        }
    }

}
