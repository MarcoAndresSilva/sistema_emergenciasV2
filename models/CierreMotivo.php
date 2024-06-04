<?php

class CierreMotivo extends Conectar {
    function add_motivo_cierre($motivo){
        $conexion = parent::Conexion();
        $sql = "INSERT INTO tm_cierre_motivo (motivo) VALUES (:motivo)";
        $query = $conexion->prepare($sql);
        $query->bindParam(':motivo',$motivo);
        $query->execute();
        $resultado = $query->fetch();
        if (is_array($resultado) and count($resultado) > 0) {
            return true;
        }elseif(is_array($resultado) and count($resultado) == 0){
            return false;
        }
    }
    function update_motivo_cierre($motivo,$id_mov){ 
        $conexion = parent::Conexion();
        $sql = "UPDATE tm_cierre_motivo SET motivo:motivo WHERE id_mov=:id_mov";
        $query = $conexion->prepare($sql);
        $query->bindParam(':motivo',$motivo);
        $query->bindParam(':id_mov',$id_mov);
        $query->execute();
        $resultado = $query->fetch();
        if (is_array($resultado) and count($resultado) > 0) {
            return true;
        }elseif(is_array($resultado) and count($resultado) == 0){
            return false;
        }
    }
    function delete_motivo_cierre($id_mov){ 
        $conexion = parent::Conexion();
        $sql = "DELETE tm_cierre_motivo WHERE id_mov=:id_mov";
        $query = $conexion->prepare($sql);
        $query->bindParam(':id_mov',$id_mov);
        $query->execute(); 
        $resultado = $query->fetch();
        if (is_array($resultado) and count($resultado) > 0) {
            return true;
        }elseif(is_array($resultado) and count($resultado) == 0){
            return false;
        }
    }
    function get_motivo_cierre(){
        $conexion = parent::Conexion();
        $sql = "SELECT * FROM tm_cierre_motivo";
        $query = $conexion->prepare($sql);
        $query->bindParam(':id_mov',$id_mov);
        $query->execute();
        $resultado = $query->fetch();
        if (is_array($resultado) and count($resultado) > 0) {
            return $resultado;
        }elseif(is_array($resultado) and count($resultado) == 0){
            return false;
        }
    }
}
