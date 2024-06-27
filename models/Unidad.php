<?php
class Unidad extends Conectar {

    public function get_unidad() {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_unidad ";
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
                ?> <script>console.log("No se encontraron Eventos")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script>console.log("Error catch     get_unidad")</script> <?php
            throw $e;
        }

    }

    //get_datos_unidad segun su id
    public function get_datos_unidad($unid_id) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_unidad where unid_id = :unid_id";
            $sql = $conectar->prepare($sql);
            $sql->bindParam(':unid_id', $unid_id);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
                ?> <script>console.log("No se encontraron Unidades")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script>console.log("Error catch     get_datos_unidad")</script> <?php
            throw $e;
        }

    }

    //get_unidad_dispo segun disponibilidad
    public function get_unidad_est($unid_est) {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_unidad where unid_est = :unid_est ";
            $sql->bindParam(':unid_est', $unid_est);
            $sql = $conectar->prepare($sql);
            $sql->execute();
            $resultado = $sql->fetchAll();
            
            if (is_array($resultado) && count($resultado) > 0) {
                return $resultado;
            } else {
                ?> <script>console.log("No se encontraron Unidades")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script>console.log("Error catch     get_unidad_est")</script> <?php
            throw $e;
        }

    }

    public function add_unidad($unid_nom,$unid_est,$responsable_rut,$reemplazante_rut) {
        try{
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_unidad (unid_nom,unid_est,responsable_rut,reemplazante_rut) VALUES ( :unid_nom, :unid_est,:responsable_rut,:reemplazante_rut)";
            
            $consulta  = $conectar->prepare($sql);
        
            $consulta->bindParam(':unid_nom', $unid_nom);
            $consulta->bindParam(':unid_est', $unid_est);
            $consulta->bindParam(':responsable_rut', $responsable_rut);
            $consulta->bindParam(':reemplazante_rut', $reemplazante_rut);
            
            $consulta->execute();

            if ($consulta->rowCount() > 0) {
                return true;
            } else {
                ?> <script>console.log("No se encontraron unidades")</script><?php
                return 0;
            }
        } catch (Exception $e) {
            ?> <script>console.log("Error catch     add_unidad")</script> <?php
            throw $e;
        }
    }

    //update_unidad segun id
public function update_unidad($unid_id, $unid_nom, $unid_est, $responsable_rut, $reemplazante_rut) {
    try {
        // Validar que no falten datos
        if (empty($unid_id) || empty($unid_nom) || empty($unid_est) || empty($responsable_rut) || empty($reemplazante_rut)) {
            return [
                'status' => 'warning',
                'message' => 'Todos los campos son obligatorios.'
            ];
        }

        $conectar = parent::conexion();
        parent::set_names();

        $sql = "UPDATE tm_unidad SET 
                    unid_nom = :unid_nom, 
                    unid_est = :unid_est, 
                    responsable_rut = :responsable_rut, 
                    reemplazante_rut = :reemplazante_rut 
                WHERE unid_id = :unid_id";
        $consulta = $conectar->prepare($sql);

        $consulta->bindParam(':unid_id', $unid_id, PDO::PARAM_INT);
        $consulta->bindParam(':unid_nom', $unid_nom);
        $consulta->bindParam(':unid_est', $unid_est);
        $consulta->bindParam(':responsable_rut', $responsable_rut);
        $consulta->bindParam(':reemplazante_rut', $reemplazante_rut);

        $consulta->execute();

        if ($consulta->rowCount() > 0) {
            return [
                'status' => 'success',
                'message' => 'Unidad actualizada correctamente.'
            ];
        } else {
            return [
                'status' => 'warning',
                'message' => 'No se logró actualizar la unidad o no hubo cambios en los datos.'
            ];
        }
    } catch (Exception $e) {
        return [
            'status' => 'error',
            'message' => 'Ocurrió un error al intentar actualizar la unidad: ' . $e->getMessage()
        ];
    }
}

    //delete_unidad segun id
	

public function delete_unidad($unid_id) {
    try {
        $conectar = parent::conexion();
        parent::set_names();

        // Verificar si la unidad está en uso en la tabla tm_ev_tm_unid
        $sql_check_evun = "SELECT * FROM tm_ev_tm_unid WHERE unid_id = :unid_id";
        $consulta_check_evun = $conectar->prepare($sql_check_evun);
        $consulta_check_evun->bindParam(':unid_id', $unid_id, PDO::PARAM_INT);
        $consulta_check_evun->execute();

        // Verificar si la unidad está en uso en la tabla tm_usuario
        $sql_check_usuario = "SELECT * FROM tm_usuario WHERE usu_unidad = :unid_id";
        $consulta_check_usuario = $conectar->prepare($sql_check_usuario);
        $consulta_check_usuario->bindParam(':unid_id', $unid_id, PDO::PARAM_INT);
        $consulta_check_usuario->execute();

        if ($consulta_check_evun->rowCount() > 0 || $consulta_check_usuario->rowCount() > 0) {
            return [
                'status' => 'warning',
                'message' => 'La unidad no se puede eliminar porque está en uso en otros registros.'
            ];
        } else {
            // La unidad no está en uso, proceder a eliminarla
            $sql_delete = "DELETE FROM tm_unidad WHERE unid_id = :unid_id";
            $consulta_delete = $conectar->prepare($sql_delete);
            $consulta_delete->bindParam(':unid_id', $unid_id, PDO::PARAM_INT);
            $consulta_delete->execute();

            if ($consulta_delete->rowCount() > 0) {
                return [
                    'status' => 'success',
                    'message' => 'Unidad eliminada correctamente.'
                ];
            } else {
                return [
                    'status' => 'warning',
                    'message' => 'No se logró borrar la unidad.'
                ];
            }
        }
    } catch (Exception $e) {
        return [
            'status' => 'error',
            'message' => 'Ocurrió un error al intentar eliminar la unidad: ' . $e->getMessage()
        ];
    }
}


}
