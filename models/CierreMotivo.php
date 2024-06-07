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

    function update_motivo_cierre($motivo, $mov_id) { 
        // Validar que $mov_id sea un número
        if (!is_numeric($mov_id)) {
            return array('status' => 'warning', 'message' => " ID del motivo debe ser un número, ID recibida: $mov_id.");
        }
    
        // Validar que $motivo sea una cadena de texto
        if (!is_string($motivo)) {
            return array('status' => 'warning', 'message' => 'El motivo debe ser un texto.');
        }
    
        $conexion = parent::Conexion();
        $sql = "UPDATE tm_cierre_motivo SET motivo = :motivo WHERE mov_id = :mov_id";
        $query = $conexion->prepare($sql);
        $query->bindParam(':motivo', $motivo);
        $query->bindParam(':mov_id', $mov_id);
    
        try {
            $query->execute();
            // Verificar si se realizó la actualización correctamente
            if ($query->rowCount() > 0) {
                return array('status' => 'success', 'message' => 'Motivo actualizado correctamente.');
            } else {
                return array('status' => 'warning', 'message' => 'No se encontró ningún registro para actualizar.');
            }
        } catch (PDOException $e) {
            return array('status' => 'error', 'message' => 'Error al ejecutar la consulta: ' . $e->getMessage());
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
    function update_motivo_cate($mov_id, $categorias){
        $conexion = parent::Conexion();
        $response = ['status' => 'success', 'message' => 'Actualización realizada con éxito'];
        try {
            foreach($categorias as $cat_nom => $cat_data){
                $cat_id = $cat_data['id'];
                $activo = $cat_data['value'] ? 1 : 0;
    
                // Primero, verificamos si el registro ya existe
                $sql = "SELECT * FROM tm_motivo_cate WHERE cat_id = :cat_id AND mov_id = :mov_id";
                $query = $conexion->prepare($sql);
                $query->execute(['cat_id' => $cat_id, 'mov_id' => $mov_id]);
                $resultado = $query->fetch();
    
                if($resultado){
                    // Si el registro existe, lo actualizamos
                    $sql = "UPDATE tm_motivo_cate SET activo = :activo WHERE cat_id = :cat_id AND mov_id = :mov_id";
                } else {
                    // Si el registro no existe, lo insertamos
                    $sql = "INSERT INTO tm_motivo_cate (cat_id, mov_id, activo) VALUES (:cat_id, :mov_id, :activo)";
                }
    
                $query = $conexion->prepare($sql);
                $query->execute(['cat_id' => $cat_id, 'mov_id' => $mov_id, 'activo' => $activo]);
            }
        } catch (Exception $e) {
            $response['status'] = 'error';
            $response['message'] = 'Hubo un error al actualizar: ' . $e->getMessage();
        }
        return $response;
    }
    function get_motivo_categoria(){
        $conexion = parent::Conexion();
        $sql = 'SELECT
                    cat.cat_nom as "categoria", 
                    mov.motivo as "motivo" 
                FROM tm_categoria as cat 
                JOIN tm_motivo_cate as mc  
                ON(cat.cat_id= mc.cat_id) 
                JOIN tm_cierre_motivo as mov 
                ON(mov.mov_id=mc.mov_id);';
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
