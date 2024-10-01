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
        // Inicializa un array para los correos válidos
        $correosValidos = [];

        // Divide la cadena en correos usando ',' como separador
        $listaCorreos = explode(',', $destinatario);

        // Itera sobre cada correo en la lista
        foreach ($listaCorreos as $correo) {
            $correo = trim($correo); // Elimina espacios en blanco alrededor
            // Valida el correo y lo agrega a la lista de correos válidos
            if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $correosValidos[] = $correo;
            }
        }

        // Si hay correos válidos, únelos en una cadena y asigna a $this->destinatario
        if (!empty($correosValidos)) {
            $this->destinatario = implode(", ", $correosValidos);
        } else {
            throw new InvalidArgumentException("No se proporcionaron direcciones de correo válidas.");
        }
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
                if (filter_var($usuario['usu_correo'], FILTER_VALIDATE_EMAIL)) {
                    $correos[] = $usuario['usu_correo'];
                }
            }
        }
        $this->setDestinatario(implode(", ", $correos));
    }
}

