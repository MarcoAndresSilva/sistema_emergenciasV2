<?php

class Correo {
    // Atributos
    public $destinatario;
    public $asunto;
    public $mensaje;
    public $encabezados;
    // Constructor para inicializar los atributos
    public function __construct($destinatario, $asunto, $mensaje, $encabezados = '') {
        $this->destinatario = $destinatario;
        $this->asunto = $asunto;
        $this->mensaje = $mensaje;
        $this->encabezados = $encabezados;
    }

    // Método para enviar el correo
    public function enviar() {
        if(mail($this->destinatario, $this->asunto, $this->mensaje, $this->encabezados)) {
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

    public function setEncabezados($encabezados) {
        $this->encabezados = $encabezados;
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

