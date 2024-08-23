<?php 
require_once 'Usuario.php';
class Noticia extends Conectar {

  public function add_noticia(string $asunto, string $mensaje, string $url=null){
    $sql = "INSERT INTO tm_noticia (asunto, mensaje, url) VALUES (:asunto,:mensaje,:url)";
    $params = [
      ':asunto'=> $asunto,
      ':mensaje'=> $mensaje,
      ':url'=> $url,
    ];
    $result = $this->ejecutarAccion($sql, $params);
    if ($result){
        return [
            'status' => 'success',
            'message' => "Se agregó la noticia"
        ];
    }
    return [
        'status' => 'warning',
        'message' => "Problemas al agregar dato"
    ];
  }

  /**
   * Crea una noticia y la envía a un grupo de usuarios basado en el asunto.
   *
   * @param array $argsNoticia Un array asociativo con los siguientes elementos:
   *  - 'asunto': (string) El asunto de la noticia.
   *  - 'mensaje': (string) El mensaje de la noticia.
   *  - 'url': (string|null) La URL asociada con la noticia (opcional).
   * 
   * @return array Un array con el estado y mensaje del proceso, junto con los resultados de las operaciones.
   */
  public function crear_noticia_y_enviar_grupo_usuario(array $agrsNoticia){
    $asunto = $agrsNoticia["asunto"];
    $mensaje = $agrsNoticia["mensaje"];
    $url = $agrsNoticia["url"];
    $add_noticia = $this->add_noticia($asunto,$mensaje,$url);
    if ($add_noticia["status"] !== "success"){
      return ["status"=>"error", "message"=>"Error al agregar", "add_noticia"=>$add_noticia];
    }
    $tipo_usuario = $this->regla_usuario_enviar_por_asunto($asunto);
    $ultima_noticia = $this->obtenerUltimoRegistro('tm_noticia',"noticia_id");
    $id_noticia_new = $ultima_noticia["noticia_id"];
    $envio_usuario = $this->enviar_noticia_grupal_por_tipo($id_noticia_new,$tipo_usuario);
    if ($envio_usuario["status"] !== "success"){
      return [
        "status"=>"error",
        "message"=>"Error al enviar",
        "add_noticia"=>$add_noticia,
        "enviar_grupo"=>$envio_usuario,
      ];
    }
    return [
      "status"=>"success",
      "message"=>"Crear y enviar Terminado",
      "add_noticia"=>$add_noticia,
      "enviar_grupo"=>$envio_usuario,
    ];
  }

 public function enviar_noticia_grupal_por_tipo(int $noticia, int $tipo_usuario) {
    $sql = "INSERT INTO tm_noticia_usuario(usu_id,noticia_id) VALUES ";
    $valores_usuario = $this->preparar_consulta_por_tipo_usuario($tipo_usuario,$noticia);
    $sql .= $valores_usuario;
    // ! FIX: puede ver error por sin dato en valores_usuario
    try {
        $result = $this->ejecutarAccion($sql);
        if ($result) {
            return array('status' => 'success', 'message' => "Se envió la noticia");
        } else {
            return array('status' => 'warning', 'message' => "Problemas al agregar dato");
        }
    } catch (PDOException $e) {
        return array('status' => 'error', 'message' => "Error en la base de datos: " . $e->getMessage());
    } catch (Exception $e) {
        return array('status' => 'error', 'message' => "Error: " . $e->getMessage());
    }
}
  public function enviar_noticia_simple(int $noticia_id, int $usuario_id){

    $sql = "INSERT INTO tm_noticia_usuario(usu_id,noticia_id) VALUES (:usuario_id,:noticia_id)";
    $params = [":usuario_id"=> $usuario_id, ":noticia_id"=>$noticia_id];
    $result = $this->ejecutarAccion($sql,$params);
    if($result){
      return ["status"=>"success","message"=>"se envio correo al usuario"];
    }
    return ["status"=>"warning", "message"=>"No se pudo enviar mensaje"];

  }
  public  function preparar_consulta_por_tipo_usuario($tipo_usuario,$id_noticia){
    $usuario = new Usuario();
    $list_usuario = $usuario->get_full_usuarios_tipo($tipo_usuario);
// ! FIX: en caso de que no tenga datos el list_usuario 
    $consulta = "";
    foreach($list_usuario as $item){
        $id_usuario = $item["usu_id"];
        $consulta .= "($id_usuario,$id_noticia),";
    }
    if (!empty($consulta)){
      $consulta = substr($consulta, 0, -1).';';
    }
    return $consulta;
  }
  public function check_mensaje_leido($noticia_id, $usuario_id){
    $fecha_lectura = date('Y-m-d H:i:s');
    $sql = "UPDATE tm_noticia_usuario SET leido=1, fecha_lectura=:fecha_lectura WHERE usu_id=:usuario_id and noticia_id=:noticia_id";
    $params = [
      ":usuario_id" => $usuario_id,
      ":noticia_id" => $noticia_id,
      ":fecha_lectura"=> $fecha_lectura,
    ];
    $result = $this->ejecutarAccion($sql,$params);
    if ($result){
      return array( 'status'=>'succes', 'message'=>"Se marca mensaje como leido");
    }
    return array('status'=>'error', 'message'=>"problemas al actualizar estado del mensaje noticia" );
  }
  public function get_noticias_usuario($usuario_id){
    $sql = "SELECT  nti.asunto AS 'asunto',
                    nti.mensaje AS 'mensaje',
                    ns.leido AS 'leido',
                    nti.noticia_id AS 'id',
                    coalesce(nti.url,'#') AS 'url'
            FROM tm_noticia_usuario as ns
            JOIN tm_noticia as nti
            ON (nti.noticia_id=ns.noticia_id)
            WHERE usu_id=:usuario_id;";
    $params = [":usuario_id"=>$usuario_id];
    $result = $this->ejecutarConsulta($sql,$params);
    if ($result){
      return $result;
    }
    return array('status'=>'error', 'message'=>"Problemas al optener informacion de las noticias");
  }
  public function regla_usuario_enviar_por_asunto(string $asunto){
      $super_user = 1;
      $admin = 2;
      $basico = 3;
      $actual = $_SESSION["usu_id"];

      $enviar = [
        "Nuevo Evento"=>$admin,
        "Cambio Perfil"=>$actual,
        "Cambio perfil"=>$basico,
        "Derivado"=>$admin
      ];

       return $enviar[$asunto];
  }
  public function lista_posibles_envios_por_ids(string $ids, string $id_name){
    $sql = "SELECT * FROM tm_usuario WHERE :id_name in ( :ids );";
    $params = [
      ":id_name" => $id_name,
      ":ids"=>$ids,
    ];
    return $this->ejecutarConsulta($sql,$params);
  }

}