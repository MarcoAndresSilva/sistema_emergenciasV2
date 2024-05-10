<?php

class NivelPeligro extends Conectar {

    //get_nivel_por_id
    public function get_nivel_por_id($ev_niv_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_ev_niv WHERE ev_niv_id = ". $ev_niv_id. " ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql ->fetchAll();

            if(is_array($resultado) and count($resultado) > 0){
                return $resultado;
            }else {
                ?> <script>console.log("No se encontraron Niveles de peligro con id " . $ev_niv_id . " ")</script><?php
                return 0;
            }
        }catch (Exception $e){
            echo "Error catch: ". $e -> getMessage() ." ...";
        }
    }

    //get_niveles_peligro
    public function get_nivel_peligro()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tm_ev_niv";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        $resultado = $sql ->fetchAll();

        if(is_array($resultado) and count($resultado) > 0){
            return $resultado;
        }else {
            ?> <script>console.log("No se encontraron Niveles de Peligro")</script><?php
            return 0;
        }
    }

}