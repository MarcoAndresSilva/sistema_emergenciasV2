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

        foreach ($listaUsuarios['result'] as $usuario) {
            if (isset($usuario['Correo'])) {
                $correos[] = $usuario['Correo'];
            }
        }
        $this->setDestinatario(implode(", ", $correos));
    }
}

