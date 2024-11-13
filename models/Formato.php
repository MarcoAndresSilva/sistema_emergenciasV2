<?php

class Formato {
  public $asunto;
  public $mensaje;

  public function setAsunto($asunto) {
    $this->asunto = $asunto;
  }
  public function setMensaje($mensaje) {
    $this->mensaje = $mensaje;
  }
  private function setMensajeNuevoEvento(array $datos_evento) {
    // WARNING: este formato de correo puede ser detectado como spam por algunos proveedores de correo

    // Asegúrate de que los datos necesarios están presentes en el parámetro
    if (!isset($datos_evento['ev_direc'], $datos_evento['ev_desc'], $datos_evento['cat_nom'], $datos_evento['id_evento'])) {
       return "Error: Faltan datos necesarios para generar el mensaje.";
    }
    // Extraer datos del evento desde el parámetro $datos_evento
    $direccion = $datos_evento['ev_direc'];
    $detalle = $datos_evento['ev_desc'];
    $categoria = $datos_evento['cat_nom'];
    $id_evento = $datos_evento['id_evento'];

    // Ruta del evento
    $rutaEvento = "https://emergencias.melipilla.cl/view/ControlEventos/index.php?id_evento=$id_evento";

    // Construir el mensaje en HTML
    $mensaje = "<p>Estimado(a),</p>";
    $mensaje .= "<p>Se ha agregado un nuevo evento.</p>";
    $mensaje .= "<p><strong>Categoría:</strong> $categoria<br>";
    $mensaje .= "<strong>Detalles:</strong> $detalle<br>";
    $mensaje .= "<strong>Dirección:</strong> $direccion</p>";

    // Creación del botón usando HTML y estilos en línea
    $mensaje .= "<p>Para derivar el evento, haga clic en el siguiente botón:</p>";
    $mensaje .= "<p><a href='$rutaEvento' style='background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; border-radius: 5px;'>Derivar Evento</a></p>";

    // Mostrar el enlace como texto por si el botón no funciona
    $mensaje .= "<p>Si el botón no funciona, copie y pegue el siguiente enlace en su navegador:</p>";
    $mensaje .= "<p><a href='$rutaEvento'>$rutaEvento</a></p>";
    $mensaje .= "<p>Saludos cordiales,<br>";
    $mensaje .= "El equipo de eventos.</p>";
    // Establecer el mensaje en el correo
    $this->setMensaje($mensaje);
  }
  private function setAsuntoNuevoEvento(array $datos_evento) {
    $id_evento = $datos_evento['id_evento'];
    $categoria = $datos_evento['cat_nom'];
    $nuevo_asunto="🚨Nuevo evento en $categoria - 📎ticket $id_evento";
    $this->setAsunto($nuevo_asunto);
  }
  private function setAsuntoCierreEvento(array $datos_evento){
    $id_evento = $datos_evento['id_evento'];

    $nuevo_asunto = "✨ Evento Cerrado - 📎ticket $id_evento";

    $this->setAsunto($nuevo_asunto);

  }

  private function setMensajeCierreEvento(array $datos_evento) {
    // WARNING: este formato de correo puede ser detectado como spam por algunos proveedores de correo
    $motivo = $datos_evento["motivo"];
    $detalle = $datos_evento["detalle"];
    $usuario = $datos_evento['usu_nom']. " " . $datos_evento['usu_ape'];
    $id_evento = $datos_evento['id_evento'];
    $fecha_cierre = $datos_evento["fecha_cierre"];

    $mensaje = "<p>Estimado(a),</p>";
    $mensaje .= "<p>Se ha cerrado el evento $id_evento.</p>";
    $mensaje .= "<p><strong>Evento Cerrado por :</strong> $usuario</p>";
    $mensaje .= "<p><strong>Fecha Cierre:</strong> $fecha_cierre</p>";

    $mensaje .= "<storng>Motivo:</strong> $motivo</p>";
    $mensaje .= "<p><strong>Detalles Cierre:</strong> $detalle<br>";

    $mensaje .= "<p>Saludos cordiales,<br>";
    $mensaje .= "El equipo de eventos.</p>";
    $this->setMensaje($mensaje);
  }
  public function setCuerpoNuevoEvento(array $datos_evento) {
    $this->setMensajeNuevoEvento($datos_evento);
    $this->setAsuntoNuevoEvento($datos_evento);
  }
  public function SetCuerpoCierreEvento(array $datos_evento) {
    $this->setMensajeCierreEvento($datos_evento);
    $this->setAsuntoCierreEvento($datos_evento);
  }
  private function setAsuntoActualizarEvento(array $datos_evento) {
    $id = $datos_evento['id_evento'];
    $asunto = "📨 Actualizacion  al 📎Ticket $id ";
    $this->setAsunto($asunto);
  }
  private function setMensajeActualizarEvento(array $datos_evento) {
    $id = $datos_evento['id_evento'];
    $usuario = $datos_evento['usuario'];
    $mensaje = "Estimado(a),";
    $mensaje .= "<p>Se ha actualizado el evento $id.</p>";
    $mensaje .= "<p><strong>Usuario:</strong> $usuario</p>";
    $mensaje .= "<p>agrego informacion</p>";
    $mensaje .= "<p>Saludos cordiales,<br>";
    $mensaje .= "El equipo de eventos.</p>";
    $this->setMensaje($mensaje);
  }
  public function setCuerpoActualizarEvento(array $datos_evento) {
    $this->setMensajeActualizarEvento($datos_evento);
    $this->setAsuntoActualizarEvento($datos_evento);
  }

  private function setAsuntoDerivadoAgregado(array $datos_evento){
    $id_evento = $datos_evento['id_evento'];
    $asunto = "🔄️ Derivado al 📎Ticket $id_evento";
    $this->setAsunto($asunto);
  }
  private function setMensajeDerivadoAgregado(array $datos_evento){
    $id_evento = $datos_evento['id_evento'];
    $unidad = $datos_evento['unidad'];
    $mensaje = "Estimado(a),";
    $mensaje .= "<p>Se ha agregado la unidad <strong>$unidad</strong> al ticket $id_evento.</p>";
    $mensaje .= "<p>Saludos cordiales,<br>";
    $mensaje .= "El equipo de eventos.</p>";
    $this->setMensaje($mensaje);
  }
  public function setCuerpoDerivadoAgregado(array $datos_evento) {
    $this->setMensajeDerivadoAgregado($datos_evento);
    $this->setAsuntoDerivadoAgregado($datos_evento);
  }

  private function setMensajeDerivadorEliminado(array $datos_evento){
    $id_evento = $datos_evento['id_evento'];
    $unidad = $datos_evento['unidad'];
    $mensaje = "Estimado(a),";
    $mensaje .= "<p>Se delega el ticket $id_evento a la unidad $unidad.</p>";
    $mensaje .= "<p>Saludos cordiales,<br>";
    $mensaje .= "El equipo de eventos.</p>";
    $this->setMensaje($mensaje);
  }
  private function setAsuntoDerivadorEliminado(array $datos_evento){
    $id_evento = $datos_evento['id_evento'];
    $asunto = "🔄️ Derivado al 📎Ticket $id_evento";
    $this->setAsunto($asunto);
  }

  public function setCuerpoDerivadorEliminado(array $datos_evento) {
    $this->setMensajeDerivadorEliminado($datos_evento);
    $this->setAsuntoDerivadorEliminado($datos_evento);
  }

}
