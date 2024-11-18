<?php
require_once "SeguridadPassword.php";
class Unidad extends Conectar
{
     // get_unidad
    public function get_unidad()
    {
        $sql = "SELECT * FROM tm_unidad";
        return $this->ejecutarConsulta($sql);
    }

    // get_datos_unidad según su id
    public function get_datos_unidad($unid_id)
    {
        $sql = "SELECT * FROM tm_unidad WHERE unid_id = :unid_id";
        $params = [':unid_id' => $unid_id];
        return $this->ejecutarConsulta($sql, $params);
    }
   public function get_seccion_unidad($sec_id)
    {
    $sql = "SELECT * FROM tm_seccion sec
    INNER JOIN tm_unidad uni ON uni.unid_id = sec.sec_unidad
    WHERE sec_id = :sec_id";
        $params = [':sec_id' => $sec_id];
        return $this->ejecutarConsulta($sql, $params);
    }

    // get_unidad_est según disponibilidad
    public function get_unidad_est($unid_est)
    {
        $sql = "SELECT * FROM tm_unidad WHERE unid_est = :unid_est";
        $params = [':unid_est' => $unid_est];
        return $this->ejecutarConsulta($sql, $params);
    }

    // add_unidad
    public function add_unidad($unid_nom, $unid_est)
    {
        if (empty($unid_nom) || empty($unid_est) ) {
            return ['status' => 'warning', 'message' => 'Todos los campos son obligatorios.'];
        }

        $sql = "INSERT INTO tm_unidad (unid_nom, unid_est) VALUES (:unid_nom, :unid_est)";
        $params = [
            ':unid_nom' => $unid_nom,
            ':unid_est' => $unid_est,
        ];

        if ($this->ejecutarAccion($sql, $params)) {
            $seguridad = new SeguridadPassword();
            $seguridad->create_password_Configuracion_a_ultima_unidad();
            return ['status' => 'success', 'message' => 'Unidad agregada exitosamente.'];
        } else {
            return ['status' => 'error', 'message' => 'No se pudo agregar la unidad.'];
        }
    }

    // update_unidad según id
    public function update_unidad($unid_id, $unid_nom, $unid_est, $responsable_rut, $reemplazante_rut)
    {
        if (empty($unid_id) || empty($unid_nom) || empty($unid_est) || empty($responsable_rut) || empty($reemplazante_rut)) {
            return ['status' => 'warning', 'message' => 'Todos los campos son obligatorios.'];
        }

        $sql = "UPDATE tm_unidad SET 
                    unid_nom = :unid_nom, 
                    unid_est = :unid_est, 
                    responsable_rut = :responsable_rut, 
                    reemplazante_rut = :reemplazante_rut 
                WHERE unid_id = :unid_id";
        $params = [
            ':unid_id' => $unid_id,
            ':unid_nom' => $unid_nom,
            ':unid_est' => $unid_est,
            ':responsable_rut' => $responsable_rut,
            ':reemplazante_rut' => $reemplazante_rut
        ];

        if ($this->ejecutarAccion($sql, $params)) {
            return ['status' => 'success', 'message' => 'Unidad actualizada correctamente.'];
        } else {
            return ['status' => 'warning', 'message' => 'No se logró actualizar la unidad o no hubo cambios en los datos.'];
        }
    }

    // delete_unidad según id
    public function delete_unidad($unid_id)
    {
        try {
            // Verificar si la unidad está en uso en otras tablas
            $sql_check_evun = "SELECT * FROM tm_asignado WHERE unid_id = :unid_id";
            $params = [':unid_id' => $unid_id];
            if ($this->ejecutarConsulta($sql_check_evun, $params)) {
                return ['status' => 'warning', 'message' => 'La unidad no se puede eliminar porque está en uso en otros registros.'];
            }

            $sql_check_usuario = "SELECT * FROM tm_usuario WHERE usu_unidad = :unid_id";
            if ($this->ejecutarConsulta($sql_check_usuario, $params)) {
                return ['status' => 'warning', 'message' => 'La unidad no se puede eliminar porque está en uso en otros registros.'];
            }

            // La unidad no está en uso, proceder a eliminarla
            $sql_delete = "DELETE FROM tm_unidad WHERE unid_id = :unid_id";
            if ($this->ejecutarAccion($sql_delete, $params)) {
                return ['status' => 'success', 'message' => 'Unidad eliminada correctamente.'];
            } else {
                return ['status' => 'warning', 'message' => 'No se logró borrar la unidad.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Ocurrió un error al intentar eliminar la unidad: ' . $e->getMessage()];
        }
    }
}
?>
