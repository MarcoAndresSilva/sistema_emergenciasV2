<?php
require_once '../config/conexion.php';
require_once '../models/Unidad.php';

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
        $sql = "SELECT sec.sec_id as 'sec_id',
                     sec.sec_nombre as 'sec_nombre',
                     sec.sec_detalle as 'sec_detalle',
                     sec.sec_est as 'sec_est',
                     sec.sec_unidad as 'sec_unidad',
                     uni.unid_nom as 'sec_unidad_nom'
               FROM tm_seccion as sec
               JOIN tm_unidad as uni
               on(uni.unid_id=sec.sec_unidad)
               WHERE sec_id = :sec_id";
        $params = [':sec_id' => $sec_id];
        $resultado = $this->ejecutarConsulta($sql, $params, false);
        if (is_array($resultado) && count($resultado) > 0) {
            return $resultado;
        } else {
            return false;
        }
    }
  public function get_todos_secciones(){
    $sql = "SELECT * FROM tm_seccion";
    $resultado = $this->ejecutarConsulta($sql);
    if (is_array($resultado) && count($resultado) > 0) {
      return $resultado;
    } else {
      return [];
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
        $sql = "SELECT sec.sec_id as 'sec_id',
                     sec.sec_nombre as 'sec_nombre',
                     sec.sec_detalle as 'sec_detalle',
                     sec.sec_est as 'sec_est',
                     sec.sec_unidad as 'sec_unidad',
                     uni.unid_nom as 'sec_unidad_nombre'
               FROM tm_seccion as sec
               JOIN tm_unidad as uni
               on(uni.unid_id=sec.sec_unidad)
               WHERE sec_unidad = :unidad";
        $params = [':unidad' => $unidad];
        $resultado = $this->ejecutarConsulta($sql, $params);
        if (is_array($resultado) && count($resultado) > 0) {
            return $resultado;
        } else {
            return [
              "status"=>"warning",
              "message"=>"No se pudo obtener las secciones del unidad",
            ];
        }
    }
    private function seccion_tiene_usuarios($seccion){
        $sql = "SELECT * FROM tm_usuario WHERE usu_seccion = :seccion LIMIT 1";
        $params = [':seccion' => $seccion];
        $resultado = $this->ejecutarConsulta($sql, $params, false);
        if (is_array($resultado) && count($resultado) > 0) {
            return true;
        } else {
            return false;
        }
    }
    private function seccion_tiene_evento_asignado($seccion){
    $sql = "SELECT * FROM tm_evento as ev
            INNER JOIN tm_asignado as asig
            ON (asig.ev_id = ev.ev_id)
            WHERE asig.sec_id = :seccion LIMIT 1";
      $params = [':seccion' => $seccion];
      $resultado = $this->ejecutarConsulta($sql, $params, false);
      if (is_array($resultado) && count($resultado) > 0) {
        return true;
      } else {
        return false;
      }
    }
  public function delete_seccion($sec_id){
    if ($this->seccion_tiene_usuarios($sec_id)){
      return array('status' => 'Warning', 'message' => 'No se puede eliminar la sección porque tiene usuarios asignados');
    }
    if ($this->seccion_tiene_evento_asignado($sec_id)){
      return array('status' => 'warning', 'message' => 'No se puede eliminar la sección porque tiene eventos asignados');
    }
    $sql = "DELETE FROM tm_seccion WHERE sec_id = :sec_id";
    $params = [':sec_id' => $sec_id];
    $resultado = $this->ejecutarAccion($sql, $params);
    if ($resultado) {
      return array('status' => 'success', 'message' => 'Sección eliminada correctamente');
    } else {
      return array('status' => 'error', 'message' => 'No se pudo eliminar la sección');
    }

  }
  public function lista_usuarios($seccion){
    $sql = "SELECT * FROM tm_usuario WHERE usu_seccion = :seccion";
    $params = [':seccion' => $seccion];
    $resultado = $this->ejecutarConsulta($sql, $params);
    if (is_array($resultado) && count($resultado) > 0) {
      return $resultado;
    } else {
      return [];
    }
  }

  public function seccion_ocupado($seccion){
    $sql = "UPDATE tm_seccion SET sec_est = 0 WHERE sec_id = :seccion";
    $params = [':seccion' => $seccion];
    $resultado = $this->ejecutarAccion($sql, $params);
    if ($resultado) {
      return array('status' => 'success', 'message' => 'Sección actualizada correctamente');
    }
  }

  public function seccion_disponible($seccion){
    $sql = "UPDATE tm_seccion SET sec_est = 1 WHERE sec_id = :seccion";
    $params = [':seccion' => $seccion];
    $resultado = $this->ejecutarAccion($sql, $params);
    if ($resultado) {
      return array('status' => 'success', 'message' => 'Sección actualizada correctamente');
    }
  }
  public function lista_secciones_con_unidad(){
    $unidad = new Unidad();
    $lista_unidades = $unidad->get_unidad();
    $secciones = [];
    foreach ($lista_unidades as $unidad){
      $lista_secciones = $this->get_secciones($unidad['unid_id']);
      if (isset($lista_secciones["status"])){
         $lista_secciones=[];
      }
      $secciones[] = [
        'unidad' => $unidad['unid_nom'],
        'secciones' => $lista_secciones,
      ];
    }
    return $secciones;
  }
  public function get_secciones_evento($id_evento){
    $sql = "SELECT sec.sec_id as 'id',
                  sec.sec_nombre as 'nombre',
                  sec.sec_detalle as 'detalle',
                  sec.sec_est as 'estado',
                  if(sec.sec_est=1,'Disponible','Ocupado') as 'estado_seccion',
                  sec.sec_unidad as 'unidad_id',
                  uni.unid_nom as 'unidad'
            FROM tm_asignado as asig
            INNER JOIN tm_seccion as sec
            ON (sec.sec_id = asig.sec_id)
            INNER JOIN tm_evento as ev
            ON (ev.ev_id = asig.ev_id)
            INNER JOIN tm_unidad as uni
            ON (uni.unid_id = sec.sec_unidad)
            WHERE ev.ev_id = :id_evento";
    $params = [':id_evento' => $id_evento];
    $resultado = $this->ejecutarConsulta($sql, $params);
    if (is_array($resultado) && count($resultado) > 0) {
      return $resultado;
    }
  }
  public function update_disponible_todos_de_evento_cerrado($id_evento){
      $secciones = $this->get_secciones_evento($id_evento);
      foreach ($secciones as $seccion){
        $this->seccion_disponible($seccion['id']);
      }
  }
  public function seccion_estado($id_seccion){
    $seccion = $this->get_seccion($id_seccion);
    if ($seccion['sec_est']==1){
      return true;
    }
    return false;
  }
}
?>
