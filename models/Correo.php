<?php

class Correo {
    // Atributos
    public $destinatario;
    public $asunto;
    public $mensaje;
    public $encabezados = [];
    private $remitente = "no-reply@emergencias.melipilla.cl";
    // Constructor para inicializar los atributos
    public function __construct($destinatario = '', $asunto = '', $mensaje = '', $encabezados = []) {
        $this->destinatario = $destinatario;
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
        $this->encabezados = $encabezados;
        $this->agregarEncabezado('From', $this->remitente);
    }

    // Método para enviar el correo
    public function enviar() {
        // Unir todos los encabezados en una cadena
        $encabezadosStr = implode("\r\n", $this->encabezados);

        if(mail($this->destinatario, $this->asunto, $this->mensaje, $encabezadosStr)) {
            return "Correo enviado exitosamente a $this->destinatario";
        } else {
            return "Error al enviar el correo a $this->destinatario";
        }
    }

    // Métodos para cambiar los atributos
    public function setDestinatario($destinatario) {
        $this->destinatario = $destinatario;
    }

    public function setAsunto($asunto) {
        $this->asunto = $asunto;
    }

    public function setMensaje($mensaje) {
        $this->mensaje = $mensaje;
    }

    // Método para agregar un encabezado
    public function agregarEncabezado($clave, $valor) {
        $this->encabezados[] = "$clave: $valor";
    }

    // Método para definir un grupo de destinatarios
    public function setGrupoDestinatario($listaUsuarios) {
        $correos = [];

        foreach ($listaUsuarios as $usuario) {
            if (isset($usuario['usu_correo'])) {
                $correos[] = $usuario['usu_correo'];
            }
        }
        $this->setDestinatario(implode(", ", $correos));
    }
    public function setMensajeNuevoEvento($datos_evento) {
        // WARNING: este formato de correo puede ser detectado como spam por algunos proveedores de correo

        // Asegúrate de que los datos necesarios están presentes en el parámetro
        if (!isset($datos_evento['ev_direc'], $datos_evento['ev_desc'], $datos_evento['cat_nom'], $datos_evento['id_evento'])) {
            return "Error: Faltan datos necesarios para generar el mensaje.";
        }
        // Agregar el encabezado para definir el tipo de contenido
        $this->agregarEncabezado('Content-Type', 'text/html; charset=UTF-8');
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
    public function setAsuntoNuevoEvento($datos_evento) {
        $id_evento = $datos_evento['id_evento'];
        $categoria = $datos_evento['cat_nom'];
        $nuevo_asunto="🚨Nuevo evento en $categoria - 📎ticket $id_evento";
        $this->setAsunto($nuevo_asunto);
    }
}

