<?php 
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
  public function enviar_notica_usuario($usuario, $noticia) {
    $sql = "INSERT INTO tm_noticia_usuario(usu_id,noticia_id) VALUES (:usuario , :noticia)";
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
