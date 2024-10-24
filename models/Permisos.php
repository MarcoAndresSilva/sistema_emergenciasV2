<?php
require_once(__DIR__."/../config/conexion.php");
class Permisos extends Conectar{
  public static function verificarLogin() {
        try {
            return !empty($_SESSION["usu_id"]) && !empty($_SESSION["usu_tipo"]);
        } catch (Exception $e) {
            // Puedes registrar el error si es necesario
            error_log($e->getMessage());
            return false;
        }
  }
  public static function redirigirSiNoAutorizado($pagina = null) {
        // INFO: Default deja que el usuario pueda ver la pagina si se logea
       $resultado = false;
       if ($pagina != null && !self::verificarLogin()) {
            header("Location: " . Conectar::ruta() . "index.php");
            exit();
       }elseif(self::verificarLogin()){
            $resultado = true;
       }
       if(!empty($pagina)){
          // NOTE: crear una instancia de la clase para que pueda llamar a la funcion
          $permisos = new Permisos();
          $resultado = $permisos->verificarPermiso($pagina);
       }
       if(!$resultado){
          header("Location: " . Conectar::ruta() . "index.php");
          exit();
       }
  }

  public static function isPermited($permiso){
    $permisos = new Permisos();
    return $permisos->verificarPermiso($permiso);
  }

  public function verificarPermiso($permiso){
    $usuario = $_SESSION["usu_id"];
    $permisos = $this->usuarios_permitidos($permiso);
    if(count($permisos) > 0){
      foreach ($permisos as $permiso){
        if($permiso["usu_id"] == $usuario){
          return true;
        }
      }
    }
    return false;
  }

  public function usuarios_permitidos($permiso){
    //  NOTE: no importa las mayusculas o minusculas
    $sql = "select * from tm_permisos where LOWER(permiso)= LOWER(:permiso)";
    $params = [":permiso" => $permiso];
    $informacionBruta = $this->ejecutarConsulta($sql, $params, false);
    $lista_usuario = $this->limpiarIds($informacionBruta);
    return $lista_usuario;
  }

  private function lista_usuarios(string $ids, string $id_name){
    // WARNING: no se puede usar el ejecutarConsulta porque agrega comillas o 
    // termina utilizando los numeros como un string
    $conectar = parent::conexion();
    parent::set_names();
    $idsArray = explode(',', $ids);
    $placeholders = implode(',', array_fill(0, count($idsArray), '?'));
    $sql = "SELECT * FROM tm_usuario WHERE $id_name IN ($placeholders);";
    $consulta = $conectar->prepare($sql);
    $consulta->execute($idsArray);
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
  }

  private function limpiarIds($list_regla){
    $filtro = [
      "tipo_usuario" => [
        "id_name" =>"usu_tipo",
        "value"=> $list_regla["tipo_usuario"],
      ],
      "usuario" =>[
        "id_name" =>"usu_id",
        "value"=> $list_regla["usuario"],
      ],
      "seccion" =>[
        "id_name" =>"usu_seccion",
        "value"=> $list_regla["seccion"],
      ],
      "unidad" =>[
        "id_name" =>"usu_unidad",
        "value"=> $list_regla["unidad"],
      ]
    ];

    // Si el valor es null, asigna un array vacío
    $tipo_usuario = $filtro["tipo_usuario"]["value"] !== null
        ? $this->lista_usuarios($filtro["tipo_usuario"]["value"], $filtro["tipo_usuario"]["id_name"])
        : [];

    $usuario = $filtro["usuario"]["value"] !== null
        ? $this->lista_usuarios($filtro["usuario"]["value"], $filtro["usuario"]["id_name"])
        : [];

    $unidad = $filtro["unidad"]["value"] !== null
        ? $this->lista_usuarios($filtro["unidad"]["value"], $filtro["unidad"]["id_name"])
        : [];

    $seccion = $filtro["seccion"]["value"] !== null
        ? $this->lista_usuarios($filtro["seccion"]["value"], $filtro["seccion"]["id_name"])
        : [];

    return $this->eliminarDuplicadosPorUsuId($tipo_usuario,$usuario,$unidad,$seccion);
  }

  public function eliminarDuplicadosPorUsuId(...$listas) {
       $todosLosElementos = [];
       foreach ($listas as $lista) {
           $todosLosElementos = array_merge($todosLosElementos, $lista);
       }
       // Utilizar un array asociativo para eliminar duplicados basados en usu_id
       $usuariosUnicos = [];
       foreach ($todosLosElementos as $elemento) {
           $usuariosUnicos[$elemento['usu_id']] = $elemento;
       }
       return array_values($usuariosUnicos);
  }
  public function update_permisos($id, $unidad, $seccion, $usuario, $tipo_usuario){
    $sql = "UPDATE tm_permisos SET unidad = :unidad, seccion = :seccion, usuario = :usuario, tipo_usuario = :tipo_usuario WHERE id_permiso = :id";
    $params = [
      ":unidad" => $unidad,
      ":seccion" => $seccion,
      ":usuario" => $usuario,
      ":tipo_usuario" => $tipo_usuario,
      ":id" => $id
    ];
    $resultado = $this->ejecutarAccion($sql, $params);
    if($resultado){
      return ["status"=> "success", "message"=> "Permisos actualizados correctamente"];
    }else{
      return ["status"=> "warning", "message"=> "no se pudo actualizar los permisos"];
    }
  }
  public function get_permisos(){
      $sql = "SELECT * FROM tm_permisos";
      $resultado = $this->ejecutarConsulta($sql);
      if($resultado){
        return $resultado;
      }else{
        return [];
      }
   }
}
?>
