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
    $params = [
      ":usuario"=>$usuario,
      ":noticia"=>$noticia,
    ];
    $result = $this->ejecutarAccion($sql,$params);
    if ($result){
      return array('status'=>'succes', 'message'=>"se agrego la noticia");
    }
    return array('status'=>'warning', 'message'=>"problemas al agregar dato");
  }
}
