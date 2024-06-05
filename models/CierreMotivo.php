<?php

class CierreMotivo extends Conectar {
    function add_motivo_cierre($motivo){
        $response = array();

        if(empty($motivo)){
            $response['status'] = 'error';
            $response['message'] = 'El motivo no puede estar vacio.';
            return $response;
        }
        $conexion = parent::Conexion();
        $sql = "SELECT * FROM tm_cierre_motivo WHERE motivo = :motivo;";
        $query = $conexion->prepare($sql);
        $query->bindParam(':motivo', $motivo);
        $query->execute();
        $resultado = $query->fetch();
    
        if(is_array($resultado) and count($resultado) > 0){
            $response['status'] = 'warning';
            $response['message'] = 'El motivo ya existe.';
            return $response;
        }
    
        $sql = "INSERT INTO tm_cierre_motivo (motivo) VALUES (:motivo);";
        $query = $conexion->prepare($sql);
        $query->bindParam(':motivo', $motivo);
        $query->execute();
    
        if($query->rowCount() > 0){
            $response['status'] = 'success';
            $response['message'] = 'El motivo de cierre se agregó correctamente.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No se pudo insertar el motivo.';
        }
        return $response;
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
    $response = [];
    $conexion = parent::Conexion();
    $sql = "DELETE FROM tm_cierre_motivo WHERE mov_id=:id_mov";
    $query = $conexion->prepare($sql);
    $query->bindParam(':id_mov',$id_mov);
    $query->execute(); 
    $numRows = $query->rowCount();
    if ($numRows > 0) {
        $response['status'] = 'success';
        $response['message'] = 'Se pudo borrar el motivo de forma exitosa';
    } else {
        $response['status'] = 'warning';
        $response['message'] = 'Problemas al eliminar el motivo de cierre';
    }
    return $response;
}
    function get_motivo_cierre(){
        $conexion = parent::Conexion();
        $sql = "SELECT * FROM tm_cierre_motivo";
        $query = $conexion->prepare($sql);
        $query->execute();
        $resultado = $query->fetchAll();
        if (is_array($resultado) and count($resultado) > 0) {
            return $resultado;
        }elseif(is_array($resultado) and count($resultado) == 0){
            return false;
        }
    }
}
