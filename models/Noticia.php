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
      return array('status'=>'succes', 'message'=>"se agrego la noticia");
    }
    return array('status'=>'warning', 'message'=>"problemas al agregar dato");
  }
  public function enviar_noticia_usuario($noticia,$tipo_usuario) {
    $sql = "INSERT INTO tm_noticia_usuario(usu_id,noticia_id) VALUES ";
    $valores_usuario = $this->preparar_consulta_por_tipo_usuario($tipo_usuario,$noticia);
    $sql .= $valores_usuario;
    $result = $this->ejecutarAccion($sql);
    if ($result){
      return array('status'=>'succes', 'message'=>"se envio noticia");
    }
    return array('status'=>'warning', 'message'=>"problemas al agregar dato");
  }
  public  function preparar_consulta_por_tipo_usuario($tipo_usuario,$id_noticia){
    $usuario = new Usuario();
    $list_usuario = $usuario->get_full_usuarios_tipo($tipo_usuario);
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
                    nti.url AS 'url'
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
}
