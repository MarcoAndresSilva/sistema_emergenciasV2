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
  public static function redirigirSiNoAutorizado() {
        if (!self::verificarLogin()) {
            header("Location: " . Conectar::ruta() . "index.php");
            exit();
        }
    }

  public function usuarios_permitidos($permiso){
    $sql = "select * from tm_permisos where permiso=:permiso";
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
}
?>
