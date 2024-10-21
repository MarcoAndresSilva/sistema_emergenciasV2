<?php
require_once '../config/conexion.php';

class Seccion extends Conectar {
    public function add_seccion($sec_nombre, $sec_detalle, $sec_unidad){
        $sql = "INSERT INTO tm_seccion (sec_nombre, sec_detalle, sec_unidad) VALUES (:sec_nombre, :sec_detalle, :sec_unidad)";
        $params = [
            ':sec_nombre' => $sec_nombre,
            ':sec_detalle' => $sec_detalle,
            ':sec_unidad' => $sec_unidad
        ];
        $resultado = $this->ejecutarAccion($sql, $params);
        if ($resultado) {
            return array('status' => 'success', 'message' => 'Sección agregada correctamente');
        } else {
            return array('status' => 'error', 'message' => 'No se pudo agregar la sección');
        }
    }
    public function update($sec_id,$sec_nombre, $sec_detalle, $sec_unidad){
        $sql = "UPDATE tm_seccion SET sec_nombre = :sec_nombre, sec_detalle = :sec_detalle, sec_unidad = :sec_unidad WHERE sec_id = :sec_id";
        $params = [
            ':sec_nombre' => $sec_nombre,
            ':sec_detalle' => $sec_detalle,
            ':sec_unidad' => $sec_unidad,
            ':sec_id' => $sec_id
        ];
        $resultado = $this->ejecutarAccion($sql, $params);
        if ($resultado) {
            return array('status' => 'success', 'message' => 'Sección actualizada correctamente');
        } else {
            return array('status' => 'error', 'message' => 'No se pudo actualizar la sección');
        }
    }
    public function get_seccion($sec_id){
        $sql = "SELECT * FROM tm_seccion WHERE sec_id = :sec_id";
        $params = [':sec_id' => $sec_id];
        $resultado = $this->ejecutarConsulta($sql, $params);
        if (is_array($resultado) && count($resultado) > 0) {
            return $resultado;
        } else {
            return false;
        }
    }
    public function get_usuarios_por_seccion($sec_id){
        $sql = "SELECT * FROM tm_usuario WHERE usu_seccion = :sec_id";
        $params = [':sec_id' => $sec_id];
        $resultado = $this->ejecutarConsulta($sql, $params);
        if (is_array($resultado) && count($resultado) > 0) {
            return $resultado;
        } else {
            return false;
        }
    }
    public function get_secciones($unidad){
        $sql = "SELECT * FROM tm_seccion WHERE sec_unidad = :unidad";
        $params = [':unidad' => $unidad];
        $resultado = $this->ejecutarConsulta($sql, $params);
        if (is_array($resultado) && count($resultado) > 0) {
            return $resultado;
        } else {
            return ["status"=>"warning","message"=>"No se pudo obtener las secciones del unidad"];
        }
    }
}
?>
